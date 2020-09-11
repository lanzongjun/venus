<?php

include_once 'BaseController.php';

class ProviderGoodsSampleController extends BaseController
{
    public $_s_view  = 'ProviderGoodsSampleView';
    public $_s_model = 'ProviderGoodsSampleModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'ProviderGoodsSampleController';
        $this->load->helper('url');
        $this->load->view("admin/baseCheck/$this->_s_view", $data);
    }

    /**
     * 获取列表
     * @author zongjun.lan
     */
    public function getList()
    {
        $getData = $this->input->get();

        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getList($providerGoodsName, $page, $rows, $rowsOnly);
        echo json_encode($o_result);
    }

    public function getProviderGoodsSampleInfo()
    {
        $getData = $this->getGetData();
        $id = isset($getData['id']) ? $getData['id'] : '';

        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getProviderGoodsSampleInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addProviderGoodsSampleInfo()
    {
        $postData = $this->getPostData();

        $pgId = isset($postData['pg_id']) ? $postData['pg_id'] : '';
        $weight = isset($postData['weight']) ? $postData['weight'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';

        if (empty($pgId) || empty($weight) || empty($num)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->addProviderGoodsSampleInfo($pgId, $weight, $num);

        echo json_encode($o_result);
    }

    public function editProviderGoodsSampleInfo()
    {
        $postData = $this->getPostData();

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editProviderGoodsSampleInfo($postData);
        echo json_encode($result);
    }
}