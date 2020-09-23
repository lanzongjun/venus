<?php

include_once 'BaseModel.php';

class GoodsStockModel extends BaseModel
{
    public function getList($startDate, $endDate, $providerGoodsName, $page, $rows, $rowsOnly)
    {

        $query = $this->db->join('provider_goods', 'gs_provider_goods_id = pg_id', 'left');
        $query->join('core_shop', 'cs_id = gs_shop_id', 'left');
        $query->join('user', 'u_id = gs_operator_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gs_date >=', $startDate);
            $query->where('gs_date <=', $endDate);
        }

        if (!empty($providerGoodsName)) {
            $query->like('pg_name', $providerGoodsName);
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
         u_name, gs_create_time, gs_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_stock')->result_array();

        foreach ($rows as &$row) {
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

    public function addGoodsStock($userId, $shopId, $providerGoodsId, $date, $num, $unit)
    {
        $this->db->trans_begin();
        try {
            $insertData = [
                'gs_shop_id'           => $shopId,
                'gs_provider_goods_id' => $providerGoodsId,
                'gs_date'              => $date,
                'gs_num'               => $num,
                'gs_unit'              => $unit,
                'gs_operator_id'       => $userId
            ];

            $this->db->insert('goods_stock', $insertData);

            // 添加库存
            $modifyRes = $this->modifyRepertory(
                $shopId, $providerGoodsId, $num, $unit,
                REPERTORY_TYPE_ADD_STOCK
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
        $this->modifyRepertory(
            $row->gs_shop_id,
            $row->gs_provider_goods_id,
            -$row->gs_num,
            $row->gs_unit, REPERTORY_TYPE_DELETE_STOCK);

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