<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/28
 * Time: 10:16 AM
 */

include_once 'BaseModel.php';

class BaseNavNode extends BaseModel
{
    public $my_redis;

    public function __construct()
    {
        // 加载redis

        $this->load->library('redisdb');
        $this->my_redis = $this->redisdb->connect();

        // 加载session

        $this->load->library('session');
    }


    public function loadUserNav()
    {

        $uid = $this->session->s_user->u_id;
        $permsKey = 'vms_perm_key_'.$uid;
        $perms = $this->my_redis->get($permsKey);
//dd($this->my_redis->del($permsKey));
        if (!$perms) {
            $perms = $this->initCache($uid);
        } else {
            $perms = json_decode($perms, true);
        }

        return $perms;
    }

    public function initCache($uid)
    {
        $this->load->model("ManageModel");
        $userPerms = $this->ManageModel->getPermsByUID($uid);

        $this->load->model("ManagePermsModel");
        $userPermsList = $this->ManagePermsModel->getRulesByIds(explode(',', $userPerms->perms));

        $treePerms = $this->getPermsTree($userPermsList);
        $jsonTreePerms = json_encode($treePerms);
        $this->my_redis->set('vms_perm_key_'.$uid, $jsonTreePerms, 3600);


        return $jsonTreePerms;
    }

    public function getPermsTree($userPermsList)
    {



//array:4 [▼
//  0 => array:10 [▼
//    "id" => "1"
//    "name" => "供应商管理"
//    "identity_code" => ""
//    "is_open" => "1"
//    "parent_id" => "0"
//    "is_show" => "1"
//    "status" => "1"
//    "url" => ""
//    "create_time" => "2021-02-04 15:57:00"
//    "update_time" => "2021-02-04 15:57:20"
//  ]
//  1 => array:10 [▼
//    "id" => "2"
//    "name" => "添加供应商"
//    "identity_code" => "add_provider"
//    "is_open" => "0"
//    "parent_id" => "1"
//    "is_show" => "1"
//    "status" => "1"
//    "url" => "ProviderController/index"
//    "create_time" => "2021-02-04 15:57:50"
//    "update_time" => "2021-02-04 15:58:13"
//  ]
//  2 => array:10 [▼
//    "id" => "3"
//    "name" => "商品管理"
//    "identity_code" => ""
//    "is_open" => "1"
//    "parent_id" => "0"
//    "is_show" => "1"
//    "status" => "1"
//    "url" => ""
//    "create_time" => "2021-02-04 15:59:19"
//    "update_time" => "2021-02-04 15:59:19"
//  ]
//  3 => array:10 [▼
//    "id" => "4"
//    "name" => "添加商品"
//    "identity_code" => "add_goods"
//    "is_open" => "0"
//    "parent_id" => "3"
//    "is_show" => "1"
//    "status" => "1"
//    "url" => ""
//    "create_time" => "2021-02-04 15:59:48"
//    "update_time" => "2021-02-04 15:59:48"
//  ]
//]

        $navNode = [];
        foreach ($userPermsList as $node) {
            if ($node['parent_id'] == 0) { // 父类

                $node['text'] = $node['name'];
                $navNode[] = $node;

            } else {

                $navNode = $this->getChildrenNode($navNode, $node);

            }
        }

        return $navNode;
    }

    public function getChildrenNode(&$navNode, $node)
    {

        foreach ($navNode as &$navItem) {
            if ($navItem['id'] == $node['parent_id']) {

                if (!empty($node['identity_code'])) {
                    $node['id'] = $node['identity_code'];
                }
                $node['text'] = $node['name'];
                $navItem['children'][] = $node;
            } else {
                if (!empty($navItem['children'])) {
                    $this->getChildrenNode($navItem['children'], $node);
                }
            }
        }

        return $navNode;
    }

    public function getTreeNode($userPermsList, $perm)
    {
        $children = array();
        foreach ($userPermsList as $item) {
            if ($item['id'] == $perm['parent_id']) {
                $node = $this->getChildrenNode($perm);
                $node_children = $this->getTreeNode($userPermsList, $node);
                if (count($node_children) > 0) {
                    $node['children'] = $node_children;
                }
//                if (count($a_node_children) > 0 || $o_node->bnn_code <> '') {
//                    //TODO 增加权限判断
//                    array_push($a_children, $o_node);
//                }
            }
        }
        return $children;
    }

    /* * *******************************************************************
      函数名称:encrypt
      函数作用:加密解密字符串
      使用方法:
      加密     :encrypt('str','E','nowamagic');
      解密     :encrypt('被加密过的字符串','D','nowamagic');
      参数说明:
      $string   :需要加密解密的字符串
      $operation:判断是加密还是解密:E:加密   D:解密
      $key      :加密的钥匙(密匙);
     * ******************************************************************* */

    function encrypt($string, $operation, $key = '') {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}