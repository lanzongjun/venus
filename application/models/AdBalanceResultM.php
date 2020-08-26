<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 结算结果
 * Description of AdBalanceResultM
 *
 * @author Vincent
 */
class AdBalanceResultM extends CI_Model {
    public $__pay_account = '802320201183858';
    public $__body_empty = '<tr><td colspan=7>没有需要结算的商品</td></tr>';
    public $__body_empty_ab = '<tr><td colspan=5>无商品</td></tr>';
    public $__body_empty_delay = '<tr><td colspan=4>无商品</td></tr>';
    
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
     * 创建邮件文件
     * @param type $ba_id
     * @param type $s_date_begin
     * @param type $s_date_end
     */
    function newMailHTML($ba_id,$s_date_begin, $s_date_end) {        
        $this->load->helper('file');
        if ($s_date_begin == $s_date_end) {
            $s_date = $s_date_begin;
        } else {
            $s_date = "$s_date_begin~$s_date_end";
        }
        if(!file_exists("./output_bal/$s_date/")) {
            mkdir("./output_bal/$s_date/");
        }
        
        $a_shops = $this->_getDetailShops($ba_id);
        foreach ($a_shops as $o_line) {            
            $this->_newMailDetail($ba_id, $o_line->bss_bs_org_sn,$o_line->bs_shop_sn
                    ,$o_line->bss_bs_shop_name,$ba_id,$s_date_begin, $s_date_end);
        }
    }
    
    /**
     * 
     * @param type $ba_id
     * @return type
     */
    function _getDetailShops($ba_id) {
        $s_sql = "SELECT bss_bs_org_sn,bs_shop_sn,bss_bs_shop_name FROM balance_shop_storage "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bss_bs_org_sn "
                . "WHERE bss_ba_id=$ba_id GROUP BY bss_bs_org_sn";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 创建邮件详情
     * @param type $i_ba_id
     * @param type $bs_org_sn
     * @param type $bs_shop_sn
     * @param type $bs_shop_name
     * @param type $ba_id
     * @param type $s_date_begin
     * @param type $s_date_end
     */
    function _newMailDetail($i_ba_id, $bs_org_sn,$bs_shop_sn,$bs_shop_name,$ba_id,$s_date_begin, $s_date_end){
        if ($s_date_begin == $s_date_end) {
            $s_date = $s_date_begin;
        } else {
            $s_date = "$s_date_begin~$s_date_end";
        }
        $s_bodys = "";
        $i_no = 1;
        
        $s_sql = "SELECT bad_pay_account,bad_bgs_code,bad_bbp_settlement_price,"
                . "bad_count,bad_bgs_barcode,bad_bgs_name FROM balance_account_detail "
                . "WHERE bad_ba_id=$ba_id AND bad_bs_org_sn = $bs_org_sn "
                . "ORDER BY bad_bs_org_sn ";
        log_message('debug', "SQL文:$s_sql");
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        foreach ($a_line as $o_line) {
                $s_bodys .= "<tr>";
                $s_bodys .= "<td>".$i_no++."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_code."</td>";
                $s_bodys .= "<td>".$o_line->bad_bbp_settlement_price."</td>";
                $s_bodys .= "<td>".$o_line->bad_count."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_barcode."</td>";
                $s_bodys .= "<td>".$o_line->bad_bgs_name."</td>";
                $s_bodys .= "<td></td>";
                $s_bodys .= "</tr>";
        }
        
        //追加临期销售
        $this->load->model('AdBalanceOnSaleYJM');
        $s_bodys .= $this->AdBalanceOnSaleYJM->appendHTMLTable($bs_org_sn, $ba_id,$i_no);
        
        //写本地邮件文件
        $this->load->helper('file');
        $s_template = read_file('./output_bal/template.html');
        
        //标题
        $s_mail_title = "易捷结算表(".$bs_shop_sn."站)($s_date)";
        
        $s_html = str_replace( 't_var_title', $s_mail_title, $s_template);        
        //付款账号
        $s_html = str_replace( 't_pay_account', $this->__pay_account, $s_html);
        //站点名称
        $s_html = str_replace( 't_shop_name', $bs_shop_name, $s_html);
        //时间
        $s_html = str_replace( 't_bal_date', $s_date, $s_html);
        //结算信息
        if ($s_bodys != ''){
            $s_html = str_replace('t_var_balance_content', $s_bodys, $s_html);        
        } else {
            $s_html = str_replace('t_var_balance_content', $this->__body_empty, $s_html);        
        }
        
        //AB库
        $this->load->model('AdBalanceOnSaleStM');
        $s_body_ab = $this->AdBalanceOnSaleStM->getHTMLTable($ba_id,$bs_org_sn);
        if ($s_body_ab != ''){
            $s_html = str_replace('t_var_bal_ab', $s_body_ab, $s_html);        
        } else {
            $s_html = str_replace('t_var_bal_ab', $this->__body_empty_ab, $s_html);        
        }
        
        //延期库
        $this->load->model('AdBalanceDelayM');
        $s_body_delay = $this->AdBalanceDelayM->getHTMLTable($ba_id,$bs_org_sn);
        if ($s_body_delay != ''){
            $s_html = str_replace('t_var_bal_delay', $s_body_delay, $s_html);        
        } else {
            $s_html = str_replace('t_var_bal_delay', $this->__body_empty_delay, $s_html);        
        }
        
        $s_mail_path = "/output_bal/$s_date/$bs_shop_name.$s_date.html";
        write_file(".$s_mail_path", $s_html);
        
        //更新结算邮件报告
        $this->load->model('AdBalanceAccountShopM');
        try {
            $this->AdBalanceAccountShopM->updateMailInfo($i_ba_id, $bs_org_sn, $s_mail_title,$s_mail_path);
        } catch (Exception $e) {
            log_message('error', '汇总店铺结算信息发生错误，停止结算！\r\n' . $e->getMessage());
        }
        
        //发送邮件
        //$this->sendMail($bs_shop_name.$s_date.'结算表',$s_html);
    }
    
    function sendMail($s_subject,$s_html){
        log_message('debug', "$s_subject");
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
        
//        $this->email->from('wangmin7391@dingtalk.com', 'Vincent Wong');
        $this->email->from('wangmin@iuoo.onaliyun.com', 'Vincent Wong');
        $this->email->to('wangmin@iuoo.onaliyun.com,huzizhuo@iuoo.onaliyun.com');//孙梦晨
//        $this->email->cc('yn8_qbm0kggqi@dingtalk.com,j8cs5fq@dingtalk.com');//CZY

        $this->email->subject($s_subject);
        $this->email->message($s_html);

        $this->email->send();
        log_message('debug', $this->email->print_debugger());
        
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

    /**
     * 导出XML
     * @return type
     */
    function _outputXML() {
        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM $this->__table_name");
        $config = array(
            'root' => 'root',
            'element' => 'element',
            'newline' => "\n",
            'tab' => "\t"
        );
        return $this->dbutil->xml_from_result($query, $config);
    }

}
