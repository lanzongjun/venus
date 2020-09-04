<?php

include_once 'BaseModel.php';

class GoodsStockModel extends BaseModel
{
    public function getList($startDate, $endDate, $providerGoodsName, $page, $rows)
    {
        $this->db->select('gs_id, cs_name, pg_name, gs_date, gs_stock, gs_create_time, gs_update_time');

        $this->db->join('provider_goods', 'gs_provider_goods_id = pg_id', 'left');
        $this->db->join('core_shop', 'cs_id = gs_shop_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $this->db->where('gs_date >=', $startDate);
            $this->db->where('gs_date <=', $endDate);
        }

        if (!empty($providerGoodsName)) {
            $this->db->like('pg_name', $providerGoodsName);
        }

        $offset = ($page - 1) * $rows;
        $this->db->limit($rows, $offset);

        $query = $this->db->get('goods_stock');

        return $query->result();
    }

    public function addGoodsStock($shopId, $providerGoodsId, $date, $stock)
    {
        $insertData = [
            'gs_shop_id' => $shopId,
            'gs_provider_goods_id' => $providerGoodsId,
            'gs_date' => $date,
            'gs_stock' => $stock,
        ];

        $this->db->insert('goods_stock', $insertData);

        return array(
            'state' => true,
            'msg' => '添加成功'
        );
    }

    public function deleteGoodsStockRecord($id)
    {
        $result = $this->db->delete('goods_stock', array('gs_id' => $id));

        return array(
            'state' => true,
            'msg'   => $result ? 1 : 0
        );
    }
}