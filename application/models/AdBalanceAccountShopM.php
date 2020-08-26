<?php

/**
 * AdBalanceAccountShopM
 * 每日商铺结算报告	
 * @author Vincent
 */
class AdBalanceAccountShopM extends CI_Model {
    var $__ENUM_SEND_STATE_TODO = 'todo';        //未知
    var $__ENUM_SEND_STATE_SUCCESS = 'success';  //已完结
    var $__ENUM_SEND_STATE_FAIL = 'fail';        //已取消
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    /**
     * 汇总店铺结算信息
     * @param type $ba_id
     * @return type
     * @throws Exception
     */
    function countShopInfo($ba_id) {
        log_message('debug', '汇总店铺结算信息');
        $s_sql = "INSERT INTO balance_account_shop (bas_bs_org_sn,bas_bs_sale_sn,"
                . "bas_bs_shop_name,bas_mail_to,bas_order_count,bas_order_amount,bas_ba_id) "
                . "SELECT bs_org_sn, bs_sale_sn,bs_shop_sn,bs_email,COUNT(eoi_code) order_count,"
                . "SUM(eoi_shop_receive_amount) order_amount,ba_id FROM balance_account " 
                . "LEFT JOIN order_info_eb ON eoi_ba_bat_id = ba_bat_id "
                . "LEFT JOIN base_shop_info ON bs_e_id = eoi_shop_id "
                . "WHERE ba_id = $ba_id GROUP BY bs_org_sn ";
        log_message('debug', "SQL文:$s_sql");        
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:" . $this->db->affected_rows());
        return $this->db->insert_id();
    }
    
    /**
     * 更新结算邮件报告
     * @param type $i_ba_id
     * @param type $i_bs_org_sn
     * @param type $s_mail_title
     * @param type $s_mail_file
     * @return type
     * @throws Exception
     */
    function updateMailInfo($i_ba_id, $i_bs_org_sn, $s_mail_title,$s_mail_file) {
        log_message('debug', '更新结算邮件报告');
        $s_mail_n = stripslashes($s_mail_title);
        $s_mail_f = stripslashes($s_mail_file);
        $s_sql = "UPDATE balance_account_shop SET bas_mail_file='$s_mail_f',"
                . "bas_mail_name='$s_mail_n' "
                . "WHERE bas_ba_id=$i_ba_id AND bas_bs_org_sn=$i_bs_org_sn ";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }
    
    /**
     * 更新邮件发送状态
     * @param type $i_bas_id
     * @param type $enum_send_state
     */
    function updateMailState($i_bas_id, $enum_send_state) {
        log_message('debug', '更新邮件发送状态');
        $s_sql = "UPDATE balance_account_shop SET bas_mail_send='$enum_send_state' "
                . "WHERE bas_id=$i_bas_id ";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }
}
