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
            ->result_array();

        return $result;
    }
}