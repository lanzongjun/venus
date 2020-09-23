<?php
include_once 'BaseModel.php';

class GoodsSaleLossModel extends BaseModel
{
    public function getList($providerGoodsName, $lossType, $page, $rows)
    {
        $query = $this->db->join('provider_goods', 'pg_id = gl_provider_goods_id', 'left');
        $query->join('core_shop', 'gl_shop_id = cs_id', 'left');
        $query->join('user', 'gl_operator = u_id', 'left');

        $query->where('gl_type', $lossType);

        if (!empty($providerGoodsName)) {
            $query->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('goods_loss')->first_row();
            if (empty($total->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }

        // 获取分页数据
        $queryList->select(
            'gl_id, gl_provider_goods_id, cs_name, cs_city,
            pg_name, gl_date, gl_type, gl_num, gl_unit, gl_operator, u_name, gl_order, 
            gl_create_time, gl_update_time'
        );

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_loss')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['gl_num'].'('. self::unitMap($row['gl_unit']) .')';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function getGoodsLossInfo($id)
    {
        $this->db->select(
            'gl_id, gl_provider_goods_id as goods_id, gl_num as num,
             gl_unit as unit,
             gl_date as date, gl_order as order, gl_type'
        );

        $this->db->where('gl_id', $id);
        $query = $this->db->get('goods_loss');
        $result = $query->first_row();
        if ($result) {
            return $result;
        }
        return array();
    }

    public function addGoodsLossInfo($shopId, $userId, $type, $goodsId, $date, $num, $unit, $order)
    {
        $this->db->trans_begin();

        $insertData = [
            'gl_shop_id'           => $shopId,
            'gl_date'              => $date,
            'gl_type'              => $type,
            'gl_provider_goods_id' => $goodsId,
            'gl_num'               => $num,
            'gl_unit'              => $unit,
            'gl_order'             => $order,
            'gl_operator'          => $userId
        ];

        $this->db->insert('goods_loss', $insertData);

        $type = $type == 1 ? REPERTORY_TYPE_DELETE_SALE_SHOP_LOSS : REPERTORY_TYPE_DELETE_SALE_ORDER_LOSS;

        // 修改库存
        $modifyRes = $this->modifyRepertory(
            $shopId,
            $goodsId,
            -$num,
            $unit,
            $type
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
            'msg'   => '添加成功'
        );
    }

    public function editGoodsLossInfo($shopId, $id, $userId, $date, $num, $unit, $type, $order)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $this->db->trans_begin();

        $constType = $type == 1 ? REPERTORY_TYPE_DELETE_SALE_SHOP_LOSS : REPERTORY_TYPE_DELETE_SALE_ORDER_LOSS;

        // 修改库存
        $editRes = $this->editRepertory(
            $shopId,
            'goods_loss',
            'gl',
            $id,
            $num,
            $unit,
            $constType);

        if ($editRes['state'] === false) {
            $this->db->trans_rollback();

            return array(
                'state' => false,
                'msg'   => $editRes['msg']
            );
        }

        $updateData = [
            'gl_operator'          => $userId,
            'gl_date'              => $date,
            'gl_num'               => $num,
            'gl_unit'              => $unit,
            'gl_order'             => $order
        ];
        $this->db->where('gl_id', $id);

        try {
            $this->db->update('goods_loss',$updateData);
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

    public function deleteGoodsLoss($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('gl_id', $id)->get('goods_loss')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        $type = $row->gl_type == 1 ? REPERTORY_TYPE_DELETE_SALE_SHOP_LOSS : REPERTORY_TYPE_DELETE_SALE_ORDER_LOSS;

        // 删除记录
        $this->db->delete('goods_loss', array('gl_id' => $id));

        // 减少库存
        $this->modifyRepertory(
            $row->gl_shop_id,
            $row->gl_provider_goods_id,
            $row->gl_num,
            $row->gl_unit, $type);

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