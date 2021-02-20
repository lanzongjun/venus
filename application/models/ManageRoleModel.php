<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 3:43 PM
 */

include_once 'BaseModel.php';

class ManageRoleModel extends BaseModel
{

    public function getList()
    {
        $result = $this->db->where('status', 1)->where('is_deleted', 0)->get('vms_manage_role')->result_array();

        return $result;
    }

    public function add($name, $desc, $permsIds, $status)
    {
        $insertData = [
            'name'   => $name,
            'desc'   => $desc,
            'perms'  => $permsIds,
            'status' => $status
        ];

        $this->db->insert('vms_manage_role', $insertData);

        return array(
            'state' => true,
            'msg'   => "角色:【{$name}】添加成功"
        );
    }
}