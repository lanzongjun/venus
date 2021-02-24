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

    public function getList()
    {
        $query = $this->db->where('manage.is_deleted', 0);

        $query->join('user', 'uid = u_id', 'left');
        $query->join('manage_role', 'role_id = manage_role.id', 'left');
        $query->where('manage_role.is_deleted', 0);

        $query->select('manage.id, manage.status as manage_status, manage_role.name as role_name, u_name as username');

        $result = $query->get('manage')->result_array();

        return $result;
    }
}