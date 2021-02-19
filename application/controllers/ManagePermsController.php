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
    public function getList()
    {

        $uid = $this->session->s_user->u_id;

        $this->load->model('ManagePermsModel');
        $result = $this->ManagePermsModel->getUserPerms($uid);

        echo json_encode($result);
    }
}