<?php
require_once APPPATH . 'controllers/BaseController.php';

class GoodsStaffMealController extends BaseController
{
    public $_s_view  = 'GoodsStaffMealView';
    public $_s_model = 'GoodsStaffMealModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsStaffMealController';
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

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($this->shop_id, $startDate, $endDate, $goodsName, $page, $rows);

        echo json_encode($result);
    }

    public function addStaffMeal()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $unit    = isset($postData['unit']) ? $postData['unit'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $remark  = isset($postData['remark']) ? $postData['remark'] : '';

        if (empty($goodsId) || empty($date) || empty($unit) || empty($num)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addStaffMeal(
            $this->user_id,
            $this->shop_id,
            $goodsId,
            $date,
            $unit,
            $num,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsStaffMealConstant::ADD_STAFF_MEAL,
            GoodsStaffMealConstant::getMessage(GoodsStaffMealConstant::ADD_STAFF_MEAL),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function deleteStaffMealRecord()
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
        $result = $this->{$this->_s_model}->deleteStaffMealRecord($id);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsStaffMealConstant::DELETE_STAFF_MEAL,
            GoodsStaffMealConstant::getMessage(GoodsStaffMealConstant::DELETE_STAFF_MEAL),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function getStaffMealInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getStaffMealInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function editStaffMeal()
    {
        $postData = $this->getPostData();

        $unit   = isset($postData['unit']) ? $postData['unit'] : '';
        $num    = isset($postData['num']) ? $postData['num'] : '';
        $remark = isset($postData['remark']) ? $postData['remark'] : '';
        $gsmId  = isset($postData['gsm_id']) ? $postData['gsm_id'] : '';

        if (empty($unit) || empty($num) || empty($gsmId)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editStaffMeal(
            $this->shop_id,
            $gsmId,
            $this->user_id,
            $unit,
            $num,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsStaffMealConstant::EDIT_STAFF_MEAL,
            GoodsStaffMealConstant::getMessage(GoodsStaffMealConstant::EDIT_STAFF_MEAL),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}