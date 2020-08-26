<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 结算向导
 *
 * @author Vincent
 */
class AdBalanceGuideC extends CI_Controller {
    var $_s_view = 'BalanceGuide';
    var $_s_model = 'AdBalanceGuideM';
    
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceGuideC';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }
    
    /**
     * 获得订单列表
     */
    function getOrderList() {
        $s_date_begin = isset($_GET['db']) ? $_GET['db'] : '';
        $s_date_end = isset($_GET['de']) ? $_GET['de'] : '';
        $s_shop = isset($_GET['s']) ? $_GET['s'] : '';
        $s_from = isset($_GET['f']) ? $_GET['f'] : '';
        $a_result = array();
        
        if ($s_from == 'ELE') {
            $this->load->model('AdEBOrderInfoM');
            $a_result = $this->AdEBOrderInfoM->getToDoOrderList($s_date_begin,$s_date_end,$s_shop);
        } else if ($s_from == 'JD') {
            //TODO 获得京东到家订单
        } else if ($s_from == 'MT') {
            
        } else {
            $this->load->model('AdEBOrderInfoM');
            $a_result = $this->AdEBOrderInfoM->getToDoOrderList($s_date_begin,$s_date_end,$s_shop);
        }
        
        echo json_encode($a_result);        
    }
    
    /**
     * 结算
     */
    function doBalance() {
        $s_db = isset($_POST['db']) ? $_POST['db'] : '';
        $s_de = isset($_POST['de']) ? $_POST['de'] : '';        
        $a_code = isset($_POST['codes']) ? $_POST['codes'] : '';
        
        $this->load->model('AdBalanceAccountM');
        $o_result = $this->AdBalanceAccountM->doBalance($s_db, $s_de, true, $a_code);
        echo json_encode($o_result);
    }
    
    
}
