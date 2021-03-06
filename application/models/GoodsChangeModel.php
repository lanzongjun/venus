<?php
include_once 'BaseModel.php';

class GoodsChangeModel extends BaseModel
{
    const CHANGE_TYPE_IN = 1;//转入

    const CHANGE_TYPE_OUT = 2;//转出

    public function getList($shopId, $startDate, $endDate, $goodsName, $page, $rows)
    {
        // 获取店铺信息
        $shop = $this->getShop();

        $query = $this->db;

        $query->join('provider_goods', 'gc_provider_goods_id = pg_id', 'left');
        $query->join('user', 'u_id = gc_operator', 'left');
        $query->where('gc_shop_id', intval($shopId));

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gc_date >=', $startDate);
            $query->where('gc_date <=', $endDate);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_change')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('gc_id, pg_name, gc_shop_id, gc_date, gc_unit, gc_num, 
        gc_change_type, gc_change_shop, gc_remark, gc_create_time, gc_update_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_change')->result_array();

        $shopMap = array_column($shop, NULL, 'cs_id');
        foreach ($rows as &$row) {
            $row['shop_name'] = isset($shopMap[$row['gc_shop_id']]) ? $shopMap[$row['gc_shop_id']]['cs_name'] : '';
            $row['change_shop'] = isset($shopMap[$row['gc_change_shop']]) ? $shopMap[$row['gc_change_shop']]['cs_name'] : '';
            $row['num_unit'] = $row['gc_num'].'('. self::unitMap($row['gc_unit']) .')';
            $row['change_type'] = $row['gc_change_type'] == 1 ? '转入' : '转出';
            $row['remark'] = empty($row['gc_remark']) ? '--' : $row['gc_remark'];
        }

        return array(
            'total' => intval($total->total),
            'rows'  => $rows
        );
    }

    public function getShop()
    {
        $shop = $this->db->select('*')->get('core_shop')->result_array();

        return $shop;
    }

    public function addGoodsChange(
        $userId,
        $shopId,
        $goodsId,
        $date,
        $unit,
        $num,
        $changeType,
        $changeShop,
        $remark
    ) {

        $this->db->trans_begin();

        $insertData = [
            'gc_shop_id'           => $shopId,
            'gc_provider_goods_id' => $goodsId,
            'gc_date'              => $date,
            'gc_unit'              => $unit,
            'gc_num'               => $num,
            'gc_change_type'       => $changeType,
            'gc_change_shop'       => $changeShop,
            'gc_operator'          => $userId,
            'gc_remark'            => $remark
        ];

        $this->db->insert('goods_change', $insertData);

        $insertId = $this->db->insert_id();

        // 修改库存
        if ($changeType == self::CHANGE_TYPE_IN) {
            // 修改库存
            $modifyRes = $this->addRepertory(
                $shopId,
                $goodsId,
                $date,
                $num,
                $unit,
                REPERTORY_TYPE_GOODS_CHANGE_IN,
                $insertId
            );
            if ($modifyRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $modifyRes['msg']
                );
            }
            // TODO 由于其他店铺库存问题， 店铺转入不计算入内
//            $modifyRes = $this->addRepertory(
//                $changeShop,
//                $goodsId,
//                $date,
//                -$num,
//                $unit,
//                REPERTORY_TYPE_GOODS_CHANGE_OUT,
//                $insertId
//            );
//            if ($modifyRes['state'] === false) {
//                $this->db->trans_rollback();
//
//                return array(
//                    'state' => false,
//                    'msg'   => $modifyRes['msg']
//                );
//            }
        } else {
            // 修改库存
            $modifyRes = $this->addRepertory(
                $shopId,
                $goodsId,
                $date,
                -$num,
                $unit,
                REPERTORY_TYPE_GOODS_CHANGE_OUT,
                $insertId
            );
            if ($modifyRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $modifyRes['msg']
                );
            }
            $modifyRes = $this->addRepertory(
                $changeShop,
                $goodsId,
                $date,
                $num,
                $unit,
                REPERTORY_TYPE_GOODS_CHANGE_IN,
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

    public function getGoodsChangeInfo($id)
    {
        $this->db->select('gc_id, gc_provider_goods_id as goods_id, gc_date as date,
        gc_unit as unit, gc_num as num, gc_change_type as change_type, 
        gc_change_shop as change_shop, gc_remark as remark');
        $this->db->where('gc_id', $id);
        $query = $this->db->get('goods_change');
        $result = $query->first_row();
        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function editGoodsChange($id, $userId, $goodsId, $date, $unit, $num, $changeType, $changeShop, $remark)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'gc_operator'          => $userId,
            'gc_provider_goods_id' => $goodsId,
            'gc_date'              => $date,
            'gc_unit'              => $unit,
            'gc_num'               => $num,
            'gc_change_type'       => $changeType,
            'gc_change_shop'       => $changeShop,
            'gc_remark'            => $remark
        ];
        $this->db->where('gc_id', $id);

        try {
            $this->db->update('goods_change',$updateData);
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

    public function deleteGoodsChangeRecord($id)
    {
        $this->db->trans_begin();

        $row = $this->db->where('gc_id', $id)->get('goods_change')->first_row();
        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        // 删除记录
        $this->db->delete('goods_change', array('gc_id' => $id));

        // 修改库存
        if ($row->gc_change_type == self::CHANGE_TYPE_IN) {
            $delRes = $this->deleteRepertory(
                $row->gc_shop_id,
                REPERTORY_TYPE_GOODS_CHANGE_IN,
                $row->gc_id
            );

            if ($delRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $delRes['msg']
                );
            }

            // TODO 由于其他店铺库存问题， 店铺转入不计算入内
//            $delRes = $this->deleteRepertory(
//                $row->gc_change_shop,
//                REPERTORY_TYPE_GOODS_CHANGE_OUT,
//                $row->gc_id
//            );
//
//            if ($delRes['state'] === false) {
//                $this->db->trans_rollback();
//
//                return array(
//                    'state' => false,
//                    'msg'   => $delRes['msg']
//                );
//            }
        } else {
            $delRes = $this->deleteRepertory(
                $row->gc_shop_id,
                REPERTORY_TYPE_GOODS_CHANGE_OUT,
                $row->gc_id
            );

            if ($delRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $delRes['msg']
                );
            }
            $delRes = $this->deleteRepertory(
                $row->gc_change_shop,
                REPERTORY_TYPE_GOODS_CHANGE_IN,
                $row->gc_id
            );

            if ($delRes['state'] === false) {
                $this->db->trans_rollback();

                return array(
                    'state' => false,
                    'msg'   => $delRes['msg']
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
            'msg' => '删除成功'
        );
    }
}