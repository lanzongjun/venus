<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:48 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * SKU常量
 * Class CoreSkuConstant
 * @method getMessage($code) static 获取文案
 */
class CoreSkuConstant extends BaseConstant
{
    /**
     * @Message("新增SKU")
     */
    const ADD_SKU = 1300001;

    /**
     * @Message("编辑SKU")
     */
    const EDIT_SKU = 1300002;
}