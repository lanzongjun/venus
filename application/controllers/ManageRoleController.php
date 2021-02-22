<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/1
 * Time: 5:14 PM
 */

require_once APPPATH . 'controllers/BaseController.php';

class ManageRoleController extends BaseController
{
    public $_s_view = 'ManageRoleView';
    public $_s_model = 'ManageRoleModel';

    public function index()
    {
        $data['c_name'] = 'ManageRoleController';
        $this->load->helper('url');
        $this->load->view("admin/sys/$this->_s_view", $data);
    }

    /**
     * 获取角色列表
     * @author zongjun.lan
     */
    public function getList()
    {
        $getData = $this->getGetData();

        $this->load->model('ManageRoleModel');

        $result = $this->ManageRoleModel->getList();

        echo json_encode($result);
    }

    /**
     * 角色分配权限
     * @author zongjun.lan
     */
    public function add()
    {
        $postData = $this->getPostData();
        $permsIds = isset($postData['perms_ids']) ? $postData['perms_ids'] : '';
        $name = isset($postData['name']) ? $postData['name'] : '';
        $desc = isset($postData['desc']) ? $postData['desc'] : '';
        $status = isset($postData['status']) ? $postData['status'] : 1;

        if (empty($permsIds) || empty($name) || empty($desc)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不能为空'
            ));
            exit();
        }

        $this->load->model('ManageRoleModel');

        $result = $this->ManageRoleModel->add($name, $desc, $permsIds, $status);

        echo json_encode($result);
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function getManageRoleInfo()
    {
        $getData = $this->getGetData();

        $id = isset($getData['id']) ? $getData['id'] : '';

        if (empty($id)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => 'ID不能为空'
            ));
            exit();
        }

        $this->load->model('ManageRoleModel');

        $result = $this->ManageRoleModel->getManageRoleInfo($id);

        echo json_encode($result);
    }
}