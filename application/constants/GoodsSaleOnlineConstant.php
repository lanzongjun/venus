<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:43 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺线上销售常量
 * Class GoodsSaleOnlineConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsSaleOnlineConstant extends BaseConstant
{
    /**
     * @Message("预导入线上销售记录")
     */
    const IMPORT_EXCEL = 1200001;

    /**
     * @Message("修改线上销售记录")
     */
    const EDIT_GOODS_ONLINE_INFO = 1200002;

    /**
     * @Message("删除线上销售记录")
     */
    const DELETE_GOODS_ONLINE_INFO = 1200003;
}