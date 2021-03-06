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
        $getData = $this->getGetData();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $providerName = isset($getData['provider_name']) ? $getData['provider_name'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;
        $dumplingsOnly = isset($getData['dumplings_only']) ? $getData['dumplings_only'] : false;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $providerName,
            $providerGoodsName,
            $page,
            $rows,
            $rowsOnly,
            $dumplingsOnly
        );

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
        $isDumplings = isset($postData['is_dumplings']) ? $postData['is_dumplings'] : 0;

        if (empty($providerId) || empty($providerGoodsName)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addProviderGoods($providerId, $providerGoodsName, $isDumplings);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsConstant::ADD_PROVIDER_GOODS,
            ProviderGoodsConstant::getMessage(ProviderGoodsConstant::ADD_PROVIDER_GOODS),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

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

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsConstant::EDIT_PROVIDER_GOODS,
            ProviderGoodsConstant::getMessage(ProviderGoodsConstant::EDIT_PROVIDER_GOODS),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}