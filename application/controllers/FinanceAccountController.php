<?php

include_once 'BaseController.php';

class FinanceAccountController extends BaseController
{
    public $_s_view  = 'FinanceAccountView';
    public $_s_model = 'FinanceAccountModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'FinanceAccountController';
        $this->load->helper('url');
        $this->load->view("admin/stock/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $startDate,
            $endDate
        );

        echo json_encode($o_result);
    }
}