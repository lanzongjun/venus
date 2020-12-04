<?php

include_once 'BaseController.php';

class CoreRepertoryController extends BaseController
{
    public $_s_view  = 'CoreRepertoryView';
    public $_s_model = 'CoreRepertoryModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'CoreRepertoryController';
        $this->load->helper('url');
        $this->load->view("admin/stock/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $selectDate = isset($getData['select_date']) ? $getData['select_date'] : '';
        $providerId = isset($getData['provider_id']) ? $getData['provider_id'] : '';
        $goodsName = isset($getData['goods_name']) ? $getData['goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $isDownload = isset($getData['is_download']) ? $getData['is_download'] : 0;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $selectDate,
            $providerId,
            $goodsName,
            $page,
            $rows
        );

        if ($isDownload) {
            $intersectKeys = [
                'crd_id' => true,
                'shop_name' => true,
                'provider_name' => true,
                'crd_date' => true,
                'goods_name' => true,
                'num_unit' => true
            ];

            $intersectData = array_map(function ($item) use ($intersectKeys) {
                return array_intersect_key(array_merge($intersectKeys, $item), $intersectKeys);
            } ,$o_result['rows']);

            $this->output(
                '库存列表',
                [
                    '商品ID','店铺','供应商','日期','商品名称','剩余库存(单位)'
                ],
                $intersectData
            );
            exit();
        }


        echo json_encode($o_result);
    }
}