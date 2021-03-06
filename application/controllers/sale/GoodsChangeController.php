<?php
require_once APPPATH . 'controllers/BaseController.php';

class GoodsChangeController extends BaseController
{
    public $_s_view  = 'GoodsChangeView';
    public $_s_model = 'GoodsChangeModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsChangeController';
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

    public function addGoodsChange()
    {
        $postData = $this->getPostData();

        $goodsId    = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date       = isset($postData['date']) ? $postData['date'] : '';
        $unit       = isset($postData['unit']) ? $postData['unit'] : '';
        $num        = isset($postData['num']) ? $postData['num'] : '';
        $changeType = isset($postData['change_type']) ? $postData['change_type'] : '';
        $changeShop = isset($postData['change_shop']) ? $postData['change_shop'] : '';
        $remark     = isset($postData['remark']) ? $postData['remark'] : '';


        if (empty($goodsId) || empty($date) || empty($unit) || empty($num)
        || empty($changeType) || empty($changeShop)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsChange(
            $this->user_id,
            $this->shop_id,
            $goodsId,
            $date,
            $unit,
            $num,
            $changeType,
            $changeShop,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsChangeConstant::ADD_GOODS_CHANGE,
            GoodsChangeConstant::getMessage(GoodsChangeConstant::ADD_GOODS_CHANGE),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function deleteGoodsChangeRecord()
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
        $result = $this->{$this->_s_model}->deleteGoodsChangeRecord($id);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsChangeConstant::DELETE_GOODS_CHANGE,
            GoodsChangeConstant::getMessage(GoodsChangeConstant::DELETE_GOODS_CHANGE),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function getGoodsChangeInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getGoodsChangeInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function editGoodsChange()
    {
        $postData = $this->getPostData();

        $goodsId    = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date       = isset($postData['date']) ? $postData['date'] : '';
        $unit       = isset($postData['unit']) ? $postData['unit'] : '';
        $num        = isset($postData['num']) ? $postData['num'] : '';
        $changeType = isset($postData['change_type']) ? $postData['change_type'] : '';
        $changeShop = isset($postData['change_shop']) ? $postData['change_shop'] : '';
        $remark     = isset($postData['remark']) ? $postData['remark'] : '';
        $gcId       = isset($postData['gc_id']) ? $postData['gc_id'] : '';

        if (empty($goodsId) || empty($date) || empty($unit) || empty($num)
            || empty($changeType) || empty($changeShop) || empty($gcId)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsChange(
            $gcId,
            $this->user_id,
            $goodsId,
            $date,
            $unit,
            $num,
            $changeType,
            $changeShop,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsChangeConstant::EDIT_GOODS_CHANGE,
            GoodsChangeConstant::getMessage(GoodsChangeConstant::EDIT_GOODS_CHANGE),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}