<?php

include_once 'BaseModel.php';

class GoodsStockModel extends BaseModel
{
    public function getList($startDate, $endDate, $providerGoodsName, $page, $rows, $rowsOnly)
    {

        $query = $this->db->join('provider_goods', 'gs_provider_goods_id = pg_id', 'left');
        $query = $this->db->join('core_shop', 'cs_id = gs_shop_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $query = $this->db->where('gs_date >=', $startDate);
            $query = $this->db->where('gs_date <=', $endDate);
        }

        if (!empty($providerGoodsName)) {
            $query = $this->db->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('goods_stock')->result();
            if (empty($total['0']) || empty($total['0']->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('gs_id, cs_name, pg_name, gs_date, gs_stock, gs_create_time, gs_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_stock')->result();

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total['0']->total,
                'rows' => $rows
            );
        }
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