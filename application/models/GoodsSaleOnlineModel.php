<?php

include_once 'BaseModel.php';

class GoodsSaleOnlineModel extends BaseModel
{
    const IMPORT_GOODS_SALE_MAP = [
        'A' => 'shop_name',
        'B' => 'shop_no',
        'C' => 'shop_city',
        'D' => 'date',
        'E' => 'goods_title',
        'F' => 'goods_num',
        'G' => 'sku',
    ];

    public function addSaleOnlineExcelData($data)
    {
        set_time_limit(1800);


        // shop map
        $shopMap = $this->db->select('cs_id, cs_code')->where('cs_code !=', '')->get('core_shop')->result_array();
        $shopMap = array_column($shopMap, NULL, 'cs_code');

        //处理数据
        $formatData = [];
        if (!empty($data) && is_array($data)){
            // 删除表头
            array_shift($data);
            foreach ($data as $datum) {
                $shopId = isset($shopMap[strval($datum['B'])]) ? $shopMap[strval($datum['B'])]['cs_id'] : '';
                $date = date('Y-m-d', strtotime($datum['D']));
                $sku = $datum['G'];
                $uniKey = $shopId.'-'.$date.'-'.$sku;
                if (isset($formatData[$uniKey])) {
                    $formatData[$uniKey]['gso_num'] += intval($datum['F']);
                } else {
                    $formatData[$uniKey] = [
                        'gso_shop_id' => $shopId,
                        'gso_date' => $date,
                        'gso_sku_code' => $sku,
                        'gso_num' => intval($datum['F'])
                    ];
                }
            }
        }

        if (!empty($formatData)) {

            $this->db->trans_begin();

            //$this->db->insert_batch('goods_sale_online', $formatData);



            //处理库存关系
            $skuList = array_unique(array_column($formatData, 'gso_sku_code'));

            $query = $this->db;
            $query->where_in('pgs_sku_code', $skuList);
            $query->select('pgs_sku_code, pgs_provider_goods_id, pgs_num');
            $goodsSkuMap = $query->get('provider_goods_sku')->result_array();

            foreach ($formatData as $formatDataItem) {

                // 插入数据
                $this->db->insert('goods_sale_online', $formatDataItem);
                // 返回的id 是最后一次插入的那段数据的第一个ID
                $firstInsertId = $this->db->insert_id();
                foreach ($goodsSkuMap as $goodsSkuMapItem) {

                    if ($formatDataItem['gso_sku_code'] == $goodsSkuMapItem['pgs_sku_code']) {

                        // 修改库存
                        $modifyRes = $this->addRepertory(
                            $formatDataItem['gso_shop_id'],
                            $goodsSkuMapItem['pgs_provider_goods_id'],
                            $formatDataItem['gso_date'],
                            -($formatDataItem['gso_num'] * $goodsSkuMapItem['pgs_num']),
                            1,
                            REPERTORY_TYPE_GOODS_SALE_ONLINE,
                            $firstInsertId
                        );

                        if ($modifyRes['state'] === false) {
                            $this->db->trans_rollback();

                            return array(
                                'state' => false,
                                'msg'   => $modifyRes['msg']
                            );
                        }
                    }
                }
                //$firstInsertId++;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            return array(
                'state' => true,
                'msg'   => '线上销售数据导入成功'
            );
        } else {
            return array(
                'state' => false,
                'msg'   => '没有符合要求的数据插入'
            );
        }
    }

    public function getList($shopId, $page, $rows, $rowsOnly)
    {
        $query = $this->db->join('core_shop', 'cs_id = gso_shop_id', 'left');
        $query->join('core_sku', 'core_sku.cs_code = gso_sku_code', 'left');
        $query->where('gso_shop_id', $shopId);

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_sale_online')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('gso_id, core_shop.cs_name as shop_name, cs_city, gso_date, 
        gso_sku_code, core_sku.cs_name as sku_name, gso_num, gso_create_time, gso_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_sale_online')->result_array();

        return array(
            'total' => intval($total->total),
            'rows' => $rows
        );

    }

