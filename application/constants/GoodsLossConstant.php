<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:37 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺损耗常量
 * Class GoodsLossConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsLossConstant extends BaseConstant
{
    /**
     * @Message("添加商品损耗记录")
     */
    const ADD_GOODS_LOSS_INFO = 700001;

    /**
     * @Message("编辑商品损耗记录")
     */
    const EDIT_GOODS_LOSS_INFO = 700002;

    /**
     * @Message("删除商品损耗记录")
     */
    const DELETE_GOODS_LOSS_INFO = 700003;
}