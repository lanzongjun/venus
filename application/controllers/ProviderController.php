<?php

include_once 'BaseController.php';

/**
 * 供应商
 * Class ProviderController
 */
class ProviderController extends BaseController
{
    public $_s_view  = 'ProviderView';
    public $_s_model = 'ProviderModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'ProviderController';
        $this->load->helper('url');
        $this->load->view("admin/baseCheck/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    public function getList()
    {
        $getData = $this->input->get();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $providerName = isset($getData['provider_name']) ? $getData['provider_name'] : '';
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getList($providerName, $page, $rows, $rowsOnly);
        echo json_encode($o_result);
    }

    /**
     * 获得详情
     */
    function getProviderInfo()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->ProviderModel->getProviderInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    /**
     * 新增商铺信息
     */
    public function addProviderInfo()
    {
        $postData = $this->input->post();

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addProviderInfo($postData);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderConstant::ADD_PROVIDER,
            ProviderConstant::getMessage(ProviderConstant::ADD_PROVIDER),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    /**
     * 保存商铺信息
     */
    public function editProviderInfo()
    {
        $postData = $this->input->post();

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editProviderInfo($postData);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderConstant::EDIT_PROVIDER,
            ProviderConstant::getMessage(ProviderConstant::EDIT_PROVIDER),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}