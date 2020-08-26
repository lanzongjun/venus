<?php

/**
 * AdBalanceCountEBM
 * 饿百平台结算汇总
 * @author Vincent
 */
class AdBalanceCountEBM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    /**
     * 汇总饿百平台结算金额
     * @param type $ba_id
     * @return type
     * @throws Exception
     */
    function countAccount($ba_id) {
        $d_count = 0.00;
        $s_sql = "SELECT SUM(bce_amount) account_count FROM balance_count_eb WHERE bce_ba_id=$ba_id";
        try {
            $o_result = $this->db->query($s_sql);
            $a_line = $o_result->result();
            if (count($a_line) == 1) {
                $d_count = $a_line[0]->account_count;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $d_count;        
    }

}