    public function getSummaryList($shopId, $startDate, $endDate, $goodsName, $page, $rows)
    {
        $query = $this->db;
        $query->join('provider_goods_sku as b', 'gso_sku_code = pgs_sku_code', 'left');
        $query->join('provider_goods', 'b.pgs_provider_goods_id = pg_id', 'left');
        $query->join('provider_goods_sample as a', 'a.pgs_provider_goods_id = pg_id', 'left');
        $query->where('gso_shop_id', intval($shopId));
        $query->group_by('pgs_sku_code, gso_date');
        $query->select('pg_id, pg_name, (gso_num*pgs_num) as total, pg_is_dumplings, pgs_weight, gso_date');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gso_date >=', $startDate);
            $query->where('gso_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $query->get('goods_sale_online')->result_array();
        $subQuerySql = $query->last_query();



        // 获取总数
        $resultTotal = $this->db->query("select count(1) as total from (select pg_id,pg_name,sum(total) as total,pgs_weight,gso_date,pg_is_dumplings from ({$subQuerySql}) as aaa group by pg_id,gso_date) as bbb")->first_row();
        if (empty($resultTotal->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        $offset = ($page - 1) * $rows;

        $resultList = $this->db->query("select pg_id,pg_name,sum(total) as total,pgs_weight,gso_date,pg_is_dumplings from ({$subQuerySql}) as aaa group by pg_id,gso_date,pg_is_dumplings limit {$offset}, {$rows}")->result_array();


        foreach ($resultList as &$item) {
            if ($item['pg_is_dumplings'] && !empty($item['pgs_weight'])) {
                $item['num_unit'] = round($item['total'] * $item['pgs_weight'] / 500, 4).'斤';
            } else {
                $item['num_unit'] = $item['total'].'个';
            }
        }

        return array(
            'total' => intval($resultTotal->total),
            'rows'  => $resultList
        );
    }

    public function editGoodsOnlineInfo($id, $num)
    {
        $originRow = $this->db->where('gso_id', intval($id))->get('goods_sale_online')->first_row();
        $gap = bcsub($num, $originRow->gso_num);

        // 数量没有变化不做修改
        if (empty($gap)) {
            return  array(
                'state' => true,
                'msg' => '销量未更改，不做修改'
            );
        }

        $shopId  = intval($originRow->gso_shop_id);
        $skuCode = $originRow->gso_sku_code;
        $date    = $originRow->gso_date;

        $this->db->trans_begin();

        /* 修改库存份数 */
        $this->db->where('gso_id', intval($id))->update('goods_sale_online', ['gso_num' => intval($num)]);

        /* 查询库存记录表记录 */
        $repertoryRecordRows = $this->db
            ->where('crr_shop_id', $shopId)
            ->where('crr_ref_id', intval($originRow->gso_id))
            ->where('crr_type', REPERTORY_TYPE_GOODS_SALE_ONLINE)
            ->where('crr_date', $date)
            ->get('core_repertory_record')
            ->result();

        /* 获取sku-map */
        $skuMap = $this->db
            ->join('provider_goods_sample', 'provider_goods_sample.pgs_provider_goods_id = provider_goods_sku.pgs_provider_goods_id', 'left')
            ->where('pgs_sku_code', $skuCode)
            ->select('provider_goods_sku.pgs_provider_goods_id, provider_goods_sku.pgs_num, provider_goods_sample.pgs_weight')
            ->get('provider_goods_sku')
            ->result();

        $skuMap = array_column($skuMap, NULL, 'pgs_provider_goods_id');

        /* 修改库存记录数据 */
        $recordWhere = [
            'crr_shop_id' => $shopId,
            'crr_ref_id' => intval($originRow->gso_id),
            'crr_type'   => REPERTORY_TYPE_GOODS_SALE_ONLINE
        ];
        $repWhere = [
            'cr_shop_id' => $shopId,
        ];
        $dailyWhere = [
            'crd_shop_id' => $shopId,
            'crd_date >=' => $date,
            'crd_date <'  => date('Y-m-d')
        ];
        foreach ($repertoryRecordRows as $recordRow) {

            $goodsId = $recordRow->crr_provider_goods_id;
            $recordWhere['crr_provider_goods_id'] = intval($goodsId);
            $recordWhere['crr_date'] = $recordRow->crr_date;
            $recordGap = $gap * $skuMap[$goodsId]->pgs_num;

            /* 修改库存记录数据 */
            $this->db->where($recordWhere)
                ->set('crr_num', "crr_num - ({$recordGap})", false)
                ->update('core_repertory_record');

            /* 修改总库存数据 */
            $repWhere['cr_provider_goods_id'] = intval($goodsId);
            if (empty($skuMap[$goodsId]->pgs_weight)) {
                $repGap = $gap * $skuMap[$goodsId]->pgs_num;
            } else {
                $repGap = round($gap * $skuMap[$goodsId]->pgs_num * $skuMap[$goodsId]->pgs_weight, 4);
            }
            $this->db->where($repWhere)
                ->set('cr_num', "cr_num - ({$repGap})", false)
                ->update('core_repertory');

            /* 修改库存日表数据 */
            $dailyWhere['crd_provider_goods_id'] = intval($goodsId);
            $this->db->where($dailyWhere)
                ->set('crd_num', "crd_num - ({$repGap})", false)
                ->update('core_repertory_daily');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return  array(
            'state' => true,
            'msg' => '修改成功'
        );
    }

    public function deleteGoodsOnlineInfo($id)
    {
        $originRow = $this->db->where('gso_id', intval($id))->get('goods_sale_online')->first_row();

        if (empty($originRow)) {
            return  array(
                'state' => true,
                'msg' => '记录不存在'
            );
        }

        $num     = $originRow->gso_num;
        $shopId  = intval($originRow->gso_shop_id);
        $skuCode = $originRow->gso_sku_code;
        $date    = $originRow->gso_date;

        $this->db->trans_begin();

        /* 删除库存份数 */
        $this->db->where('gso_id', intval($id))->delete('goods_sale_online');

        /* 删除库存记录数据 */
        $recordWhere = [
            'crr_shop_id' => $shopId,
            'crr_date'    => $date,
            'crr_type'    => REPERTORY_TYPE_GOODS_SALE_ONLINE,
            'crr_ref_id'  => intval($id),
        ];
        $this->db->where($recordWhere)->delete('core_repertory_record');

        /* 获取sku-map */
        $skuMap = $this->db
            ->join('provider_goods_sample', 'provider_goods_sample.pgs_provider_goods_id = provider_goods_sku.pgs_provider_goods_id', 'left')
            ->where('pgs_sku_code', $skuCode)
            ->select('provider_goods_sku.pgs_provider_goods_id, provider_goods_sku.pgs_num, provider_goods_sample.pgs_weight')
            ->get('provider_goods_sku')
            ->result();

        foreach ($skuMap as $item) {

            $goodsId = $item->pgs_provider_goods_id;

            /* 修改总库存数据 */
            $repWhere = [
                'cr_shop_id' => $shopId,
                'cr_provider_goods_id' => intval($goodsId)
            ];
            if (empty($item->pgs_weight)) {
                $repGap = $num * $item->pgs_num;
            } else {
                $repGap = round($num * $item->pgs_num * $item->pgs_weight, 4);
            }
            $this->db->where($repWhere)
                ->set('cr_num', "cr_num + ({$repGap})", false)
                ->update('core_repertory');

            /* 修改库存日表数据 */
            $dailyWhere = [
                'crd_shop_id' => $shopId,
                'crd_date >=' => $date,
                'crd_date <'  => date('Y-m-d'),
                'crd_provider_goods_id' => intval($goodsId)
            ];
            $this->db->where($dailyWhere)
                ->set('crd_num', "crd_num + ({$repGap})", false)
                ->update('core_repertory_daily');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return  array(
            'state' => true,
            'msg' => '修改成功'
        );

    }
}