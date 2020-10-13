<?php

include_once 'BaseModel.php';

class GoodsStockModel extends BaseModel
{
    public function getList($shopId, $startDate, $endDate, $goodsName, $page, $rows, $rowsOnly)
    {

        $query = $this->db;
        $query->join('provider_goods', 'gs_provider_goods_id = pg_id', 'left');
        $query->join('core_shop', 'cs_id = gs_shop_id', 'left');
        $query->join('user', 'u_id = gs_operator_id', 'left');
        $query->where('gs_shop_id', intval($shopId));

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gs_date >=', $startDate);
            $query->where('gs_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('goods_stock')->first_row();
            if (empty($total->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('gs_id, cs_name, pg_name, gs_date, gs_num, gs_unit,
         u_name, gs_remark, gs_create_time, gs_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_stock')->result_array();

        foreach ($rows as &$row) {
            $row['remark'] = empty($row['gs_remark']) ? '--' : $row['gs_remark'];
            $row['num_unit'] = $row['gs_num'].'('. self::unitMap($row['gs_unit']) .')';
        }

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total->total,
                'rows' => $rows
            );
        }
    }

    public function addGoodsStock($userId, $shopId, $goodsId, $date, $num, $unit, $remark)
    {
        $this->db->trans_begin();
        try {
            $insertData = [
                'gs_shop_id'           => $shopId,
                'gs_provider_goods_id' => $goodsId,
                'gs_date'              => $date,
                'gs_num'               => $num,
                'gs_unit'              => $unit,
                'gs_operator_id'       => $userId,
                'gs_remark'            => $remark
            ];

            $this->db->insert('goods_stock', $insertData);

            $insertId = $this->db->insert_id();

            // 添加库存
            $modifyRes = $this->addRepertory(
                $shopId,
                $goodsId,
                $date,
                $num,
                $unit,
                REPERTORY_TYPE_GOODS_STOCK,
                $insertId
            );

            if ($modifyRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $modifyRes['msg']
                );
            }

        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return array(
                'state' => false,
                'msg'  => $e->getMessage()
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

    public function deleteGoodsStockRecord($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('gs_id', $id)->get('goods_stock')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        $this->db->delete('goods_stock', array('gs_id' => $id));

        // 减少库存
        $delRes = $this->deleteRepertory(
            $row->gs_shop_id,
            REPERTORY_TYPE_GOODS_STOCK,
            $row->gs_id
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
            'msg'   => '删除成功'
        );
    }

    public function getGoodsStockInfo($id)
    {
        $this->db->select('gs_id, 
        gs_provider_goods_id as goods_id, 
        gs_date as date, gs_num as num, gs_unit as unit, gs_remark as remark');

        $this->db->where('gs_id', $id);

        $result = $this->db->get('goods_stock')->first_row();

        return $result;
    }

    public function editGoodsStock($shopId, $id, $userId, $date, $num, $unit, $remark)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $this->db->trans_begin();

        // 修改库存
        $editRes = $this->editRepertory(
            $shopId,
            REPERTORY_TYPE_GOODS_STOCK,
            $id,
            $date,
            $num,
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
            'gs_date'        => $date,
            'gs_num'         => $num,
            'gs_unit'        => $unit,
            'gs_remark'      => $remark,
            'gs_operator_id' => $userId
        ];
        $this->db->where('gs_id', $id);

        try {
            $this->db->update('goods_stock',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', "编辑进货信息-异常中断！\r\n" . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑进货信息-异常中断！\r\n" . $ex->getMessage();
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
}