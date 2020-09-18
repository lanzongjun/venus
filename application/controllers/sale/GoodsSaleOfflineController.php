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

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($page, $rows, $rowsOnly);

        echo json_encode($result);
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

        $skuCode = isset($postData['cs_code']) ? $postData['cs_code'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';

        if (empty($skuCode) || empty($date) || empty($num) || empty($unit)) {
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
            $skuCode,
            $date,
            $num,
            $unit
        );

        echo json_encode($result);
    }

    public function editGoodsSaleOffline()
    {
        $postData = $this->getPostData();

        $skuCode = isset($postData['cs_code']) ? $postData['cs_code'] : '';
        $date = isset($postData['date']) ? $postData['date'] : '';
        $num = isset($postData['num']) ? $postData['num'] : '';
        $unit = isset($postData['unit']) ? $postData['unit'] : '';
        $id = isset($postData['gso_id']) ? $postData['gso_id'] : '';

        if (empty($skuCode) || empty($date) || empty($num) || empty($id) || empty($unit)) {
            echo json_encode(
                array(
                    'state' => false,
                    'msg'   => '参数不正确'
                )
            );
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->editGoodsSaleOffline($id, $this->user_id, $skuCode, $date, $num, $unit);

        echo json_encode($result);
    }

    public function deleteGoodsSaleOffline()
    {
        $postData = $this->getPostData();

        $id = isset($postData['gso_id']) ? $postData['gso_id'] : '';

        if (empty($id)) {
            return array(
                'state' => false,
                'msg'   => '参数不正确'
            );
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->deleteGoodsSaleOffline($id);

        echo json_encode($result);
    }
}