<?php

include_once 'BaseModel.php';

class GoodsSaleOfflineModel extends BaseModel
{
    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db->join('core_shop', 'cs_id = gso_shop_id', 'left');
        $query->join('core_sku', 'core_sku.cs_code = gso_sku_code', 'left');

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
        gso_sku_code, core_sku.cs_name as sku_name, gso_num, gso_create_time, gso_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_sale_offline')->result_array();


        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function getSaleOfflineInfo($id)
    {

        $this->db->select('gso_id, gso_sku_code as cs_code, gso_date as date, gso_num as num');

        $this->db->where('gso_id', $id);

        $result = $this->db->get('goods_sale_offline')->first_row();

        return $result;
    }

    public function addGoodsSaleOffline($userId, $shopId, $skuCode, $date, $num)
    {
        $insertData = [
            'gso_operator' => $userId,
            'gso_shop_id'  => $shopId,
            'gso_sku_code' => $skuCode,
            'gso_date'     => $date,
            'gso_num'      => $num
        ];

        $result = $this->db->insert('goods_sale_offline', $insertData);

        return array(
            'state' => true,
            'msg' => '添加成功'
        );
    }

    public function editGoodsSaleOffline($id, $userId, $skuCode, $date, $num)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'gso_date'     => $date,
            'gso_sku_code' => $skuCode,
            'gso_num'      => $num,
            'gso_operator' => $userId
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
        return $o_result;
    }

    public function deleteGoodsSaleOffline($id)
    {
        // 删除记录

        // 回滚库存
    }
}