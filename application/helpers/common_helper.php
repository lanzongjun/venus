<?php

/**
 * 获取环境变量
 */
if ( ! function_exists('my_env')){
    function my_env($env){
        $dotenv = Dotenv\Dotenv::createImmutable(str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
        $dotenv->load();
        return  $_ENV[$env];
    }
}