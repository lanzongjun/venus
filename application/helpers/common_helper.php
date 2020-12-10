<?php

/**
 * 获取环境变量
 */
if ( ! function_exists('my_env')){
    function my_env($env){
        $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();
        return  $_ENV[$env];
    }
}