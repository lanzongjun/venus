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

        $shopId = isset($getData['shop_id']) ? $getData['shop_id'] : '';
        $providerId = isset($getData['provider_id']) ? $getData['provider_id'] : '';
        $goodsName = isset($getData['goods_name']) ? $getData['goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList($shopId, $providerId, $goodsName, $page, $rows);

        echo json_encode($o_result);
    }
}