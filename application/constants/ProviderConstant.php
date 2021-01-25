<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:33 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 供应商常量
 * Class ProviderConstant
 * @method getMessage($code) static 获取文案
 */
class ProviderConstant extends BaseConstant
{
    /**
     * @Message("添加供应商")
     */
    const ADD_PROVIDER = 100001;

    /**
     * @Message("编辑供应商")
     */
    const EDIT_PROVIDER = 100002;
}