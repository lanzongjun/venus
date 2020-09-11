<?php

include_once 'BaseModel.php';

class ProviderGoodsCheckModel extends BaseModel
{
    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db->join('core_shop', 'pgc_shop_id = cs_id', 'left');

        $query->join('user', 'pgc_operator = u_id', 'left');

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('provider_goods_check')->result();
            if (empty($total['0']) || empty($total['0']->total)) {
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
                'total' => $total['0']->total,
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
        $this->db->select('pgcd_id, pg_name as goods_name, pgcd_num, u_name as operator_name, pgcd_create_time, pgcd_update_time');

        $result = $this->db->get('provider_goods_check_detail')->result_array();

        return $result;
    }

    public function getProviderGoodsCheckDetailInfo($id)
    {
        $this->db->join('provider_goods', 'pg_id = pgcd_provider_goods_id', 'left');

        $this->db->where('pgcd_id', $id);

        $this->db->select('pgcd_id, pgcd_provider_goods_id as pg_id, pgcd_num');

        $result = $this->db->get('provider_goods_check_detail')->first_row();

        return $result;
    }

    public function addGoodsCheck($shopId, $userId, $date, $pgId, $num)
    {
        // 检验是否存在该日期-店铺的盘点记录
        $check = $this->db->where('pgc_shop_id', $shopId)
            ->where('pgc_date', $date)
            ->get('provider_goods_check')->first_row();

        if (empty($check)) {
            $insertCheckData = [
                'pgc_shop_id' => $shopId,
                'pgc_date' => $date,
                'pgc_operator' => $userId
            ];
            $this->db->insert('provider_goods_check', $insertCheckData);

            $pgcId=$this->db->insert_id('provider_goods_check');
        } else {
            $pgcId = $check->pgc_id;
        }

        // 添加详情
        $insertDetailData = [
            'pgcd_gsc_id' => $pgcId,
            'pgcd_provider_goods_id' => $pgId,
            'pgcd_operator' => $userId,
            'pgcd_num' => $num
        ];

        $o_result = $this->db->insert('provider_goods_check_detail', $insertDetailData);

        $result = ['state' => $o_result, 'msg' => "添加成功"];

        return $result;
    }

    public function editGoodsCheck($userId, $id, $pgId, $num)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'pgcd_operator' => $userId,
            'pgcd_provider_goods_id' => $pgId,
            'pgcd_num' => $num
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
}