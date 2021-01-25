<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:43 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺异常订单常量
 * Class GoodsExceptionHandleConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsExceptionHandleConstant extends BaseConstant
{
    /**
     * @Message("添加异常订单记录")
     */
    const ADD_EXCEPTION_HANDLE = 1100001;

    /**
     * @Message("编辑异常订单记录")
     */
    const EDIT_EXCEPTION_HANDLE = 1100002;

    /**
     * @Message("删除异常订单记录")
     */
    const DELETE_EXCEPTION_HANDLE = 1100003;
}