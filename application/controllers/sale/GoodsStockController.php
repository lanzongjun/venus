<?php

require_once APPPATH . 'controllers/BaseController.php';

class GoodsStockController extends BaseController
{
    public $_s_view  = 'GoodsStockView';
    public $_s_model = 'GoodsStockModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsStockController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate   = isset($getData['end_date']) ? $getData['end_date'] : '';
        $goodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page      = isset($getData['page']) ? $getData['page'] : 1;
        $rows      = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly  = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $startDate, $endDate,
            $goodsName,
            $page,
            $rows,
            $rowsOnly
        );

        echo json_encode($result);
    }

    public function addGoodsStock()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $unit    = isset($postData['unit']) ? $postData['unit'] : '';
        $remark  = isset($postData['remark']) ? $postData['remark'] : '';

        if (empty($goodsId) || empty($date) || empty($num) || empty($unit)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsStock(
            $this->user_id,
            $this->shop_id,
            $goodsId,
            $date,
            $num,
            $unit,
            $remark
        );

        echo json_encode($result);
    }

    public function deleteGoodsStockRecord()
    {
        $postData = $this->getPostData();

        $gsId = isset($postData['gs_id']) ? $postData['gs_id'] : '';

        if (empty($gsId)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsStockRecord($gsId);

        echo json_encode($result);
    }

    public function getGoodsStockInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getGoodsStockInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function editGoodsStock()
    {
        $postData = $this->getPostData();

        $date   = isset($postData['date']) ? $postData['date'] : '';
        $num    = isset($postData['num']) ? $postData['num'] : '';
        $unit   = isset($postData['unit']) ? $postData['unit'] : '';
        $remark = isset($postData['remark']) ? $postData['remark'] : '';
        $id     = isset($postData['gs_id']) ? $postData['gs_id'] : '';

        if (empty($date) || empty($num) || empty($id) || empty($unit)) {
            echo json_encode(
                array(
                    'state' => false,
                    'msg'   => '参数不正确'
                )
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsStock(
            $this->shop_id,
            $id,
            $this->user_id,
            $date,
            $num,
            $unit,
            $remark
        );

        echo json_encode($result);
    }
}