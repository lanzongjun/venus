<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 3:42 PM
 */

include_once 'BaseModel.php';

class ManageModel extends BaseModel
{
    public function getPermsByUID($uid)
    {
        $result =  $this->db
            ->join('manage_role', 'role_id = manage_role.id', 'left')
            ->where('uid', intval($uid))
            ->where('manage.status', 1)
            ->where('manage.is_deleted', 0)
            ->where('manage_role.status', 1)
            ->where('manage_role.is_deleted', 0)
            ->select('manage.id, uid, role_id, name as role_name, desc as role_desc, perms')
            ->get('manage')
            ->first_row();

        return $result;
    }

    public function getList($username)
    {
        $query = $this->db->where('manage.is_deleted', 0);

        $query->join('user', 'uid = u_id', 'left');
        $query->join('manage_role', 'role_id = manage_role.id', 'left');
        $query->where('manage_role.is_deleted', 0);

        if (!empty($username)) {
            $query->like('u_name', $username);
        }

        $query->select('manage.id, manage.status as manage_status, manage_role.name as role_name, u_name as username, uid, role_id');

        $result = $query->get('manage')->result_array();

        foreach ($result as &$item) {

            $item['manage_status_text'] = $item['manage_status'] == 1 ? '启用' : '禁用';
        }

        return $result;
    }

    public function addManage($manageId, $roleId, $status)
    {
        // 判断是否有关系

        $row = $this->db
            ->where('uid', $manageId)
            ->where('status', 1)
            ->get('manage')
            ->first_row();

        if (!empty($row)) {
            return [
                'state' => false,
                'msg'   => "该用户已经有授权角色"
            ];
        }

        $insertData = [
            'uid' => $manageId,
            'role_id' => $roleId,
            'status' => $status
        ];
        $this->db->insert('manage', $insertData);

        return [
            'state' => true,
            'msg'   => "用户角色授权成功"
        ];
    }

    public function editManage($id, $manageId, $roleId, $status)
    {
        $updateData = [
            'uid'     => $manageId,
            'role_id' => $roleId,
            'status'  => $status
        ];

        $this->db->where('id', $id)->update('manage', $updateData);

        return [
            'state' => true,
            'msg'   => "用户角色更新成功"
        ];
    }

    public function deleteManage($id)
    {
        $updateData = [
            'is_deleted' => 1,
        ];

        $this->db->where('id', $id)->update('manage', $updateData);

        return [
            'state' => true,
            'msg'   => "用户角色删除成功"
        ];
    }
}