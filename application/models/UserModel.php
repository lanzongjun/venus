<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author zongjun.lan
 */
include_once 'BaseModel.php';

class UserModel extends BaseModel
{

    function u_insert($arr) {
        $this->db->insert('user', $arr);
    }

    function u_update($id, $arr) {
        $this->db->where('id', $id);
        $this->db->update('user', $arr);
    }

    public function u_del($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }

    public function u_select($name)
    {
        $this->db->where('u_name', $name);
        $this->db->select('*');
        $query = $this->db->get('user');
        return $query->first_row();
    }

    /**
     * 检测是否登录
     */
    public function isLogin()
    {
        $adminUserId = $this->session->s_user->u_id;
        if (empty($adminUserId)) {
            redirect(site_url('admin/index'));
        }
        return true;
    }

}
