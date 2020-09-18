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

        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $lossType = isset($getData['type']) ? $getData['type'] : self::SHOP_TYPE;
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getList($providerGoodsName, $lossType, $page, $rows);
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
        $order   = isset($postData['order']) ? $postData['order'] : '';
        $type    = isset($postData['type']) ? $postData['type'] : '';

        if (empty($goodsId) || empty($date) || empty($num) || empty($type)) {
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
            $order
        );

        echo json_encode($o_result);
    }

    public function editGoodsLossInfo()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $order   = isset($postData['order']) ? $postData['order'] : '';
        $id      = isset($postData['gl_id']) ? $postData['gl_id'] : '';

        if (empty($goodsId) || empty($date) || empty($num) || empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsLossInfo($id, $this->user_id, $goodsId, $date, $num, $order);

        echo json_encode($result);
    }
}