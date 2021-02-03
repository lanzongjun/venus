<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/2
 * Time: 2:59 PM
 */

/**
 * 权限验证
 */
class Authhandler
{
    protected $_CI;

    // 默认配置
    public $_config;

    public function __construct()
    {
        $this->_CI =& get_instance();
        $this->_CI->db=$this->_CI->load->database();


        $this->_config = [
            'AUTH_ON'               =>  empty(my_env('AUTH_ON')) ? true : my_env('AUTH_ON'), // 验证开关
            'SUPER_ADMINISTRATOR'   =>  'admin',           // 超级管理员用户名
        ];
    }

    /**
     * 权限验证
     * @param  int          $uid        用户id
     * @param  array        $names      待验证的权限，支持逗号分隔的权限规则或索引数组
     * @param  string       $relation   待验证权限是否都需要满足，'or'|'and'
     * @return boolean                  true|false
     */
    public function check($uid, $names, $relation = 'or')
    {
        // 关闭验证跳过
        if (!$this->_config['AUTH_ON']) {
            return true;
        }

        // 超管跳过
        if($_SESSION['s_user']->u_name === $this->_config['SUPER_ADMINISTRATOR']) {
            return true;
        }

        // 获取用户权限
        $authList = $this->getAuthList($uid);

        // 待验证权限
        if (is_string($names)) {
            $names = strtolower($names);
            if (strpos($names, ',') !== false) {
                $names = explode(',', $names);
            } else {
                $names = array($names);
            }
        }

        // 通过验证的权限数组
        $list = [];
        foreach ($names as $name) {
            if(in_array($name, $authList))
            {
                $list[] = $name;
            }
        }

        if ('or' == $relation and !empty($list)) {
            return true;
        }
        $diff = array_diff($names, $list);
        if ('and' == $relation and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 获取用户所有角色组下的权限name集合
     * @param  int      $uid        管理员id
     * @return array    $authList   权限name集合
     */
    public function getAuthList($uid)
    {
        $this->_CI->load->model("ManageModel");
        $perms = $this->_CI->ManageModel->getPermsByUID($uid);

        $ids = [];
        foreach ($perms as $perm) {
            $ids = array_merge($ids, explode(',', trim($perm['perms'], ',')));
        }
        $ids = array_unique($ids);
        $this->_CI->load->model("ManagePermsModel");
        $rules = $this->_CI->ManagePermsModel->getRulesByIds($ids);
        dd($rules);
        // TODO 
        $_SESSION['admin']['AUTH_'.$uid] = $rules;
        $authList = array_column($rules, 'name');
        return $authList;
    }
}