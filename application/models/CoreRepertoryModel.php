<?php

include_once 'BaseModel.php';

class CoreRepertoryModel extends BaseModel
{
    public function getList($shopId, $selectDate, $providerId, $goodsName, $page, $rows)
    {
        $query = $this->db;
        $query->join('provider_goods', 'crd_provider_goods_id = pg_id', 'left');
        $query->join('provider', 'pg_provider_id = p_id');
        $query->join('core_shop', 'crd_shop_id = cs_id', 'left');
        $query->where('crd_shop_id', intval($shopId));

        if (!empty($selectDate)) {
            $query->where('crd_date', $selectDate);
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
        $queryList->select('crd_id, p_name as provider_name, pg_id as goods_id, pg_is_dumplings, cs_name as shop_name, pg_name as goods_name, crd_date, crd_num, crd_unit');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);
        $queryList->order_by('crd_date desc, crd_provider_goods_id asc');

        $rows = $queryList->get('core_repertory_daily')->result_array();

        $this->db->close();


        // 获取盘点数据
        $this->load->model('ProviderGoodsCheckModel');
        $checkRows = $this->ProviderGoodsCheckModel->getDetailList($shopId, $selectDate);
        if (!empty($checkRows)) {
            $checkRows = array_column($checkRows, NULL, 'pgcd_provider_goods_id');
        }



        foreach ($rows as &$row) {

            if ($row['pg_is_dumplings']) {
                $numUnit = round($row['crd_num'] / 500, 4);
                $row['num_unit'] = $numUnit.'(斤)';

                if (empty($checkRows[$row['goods_id']]['pgcd_num'])) {
                    $row['check_num_unit'] = '--';
                    $row['diff_num_unit'] = '--';
                } else {
                    $checkUnitNum = $checkRows[$row['goods_id']]['pgcd_num'];
                    $row['check_num_unit'] = $checkUnitNum.'(斤)';
                    $gap = $checkUnitNum - $numUnit;
                    $row['diff_num_unit'] = round($gap, 4).'(斤)';
                }
            } else {
                $numUnit = round($row['crd_num']);
                $row['num_unit'] = $numUnit.'(个)';


                if (empty($checkRows[$row['goods_id']]['pgcd_num'])) {
                    $row['check_num_unit'] = '--';
                    $row['diff_num_unit'] = '--';
                } else {
                    $checkUnitNum = $checkRows[$row['goods_id']]['pgcd_num'];
                    $row['check_num_unit'] = intval($checkUnitNum).'(个)';
                    $gap = $checkUnitNum - $numUnit;
                    $row['diff_num_unit'] = $gap.'(个)';
                }
            }
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }
}