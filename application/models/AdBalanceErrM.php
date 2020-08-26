<?php

/**
 * Description of AdBalanceErrM
 * 结算异常处理
 *
 * @author Vincent
 */
class AdBalanceErrM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    function getErrList($ba_id) {
        $s_sql = "SELECT bae_platform,bs_shop_sn,bae_error_code,bae_error_reason "
                . "FROM balance_account_error "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bae_bs_org_sn "
                . "WHERE bae_ba_id=$ba_id ";
        $a_result = array();
        try {
            $o_result = $this->db->query($s_sql);
            $a_result = $o_result->result();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $a_result;
    }

    /**
     * 获取异常订单及商品
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $ba_id
     * @param type $a_order_codes
     */
    function setBalError($s_date_begin, $s_date_end, $ba_id, $a_order_codes) {
        log_message('debug', '获取异常订单及商品');
        //获得所有需要人工处理的订单号及相关店铺信息
        try {
            $this->_setErrOrder($s_date_begin, $s_date_end, $ba_id, $a_order_codes);
        } catch (Exception $ex) {
            throw $ex;
        }
        //获得缺少结算价的商品
        try {
            $this->_setErrGoods($s_date_begin, $s_date_end, $ba_id, $a_order_codes);
        } catch (Exception $ex) {
            throw $ex;
        }        
    }

    /**
     * 获得异常订单并加入异常库
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $ba_id
     * @param type $a_order_codes
     */
    function _setErrOrder($s_date_begin, $s_date_end, $ba_id, $a_order_codes = null) {
        log_message('debug', '获得异常订单并加入异常库');
        //获得当日所有订单
        $s_sql = "SELECT order_info_eb.*,bs_org_sn FROM order_info_eb "
                . "LEFT JOIN base_shop_info ON eoi_shop_id = bs_e_id "
                . "WHERE eoi_is_delete=0 ";
        if (is_null($a_order_codes) || !is_array($a_order_codes) || count($a_order_codes) == 0) {
            $s_sql .= "AND eoi_order_create_dt >= '$s_date_begin' "
                    . "AND eoi_order_create_dt <= '$s_date_end' ";
        } else {
            $s_codes = "'$a_order_codes[0]'";
            for ($i = 1; $i < count($a_order_codes); $i++) {
                $s_codes .= ",'$a_order_codes[$i]'";
            }
            $s_sql .= "AND eoi_code IN ($s_codes) ";
        }

        $this->load->model('AdEBOrderInfoM');

        $a_result = array();
        try {
            $o_result = $this->db->query($s_sql);
            $a_result = $o_result->result();
        } catch (Exception $ex) {
            throw $ex;
        }
        $a_line = $a_result;
        foreach ($a_line as $o_line) {
            //存在部分退款或者全单退
            if ($o_line->eoi_order_state_enum != $this->AdEBOrderInfoM->__ENUM_STATE_FINISH) {
                try {
                    $this->_newOrderErr($o_line, $ba_id, '存在非[已完结]订单');
                } catch (Exception $ex) {
                    throw $ex;
                }
                continue;
            }
//            //存在订单超时 TODO 暂时不判断超时情况
//            if ($this->_isOrderTimeout($o_line)) {
//                try {
//                    $this->_newOrderErr($o_line, $ba_id, '订单耗时超过三个小时');
//                } catch (Exception $ex) {
//                    throw $ex;
//                }
//                continue;
//            }
        }
    }

    /**
     * 获得缺少结算价的异常商品
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $ba_id
     * @param type $a_order_codes
     * @return boolean
     */
    function _setErrGoods($s_date_begin, $s_date_end, $ba_id, $a_order_codes = null) {
        log_message('debug', '获得缺少结算价的异常商品');
        // 获得缺少结算价的商品
        $s_sql = "SELECT sge_barcode,bbp_settlement_price,dbc_id,dbc_reason,"
                . "bs_org_sn,order_detail_eb.* "
                . "FROM order_detail_eb INNER JOIN order_info_eb ON eod_eoi_code = eoi_code "
                . "LEFT JOIN base_shop_info ON eoi_shop_id = bs_e_id "
                . "LEFT JOIN shop_goods_eb ON sge_gid = eod_sku_code "
                . "LEFT JOIN base_balance_price ON bbp_bar_code = sge_barcode "
                . "LEFT JOIN delay_balance_conf ON sge_barcode = dbc_bgs_barcode "
                . "WHERE eoi_is_delete=0 ";
        if (is_null($a_order_codes) || !is_array($a_order_codes) || count($a_order_codes) == 0) {
            $s_sql .= "AND eoi_order_create_dt >= '$s_date_begin' "
                    . "AND eoi_order_create_dt <= '$s_date_end' ";
        } else {
            $s_codes = "'$a_order_codes[0]'";
            for ($i = 1; $i < count($a_order_codes); $i++) {
                $s_codes .= ",'$a_order_codes[$i]'";
            }
            $s_sql .= "AND eoi_code IN ($s_codes) ";
        }
        $s_sql .= "AND bbp_settlement_price IS NULL ";
        
        log_message('debug', "SQL文:$s_sql");
        $a_result = array();
        try {
            $o_result = $this->db->query($s_sql);
            $a_result = $o_result->result();
        } catch (Exception $ex) {
            throw $ex;
        }
        $a_line = $a_result;

        if (count($a_line) > 0) {
            $this->db->trans_start();
            foreach ($a_line as $o_line) {
                if ($o_line->dbc_id-0 > 0){
                    continue;
                }
                $s_sql1 = "INSERT INTO balance_account_error (bae_platform,bae_bgs_barcode,"
                        . "bae_bs_org_sn,bae_error_code,bae_error_reason,bae_ba_id) VALUES ('饿百','"
                        . $o_line->sge_barcode . "',$o_line->bs_org_sn,'ERR_GOODS','缺少结算价',$ba_id)";
                log_message('debug', "SQL文:$s_sql1");
                $this->db->query($s_sql1);
            }
            $b_result = false;
            try {
                $b_result = $this->db->trans_complete();
            } catch (Exception $ex) {
                throw $ex;
            }
            return $b_result;
        } else {
            return true;
        }
    }

    /**
     * 订单是否超时
     * @param type $o_line
     * @return boolean
     */
    function _isOrderTimeout($o_line) {
        //接单时间超过3小时
        $d_t1 = strtotime($o_line->eoi_shop_receive);
        $d_t2 = strtotime($o_line->eoi_order_finish);
        $d_diff_m = ($d_t2 > $d_t1) ? ($d_t2 - $d_t1) / 60 : ($d_t2 + (24 * 60 * 60) - $d_t1) / 60;
        if ($d_diff_m / 60 > 3) {
            return true;
        }
        return false;
    }

    /**
     * 新增结算异常订单信息
     * @param type $o_line
     * @param type $ba_id
     * @param type $err_msg
     * @return type
     */
    function _newOrderErr($o_line, $ba_id, $err_msg) {
        log_message('debug', "新增结算异常订单信息:$err_msg");
        $s_sql = "INSERT INTO balance_account_error (bae_platform,bae_order_id,"
                . "bae_bs_org_sn,bae_error_code,bae_error_reason,bae_ba_id) VALUES ('饿百','"
                . $o_line->eoi_code . "',$o_line->bs_org_sn,'ERR_ORDER','$err_msg',$ba_id)";
        log_message('debug', "SQL文:$s_sql");
        $i_rows = 0;
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "受影响记录数:$i_rows");
        return $i_rows;
    }

}
