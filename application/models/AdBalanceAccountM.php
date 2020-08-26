<?php

/**
 * 综合结算
 * Description of AdBalanceAccountM
 *
 * @author Vincent
 */
class AdBalanceAccountM extends CI_Model {

    var $__pay_account = '802320201183858';
    var $__table_name = 'balance_account';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
        $s_str = str_replace('"', "", $s_str);
        $s_str = str_replace("'", "", $s_str);
        $s_str = trim($s_str);
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $str_encode = mb_convert_encoding($s_str, 'UTF-8', $encode);
        return $str_encode;
    }

    /**
     * 获得列表
     * @return type
     */
    function getList() {
        $o_result = $this->db->query("SELECT * FROM $this->__table_name ORDER BY ba_balance_date_begin DESC");
        return $o_result->result();
    }

    /**
     * 重新结算
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return type
     */
    function doReBalance($s_date_begin, $s_date_end) {
        $this->_clearBalTotal($s_date_begin, $s_date_end);
        return $this->doBalance($s_date_begin, $s_date_end, false);
    }

    /**
     * 获得指定日结算信息
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $b_re
     * @return string
     */
    function doBalance($s_date_begin, $s_date_end, $b_re = true, $a_order_codes = null) {
        set_time_limit(0);
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        if ($b_re && $this->_isBalanced($s_date_begin, $s_date_end)) {
            $o_result['state'] = false;
            $o_result['msg'] = '已经做过结算，无需结算';
            return $o_result;
        }

        // 生成批处理流水号
        $i_ba_bat_id = time();
        log_message('debug', "生成批处理流水号:$i_ba_bat_id");

        //新增结算记录，并获得结算ID
        log_message('debug', '新增结算记录');
        $i_ba_id = 0;
        try {
            $i_ba_id = $this->_newBalTotal($s_date_begin, $s_date_end, $i_ba_bat_id);
            if ($i_ba_id == 0) {
                log_message('error', '新增结算记录失败，停止结算');
                $o_result['state'] = false;
                $o_result['msg'] = '新增结算记录失败，停止结算';
                return $o_result;
            }
        } catch (Exception $e) {
            log_message('error', '新增结算记录-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "新增结算记录-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }

        //获取异常订单及商品
        $this->load->model('AdBalanceErrM');
        try {
            $this->AdBalanceErrM->setBalError($s_date_begin, $s_date_end, $i_ba_id, $a_order_codes);
        } catch (Exception $e) {
            log_message('error', '获取异常订单时发生错误，停止结算！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "获取异常订单时发生错误，停止结算！\r\n" . $e->getMessage();
            return $o_result;
        }

        //设置需要批处理的订单
        try {
            $this->_setBatBalOrder($s_date_begin, $s_date_end, $i_ba_id, $i_ba_bat_id, $a_order_codes);
        } catch (Exception $e) {
            log_message('error', '设置需要批处理的订单-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "设置需要批处理的订单-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }

        //结算饿百各站销量
        try {
            $i_rows1 = $this->_balShopSaleEB($i_ba_id, $i_ba_bat_id);
            if ($i_rows1 < 1) {
                log_message('error', '结算饿百各站销量-没有数据被更新，停止结算');
                $o_result['state'] = false;
                $o_result['msg'] = '结算饿百各站销量-没有数据被更新，停止结算';
                return $o_result;
            }
        } catch (Exception $e) {
            log_message('error', '结算饿百各站销量-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "结算饿百各站销量-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }

        //填充结算明细
        try {
            $i_rows2 = $this->_fillDetail($i_ba_id);
            if ($i_rows2 < 1) {
                log_message('error', '填充结算明细-没有数据被更新，停止结算');
                $o_result['state'] = false;
                $o_result['msg'] = '填充结算明细-没有数据被更新，停止结算';
                return $o_result;
            }
        } catch (Exception $e) {
            log_message('error', '填充结算明细-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "填充结算明细-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }

        //结算临期库
        $this->load->model('AdBalanceOnSaleYJM');
        try {
            $this->AdBalanceOnSaleYJM->balOnSaleYJ($i_ba_id);
        } catch (Exception $e) {
            log_message('error', '结算临期库-异常中断！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "结算临期库时-异常中断！\r\n" . $e->getMessage();
            return $o_result;
        }

        //结算促销库
        log_message('debug', '结算促销库');
        $this->load->model('AdBalanceOnSaleStM');
        try {
            $this->AdBalanceOnSaleStM->balOnSaleSt($i_ba_id);
        } catch (Exception $e) {
            log_message('error', '结算促销库时发生错误，停止结算！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "结算促销库时发生错误，停止结算！\r\n" . $e->getMessage();
            return $o_result;
        }

        //结算延期库
        log_message('debug', '结算延期库');
        $this->load->model('AdBalanceDelayM');
        try {
            $this->AdBalanceDelayM->balDelay($i_ba_id);
        } catch (Exception $e) {
            log_message('error', '结算延期库时发生错误，停止结算！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "结算延期库时发生错误，停止结算！\r\n" . $e->getMessage();
            return $o_result;
        }
        
        //每日商铺结算报告
        $this->load->model('AdBalanceAccountShopM');
        try {
            $this->AdBalanceAccountShopM->countShopInfo($i_ba_id);
        } catch (Exception $e) {
            log_message('error', '汇总店铺结算信息发生错误，停止结算！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "汇总店铺结算信息发生错误，停止结算！\r\n" . $e->getMessage();
            return $o_result;
        }
        
        //汇总更新所有
        $b_result = false;
        try {
            $b_result = $this->_balTotalAccount($i_ba_id);
        } catch (Exception $ex) {
            log_message('error', '更新结算汇总信息时发生错误，停止结算！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "更新结算汇总信息时发生错误，停止结算！\r\n" . $ex->getMessage();
            return $o_result;
        }
        
        $this->load->model('AdBalanceResultM');
        $this->AdBalanceResultM->newMailHTML($i_ba_id, $s_date_begin, $s_date_end);

        $o_result['state'] = $b_result;
        $o_result['msg'] = $b_result ? "结算成功" : "结算失败";
        return $o_result;
    }

    /**
     * 设置需要批处理的订单
     * @param type $s_date_begin
     * @param type $s_date_end
     * @param type $i_ba_id
     * @param type $i_ba_bat_id
     * @param type $a_order_codes
     * @return type
     * @throws Exception
     */
    function _setBatBalOrder($s_date_begin, $s_date_end, $i_ba_id, $i_ba_bat_id, $a_order_codes = null) {
        $enum_state_finish = $this->AdEBOrderInfoM->__ENUM_STATE_FINISH;
        log_message('debug', '设置需要批处理的订单');
        $s_sql = "SELECT eoi_code FROM order_info_eb "
                . "WHERE eoi_is_delete=0 AND eoi_order_state_enum='$enum_state_finish' ";          //订单未被异常删除或者商品未被删除
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

        $this->db->trans_start();

        $s_sql_bat = "UPDATE order_info_eb SET eoi_ba_bat_id=$i_ba_bat_id WHERE eoi_code IN ($s_sql) ";
        $this->db->query($s_sql_bat);
        log_message('debug', "SQL文:$s_sql_bat");
        $s_sql_bat_err = "UPDATE order_info_eb SET eoi_ba_bat_id=0 WHERE eoi_code IN "
                . "(SELECT bae_order_id FROM balance_account_error WHERE bae_ba_id=$i_ba_id) ";
        log_message('debug', "SQL文:$s_sql_bat_err");
        $b_result = false;
        try {
            $b_result = $this->db->trans_complete();
        } catch (Exception $ex) {
            throw $ex;
        }
        log_message('debug', "事务执行结果:$b_result");
        return $b_result;
    }

    /**
     * 汇总更新所有
     * @param type $ba_id
     */
    function _balTotalAccount($ba_id) {
        $d_count1 = 0.00;
        $d_count2 = 0.00;
        $d_count3 = 0.00;
        $b_result = true;

        log_message('debug', '汇总正常结算金额');
        try {
            $d_count1 += $this->_countDetailAccount($ba_id);
            log_message('debug', "汇总正常结算金额:$d_count1");
        } catch (Exception $ex) {
            throw $ex;
        }

        log_message('debug', '汇总临期结算金额');
        $this->load->model('AdBalanceOnSaleYJM');
        try {
            $d_count2 += $this->AdBalanceOnSaleYJM->countAccount($ba_id);
            log_message('debug', "汇总临期结算金额:$d_count2");
        } catch (Exception $ex) {
            throw $ex;
        }

        log_message('debug', '更新易捷结算总金额');
        $d_total = $d_count1 + $d_count2;
        if ($d_total > 0) {
            $s_sql1 = "UPDATE balance_account SET ba_balance_yj=$d_total WHERE ba_id=$ba_id ";
            try {
                $this->db->query($s_sql1);
                $i_rows1 = $this->db->affected_rows();
            } catch (Exception $e) {
                throw $e;
            }
            $b_result = $i_rows1 === 1;
        } else {
            log_message('debug', "易捷结算总金额为:$d_total,不需更新");
        }

        log_message('debug', '汇总饿百平台结算金额');
        $this->load->model('AdBalanceCountEBM');
        try {
            $d_count3 += $this->AdBalanceCountEBM->countAccount($ba_id);
            log_message('debug', "汇总饿百平台结算金额:$d_count3");
        } catch (Exception $ex) {
            throw $ex;
        }

        log_message('debug', '更新饿百结算金额');
        if ($d_count3 > 0) {
            $s_sql2 = "UPDATE balance_account SET ba_balance_eb=$d_count3 WHERE ba_id=$ba_id ";
            try {
                $this->db->query($s_sql2);
                $i_rows2 = $this->db->affected_rows();
            } catch (Exception $e) {
                throw $e;
            }
            $b_result = $i_rows2 === 1;
        } else {
            log_message('debug', "饿百结算金额为:$d_count3,不需更新！");
        }

//TODO 此处添加其他平台汇总金额

        return $b_result;
    }

    /**
     * 汇总正常结算明细金额 TODO
     * @param type $ba_id
     * @return type
     * @throws Exception
     */
    function _countDetailAccount($ba_id) {
        $d_count1 = 0.00;
        //正常结算金额
        $s_sql1 = "SELECT SUM(bad_bbp_settlement_price*bad_count) account_count "
                . "FROM balance_account_detail WHERE bad_ba_id=$ba_id ";
        try {
            $o_result1 = $this->db->query($s_sql1);
            $a_line1 = $o_result1->result();
            if (count($a_line1) == 1) {
                $d_count1 = $a_line1[0]->account_count;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $d_count1;
    }

    /**
     * 是否已经进行过结算
     * @param type $s_date_begin
     * @param type $s_date_end
     * @return boolean
     */
    function _isBalanced($s_date_begin, $s_date_end) {
        $s_sql = "SELECT 1 FROM balance_account WHERE ba_balance_date_begin <= '$s_date_begin' AND ba_balance_date_end >= '$s_date_end' ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        if (count($a_line) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 新增每日结算
     * @return type 总结算ID
     */
    function _newBalTotal($s_date_begin, $s_date_end, $i_ba_bat_id) {
        $s_sql = "INSERT INTO balance_account (ba_balance_date_begin,"
                . "ba_balance_date_end,ba_bat_id,ba_balance_time) "
                . "VALUES ('$s_date_begin','$s_date_end',$i_ba_bat_id,'" 
                . date("Y-m-d H:i:s", time()) . "')";
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
     * 清空某日期结算记录
     * @param type $s_date
     */
    function _clearBalTotal($s_date_begin, $s_date_end) {
        log_message('debug', "清空[$s_date]结算记录");
        $s_sql = "SELECT ba_id FROM balance_account WHERE ba_balance_date_begin='$s_date_begin' "
                . "AND ba_balance_date_end='$s_date_end' ";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();

        if (count($a_line) == 0) { return true; }

        $ba_id = $a_line[0]->ba_id;

        $this->db->trans_start();
        $s_sql1 = "DELETE FROM balance_account_collect WHERE bac_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql1");
        $this->db->query($s_sql1);
        $s_sql2 = "DELETE FROM balance_account_detail WHERE bad_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql2");
        $this->db->query($s_sql2);
        $s_sql3 = "DELETE FROM balance_account_error WHERE bae_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql3");
        $this->db->query($s_sql3);
        $s_sql4 = "DELETE FROM balance_shop_storage WHERE bss_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql4");
        $this->db->query($s_sql4);
        $s_sql5 = "DELETE FROM delay_balance_shop WHERE dbs_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql5");
        $this->db->query($s_sql5);
        $s_sql6 = "DELETE FROM onsale_storage_shop WHERE oss_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql6");
        $this->db->query($s_sql6);
        $s_sql7 = "DELETE FROM onsale_yj_shop WHERE oys_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql7");
        $this->db->query($s_sql7);
        $s_sql8 = "UPDATE balance_count_eb SET bce_ba_id=0 WHERE bce_ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql8");
        $this->db->query($s_sql8);
        $s_sql9 = "DELETE FROM balance_account WHERE ba_id=$ba_id ";
        log_message('debug', "SQL文:$s_sql9");
        $this->db->query($s_sql9);
        $s_sql10 = "DELETE FROM balance_account_shop WHERE bas_ba_id=$ba_id ";
        $this->db->query($s_sql10);
        return $this->db->trans_complete();
    }

    /**
     * 结算饿百各站销量
     * @param type $ba_id
     * @param type $i_ba_bat_id
     * @return type
     */
    function _balShopSaleEB($ba_id, $i_ba_bat_id) {
        log_message('debug', '结算饿百各站销量');
        $s_sql = "INSERT INTO balance_shop_storage (bss_bs_org_sn,bss_bs_sale_sn,"
                . "bss_bs_shop_name,bss_bgs_code,bss_bgs_barcode,bss_count,bss_bgs_name,bss_ba_id) "
                . "SELECT bs_org_sn,bs_sale_sn,eoi_shop_name,bbp_yj_code,"
                . "sge_barcode, SUM(eod_buy_count) t_eod_buy_count, "
                . "eod_goods_name,$ba_id FROM order_info_eb "
                . "INNER JOIN order_detail_eb ON eoi_code = eod_eoi_code "      //匹配订单信息
                . "LEFT JOIN shop_goods_eb ON sge_gid = eod_sku_code "          //匹配商品条码
                . "LEFT JOIN base_balance_price ON bbp_bar_code = sge_barcode " //匹配商品结算价
                . "LEFT JOIN base_shop_info ON bs_e_id = eoi_shop_id "          //匹配店铺信息
                . "WHERE eoi_ba_bat_id=$i_ba_bat_id AND eod_buy_count>0 "       //订单未被异常删除或者商品未被删除
                . "GROUP BY eoi_shop_id,eod_sku_code ";
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

    /**
     * 填充结算明细
     * @param type $ba_id
     * @return type
     */
    function _fillDetail($ba_id) {
        log_message('debug', '填充结算明细');
        $s_sql = "INSERT INTO balance_account_detail (bad_bs_org_sn,bad_bs_sale_sn,"
                . "bad_bs_shop_name,bad_pay_account,bad_bgs_code,bad_bgs_barcode,"
                . "bad_bbp_settlement_price,bad_count,bad_bgs_name,bad_ba_id) "
                . "SELECT bss_bs_org_sn,bss_bs_sale_sn,bss_bs_shop_name,'$this->__pay_account',"
                . "bss_bgs_code,bss_bgs_barcode,bbp_settlement_price,bss_count,"
                . "bss_bgs_name,$ba_id FROM balance_shop_storage "
                . "LEFT JOIN base_balance_price ON bbp_bar_code = bss_bgs_barcode "
                . "WHERE bss_ba_id=$ba_id ";
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

    /**
     * 导出CSV
     * @return type
     */
    function _outputCSV() {
        $this->load->dbutil();

        $query = $this->db->query("SELECT * FROM $this->__table_name");

        return $this->dbutil->csv_from_result($query);
    }

}
