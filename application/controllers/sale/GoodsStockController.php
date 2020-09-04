<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/application/controllers/BaseController.php';

class GoodsStockController extends BaseController
{
    public $_s_view  = 'GoodsStockView';
    public $_s_model = 'GoodsStockModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'GoodsStockController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($startDate, $endDate, $providerGoodsName, $page, $rows, $rowsOnly);

        echo json_encode($result);
    }

    public function addGoodsStock()
    {
        $postData = $this->getPostData();

        $providerGoodsId = isset($postData['pg_id']) ? $postData['pg_id'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $stock = isset($postData['stock']) ? $postData['stock'] : '';

        if (empty($providerGoodsId) || empty($date) || empty($stock)) {
            return array(
                'state' => false,
                'msg'   => '参数不正确'
            );
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsStock($this->shop_id, $providerGoodsId, $date, $stock);

        echo json_encode($result);
    }

    public function deleteGoodsStockRecord()
    {
        $postData = $this->getPostData();

        $gsId = isset($postData['gs_id']) ? $postData['gs_id'] : '';

        if (empty($gsId)) {
            return json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsStockRecord($gsId);

        echo json_encode($result);
    }
}