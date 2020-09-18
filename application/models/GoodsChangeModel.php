<?php
include_once 'BaseModel.php';

class GoodsChangeModel extends BaseModel
{
    public function getList($startDate, $endDate, $providerGoodsName, $page, $rows)
    {
        // 获取店铺信息
        $shop = $this->getShop();

        $query = $this->db;

        $query->join('provider_goods', 'gc_provider_goods_id = pg_id', 'left');
        $query->join('user', 'u_id = gc_operator', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('gc_date >=', $startDate);
            $query->where('gc_date <=', $endDate);
        }

        if (!empty($providerGoodsName)) {
            $query->like('pg_name', $providerGoodsName);
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
        gc_change_type, gc_change_shop, gc_create_time, gc_update_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_change')->result_array();

        $shopMap = array_column($shop, NULL, 'cs_id');
        foreach ($rows as &$row) {
            $row['shop_name'] = isset($shopMap[$row['gc_shop_id']]) ? $shopMap[$row['gc_shop_id']]['cs_name'] : '';
            $row['change_shop'] = isset($shopMap[$row['gc_change_shop']]) ? $shopMap[$row['gc_change_shop']]['cs_name'] : '';
            $row['num_unit'] = $row['gc_num'].'('. self::unitMap($row['gc_unit']) .')';
            $row['change_type'] = $row['gc_change_type'] == 1 ? '转入' : '转出';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
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
        $changeShop)
    {
        $insertData = [
            'gc_shop_id'           => $shopId,
            'gc_provider_goods_id' => $goodsId,
            'gc_date'              => $date,
            'gc_unit'              => $unit,
            'gc_num'               => $num,
            'gc_change_type'       => $changeType,
            'gc_change_shop'       => $changeShop,
            'gc_operator'          => $userId
        ];

        $this->db->insert('goods_change', $insertData);

        return array(
            'state' => true,
            'msg' => '添加成功'
        );
    }

    public function getGoodsChangeInfo($id)
    {
        $this->db->select('gc_id, gc_provider_goods_id as goods_id, gc_date as date,
        gc_unit as unit, gc_num as num, gc_change_type as change_type, gc_change_shop as change_shop');
        $this->db->where('gc_id', $id);
        $query = $this->db->get('goods_change');
        $result = $query->first_row();
        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function editGoodsChange($id, $userId, $goodsId, $date, $unit, $num, $changeType, $changeShop)
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
            'gc_change_shop'       => $changeShop
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
        $result = $this->db->delete('goods_change', array('gc_id' => $id));

        return array(
            'state' => true,
            'msg'   => $result ? 1 : 0
        );
    }
}