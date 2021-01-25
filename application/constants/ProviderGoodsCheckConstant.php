<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/14
 * Time: 4:45 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺商品盘点常量
 * Class ProviderGoodsCheckConstant
 * @method getMessage($code) static 获取文案
 */
class ProviderGoodsCheckConstant extends BaseConstant
{
    /**
     * @Message("添加商品盘点")
     */
    const ADD_GOODS_CHECK = 300001;

    /**
     * @Message("添加商品盘点详情")
     */
    const ADD_GOODS_CHECK_DETAIL = 300002;

    /**
     * @Message("编辑商品盘点")
     */
    const EDIT_GOODS_CHECK = 300003;

    /**
     * @Message("删除商品盘点")
     */
    const DELETE_GOODS_CHECK = 300004;

    /**
     * @Message("删除商品盘点详情")
     */
    const DELETE_GOODS_CHECK_DETAIL = 300005;
}