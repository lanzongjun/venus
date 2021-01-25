<?php

include_once 'BaseController.php';

class ProviderGoodsSkuController extends BaseController
{
    public $_s_view  = 'ProviderGoodsSkuView';
    public $_s_model = 'ProviderGoodsSkuModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'ProviderGoodsSkuController';
        $this->load->helper('url');
        $this->load->view("admin/baseCheck/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->input->get();

        $skuCode = isset($getData['sku_code']) ? $getData['sku_code'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList($skuCode, $providerGoodsName, $page, $rows);
        echo json_encode($o_result);
    }

    public function getProviderGoodsSkuInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';

        if ($id != '') {
            $this->load->model($this->_s_model);
            $result = $this->{$this->_s_model}->getProviderGoodsSkuInfo($id);
            echo json_encode($result);
        } else {
            echo '';
        }
    }

    public function addProviderGoodsSku()
    {
        $postData = $this->getPostData();

        $skuCode = isset($postData['cs_code']) ? $postData['cs_code'] : '';
        $providerGoods = isset($postData['pg_id']) ? $postData['pg_id'] : '';
        $num = isset($postData['pgs_num']) ? $postData['pgs_num'] : '';

        if (empty($skuCode) || empty($providerGoods) || empty($num)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addProviderGoodsSku($skuCode, $providerGoods, $num);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsSkuConstant::ADD_PROVIDER_GOODS_SKU,
            ProviderGoodsSkuConstant::getMessage(ProviderGoodsSkuConstant::ADD_PROVIDER_GOODS_SKU),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function editProviderGoodsSku()
    {
        $postData = $this->getPostData();

        $providerGoodsSkuId = isset($postData['pgs_id']) ? $postData['pgs_id'] : '';
        $providerGoodsId = isset($postData['pg_id']) ? $postData['pg_id'] : '';
        $skuCode = isset($postData['cs_code']) ? $postData['cs_code'] : '';
        $num = isset($postData['pgs_num']) ? $postData['pgs_num'] : '';

        if (empty($providerGoodsSkuId) || empty($providerGoodsId)
        || empty($skuCode) || empty($num)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editProviderGoodsSku($providerGoodsSkuId, $providerGoodsId, $skuCode, $num);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsSkuConstant::EDIT_PROVIDER_GOODS_SKU,
            ProviderGoodsSkuConstant::getMessage(ProviderGoodsSkuConstant::EDIT_PROVIDER_GOODS_SKU),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}