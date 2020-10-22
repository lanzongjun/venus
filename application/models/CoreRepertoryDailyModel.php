<?php

include_once 'BaseModel.php';

class CoreRepertoryDailyModel extends BaseModel
{
    public $model = 'core_repertory_daily';

    public function insertData($shopId)
    {
        // 查看昨日库存是否存在
        $yesterday = date('Y-m-d', strtotime("-1 day"));
        $yesterdayData = $this->db
            ->where('crd_shop_id', $shopId)
            ->where('crd_date', $yesterday)
            ->get($this->model)
            ->result_array();

        if (!empty($yesterdayData)) {
            // 昨日库存已经记录 退出
            log_message('debug', '昨日数据已经记录-'.date('Y-m-d H:i:s'));
            return true;
        }

        // 获取昨日库存
        $coreRepertory = $this->db
            ->where('cr_shop_id', $shopId)
            ->get('core_repertory')
            ->result_array();

        if (empty($coreRepertory)) {
            // 没有该店铺的库存数据
            log_message('debug', '该店铺昨日库存数据为空-'.date('Y-m-d H:i:s'));
            return true;
        }

        // 获取上一条每日库存日期，用于补全没有登录的日期
        $lastDailyRepertory = $this->db
            ->where('crd_shop_id', $shopId)
            ->order_by('crd_date desc')
            ->get($this->model)
            ->first_row();
        if (empty($lastDailyRepertory)) {
            $lastInsertDate = $yesterday;
        } else {
            $lastInsertDate = date('Y-m-d', strtotime($lastDailyRepertory->crd_date) + 86400);
        }

//TODO 逻辑有问题
        $insertDailyData = [];
        while (true) {
            foreach ($coreRepertory as $item) {
                $insertDailyData[] = [
                    'crd_shop_id'           => $shopId,
                    'crd_provider_goods_id' => $item['cr_provider_goods_id'],
                    'crd_date'              => $lastInsertDate,
                    'crd_num'               => $item['cr_num'],
                    'crd_unit'              => $item['cr_unit'],
                ];
            }

            if ($lastInsertDate == $yesterday) {
                break;
            }
            $lastInsertDate = date('Y-m-d', strtotime($lastInsertDate) + 86400);
        }

        $this->db->insert_batch($this->model, $insertDailyData);

        log_message('debug', '该店铺昨日库存数据添加成功-'.date('Y-m-d H:i:s'));
        return true;
    }

    public function getLastThreeDayRepertory($shopId, $goodsName, $page, $rows)
    {
        $lastThreeDate = date('Y-m-d', strtotime('-3 day'));

        $query = $this->db;

        $queryTotal = clone $query;
        $queryList  = clone $query;

        $queryList->join('provider_goods', 'pg_id = crd_provider_goods_id', 'left')
            ->where('crd_shop_id', $shopId)
            ->where('crd_date >=', $lastThreeDate)
            ->group_by('crd_provider_goods_id');

        if (!empty($goodsName)) {
            $query->like('pg_name', $goodsName);
        }
        
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get($this->model)->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        $queryList->select('pg_name as goods_name, crd_provider_goods_id as goods_id, sum(crd_num) as num, crd_unit as unit, count(1) as total');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $lastThreeData = $queryList->get($this->model)->result_array();
        $goodsIds = array_column($lastThreeData, 'goods_id');
        // 库存
        $repertory = $this->db
            ->where('cr_shop_id', $shopId)
            ->where_in('cr_provider_goods_id', $goodsIds)
            ->select('cr_provider_goods_id as goods_id, cr_num as num, cr_unit as unit')
            ->get('core_repertory')
            ->result_array();

        $repertory = array_column($repertory, NULL, 'goods_id');

        $returnData = [];
        foreach ($lastThreeData as $item) {
            // 平均销量
            if ($item['unit'] == 1) {
                $avgSale = empty($item['total']) ? 0 : round($item['num'] / $item['total'], 4);
            } else {
                $avgSale = empty($item['total']) ? 0 : round($item['num'] / $item['total'] / 500, 4);
            }
            // 库存
            $stock = isset($repertory[$item['goods_id']]['num']) ? $repertory[$item['goods_id']]['num'] : 0;
            $returnData[] = [
                'goods_name' => $item['goods_name'],
                'unit' => self::unitMap($item['unit']),
                'avg_sale' => $avgSale,
                'stock' => $stock,
                'is_add' => $stock >= $avgSale ? '否' : '是'
            ];
        }

        return array(
            'total' => $total->total,
            'rows' => $returnData
        );
    }
}