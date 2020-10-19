<?php

include_once 'BaseModel.php';

class ProviderGoodsCheckModel extends BaseModel
{
    public function getList($shopId, $startDate, $endDate, $page, $rows, $rowsOnly)
    {
        $query = $this->db;

        $query->join('core_shop', 'pgc_shop_id = cs_id', 'left');
        $query->join('user', 'pgc_operator = u_id', 'left');
        $query->where('pgc_shop_id', intval($shopId));

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('pgc_date >=', $startDate);
            $query->where('pgc_date <=', $endDate);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('provider_goods_check')->first_row();
            if (empty($total->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('pgc_id, pgc_shop_id, pgc_date, pgc_operator,
         u_name as operator_name, cs_name as shop_name, pgc_create_time, pgc_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('provider_goods_check')->result_array();

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => intval($total->total),
                'rows' => $rows
            );
        }
    }

    public function loadDetailData($id)
    {
        $this->db->join('provider_goods', 'pgcd_provider_goods_id = pg_id', 'left');

        $this->db->join('user', 'pgcd_operator = u_id', 'left');

        $this->db->where('pgcd_gsc_id', $id);

        // 获取分页数据
        $this->db->select('pgcd_id, pg_name as goods_name, pgcd_num, pgcd_unit, 
        u_name as operator_name, pgcd_create_time, pgcd_update_time');

        $rows = $this->db->get('provider_goods_check_detail')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['pgcd_num'].'('. self::unitMap($row['pgcd_unit']) .')';
        }

        return $rows;
    }

    public function getProviderGoodsCheckDetailInfo($id)
    {
        $this->db->join('provider_goods', 'pg_id = pgcd_provider_goods_id', 'left');

        $this->db->where('pgcd_id', $id);

        $this->db->select('pgcd_id, pgcd_provider_goods_id as goods_id, pgcd_num');

        $result = $this->db->get('provider_goods_check_detail')->first_row();

        return $result;
    }

    public function addGoodsCheck($shopId, $userId, $date, $goodsId, $num, $unit)
    {
        // 检验是否存在该日期-店铺的盘点记录
        $check = $this->db
            ->where('pgc_shop_id', $shopId)
            ->where('pgc_date', $date)
            ->get('provider_goods_check')
            ->first_row();

        if (empty($check)) {
            $insertCheckData = [
                'pgc_shop_id'  => $shopId,
                'pgc_date'     => $date,
                'pgc_operator' => $userId
            ];
            $this->db->insert('provider_goods_check', $insertCheckData);

            $pgcId=$this->db->insert_id('provider_goods_check');
        } else {
            $pgcId = $check->pgc_id;
        }

        // 检验是否存在改商品的详情
        $checkDetail = $this->db
            ->where('pgcd_gsc_id', $pgcId)
            ->where('pgcd_provider_goods_id', $goodsId)
            ->get('provider_goods_check_detail')
            ->first_row();

        if (empty($checkDetail)) {
            // 添加详情
            $insertDetailData = array(
                'pgcd_gsc_id'            => $pgcId,
                'pgcd_provider_goods_id' => $goodsId,
                'pgcd_operator'          => $userId,
                'pgcd_num'               => $num,
                'pgcd_unit'              => $unit
            );
            $o_result = $this->db->insert('provider_goods_check_detail', $insertDetailData);
        } else {
            // 更新详情
            $updateDetailData = array(
                'pgcd_operator'          => $userId,
                'pgcd_num'               => $num,
                'pgcd_unit'              => $unit
            );
            $where = array(
                'pgcd_gsc_id' => $pgcId,
                'pgcd_provider_goods_id' => $goodsId
            );
            $o_result = $this->db
                ->update('provider_goods_check_detail', $updateDetailData, $where);
        }


        $result = ['state' => $o_result, 'msg' => "添加成功"];

        return $result;
    }

    public function editGoodsCheck($userId, $id, $num, $unit)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'pgcd_operator' => $userId,
            'pgcd_num'      => $num,
            'pgcd_unit'     => $unit
        ];
        $this->db->where('pgcd_id', $id);

        try {
            $this->db->update('provider_goods_check_detail',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', "编辑商铺信息-异常中断！\r\n" . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }

    public function reloadGoodsCheck($shopId, $id)
    {
        // 获取盘点数据
        $checkData = $this->db
            ->join('provider_goods_check_detail', 'pgcd_gsc_id = pgc_id', 'left')
            ->join('provider_goods', 'pg_id = pgcd_provider_goods_id', 'left')
            ->join('provider_goods_sample', 'pgs_provider_goods_id = pgcd_provider_goods_id', 'left')
            ->where('pgc_shop_id', $shopId)
            ->where('pgc_id', $id)
            ->select('pgcd_provider_goods_id as goods_id, pgcd_num, pgcd_unit, pg_is_dumplings, pgs_weight')
            ->get('provider_goods_check')
            ->result_array();

        if (empty($checkData)) {
            return array(
                'state' => false,
                'msg'   => '暂无盘点数据'
            );
        }

        $updateData = array();
        foreach ($checkData as $datum) {
            if ($datum['pg_is_dumplings']) {
                // 饺子转化成克
                if ($datum['cr_unit'] == 1 && empty($datum['pgs_weight'])) {
                    continue;
                } elseif ($datum['cr_unit'] == 1 && !empty($datum['pgs_weight'])) {
                    $weight = round($datum['pgcd_num'] * $datum['pgs_weight'], 2);
                } else {
                    $weight = round($datum['pgcd_num'] * 500, 2);
                }

                $updateData[] = [
                    'cr_provider_goods_id' => $datum['goods_id'],
                    'cr_num' => $weight,
                    'cr_unit' => 2
                ];
            } else {
                $updateData[] = [
                    'cr_provider_goods_id' => $datum['goods_id'],
                    'cr_num' => $datum['pgcd_num'],
                    'cr_unit' => 1
                ];
            }
        }

        // 覆盖数据
        $result = $this->db->where('cr_shop_id', $shopId)->update_batch('core_repertory', $updateData, 'cr_provider_goods_id');

        if ($result) {
            $msg = array(
                'state' => true,
                'msg'   => $result
            );
        } else {
            $msg = array(
                'state' => false,
                'msg'   => '没有符合要求的数据覆盖'
            );
        }

        return $msg;
    }

    public function deleteGoodsCheckDetail($id)
    {
        $this->db->delete('provider_goods_check_detail', array('pgcd_id' => $id));

        return array(
            'state' => true,
            'msg'   => '删除成功'
        );
    }
}