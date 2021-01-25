<?php

require_once APPPATH . 'controllers/BaseController.php';

class GoodsLossController extends BaseController
{
    public $_s_view  = 'GoodsSaleLossView';
    public $_s_model = 'GoodsSaleLossModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsLossController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    /**
     * 获取列表
     * @author zongjun.lan
     */
    public function getList()
    {
        $getData = $this->input->get();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate   = isset($getData['end_date']) ? $getData['end_date'] : '';
        $goodsName = isset($getData['provider_goods_name']) ? $getData['provider_goods_name'] : '';
        $lossType  = isset($getData['type']) ? $getData['type'] : '';
        $page      = isset($getData['page']) ? $getData['page'] : 1;
        $rows      = isset($getData['rows']) ? $getData['rows'] : 50;
        $isDownload = isset($getData['is_download']) ? $getData['is_download'] : 0;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $startDate, $endDate,
            $goodsName,
            $lossType, $page, $rows
        );

        if ($isDownload) {
            $intersectKeys = [
                'cs_name' => true,
                'cs_city' => true,
                'gl_date' => true,
                'pg_name' => true,
                'type' => true,
                'num_unit' => true,
                'order' => true,
                'remark' => true,
                'u_name' => true,
                'gl_create_time' => true,
                'gl_update_time' => true
            ];

            $intersectData = array_map(function ($item) use ($intersectKeys) {
                return array_intersect_key(array_merge($intersectKeys, $item), $intersectKeys);
            } ,$o_result['rows']);

            $this->output(
                '销售管理-损耗',
                [
                    '店铺名称','店铺所在城市','损耗日期','商品名称','类型','数量(单位)','订单','备注','操作员','创建时间','更新时间'
                ],
                $intersectData
            );
            exit();
        }

        echo json_encode($o_result);
    }

    public function getGoodsLossInfo()
    {
        $getData = $this->getGetData();
        $id = isset($getData['id']) ? $getData['id'] : '';

        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getGoodsLossInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addGoodsLossInfo()
    {
        $postData = $this->getPostData();

        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $unit    = isset($postData['unit']) ? $postData['unit'] : '';
        $order   = isset($postData['order']) ? $postData['order'] : '';
        $type    = isset($postData['type']) ? $postData['type'] : '';
        $remark  = isset($postData['remark']) ? $postData['remark'] : '';

        if (empty($goodsId) || empty($date) || empty($num) || empty($type) || empty($unit)) {
            echo array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsLossInfo(
            $this->shop_id,
            $this->user_id,
            $type,
            $goodsId,
            $date,
            $num,
            $unit,
            $order,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsLossConstant::ADD_GOODS_LOSS_INFO,
            GoodsLossConstant::getMessage(GoodsLossConstant::ADD_GOODS_LOSS_INFO),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function editGoodsLossInfo()
    {
        $postData = $this->getPostData();

        $num    = isset($postData['num']) ? $postData['num'] : '';
        $unit   = isset($postData['unit']) ? $postData['unit'] : '';
        $order  = isset($postData['order']) ? $postData['order'] : '';
        $type   = isset($postData['type']) ? $postData['type'] : '';
        $remark = isset($postData['remark']) ? $postData['remark'] : '';
        $id     = isset($postData['gl_id']) ? $postData['gl_id'] : '';

        if (empty($num) || empty($unit) || empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '请填写正确的参数'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsLossInfo(
            $this->shop_id,
            $id,
            $this->user_id,
            $num,
            $unit,
            $type,
            $order,
            $remark
        );

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsLossConstant::EDIT_GOODS_LOSS_INFO,
            GoodsLossConstant::getMessage(GoodsLossConstant::EDIT_GOODS_LOSS_INFO),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }

    public function deleteGoodsLoss()
    {
        $postData = $this->getPostData();

        $id = isset($postData['id']) ? $postData['id'] : '';

        if (empty($id)) {
            echo array(
                'state' => false,
                'msg'   => '参数不正确'
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsLoss($id);

        // LOG
        $this->OperationLogModel->write(
            $this->user_id,
            GoodsLossConstant::DELETE_GOODS_LOSS_INFO,
            GoodsLossConstant::getMessage(GoodsLossConstant::DELETE_GOODS_LOSS_INFO),
            [
                'params' => $postData,
                'result' => $result
            ]
        );

        echo json_encode($result);
    }
}