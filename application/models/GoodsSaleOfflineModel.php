<?php

include_once 'BaseModel.php';

class GoodsSaleOfflineModel extends BaseModel
{
    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db;

        $query->join('core_shop', 'cs_id = gso_shop_id', 'left');
        $query->join('provider_goods', 'pg_id = gso_provider_goods_id', 'left');

        $queryTotal = clone $query;
        $queryList = clone  $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_sale_offline')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('gso_id, core_shop.cs_name as shop_name, cs_city, gso_date, 
        pg_name as goods_name, gso_num, gso_unit, gso_create_time, gso_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_sale_offline')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['gso_num'].'('. self::unitMap($row['gso_unit']) .')';
        }


        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function getSaleOfflineInfo($id)
    {

        $this->db->select('gso_id, 
        gso_provider_goods_id as goods_id, 
        gso_date as date, gso_num as num, gso_unit as unit');

        $this->db->where('gso_id', $id);

        $result = $this->db->get('goods_sale_offline')->first_row();

        return $result;
    }

    public function addGoodsSaleOffline($userId, $shopId, $goodsId, $date, $num, $unit)
    {
        $this->db->trans_begin();

        $insertData = [
            'gso_operator'          => $userId,
            'gso_shop_id'           => $shopId,
            'gso_provider_goods_id' => $goodsId,
            'gso_date'              => $date,
            'gso_num'               => $num,
            'gso_unit'              => $unit
        ];

        $this->db->insert('goods_sale_offline', $insertData);

        // 修改库存
        $modifyRes = $this->modifyRepertory(
            $shopId,
            $goodsId,
            -$num,
            $unit,
            REPERTORY_TYPE_ADD_SALE_OFFLINE
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

    public function editGoodsSaleOffline($shopId, $id, $userId, $date, $num, $unit)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $this->db->trans_begin();

        // 修改库存
        $editRes = $this->editRepertory(
            $shopId,
            'goods_sale_offline',
            'gso',
            $id,
            $num,
            $unit,
            REPERTORY_TYPE_EDIT_SALE_OFFLINE);

        if ($editRes['state'] === false) {
            $this->db->trans_rollback();

            return array(
                'state' => false,
                'msg'   => $editRes['msg']
            );
        }

        $updateData = [
            'gso_date'              => $date,
            'gso_num'               => $num,
            'gso_unit'              => $unit,
            'gso_operator'          => $userId
        ];
        $this->db->where('gso_id', $id);

        try {
            $this->db->update('goods_sale_offline',$updateData);
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

    public function deleteGoodsSaleOffline($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('gso_id', $id)->get('goods_sale_offline')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        // 删除记录
        $this->db->delete('goods_sale_offline', array('gso_id' => $id));

        // 减少库存
        $this->modifyRepertory(
            $row->gso_shop_id,
            $row->gso_provider_goods_id,
            $row->gso_num,
            $row->gso_unit, REPERTORY_TYPE_DELETE_SALE_OFFLINE);

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