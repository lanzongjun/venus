<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:36 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 商品SKU常量
 * Class ProviderGoodsSkuConstant
 * @method getMessage($code) static 获取文案
 */
class ProviderGoodsSkuConstant extends BaseConstant
{
    /**
     * @Message("添加商品SKU")
     */
    const ADD_PROVIDER_GOODS_SKU = 500001;

    /**
     * @Message("编辑商品SKU")
     */
    const EDIT_PROVIDER_GOODS_SKU = 500002;
}