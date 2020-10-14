<?php

include_once 'BaseController.php';

class SaleForecastBaseStockController extends BaseController
{
    public $_s_view  = 'BaseStockView';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'SaleForecastBaseStockController';
        $this->load->helper('url');
        $this->load->view("admin/sale_forecast/$this->_s_view", $data);
    }

    /**
     * 获取列表
     * @author zongjun.lan
     */
    public function getList()
    {
        $getData = $this->input->get();

        $goodsName = isset($getData['goods_name']) ? $getData['goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model('CoreRepertoryDailyModel');
        $o_result = $this->CoreRepertoryDailyModel->getLastThreeDayRepertory($this->shop_id, $goodsName, $page, $rows);
        echo json_encode($o_result);
    }
}