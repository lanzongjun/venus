<?php
require_once APPPATH . 'controllers/BaseController.php';

class GoodsSaleOnlineSummaryController extends BaseController
{
    public $_s_view  = 'GoodsSaleOnlineSummaryView';
    public $_s_model = 'GoodsSaleOnlineModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsSaleOnlineSummaryController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {

        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $goodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getSummaryList($startDate, $endDate, $goodsName, $page, $rows);

        echo json_encode($o_result);
    }
}