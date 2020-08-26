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
class AdCountBalEBM extends CI_Model {

    var $__table_name = 'count_balance_eb';

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
        $s_sql = "SELECT * FROM $this->__table_name ORDER BY bce_date DESC "
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
            $s_sql = "INSERT INTO temp_$s_table_name (bce_date,bce_shop_name,bce_shop_id,"
                    . "bce_e_shop_id,bce_orders,bce_amount,bce_user_fee,bce_shop_rate,"
                    . "bce_agent_rate,bce_send_fee,bce_commission,bce_products,bce_package_fee,"
                    . "bce_baidu_rate,bce_cold_box_fee,bce_delivery_party_fee,bce_tip) VALUES "
                    . "('" . $this->_encodeUTF8($o_line['0'])
                    . "','" . $this->_encodeUTF8($o_line['1'])
                    . "','" . $this->_encodeUTF8($o_line['2'])
                    . "','" . $this->_encodeUTF8($o_line['3'])
                    . "'," . $o_line['4']
                    . "," . $o_line['5']
                    . "," . $o_line['6']
                    . "," . $o_line['7']
                    . "," . $o_line['8']
                    . "," . $o_line['9']
                    . "," . $o_line['10']
                    . "," . $o_line['11']
                    . "," . $o_line['12']
                    . "," . $o_line['13']
                    . "," . $o_line['14']
                    . "," . $o_line['15']
                    . "," . $o_line['16'] . ")";
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
        $s_sql = "INSERT INTO $this->__table_name (bce_date,bce_shop_name,bce_shop_id,"
                . "bce_e_shop_id,bce_orders,bce_amount,bce_user_fee,bce_shop_rate,"
                . "bce_agent_rate,bce_send_fee,bce_commission,bce_products,bce_package_fee,"
                . "bce_baidu_rate,bce_cold_box_fee,bce_delivery_party_fee,bce_tip) "
                . "SELECT bce_date,bce_shop_name,bce_shop_id,"
                . "bce_e_shop_id,bce_orders,bce_amount,bce_user_fee,bce_shop_rate,"
                . "bce_agent_rate,bce_send_fee,bce_commission,bce_products,bce_package_fee,"
                . "bce_baidu_rate,bce_cold_box_fee,bce_delivery_party_fee,bce_tip "
                . "FROM temp_$s_table_name";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

    var $fields = array(
        'id' => array(
            'type' => 'INT',
            'auto_increment' => TRUE
        ), 'bce_date' => array(
            'type' => 'DATE',
            'null' => TRUE,
        ), 'bce_shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bce_shop_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bce_e_shop_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'bce_orders' => array(
            'type' => 'INT',
            'null' => TRUE,
        ), 'bce_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_user_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_shop_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_agent_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_send_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_commission' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_products' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_package_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_baidu_rate' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_cold_box_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_delivery_party_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'bce_tip' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ),
    );

}
