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
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $providerGoodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($startDate, $endDate, $providerGoodsName, $page, $rows);

        echo json_encode($result);
    }

    public function addGoodsChange()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $changeType = isset($postData['change_type']) ? $postData['change_type'] : '';
        $changeShop = isset($postData['change_shop']) ? $postData['change_shop'] : '';

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
            $changeShop
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

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $changeType = isset($postData['change_type']) ? $postData['change_type'] : '';
        $changeShop = isset($postData['change_shop']) ? $postData['change_shop'] : '';
        $gcId = isset($postData['gc_id']) ? $postData['gc_id'] : '';

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
            $changeShop
        );

        echo json_encode($result);
    }
}