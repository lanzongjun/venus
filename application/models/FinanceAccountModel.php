<?php

include_once 'BaseModel.php';

class FinanceAccountModel extends BaseModel
{
    public function getList($shopId, $startDate, $endDate)
    {

        $query = $this->db;
        $query->join('provider_goods', 'pg_id = crr_provider_goods_id', 'left');
        $query->where('crr_shop_id', $shopId);
        if (!empty($startDate) && !empty($endDate)) {
            $query->where('crr_date >=', $startDate);
            $query->where('crr_date <=', $endDate);
        }
        $query->select('crr_type, crr_provider_goods_id, pg_name, pg_is_dumplings, crr_num, crr_unit');
        $query->group_by('crr_provider_goods_id,crr_type,crr_unit');
        $rows = $query->get('core_repertory_record')->result_array();

        $returnData = [];

        //入库数量
        //线上销售
        //线下销售
        //异常订单
        //员工餐
        //损耗
        foreach ($rows as $row) {
            if (isset($returnData[$row['crr_provider_goods_id']])) {
                $num = $this->transferToGram(
                    $row['crr_num'],
                    $row['crr_unit'],
                    $row['crr_provider_goods_id'],
                    $row['pg_is_dumplings']
                );
                $accountData = $returnData[$row['crr_provider_goods_id']]['data'];
                $accountData[$row['crr_type']]['num'] += abs($num);
                $returnData[$row['crr_provider_goods_id']]['data'] = $accountData;

            } else {
                $num = $this->transferToGram(
                    $row['crr_num'],
                    $row['crr_unit'],
                    $row['crr_provider_goods_id'],
                    $row['pg_is_dumplings']
                );
                $accountData = $this->dataFormat();
                $accountData[$row['crr_type']]['num'] = abs($num);
                $returnData[$row['crr_provider_goods_id']] = [
                    'goods_id' => $row['crr_provider_goods_id'],
                    'goods_name' => $row['pg_name'],
                    'unit' => $row['pg_is_dumplings'] == 1 ? '克' : '个',
                    'data' => $accountData
                ];
            }
        }
        //TODO 盘点


        return array(
            'total' => count($returnData),
            'rows' => array_values($returnData)
        );

    }

    private function dataFormat()
    {
        return [
            REPERTORY_TYPE_GOODS_STOCK => [
                'title' => '入库数量',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_SALE_OFFLINE_SHIHUA => [
                'title' => '线下销售-石化结算',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_SALE_OFFLINE_LOCATE => [
                'title' => '线下销售-线下销售',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_SALE_ONLINE => [
                'title' => '线上销售',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_EXCEPTION_HANDLE => [
                'title' => '异常订单-索赔单',
                'num'   => 0,
            ],
            REPERTORY_TYPE_STAFF_MEAL => [
                'title' => '员工餐',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_SHOP_LOSS => [
                'title' => '损耗-店内破损',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_ORDER_LOSS => [
                'title' => '损耗-退单',
                'num'   => 0,
            ],
            REPERTORY_TYPE_GOODS_CHANGE => [
                'title' => '调度',
                'num'   => 0,
            ],
            '999' => [
                'title' => '盘点',
                'num'   => 0
            ]
        ];
    }

}