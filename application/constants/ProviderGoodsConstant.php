<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:35 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 商品常量
 * Class ProviderGoodsConstant
 * @method getMessage($code) static 获取文案
 */
class ProviderGoodsConstant extends BaseConstant
{
    /**
     * @Message("添加商品")
     */
    const ADD_PROVIDER_GOODS = 200001;

    /**
     * @Message("编辑商品")
     */
    const EDIT_PROVIDER_GOODS = 200002;
}