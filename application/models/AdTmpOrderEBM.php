<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdTmpOrderEBM
 *
 * @author Vincent
 */
class AdTmpOrderEBM extends CI_Model {

    var $__table_name = 'tmp_order_info_eb';
    var $fields = array(
        'id' => array(
            'type' => 'INT',
            'auto_increment' => TRUE
        ), 'sn' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'tcode' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'tfrom' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'e_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'city' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'shop_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'shop_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'deliver' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'order_state' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'refund_state' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'order_invalid' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'order_create' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'arrive_time' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'shop_receive' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'order_finish' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'order_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'cus_pay' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'shop_receive_amount' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'commission' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'claim_settlement' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'goods_price' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'goods_name' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'sku_code' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'goods_cusid' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'onsale_before' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'onsale_after' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'buy_count' => array(
            'type' => 'int',
            'null' => TRUE,
        ), 'memo' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'deliver_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'deliver_free' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'package_fee' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_shop_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'is_present' => array(
            'type' => 'VARCHAR',
            'constraint' => '200',
            'null' => TRUE,
        ), 'subsidy_shop_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_shop_packet' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_shop_deliver' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_shop_gift' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_platform_promotion' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_platform_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_platform_packet' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_platform_deliver' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_platform_gift' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'subsidy_cus_ticket' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        )
    );

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
        $s_str = str_replace('"',"",$s_str);
        $s_str = str_replace("'","",$s_str);
        $s_str = trim($s_str);
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
        $o_result = $this->db->query("SELECT * FROM $this->__table_name ORDER BY order_create DESC LIMIT $i_start,$i_rows");
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
        return $a_line[0]->t_num-0;
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
        //$i_field_count = count($o_result->fields);
        $a_lines = $o_result->datas;
        $this->db->trans_start();
        $o_line_pre = null;
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            $s_sql = "INSERT INTO temp_$s_table_name (sn,tcode,tfrom,e_code,city,shop_name,shop_id,deliver,order_state,"
                    . "refund_state,order_invalid,order_create,arrive_time,shop_receive,order_finish,order_amount,"
                    . "cus_pay,shop_receive_amount,commission,claim_settlement,goods_price,goods_name,sku_code,"
                    . "goods_cusid,onsale_before,onsale_after,buy_count,memo,deliver_fee,deliver_free,package_fee,"
                    . "subsidy_shop_promotion,is_present,subsidy_shop_ticket,subsidy_shop_packet,subsidy_shop_deliver,"
                    . "subsidy_shop_gift,subsidy_platform_promotion,subsidy_platform_ticket,subsidy_platform_packet,"
                    . "subsidy_platform_deliver,subsidy_platform_gift,subsidy_cus_ticket) VALUES ";
            if ($o_line_pre != null && ($o_line['0'] == '' || $o_line['3'] == '')) {
                $s_sql .= "('" . $this->_encodeUTF8($o_line_pre['0']) .
                        "','" . $this->_encodeUTF8($o_line_pre['1']) .
                        "','" . $this->_encodeUTF8($o_line_pre['2']) .
                        "','" . $this->_encodeUTF8($o_line_pre['3']) .
                        "','" . $this->_encodeUTF8($o_line_pre['4']) .
                        "','" . $this->_encodeUTF8($o_line_pre['5']) .
                        "','" . $this->_encodeUTF8($o_line_pre['6']) .
                        "','" . $this->_encodeUTF8($o_line_pre['7']) .
                        "','" . $this->_encodeUTF8($o_line_pre['8']) .
                        "','" . $this->_encodeUTF8($o_line_pre['9']) .
                        "','" . $this->_encodeUTF8($o_line_pre['10']) .
                        "','" . $this->_encodeUTF8($o_line_pre['11']) .
                        "','" . $this->_encodeUTF8($o_line_pre['12']) .
                        "','" . $this->_encodeUTF8($o_line_pre['13']) .
                        "','" . $this->_encodeUTF8($o_line_pre['14']) .
                        "'," . $o_line_pre['15'] .
                        "," . $o_line_pre['16'] .
                        "," . $o_line_pre['17'] .
                        "," . $o_line_pre['18'] .
                        ",'" . $this->_encodeUTF8($o_line_pre['19']) .
                        "'," . $o_line_pre['20'] .
                        ",'" . $this->_encodeUTF8($o_line['21']) .
                        "','" . $this->_encodeUTF8($o_line['22']) .
                        "','" . $this->_encodeUTF8($o_line['23']) .
                        "'," . $o_line['24'] .
                        "," . $o_line['25'] .
                        "," . $o_line['26'] .
                        ",'" . $this->_encodeUTF8($o_line['27']) .
                        "'," . $o_line_pre['28'] .
                        ",'" . $this->_encodeUTF8($o_line_pre['29']) .
                        "'," . $o_line_pre['30'] .
                        "," . $o_line_pre['31'] .
                        ",'" . $this->_encodeUTF8($o_line_pre['32']) .
                        "'," . $o_line_pre['33'] .
                        "," . $o_line_pre['34'] .
                        "," . $o_line_pre['35'] .
                        "," . $o_line_pre['36'] .
                        "," . $o_line_pre['37'] .
                        "," . $o_line_pre['38'] .
                        "," . $o_line_pre['39'] .
                        "," . $o_line_pre['40'] .
                        "," . $o_line_pre['41'] .
                        "," . $o_line_pre['42'] .")";
            } else {
                $s_sql .= "('" . $this->_encodeUTF8($o_line['0']) .
                        "','" . $this->_encodeUTF8($o_line['1']) .
                        "','" . $this->_encodeUTF8($o_line['2']) .
                        "','" . $this->_encodeUTF8($o_line['3']) .
                        "','" . $this->_encodeUTF8($o_line['4']) .
                        "','" . $this->_encodeUTF8($o_line['5']) .
                        "','" . $this->_encodeUTF8($o_line['6']) .
                        "','" . $this->_encodeUTF8($o_line['7']) .
                        "','" . $this->_encodeUTF8($o_line['8']) .
                        "','" . $this->_encodeUTF8($o_line['9']) .
                        "','" . $this->_encodeUTF8($o_line['10']) .
                        "','" . $this->_encodeUTF8($o_line['11']) .
                        "','" . $this->_encodeUTF8($o_line['12']) .
                        "','" . $this->_encodeUTF8($o_line['13']) .
                        "','" . $this->_encodeUTF8($o_line['14']) .
                        "'," . $o_line['15'] .
                        "," . $o_line['16'] .
                        "," . $o_line['17'] .
                        "," . $o_line['18'] .
                        ",'" . $this->_encodeUTF8($o_line['19']) .
                        "'," . $o_line['20'] .
                        ",'" . $this->_encodeUTF8($o_line['21']) .
                        "','" . $this->_encodeUTF8($o_line['22']) .
                        "','" . $this->_encodeUTF8($o_line['23']) .
                        "'," . $o_line['24'] .
                        "," . $o_line['25'] .
                        "," . $o_line['26'] .
                        ",'" . $this->_encodeUTF8($o_line['27']) .
                        "'," . $o_line['28'] .
                        ",'" . $this->_encodeUTF8($o_line['29']) .
                        "'," . $o_line['30'] .
                        "," . $o_line['31'] .
                        ",'" . $this->_encodeUTF8($o_line['32']) .
                        "'," . $o_line['33'] .
                        "," . $o_line['34'] .
                        "," . $o_line['35'] .
                        "," . $o_line['36'] .
                        "," . $o_line['37'] .
                        "," . $o_line['38'] .
                        "," . $o_line['39'] .
                        "," . $o_line['40'] .
                        "," . $o_line['41'] .
                        "," . $o_line['42'] .")";
                $o_line_pre = $o_line;
            }            
            log_message('info', "SQL文: $s_sql");
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
//        $s_sql = "DELETE FROM $this->__table_name";
//        $this->db->query($s_sql);
        //执行追加
        $s_sql = "INSERT INTO $this->__table_name (sn,tcode,tfrom,e_code,city,shop_name,shop_id,deliver,order_state,"
                . "refund_state,order_invalid,order_create,arrive_time,shop_receive,order_finish,order_amount,"
                . "cus_pay,shop_receive_amount,commission,claim_settlement,goods_price,goods_name,sku_code,"
                . "goods_cusid,onsale_before,onsale_after,buy_count,memo,deliver_fee,deliver_free,package_fee,"
                . "subsidy_shop_promotion,is_present,subsidy_shop_ticket,subsidy_shop_packet,subsidy_shop_deliver,"
                . "subsidy_shop_gift,subsidy_platform_promotion,subsidy_platform_ticket,subsidy_platform_packet,"
                . "subsidy_platform_deliver,subsidy_platform_gift,subsidy_cus_ticket) "
                . "SELECT sn,tcode,tfrom,e_code,city,shop_name,shop_id,deliver,order_state,"
                . "refund_state,order_invalid,order_create,arrive_time,shop_receive,order_finish,order_amount,"
                . "cus_pay,shop_receive_amount,commission,claim_settlement,goods_price,goods_name,sku_code,"
                . "goods_cusid,onsale_before,onsale_after,buy_count,memo,deliver_fee,deliver_free,package_fee,"
                . "subsidy_shop_promotion,is_present,subsidy_shop_ticket,subsidy_shop_packet,subsidy_shop_deliver,"
                . "subsidy_shop_gift,subsidy_platform_promotion,subsidy_platform_ticket,subsidy_platform_packet,"
                . "subsidy_platform_deliver,subsidy_platform_gift,subsidy_cus_ticket "
                . "FROM temp_$s_table_name WHERE tcode NOT IN (SELECT DISTINCT(eoi_code) FROM order_info_eb)";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

}
