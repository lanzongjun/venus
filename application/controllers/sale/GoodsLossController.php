<?php

require_once APPPATH . 'controllers/BaseController.php';

class GoodsLossController extends BaseController
{
    public $_s_view  = 'GoodsSaleLossView';
    public $_s_model = 'GoodsSaleLossModel';

    const SHOP_TYPE = 1;
    const ORDER_TYPE = 2;

    /**
     * 显示信息
     */
    public function index()
    {
        $getData = $this->getGetData();

        $type = $getData['type'];

        if ($type == self::SHOP_TYPE) {
            $data['title'] = '店内破损详情';
            $data['type']  = self::SHOP_TYPE;
        } else {
            $data['title'] = '退单详情';
            $data['type']  = self::ORDER_TYPE;
        }
        $data['c_name'] = 'sale/GoodsLossController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    /**
     * 获取列表
     * @author zongjun.lan
     */
    public function getList()
    {
        $getData = $this->input->get();

        $goodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $lossType  = isset($getData['type']) ? $getData['type'] : self::SHOP_TYPE;
        $page      = isset($getData['page']) ? $getData['page'] : 1;
        $rows      = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getList($this->shop_id, $goodsName, $lossType, $page, $rows);
        echo json_encode($o_result);
    }

    public function getGoodsLossInfo()
    {
        $getData = $this->getGetData();
        $id = isset($getData['id']) ? $getData['id'] : '';

        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getGoodsLossInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addGoodsLossInfo()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['pg_id']) ? $postData['pg_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $unit     = isset($postData['unit']) ? $postData['unit'] : '';
        $order   = isset($postData['order']) ? $postData['order'] : '';
        $type    = isset($postData['type']) ? $postData['type'] : '';

        if (empty($goodsId) || empty($date) || empty($num) || empty($type) || empty($unit)) {
            echo array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->addGoodsLossInfo(
            $this->shop_id,
            $this->user_id,
            $type,
            $goodsId,
            $date,
            $num,
            $unit,
            $order
        );

        echo json_encode($o_result);
    }

    public function editGoodsLossInfo()
    {
        $postData = $this->getPostData();

        $date    = isset($postData['date']) ? $postData['date'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $unit     = isset($postData['unit']) ? $postData['unit'] : '';
        $order   = isset($postData['order']) ? $postData['order'] : '';
        $type   = isset($postData['gl_type']) ? $postData['gl_type'] : '';
        $id      = isset($postData['gl_id']) ? $postData['gl_id'] : '';

        if (empty($date) || empty($num) || empty($unit) || empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsLossInfo(
            $this->shop_id,
            $id,
            $this->user_id,
            $date,
            $num,
            $unit,
            $type,
            $order);

        echo json_encode($result);
    }

    public function deleteGoodsLoss()
    {
        $postData = $this->getPostData();

        $id = isset($postData['id']) ? $postData['id'] : '';

        if (empty($id)) {
            echo array(
                'state' => false,
                'msg'   => '参数不正确'
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsLoss($id);

        echo json_encode($result);
    }
}