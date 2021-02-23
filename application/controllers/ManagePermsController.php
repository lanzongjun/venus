<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/19
 * Time: 9:41 AM
 */

require_once APPPATH . 'controllers/BaseController.php';

class ManagePermsController extends BaseController
{
    public function index()
    {
        $data['c_name'] = 'ManagePermsController';
        $this->load->helper('url');
        $this->load->view("admin/sys/PowerManagerView", $data);
    }
    
    public function getList()
    {
        
        $this->load->model('ManagePermsModel');
        $result = $this->ManagePermsModel->getUserPerms();
        
        echo json_encode($result);
    }
}