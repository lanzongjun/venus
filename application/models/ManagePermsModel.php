<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 3:42 PM
 */

include_once 'BaseModel.php';

class ManagePermsModel extends BaseModel
{
    public function getRulesByIds($ids)
    {
        $result = $this->db
            ->where_in('id', $ids)
            ->where('status', 1)
            ->get('manage_perms')
            ->result_array();

        return $result;
    }
}