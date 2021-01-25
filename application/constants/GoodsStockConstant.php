<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/14
 * Time: 5:25 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺库存常量
 * Class GoodsStockConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsStockConstant extends BaseConstant
{
    /**
     * @Message("添加商品进货记录")
     */
    const ADD_GOODS_STOCK = 800001;

    /**
     * @Message("编辑商品进货记录")
     */
    const EDIT_GOODS_STOCK = 800002;

    /**
     * @Message("删除商品进货记录")
     */
    const DELETE_GOODS_STOCK = 800003;
}