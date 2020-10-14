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
}