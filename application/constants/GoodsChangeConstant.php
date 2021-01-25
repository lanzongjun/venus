<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:38 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺调度常量
 * Class GoodsChangeConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsChangeConstant extends BaseConstant
{
    /**
     * @Message("添加商品调度记录")
     */
    const ADD_GOODS_CHANGE = 900001;

    /**
     * @Message("编辑商品调度记录")
     */
    const EDIT_GOODS_CHANGE = 900002;

    /**
     * @Message("删除商品调度记录")
     */
    const DELETE_GOODS_CHANGE = 900003;
}