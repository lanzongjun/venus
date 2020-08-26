<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 综合结算列表
 *
 * @author Vincent
 */
class AdBalanceAccountC extends CI_Controller {
    var $_s_view = 'BalanceAccount';
    var $_s_model = 'AdBalanceAccountM';
    
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        if (!$s_userid) {
            show_404();
        }
    }
    /**
     * 显示信息
     */
    function index() {
        $data['c_name'] = 'AdBalanceAccountC';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }

    /**
     * 获得信息列表
     */
    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceAccountM->getList();
        echo json_encode($o_result);
    }
    
    function getABList(){
        if (!isset($_GET['bi'])){echo '';exit();}
        $ba_id = $_GET['bi'];
        $this->load->model('AdBalanceOnSaleStM');
        $o_result = $this->AdBalanceOnSaleStM->getABList($ba_id);
        echo json_encode($o_result);
    }
    
    function getYJList(){
        if (!isset($_GET['bi'])){echo '';exit();}
        $ba_id = $_GET['bi'];
        $this->load->model('AdBalanceOnSaleYJM');
        $o_result = $this->AdBalanceOnSaleYJM->getYJList($ba_id);
        echo json_encode($o_result);
    }
    
    function getDelayList(){
        if (!isset($_GET['bi'])){echo '';exit();}
        $ba_id = $_GET['bi'];
        $this->load->model('AdBalanceDelayM');
        $o_result = $this->AdBalanceDelayM->getDelayList($ba_id);
        echo json_encode($o_result);
    }
    
    function getErrList(){
        if (!isset($_GET['bi'])){echo '';exit();}
        $ba_id = $_GET['bi'];
        $this->load->model('AdBalanceErrM');
        $o_result = $this->AdBalanceErrM->getErrList($ba_id);
        echo json_encode($o_result);
    }
    
    function doBalance() {        
        $s_date = $_POST['dt'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceAccountM->doBalance($s_date,$s_date);
        echo json_encode($o_result);
    }
    
    function doReBalance() {        
        $s_date = $_POST['dt'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceAccountM->doReBalance($s_date,$s_date);
        echo json_encode($o_result);
    }
    
}
