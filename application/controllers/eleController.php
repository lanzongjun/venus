<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/12/2
 * Time: 10:59 AM
 */

use ElemeOpenApi\Config\Config;
use ElemeOpenApi\OAuth\OAuthClient;







class eleController extends CI_Controller
{
    public function test()
    {
        //实例化一个配置类
        $config = new Config(1, 1, false);

//使用config对象，实例化一个授权类
        $client = new OAuthClient($config);


        //使用授权类获取token
        $token = $client->get_token_in_client_credentials();
        dd($token);
//根据OAuth2.0中的对应state，scope和callback_url，获取授权URL
        $auth_url = $client->get_auth_url($state, $scope, $callback_url);
    }
}