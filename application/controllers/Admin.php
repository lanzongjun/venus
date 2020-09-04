<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author Vincent
 */
class Admin extends CI_Controller {
    
    public function index() {
        if (!file_exists(APPPATH . 'views/admin/login/index.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        $data['title'] = ucfirst('index'); // Capitalize the first letter
        
        $this->load->helper('url');
        $this->load->view('admin/login/index', $data);
    }

    public function check()
    {
        $postData = $this->input->post();
        $this->load->model('UserModel');
        $user = $this->UserModel->u_select($postData['u_name']);
        if ($user) {
            if ($user[0]->u_password == $postData['u_pw']) {
                $this->load->library('session');
                $this->session->set_userdata('s_user', $user[0]);
                //$s_user = $this->session->all_userdata();
                $this->load->helper('url');
                redirect('admin/main');
            } else {
                echo 'pw wrong';
            }
        } else {
            echo 'name wrong';
        }
    }

    function is_login() {
        $this->load->library('session');
        if ($this->session->userdata('s_id')) {
            echo "logined";
        } else {
            echo "no login";
        }
    }

    function logout() {
        $this->load->library('session');
        $this->session->unset_userdata('s_id');
    }

    function main()
    {
        $data['title'] = ucfirst('main');
        $this->load->library('session');
        $s_user = $this->session->userdata('s_user');
        if (!$s_user) {
            $data['title'] = ucfirst('index');
            $this->load->helper('url');
            $this->load->view('admin/login/index', $data);
        } else {
            $this->load->helper('url');
            $this->load->view('admin/main/index', $data);
        }
    }

    function showBCGoodsYJ() {
        $data['title'] = ucfirst('BCGoodsYJ');
        $this->load->view('admin/baseConfig/BCGoodsYJ', $data);
    }
    
    function isOrderToDo() {
        $this->load->model('AdMTOrderInfoM');
        $i_result = $this->AdMTOrderInfoM->getOrderToDo();
        $this->load->model('AdEBOrderInfoM');
        $i_result = $this->AdEBOrderInfoM->getOrderToDo();
        echo $i_result;
    }

}
