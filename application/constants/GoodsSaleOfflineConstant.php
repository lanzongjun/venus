<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:37 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺线下销售常量
 * Class GoodsSaleOfflineConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsSaleOfflineConstant extends BaseConstant
{
    /**
     * @Message("添加线下销售记录")
     */
    const ADD_GOODS_SALE_OFFLINE = 600001;

    /**
     * @Message("编辑线下销售记录")
     */
    const EDIT_GOODS_SALE_OFFLINE = 600002;

    /**
     * @Message("删除线下销售记录")
     */
    const DELETE_GOODS_SALE_OFFLINE = 600003;
}