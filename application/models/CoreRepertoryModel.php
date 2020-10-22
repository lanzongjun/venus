<?php

include_once 'BaseModel.php';

class CoreRepertoryModel extends BaseModel
{
    public function getList($shopId, $startDate, $endDate, $providerId, $goodsName, $page, $rows)
    {
        $query = $this->db;
        $query->join('provider_goods', 'crd_provider_goods_id = pg_id', 'left');
        $query->join('provider', 'pg_provider_id = p_id');
        $query->join('core_shop', 'crd_shop_id = cs_id', 'left');
        $query->where('crd_shop_id', $shopId);

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('crd_date >=', $startDate);
            $query->where('crd_date <=', $endDate);
        }

        if (!empty($providerId)) {
            $query->where('p_id', $providerId);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('core_repertory_daily')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('crd_id, p_name as provider_name, pg_id as goods_id, cs_name as shop_name, pg_name as goods_name, crd_date, crd_num, crd_unit');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);
        $queryList->order_by('crd_date desc, crd_provider_goods_id asc');

        $rows = $queryList->get('core_repertory_daily')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = round($row['crd_num'] / 500, 4).'(斤)';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }
}