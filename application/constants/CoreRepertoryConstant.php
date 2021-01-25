<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:49 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 库存常量
 * Class CoreRepertoryConstant
 * @method getMessage($code) static 获取文案
 */
class CoreRepertoryConstant extends BaseConstant
{
    /**
     * @Message("导出库存列表")
     */
    const OUTPUT_CORE_REPERTORY = 1400001;
}