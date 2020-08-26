<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdBalanceStationM
 *
 * @author Vincent
 */
class AdBalanceStationM extends CI_Model {

    var $__pay_account = '802320201183858';
    var $__table_name = 'balance_account';
    
    var $__ENUM_SEND_STATE_TODO = 'todo';        //未知
    var $__ENUM_SEND_STATE_SUCCESS = 'success';  //已完结
    var $__ENUM_SEND_STATE_FAIL = 'fail';        //已取消

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
     * 获得结算报告列表
     * @return type
     */
    function getList() {
        $s_sql = "SELECT false ck, bas_id,bas_bs_org_sn,bas_bs_sale_sn,bas_bs_shop_name,"
                . "bas_mail_file,bas_mail_name,bas_mail_send,bas_mail_to,bas_order_count,"
                . "bas_order_amount,bas_ba_id,ba_balance_date_begin date_begin,"
                . "ba_balance_date_end date_end,ba_balance_time FROM balance_account_shop "
                . "LEFT JOIN balance_account ON bas_ba_id=ba_id "
                . "ORDER BY ba_balance_time DESC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 根据ID获得报告
     * @param type $i_bas_id
     * @return type
     */
    function getInfoByID($i_bas_id) {
        $s_sql = "SELECT bas_id,bas_bs_org_sn,bas_bs_sale_sn,bas_bs_shop_name,"
                . "bas_mail_file,bas_mail_name,bas_mail_send,bas_mail_to,bas_order_count,"
                . "bas_order_amount,bas_ba_id,ba_balance_date_begin date_begin,"
                . "ba_balance_date_end date_end,ba_balance_time FROM balance_account_shop "
                . "WHERE bas_id=$i_bas_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得指定商铺的结算信息
     * @param type $ba_id
     * @param type $bs_org_sn
     * @return type
     */
    function getDetail($ba_id,$bs_org_sn) {
        $s_sql = "SELECT bad_id,bad_bs_org_sn,bad_bs_sale_sn,bad_bs_shop_name,"
                . "bad_pay_account,bad_bgs_code,bad_bgs_barcode,bad_bbp_settlement_price,"
                . "bad_count,bad_bbp_settlement_price*bad_count account_count,"
                . "bad_bgs_name,bad_ba_id FROM balance_account_detail "
                . "WHERE bad_ba_id=$ba_id AND bad_bs_org_sn=$bs_org_sn ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 更新邮件发送状态
     * @param type $i_bas_id
     * @param type $b_state
     * @return type
     * @throws Exception
     */
    function updateSendState($i_bas_id, $enum_state) {
        log_message('debug', "更新邮件发送状态:[ID:$i_bas_id,STATE:$enum_state]");
        $s_sql = "UPDATE balance_account_shop SET bas_mail_send='$enum_state' "
                . "WHERE bas_id=$i_bas_id ";
        try {
            $this->db->query($s_sql);
        } catch (Exception $ex) {
            throw $ex;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        return $i_rows;
    }
    
    /**
     * 批量发送邮件
     * @param type $a_bas_id
     * @return string
     */
    function sendMails($a_bas_id){
        $o_res = array(
            'state' => true,
            'msg' => array()
        );
        $s_bas_ids = $a_bas_id[0];
        for ($i = 1; $i < count($a_bas_id); $i++) {
            $s_bas_ids .= ",$a_bas_id[$i]";
        }
        $s_sql = "SELECT bas_id,bas_mail_file,bas_mail_name,bas_mail_to FROM balance_account_shop "
                . "WHERE bas_id IN ($s_bas_ids) ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        $a_msg = array();
        foreach ($a_line as $o_line) {
            try {
                $b_send_state = $this->_sendMail($o_line->bas_mail_file,$o_line->bas_mail_name,
                        $o_line->bas_mail_to);
                $enum_state = $b_send_state ? $this->__ENUM_SEND_STATE_SUCCESS : $this->__ENUM_SEND_STATE_FAIL;
                array_push($a_msg,"{[$o_line->bas_mail_name] 发送状态:$enum_state}");
                $this->updateSendState($o_line->bas_id, $enum_state);
            } catch (Exception $ex) {
                log_message('error', '批量发送邮件-异常中断！\r\n' . $e->getMessage());
                $o_res['state'] = false;
                $o_res['msg'] = "批量发送邮件-异常中断！\r\n" . $e->getMessage();
                return $o_result;
            }
        }
        $o_res['msg'] = $a_msg;
        return $o_res;
    }
    
    /**
     * 发送邮件
     * @param type $s_mail_file 邮件文件
     * @param type $s_mail_name 邮件标题
     * @param type $s_mail_to   发送目标
     * @return type
     */
    function _sendMail($s_mail_file,$s_mail_name,$s_mail_to) {
        $s_mail_to = 'wangmin@iuoo.onaliyun.com,sunmengchen@iuoo.onaliyun.com';
        
        $this->load->helper('file');
        $s_html = read_file(".$s_mail_file");
        
        log_message('debug', "$s_mail_name");
        
        $this->load->library('email');
        
        $config['useragent']= "CodeIgniter";
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['mailpath'] = 'D:\\xampp\\sendmail\\sendmail.exe -t';
        $config['charset'] = 'utf-8';
        
        $config['smtp_host'] = 'smtp.mxhichina.com';
        $config['smtp_user'] = 'wangmin@iuoo.onaliyun.com';
        $config['smtp_pass'] = 'vincent@163';
        $config['smtp_port'] = '25';

        $this->email->initialize($config);        
        
        $this->email->from('wangmin@iuoo.onaliyun.com', 'Vincent Wong');
        $this->email->to($s_mail_to);
        $this->email->cc('wangmin@iuoo.onaliyun.com');//孙梦晨

        $this->email->subject($s_mail_name);
        $this->email->message($s_html);

        $b_result = $this->email->send();
        log_message('debug', $this->email->print_debugger());
        return $b_result;
    }
    
    
}
