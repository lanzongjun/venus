<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/9/9
 * Time: 3:18 PM
 */

require_once APPPATH . 'controllers/BaseController.php';


class GoodsSaleOfflineController extends BaseController
{
    public $_s_view  = 'GoodsSaleOfflineView';
    public $_s_model = 'GoodsSaleOfflineModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsSaleOfflineController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $type      = isset($getData['type']) ? $getData['type'] : '';
        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate   = isset($getData['end_date']) ? $getData['end_date'] : '';
        $page      = isset($getData['page']) ? $getData['page'] : 1;
        $rows      = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly  = isset($getData['rows_only']) ? $getData['rows_only'] : false;
        $isDownload = isset($getData['is_download']) ? $getData['is_download'] : 0;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $type,
            $startDate,
            $endDate,
            $page, $rows, $rowsOnly);

        if ($isDownload) {
            $intersectKeys = [
                'shop_name' => true,
                'cs_city' => true,
                'gso_date' => true,
                'goods_name' => true,
                'gso_type_text' => true,
                'num_unit' => true,
                'remark' => true,
                'gso_create_time' => true,
                'gso_update_time' => true
            ];

            $intersectData = array_map(function ($item) use ($intersectKeys) {
                return array_intersect_key(array_merge($intersectKeys, $item), $intersectKeys);
            } ,$o_result['rows']);

            $this->output(
                '销售管理-线下销售',
                [
                    '店铺','门店所在城市','销售日期','商品名称','销售类型','数量(单位)','备注','创建时间','更新时间'
                ],
                $intersectData
            );
            exit();
        }

        echo json_encode($o_result);
    }

    public function getSaleOfflineInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->{$this->_s_model}->getSaleOfflineInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addGoodsSaleOffline()
    {
        $postData = $this->getPostData();
;
        $goodsId = isset($postData['goods_id']) ? $postData['goods_id'] : '';
        $date    = isset($postData['date']) ? $postData['date'] : '';
        $type    = isset($postData['type']) ? $postData['type'] : '';
        $num     = isset($postData['num']) ? $postData['num'] : '';
        $unit    = isset($postData['unit']) ? $postData['unit'] : '';
        $remark  = isset($postData['remark']) ? $postData['remark'] : '';

        if (empty($goodsId) || empty($date) || empty($type) || empty($num) || empty($unit)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不正确'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addGoodsSaleOffline(
            $this->user_id,
            $this->shop_id,
            $goodsId,
            $date,
            $type,
            $num,
            $unit,
            $remark
        );

        echo json_encode($result);
    }

    public function editGoodsSaleOffline()
    {
        $postData = $this->getPostData();

        $type   = isset($postData['type']) ? $postData['type'] : '';
        $num    = isset($postData['num']) ? $postData['num'] : '';
        $unit   = isset($postData['unit']) ? $postData['unit'] : '';
        $remark = isset($postData['remark']) ? $postData['remark'] : '';
        $id     = isset($postData['gso_id']) ? $postData['gso_id'] : '';

        if (empty($type) || empty($num) || empty($id) || empty($unit)) {
            echo json_encode(
                array(
                    'state' => false,
                    'msg'   => '参数不正确'
                )
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsSaleOffline(
            $this->shop_id,
            $id,
            $this->user_id,
            $type,
            $num,
            $unit,
            $remark
        );

        echo json_encode($result);
    }

    public function deleteGoodsSaleOffline()
    {
        $postData = $this->getPostData();

        $id = isset($postData['gso_id']) ? $postData['gso_id'] : '';

        if (empty($id)) {
            echo array(
                'state' => false,
                'msg'   => '参数不正确'
            );
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsSaleOffline($id);

        echo json_encode($result);
    }
}