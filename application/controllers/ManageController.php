<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/1
 * Time: 5:08 PM
 */

require_once APPPATH . 'controllers/BaseController.php';

class ManageController extends BaseController
{
    public $_s_view = 'ManageView';
    public $_s_model = 'ManageModel';

    public function index()
    {
        $data['c_name'] = 'ManageController';
        $this->load->helper('url');
        $this->load->view("admin/sys/$this->_s_view", $data);
    }

    public function getList()
    {

    }

    public function add()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}