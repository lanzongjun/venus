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
            $result = $this->db->insert_batch('goods_sale_online', $formatData);

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

    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db->join('core_shop', 'cs_id = gso_shop_id', 'left');
        $query->join('core_sku', 'core_sku.cs_code = gso_sku_code', 'left');

        $queryTotal = clone $query;
        $queryList = clone  $query;

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
            'total' => $total->total,
            'rows' => $rows
        );

    }

    public function getSummaryList($startDate, $endDate, $goodsName, $page, $rows)
    {
        $query = $this->db;
        $query->join('provider_goods_sku as b', 'gso_sku_code = pgs_sku_code', 'left');
        $query->join('provider_goods', 'b.pgs_provider_goods_id = pg_id', 'left');
        $query->join('provider_goods_sample as a', 'a.pgs_provider_goods_id = pg_id', 'left');
        $query->group_by('b.pgs_provider_goods_id, gso_date');
        $query->select('b.pgs_provider_goods_id,pg_name,sum(pgs_num) as total, is_dumplings, pgs_weight, gso_date');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gso_date >=', $startDate);
            $query->where('gso_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone  $query;

        // 获取总数
        $queryTotal->get('goods_sale_online')->first_row();
        $total = $this->db->query("select count(1) as total from ({$queryTotal->last_query()}) as tmp")->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_sale_online')->result_array();

        foreach ($rows as &$row) {
            if ($row['is_dumplings'] && !empty($row['pgs_weight'])) {
                $row['num_unit'] = round($row['total'] * $row['pgs_weight'] / 500, 2).'斤';
            } else {
                $row['num_unit'] = $row['total'].'个';
            }
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }
}