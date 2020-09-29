<?php
include_once 'BaseModel.php';

class GoodsStaffMealModel extends BaseModel
{
    public function getList($shopId, $startDate, $endDate, $goodsName, $page, $rows)
    {
        $query = $this->db;

        $query->join('provider_goods', 'gsm_provider_goods_id = pg_id', 'left');
        $query->join('user', 'u_id = gsm_operator', 'left');
        $query->join('core_shop', 'cs_id = gsm_shop_id', 'left');
        $query->where('gsm_shop_id', $shopId);

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gsm_date >=', $startDate);
            $query->where('gsm_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_staff_meal')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('gsm_id, cs_name as shop_name, pg_name as goods_name, 
        gsm_date, gsm_unit, gsm_num, gsm_create_time, gsm_update_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_staff_meal')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['gsm_num'].'('. self::unitMap($row['gsm_unit']) .')';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function addStaffMeal(
        $userId,
        $shopId,
        $goodsId,
        $date,
        $unit,
        $num)
    {

        $this->db->trans_begin();

        $insertData = [
            'gsm_shop_id'           => $shopId,
            'gsm_provider_goods_id' => $goodsId,
            'gsm_date'              => $date,
            'gsm_unit'              => $unit,
            'gsm_num'               => $num,
            'gsm_operator'          => $userId
        ];

        $this->db->insert('goods_staff_meal', $insertData);

        $insertId = $this->db->insert_id();

        // 修改库存
        $modifyRes = $this->addRepertory(
            $shopId,
            $goodsId,
            $date,
            -$num,
            $unit,
            REPERTORY_TYPE_STAFF_MEAL,
            $insertId
        );

        if ($modifyRes['state'] === false) {
            $this->db->trans_rollback();

            return array(
                'state' => false,
                'msg'   => $modifyRes['msg']
            );
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return array(
            'state' => true,
            'msg' => '添加成功'
        );
    }

    public function getStaffMealInfo($id)
    {
        $this->db->select('gsm_id, gsm_provider_goods_id as goods_id, gsm_date as date,
        gsm_unit as unit, gsm_num as num');
        $this->db->where('gsm_id', $id);
        $query = $this->db->get('goods_staff_meal');
        $result = $query->first_row();
        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function editStaffMeal($shopId, $id, $userId, $date, $unit, $num)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $this->db->trans_begin();

        // 修改库存
        $editRes = $this->editRepertory(
            $shopId,
            REPERTORY_TYPE_STAFF_MEAL,
            $id,
            $date,
            -$num,
            $unit
        );

        if ($editRes['state'] === false) {
            $this->db->trans_rollback();

            return array(
                'state' => false,
                'msg'   => $editRes['msg']
            );
        }

        $updateData = [
            'gsm_operator'          => $userId,
            'gsm_date'              => $date,
            'gsm_unit'              => $unit,
            'gsm_num'               => $num,
        ];
        $this->db->where('gsm_id', $id);

        try {
            $this->db->update('goods_staff_meal',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', "编辑商铺信息-异常中断！\r\n" . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $o_result;
    }

    public function deleteStaffMealRecord($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('gsm_id', $id)->get('goods_staff_meal')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        // 删除记录
        $this->db->delete('goods_staff_meal', array('gsm_id' => $id));

        // 减少库存
        $delRes = $this->deleteRepertory(
            $row->gsm_shop_id,
            REPERTORY_TYPE_STAFF_MEAL,
            $row->gsm_id
        );

        if ($delRes['state'] === false) {
            $this->db->trans_rollback();

            return array(
                'state' => false,
                'msg'   => $delRes['msg']
            );
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return array(
            'state' => true,
            'msg' => '删除成功'
        );
    }
}