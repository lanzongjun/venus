<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdEBShop
 *
 * @author Vincent
 */
class AdBillInfoEBM extends CI_Model {

    var $__table_name = 'bill_info_eb';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $str_encode = mb_convert_encoding($s_str, 'UTF-8', $encode);
        return $str_encode;
    }

    /**
     * 获得临时表名称
     * @return string
     */
    function _getTempTableName() {
        $this->load->library('session');
        $s_userid = $this->session->userdata('s_id');
        $s_time = date("YmdHis", time());
        $s_table_name_temp = $s_userid . '_' . $s_time;
        return $s_table_name_temp;
    }

    /**
     * 创建临时预览表
     * @param type $s_table_name
     */
    function _createTempTable($s_table_name) {
        $this->dbforge->add_field($this->fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table("temp_$s_table_name");
    }

    /**
     * 加载临时表
     * @param type $s_table_name
     * @return type
     */
    function _loadTempTable($s_table_name) {
        $o_result = $this->db->query("SELECT * FROM temp_$s_table_name");
        return $o_result->result();
    }

    /**
     * 获得列表
     * @return type
     */
    function _getList($i_page, $i_rows) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_sql = "SELECT * FROM $this->__table_name ORDER BY bie_date DESC "
                . "LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal();
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function _getTotal() {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    /**
     * 备份表
     * @param type $s_table_name
     */
    function _backupTable($s_table_name) {
        $_s_backup_name = $s_table_name . '_' . date("YmdHis", time()) . '.sql';
        $prefs = array(
            'tables' => array($s_table_name), // 你要备份的表，如果留空将备份所有的表。
            'ignore' => array(), // 你要忽略备份的表。
            'format' => 'txt', // 导出文件的格式。gzip, zip, txt
            'filename' => $_s_backup_name, // 备份文件名。如果你使用了 zip 压缩这个参数是必填的。
            'add_drop' => TRUE, // 是否在导出的 SQL 文件里包含 DROP TABLE 语句
            'add_insert' => TRUE, // 是否在导出的 SQL 文件里包含 INSERT 语句
            'newline' => "\n"                     // 导出的 SQL 文件使用的换行符
        );
        $this->load->dbutil();
        $backup = $this->dbutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file("./backup/sql/$_s_backup_name", $backup);
    }

    /**
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
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
     * 导入CSV
     * @param type $o_result
     */
    function inputCSV($s_table_name, $o_result) {
        set_time_limit(0);
        $this->_createTempTable($s_table_name);
        $a_lines = $o_result->datas;

        $this->db->trans_start();
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            $s_sql = "INSERT INTO temp_$s_table_name (bie_date,bie_shop_name,"
                    . "bie_shop_id_ele,bie_shop_id,bie_order_sn,bie_order_code,"
                    . "bie_order_code_ele,bie_biz_type,bie_delivery_type,"
                    . "bie_order_type,bie_order_from,bie_order_create_dt,"
                    . "bie_balance_amount,bie_cus_pay,bie_ss_amount,bie_ss_packet,"
                    . "bie_ss_promotion,bie_ss_d_ticket,bie_ss_d_promotion,"
                    . "bie_ss_ticket,bie_ss_gift,bie_ss_ticket_p,bie_shop_rate,"
                    . "bie_agent_rate,bie_send_ratio,bie_send_min,bie_send_fee,"
                    . "bie_commission,bie_products,bie_sp_amount,bie_sp_packet,"
                    . "bie_sp_promotion,bie_sp_ticket,bie_sp_d_ticket,bie_sp_d_promotion,"
                    . "bie_sp_gift,bie_baidu_rate,bie_cold_box_fee,bie_delivery_party_fee) VALUES "
                    . "('" . $this->_encodeUTF8($o_line['0'])
                    . "','" . $this->_encodeUTF8($o_line['1'])
                    . "','" . $this->_encodeUTF8($o_line['2'])
                    . "','" . $this->_encodeUTF8($o_line['3'])
                    . "'," . ($o_line['4']?$o_line['4']:'0')
                    . ",'" . $this->_encodeUTF8($o_line['5'])
                    . "','" . $this->_encodeUTF8($o_line['6'])
                    . "','" . $this->_encodeUTF8($o_line['7'])
                    . "','" . $this->_encodeUTF8($o_line['8'])
                    . "','" . $this->_encodeUTF8($o_line['9'])
                    . "','" . $this->_encodeUTF8($o_line['10'])
                    . "','" . $this->_encodeUTF8($o_line['11'])
                    . "'," . $o_line['12']
                    . "," . $o_line['13']
                    . "," . $o_line['14']
                    . "," . $o_line['15']
                    . "," . $o_line['16']
                    . "," . $o_line['17']
                    . "," . $o_line['18']
                    . "," . $o_line['19']
                    . "," . $o_line['20']
                    . "," . $o_line['21']
                    . "," . $o_line['22']
                    . "," . $o_line['23']
                    . "," . $o_line['24']
                    . "," . $o_line['25']
                    . "," . $o_line['26']
                    . "," . $o_line['27']
                    . "," . $o_line['28']
                    . "," . $o_line['29']
                    . "," . $o_line['30']
                    . "," . $o_line['31']
                    . "," . $o_line['32']
                    . "," . $o_line['33']
                    . "," . $o_line['34']
                    . "," . $o_line['35']
                    . "," . $o_line['36']
                    . "," . $o_line['37']
                    . "," . $o_line['38'] . ")";
            $this->db->query($s_sql);
        }
        return $this->db->trans_complete();
    }

    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doAppendSQL($s_table_name) {
        $this->db->trans_start();
        //备份数据表
        $this->_backupTable($this->__table_name);
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name (bie_date,bie_shop_name,"
                . "bie_shop_id_ele,bie_shop_id,bie_order_sn,bie_order_code,"
                . "bie_order_code_ele,bie_biz_type,bie_delivery_type,"
                . "bie_order_type,bie_order_from,bie_order_create_dt,"
                . "bie_balance_amount,bie_cus_pay,bie_ss_amount,bie_ss_packet,"
                . "bie_ss_promotion,bie_ss_d_ticket,bie_ss_d_promotion,"
                . "bie_ss_ticket,bie_ss_gift,bie_ss_ticket_p,bie_shop_rate,"
                . "bie_agent_rate,bie_send_ratio,bie_send_min,bie_send_fee,"
                . "bie_commission,bie_products,bie_sp_amount,bie_sp_packet,"
                . "bie_sp_promotion,bie_sp_ticket,bie_sp_d_ticket,bie_sp_d_promotion,"
                . "bie_sp_gift,bie_baidu_rate,bie_cold_box_fee,bie_delivery_party_fee) "
                . "SELECT bie_date,bie_shop_name,"
                . "bie_shop_id_ele,bie_shop_id,bie_order_sn,bie_order_code,"
                . "bie_order_code_ele,bie_biz_type,bie_delivery_type,"
                . "bie_order_type,bie_order_from,bie_order_create_dt,"
                . "bie_balance_amount,bie_cus_pay,bie_ss_amount,bie_ss_packet,"
                . "bie_ss_promotion,bie_ss_d_ticket,bie_ss_d_promotion,"
                . "bie_ss_ticket,bie_ss_gift,bie_ss_ticket_p,bie_shop_rate,"
                . "bie_agent_rate,bie_send_ratio,bie_send_min,bie_send_fee,"
                . "bie_commission,bie_products,bie_sp_amount,bie_sp_packet,"
                . "bie_sp_promotion,bie_sp_ticket,bie_sp_d_ticket,bie_sp_d_promotion,"
                . "bie_sp_gift,bie_baidu_rate,bie_cold_box_fee,bie_delivery_party_fee "
                . "FROM temp_$s_table_name WHERE bie_order_code NOT IN "
                . "(SELECT bie_order_code FROM $this->__table_name) ";      //追加不存在的订单号
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

    var $fields = array(
        'bie_date' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ), 'bie_shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_shop_id_ele' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_shop_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_order_sn' => array(
            'type' => 'INT',
            'constraint' => '11',
            'null' => TRUE,
        ), 'bie_order_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_order_code_ele' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_biz_type' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_delivery_type' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_order_type' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_order_from' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bie_order_create_dt' => array(
            'type' => 'DATETIME',
            'null' => TRUE,
        ), 'bie_balance_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_cus_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_packet' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_d_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_d_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_gift' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_ss_ticket_p' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_shop_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_agent_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_send_ratio' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_send_min' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_send_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_commission' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_products' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_packet' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_d_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_d_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_sp_gift' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_baidu_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_cold_box_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bie_delivery_party_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ),
    );

}
