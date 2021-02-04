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

        if (!$perms) {
            $perms = $this->initCache($uid);
        } else {

            $perms = json_decode($perms, true);
        }
        echo $perms;
    }

    public function initCache($uid)
    {
        $this->load->model("ManageModel");
        $userPerms = $this->ManageModel->getPermsByUID($uid);

        $this->load->model("ManagePermsModel");
        $userPermsList = $this->ManagePermsModel->getRulesByIds(explode(',', $userPerms->perms));

        $o_struct = $this->getPermsTree($userPermsList);
        $s_json = json_encode($o_struct);
        $s_file_name = "nav_$s_userid.json";
        $s_path = $this->__base_cache_path . $s_file_name;
        $s_json_safe = $this->encrypt($s_json, 'E', $this->__json_key);
        return $s_json;
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
        $node = [];
        foreach ($userPermsList as $perms) {
            if ($perms['parent_id'] == 0) { // 父类
                $node[$perms['id']] = $perms;
                $node[$perms['id']]['children'] = [];
            } else {
                $node[$perms['parent_id']]['children'][] = $perms;
            }
        }

        dd($node);
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