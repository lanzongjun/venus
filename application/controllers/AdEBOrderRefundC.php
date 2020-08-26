<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 饿了么订单-退单
 *
 * @author Vincent
 */
class AdEBOrderRefundC extends CI_Controller {
    var $_s_view = 'EBOrderRefund';
    var $_s_model = 'AdEBOrderInfoM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdEBOrderRefundC';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }
    
    /**
     * 获得信息列表
     */
    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdEBOrderInfoM->getRefundList();
        echo json_encode($o_result);
    }
    
    function loadDetailData() {
        $s_order_id = isset($_GET['ocode']) ? $_GET['ocode'] : '';
        if ($s_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdEBOrderInfoM->getDetail($s_order_id);
            echo json_encode($o_result);
        }
    }
    
    function loadRefundDetail() {
        $s_order_id = isset($_GET['ocode']) ? $_GET['ocode'] : '';
        if ($s_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdEBOrderInfoM->getRefundDetail($s_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doRefundAgree() {
        $s_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        $s_refund_order_id = '';
        if ($s_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doRefundAgree($s_order_id,$s_refund_order_id);
            echo json_encode($o_result);
        }
    }
    
    function doRefundReject() {
        $s_order_id = isset($_POST['oi']) ? $_POST['oi'] : '';
        $s_refund_order_id = '';
        if ($s_order_id == ''){
            $o_result['state'] = true;
            $o_result['msg'] = "订单号不合法";
            echo json_encode($o_result);
        } else {
            $this->load->model('AdMTSyncOrderM');
            $o_result = $this->AdMTSyncOrderM->doRefundReject($s_order_id,$s_refund_order_id);
            echo json_encode($o_result);
        }
    }
    
}
