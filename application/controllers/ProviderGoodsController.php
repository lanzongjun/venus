<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/8/31
 * Time: 5:00 PM
 */

include_once 'BaseController.php';

/**
 * 供应商-商品
 * Class ProviderGoodsController
 */
class ProviderGoodsController extends BaseController
{
    public $_s_view  = 'ProviderGoodsView';
    public $_s_model = 'ProviderGoodsModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'ProviderGoodsController';
        $this->load->helper('url');
        $this->load->view("admin/baseCheck/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->input->get();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $providerName = isset($getData['provider_name']) ? $getData['provider_name'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList($providerName, $providerGoodsName, $page, $rows, $rowsOnly);
        echo json_encode($o_result);
    }

    public function getProviderGoodsInfo()
    {
        $getData = $this->getGetData();
        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getProviderGoodsInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addProviderGoods()
    {
        $postData = $this->getPostData();

        $providerId = isset($postData['p_id']) ? $postData['p_id'] : '';
        $providerGoodsName = isset($postData['pg_name']) ? $postData['pg_name'] : '';

        if (empty($providerId) || empty($providerGoodsName)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addProviderGoods($providerId, $providerGoodsName);

        echo json_encode($result);
    }

    public function editProviderGoods()
    {
        $postData = $this->getPostData();

        $pId = isset($postData['p_id']) ? $postData['p_id'] : '';
        $pgName = isset($postData['pg_name']) ? $postData['pg_name'] : '';
        $pgId = isset($postData['pg_id']) ? $postData['pg_id'] : '';

        if (empty($pId) || empty($pgName) || empty($pgId)) {
            echo json_encode(array(
                'state' => false,
                'msg' => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editProviderGoods($postData);

        echo json_encode($result);
    }
}