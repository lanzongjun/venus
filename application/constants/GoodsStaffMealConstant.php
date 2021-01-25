<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/15
 * Time: 5:38 PM
 */

include_once APPPATH . 'constants/BaseConstant.php';

/**
 * 店铺员工餐常量
 * Class GoodsStaffMealConstant
 * @method getMessage($code) static 获取文案
 */
class GoodsStaffMealConstant extends BaseConstant
{
    /**
     * @Message("添加员工餐记录")
     */
    const ADD_STAFF_MEAL = 1000001;

    /**
     * @Message("编辑员工餐记录")
     */
    const EDIT_STAFF_MEAL = 1000002;

    /**
     * @Message("删除员工餐记录")
     */
    const DELETE_STAFF_MEAL = 1000003;
}