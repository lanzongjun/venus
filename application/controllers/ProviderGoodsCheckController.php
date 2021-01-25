<?php

include_once 'BaseController.php';

class ProviderGoodsCheckController extends BaseController
{
    public $_s_view  = 'ProviderGoodsCheckView';
    public $_s_model = 'ProviderGoodsCheckModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'ProviderGoodsCheckController';
        $this->load->helper('url');
        $this->load->view("admin/baseCheck/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $startDate,
            $endDate,
            $page,
            $rows,
            $rowsOnly
        );

        echo json_encode($result);
    }

    public function loadDetailData()
    {
        $getData = $this->getGetData();

        $id = isset($getData['pgc_id']) ? $getData['pgc_id'] : '';

        if (!empty($id)) {
            $this->load->model($this->_s_model);
            $result = $this->{$this->_s_model}->loadDetailData($id);

            echo json_encode($result);
        } else {
            echo '';
        }
    }

    public function getProviderGoodsCheckDetailInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';

        if (!empty($id)) {
            $this->load->model($this->_s_model);
            $result = $this->{$this->_s_model}->getProviderGoodsCheckDetailInfo($id);

            echo json_encode($result);
        } else {
            echo '';
        }
    }

    public function addGoodsCheck()
    {
        $postData = $this->getPostData();

        $date = isset($postData['date']) ? $postData['date'] : '';

        if (empty($date)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsCheck($this->shop_id,$this->user_id, $date);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsCheckConstant::ADD_GOODS_CHECK,
            ProviderGoodsCheckConstant::getMessage(ProviderGoodsCheckConstant::ADD_GOODS_CHECK),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function addGoodsCheckDetail()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $pgcId = isset($postData['pgc_id']) ? $postData['pgc_id'] : '';

        if (empty($pgcId)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '请先选择盘点日期记录'
            ));
            exit();
        }

        if (empty($goodsId) || empty($num) || empty($unit)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsCheckDetail($this->user_id, $pgcId, $goodsId, $num, $unit);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsCheckConstant::ADD_GOODS_CHECK_DETAIL,
            ProviderGoodsCheckConstant::getMessage(ProviderGoodsCheckConstant::ADD_GOODS_CHECK_DETAIL),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function editGoodsCheck()
    {
        $postData = $this->getPostData();

        $id = isset($postData['pgcd_id']) ? $postData['pgcd_id'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';

        if (empty($id) || empty($unit) || empty($num))  {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsCheck($this->user_id, $id, $num, $unit);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsCheckConstant::EDIT_GOODS_CHECK,
            ProviderGoodsCheckConstant::getMessage(ProviderGoodsCheckConstant::EDIT_GOODS_CHECK),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function reloadGoodsCheck()
    {
        $postData = $this->getPostData();

        $id = isset($postData['id']) ? $postData['id'] : '';

        if (empty($id))  {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->reloadGoodsCheck($this->shop_id, $id);

        echo json_encode($result);
    }

    public function deleteGoodsCheck()
    {
        $postData = $this->getPostData();

        $id = isset($postData['id']) ? $postData['id'] : '';

        if (empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsCheck($id);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsCheckConstant::DELETE_GOODS_CHECK,
            ProviderGoodsCheckConstant::getMessage(ProviderGoodsCheckConstant::DELETE_GOODS_CHECK),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function deleteGoodsCheckDetail()
    {
        $postData = $this->getPostData();

        $id = isset($postData['id']) ? $postData['id'] : '';

        if (empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数错误'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsCheckDetail($id);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            ProviderGoodsCheckConstant::DELETE_GOODS_CHECK_DETAIL,
            ProviderGoodsCheckConstant::getMessage(ProviderGoodsCheckConstant::DELETE_GOODS_CHECK_DETAIL),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}