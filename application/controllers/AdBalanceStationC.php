<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 各站结算
 *
 * @author Vincent
 */
class AdBalanceStationC extends CI_Controller {

    var $_s_view = 'BalanceStation';
    var $_s_model = 'AdBalanceStationM';

    /**
     * 显示信息
     */
    function index() {
        if (isset($_GET['bi']) && isset($_GET['oi'])) {
            $data['ba_id'] = $_GET['bi'];
            $data['org_sn'] = $_GET['oi'];
        }
        $data['c_name'] = 'AdBalanceStationC';
        $this->load->helper('url');
        $this->load->view("admin/balanceCount/$this->_s_view", $data);
    }

    function getList() {
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStationM->getList();
        echo json_encode($o_result);
    }

    /**
     * 获得信息详情
     */
    function getDetail() {
        if (isset($_GET['bi']) && isset($_GET['oi'])) {
            $ba_id = $_GET['bi'];
            $org_sn = $_GET['oi'];
            $this->load->model($this->_s_model);
            $o_result = $this->AdBalanceStationM->getDetail($ba_id, $org_sn);
            echo json_encode($o_result);
        } else {
            $this->load->model($this->_s_model);
            $o_result = $this->AdBalanceStationM->getDetail();
            echo json_encode($o_result);
        }
    }
    
    function sendMails() {
        if (!isset($_POST['ids'])){echo '';exit();}
        $a_bas_id = $_POST['ids'];
        $this->load->model($this->_s_model);
        $o_result = $this->AdBalanceStationM->sendMails($a_bas_id);
        echo json_encode($o_result);
    }
}
