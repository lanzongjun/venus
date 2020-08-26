<?php

/**
 * AdAccountCheckM
 * 饿百平台结算汇总
 * @author Vincent
 */
class AdAccountCheckM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }    
    
    /**
     * 获得指定日期的最后一个流水号
     * @param type $s_date
     * @return string
     */
    function _getMaxBillCode($s_date) {
        $s_sql = "SELECT MAX(cpd_bill_code) bill_code FROM cash_pool_detail "
                . "WHERE cpd_biz_type='支出' AND cpd_date='$s_date'";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        if (count($a_line) === 0) {
            return '';
        }
        $bill_code = $a_line[0]->bill_code;
        return $bill_code;
    }

    /**
     * 填充资金流水信息
     * @param type $i_ba_id
     * @param type $s_bill_code
     * @return type
     */
    function _fillCashPool($i_ba_id, $s_bill_code) {
        log_message('debug', "填充资金流水信息");
        $s_sql = "UPDATE balance_account,cash_pool_detail SET ba_balance_date_begin=cpd_date,"
                . "ba_cpd_remaining_sum=cpd_remaining_sum,ba_cpd_bill_code=cpd_bill_code,ba_cpd_time=cpd_time "
                . "WHERE ba_id=$i_ba_id AND cpd_bill_code='$s_bill_code' ";
        log_message('debug', "SQL文:$s_sql");
        $this->db->query($s_sql);
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        return $i_rows;
    }

}
