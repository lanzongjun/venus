<?php
include_once 'BaseModel.php';

class GoodsExceptionHandleModel extends BaseModel
{

    /**
     * 索赔单
     */
    const EXCEPTION_HANDLE_TYPE_CHAIM = 1;

    public function getList($shopId, $type, $startDate, $endDate, $goodsName, $page, $rows)
    {
        $query = $this->db;

        $query->join('provider_goods', 'geh_provider_goods_id = pg_id', 'left');
        $query->join('user', 'u_id = geh_operator', 'left');
        $query->join('core_shop', 'cs_id = geh_shop_id', 'left');
        $query->where('geh_shop_id', $shopId);

        if (!empty($type)) {
            $query->where('geh_type', $type);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('geh_date >=', $startDate);
            $query->where('geh_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_exception_handle')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('geh_id, cs_name as shop_name, pg_name as goods_name, 
        geh_order, geh_date, geh_unit, geh_num, geh_type, u_name as operator, 
        geh_is_reduce_stock, geh_create_time, geh_update_time, 
        geh_provider_goods_id as goods_id, geh_remark, geh_type');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_exception_handle')->result_array();

        foreach ($rows as &$row) {
            $row['type'] = $row['geh_type'] == self::EXCEPTION_HANDLE_TYPE_CHAIM ? '索赔单' : '--';
            $row['remark'] = empty($row['geh_remark']) ? '--' : $row['geh_remark'];
            $row['num_unit'] = $row['geh_num'].'('. self::unitMap($row['geh_unit']) .')';
            $row['geh_type_text'] = $row['geh_type'] == 1 ? '索赔单' : '';
            $row['geh_is_reduce_stock_text'] = $row['geh_is_reduce_stock'] == 1 ? '是' : '否';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function addExceptionHandle(
        $userId,
        $shopId,
        $goodsId,
        $date,
        $type,
        $unit,
        $num,
        $order,
        $isReduceStock,
        $remark
    ) {
        $this->db->trans_begin();

        $insertData = [
            'geh_shop_id'           => $shopId,
            'geh_provider_goods_id' => $goodsId,
            'geh_date'              => $date,
            'geh_unit'              => $unit,
            'geh_num'               => $num,
            'geh_operator'          => $userId,
            'geh_order'             => $order,
            'geh_type'              => $type,
            'geh_is_reduce_stock'   => $isReduceStock,
            'geh_remark'            => $remark
        ];

        $this->db->insert('goods_exception_handle', $insertData);

        // 判断是否需要减少库存
        if ($isReduceStock) {
            $insertId = $this->db->insert_id();

            // 修改库存
            $modifyRes = $this->addRepertory(
                $shopId,
                $goodsId,
                $date,
                -$num,
                $unit,
                REPERTORY_TYPE_GOODS_EXCEPTION_HANDLE,
                $insertId
            );

            if ($modifyRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $modifyRes['msg']
                );
            }
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

    public function getExceptionHandleInfo($id)
    {
        $this->db->select('geh_id, geh_provider_goods_id as goods_id, geh_date as date,
        geh_unit as unit, geh_num as num, geh_order as order, 
        geh_is_reduce_stock as is_reduce_stock, geh_remark as remark, geh_type as type');
        $this->db->where('geh_id', $id);
        $query = $this->db->get('goods_exception_handle');
        $result = $query->first_row();
        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function editExceptionHandle($shopId, $id, $userId, $date, $unit, $num, $order, $isReduceStock, $remark)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $this->db->trans_begin();

        // 判断是减少库存
        if ($isReduceStock) {
            // 修改库存
            $editRes = $this->editRepertory(
                $shopId,
                REPERTORY_TYPE_GOODS_EXCEPTION_HANDLE,
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
        }

        $updateData = [
            'geh_operator' => $userId,
            'geh_date'     => $date,
            'geh_unit'     => $unit,
            'geh_num'      => $num,
            'geh_order'    => $order,
            'geh_remark'   => $remark
        ];
        $this->db->where('geh_id', $id);

        try {
            $this->db->update('goods_exception_handle',$updateData);
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

    public function deleteExceptionHandleRecord($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('geh_id', $id)->get('goods_exception_handle')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        // 判断是减少库存
        if ($row->geh_is_reduce_stock) {
            // 减少库存
            $delRes = $this->deleteRepertory(
                $row->geh_shop_id,
                REPERTORY_TYPE_GOODS_EXCEPTION_HANDLE,
                $row->geh_id
            );

            if ($delRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $delRes['msg']
                );
            }
        }

        // 删除记录
        $this->db->delete('goods_exception_handle', array('geh_id' => $id));

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
}