<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/9/30
 * Time: 9:08 AM
 */

class repertoryDaily
{
    public function run($params)
    {
        $shopId = $params['shop_id'];

        $CI=&get_instance();
        $CI->load->model('CoreRepertoryDailyModel');
        $CI->CoreRepertoryDailyModel->insertData($shopId);

        return true;
    }
}