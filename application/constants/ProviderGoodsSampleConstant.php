<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:36 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 商品取样常量
 * Class ProviderGoodsSampleConstant
 * @method getMessage($code) static 获取文案
 */
class ProviderGoodsSampleConstant extends BaseConstant
{
    /**
     * @Message("添加商品取样")
     */
    const ADD_PROVIDER_GOODS_SAMPLE_INFO = 400001;

    /**
     * @Message("编辑商品取样")
     */
    const EDIT_PROVIDER_GOODS_SAMPLE_INFO = 400002;
}