<?php
require_once APPPATH . 'controllers/BaseController.php';

class GoodsExceptionHandleController extends BaseController
{
    public $_s_view  = 'GoodsExceptionHandleView';
    public $_s_model = 'GoodsExceptionHandleModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsExceptionHandleController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($startDate, $endDate, $providerGoodsName, $page, $rows);

        echo json_encode($result);
    }

    public function addExceptionHandle()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $order = isset($postData['order']) ? $postData['order'] : '';

        if (empty($goodsId) || empty($date) || empty($unit) || empty($num) || empty($order)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addExceptionHandle(
            $this->user_id,
            $this->shop_id,
            $goodsId,
            $date,
            $unit,
            $num,
            $order
        );

        echo json_encode($result);
    }

    public function deleteExceptionHandleRecord()
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
        $result = $this->{$this->_s_model}->deleteExceptionHandleRecord($id);

        echo json_encode($result);
    }

    public function getExceptionHandleInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getExceptionHandleInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function editExceptionHandle()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $gehId = isset($postData['geh_id']) ? $postData['geh_id'] : '';
        $order = isset($postData['order']) ? $postData['order'] : '';

        if (empty($goodsId) || empty($date) || empty($unit) || empty($num)
            || empty($gehId) || empty($order)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editExceptionHandle(
            $gehId,
            $this->user_id,
            $goodsId,
            $date,
            $unit,
            $num,
            $order
        );

        echo json_encode($result);
    }
}