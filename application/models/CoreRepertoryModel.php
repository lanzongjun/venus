<?php

include_once 'BaseModel.php';

class CoreRepertoryModel extends BaseModel
{
    public function getList($shopId, $providerId, $goodsName, $page, $rows)
    {
        $query = $this->db;
        $query->join('provider_goods', 'cr_provider_goods_id = pg_id', 'left');
        $query->join('provider', 'pg_provider_id = p_id');
        $query->join('core_shop', 'cr_shop_id = cs_id', 'left');

        if (!empty($shopId)) {
            $query->where('cs_id', $shopId);
        }

        if (!empty($providerId)) {
            $query->where('p_id', $providerId);
        }

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone  $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('core_repertory')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('cr_id, p_name as provider_name, pg_id as goods_id, cs_name as shop_name, pg_name as goods_name, cr_num, cr_unit');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('core_repertory')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['cr_num'].'('. self::unitMap($row['cr_unit']) .')';

        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }
}