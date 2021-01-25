<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:49 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 财务常量
 * Class FinanceAccountConstant
 * @method getMessage($code) static 获取文案
 */
class FinanceAccountConstant extends BaseConstant
{
    /**
     * @Message("导出财务结算列表")
     */
    const OUTPUT_FINANCE_ACCOUNT = 1400001;
}