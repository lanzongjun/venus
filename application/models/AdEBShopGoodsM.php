<?php
/**
 * Description of AdEBShopGoodsM
 *
 * @author Vincent
 */
class AdEBShopGoodsM extends CI_Model {

    var $__table_name = 'shop_goods_eb';
    var $__out_file_root = '/output_esg_csv/';
    var $__out_file_ng_root = 'output_ng_csv';


    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
        $s_date = date("Ymd", time());
        $s_out_file_root = "$this->__out_file_root$s_date/";
        $s_out_ng_root = $s_out_file_root . $this->__out_file_ng_root . '/';
        if (!file_exists(".$s_out_file_root")) {
            mkdir(".$s_out_file_root");
        }
        if (!file_exists(".$s_out_ng_root")) {
            mkdir(".$s_out_ng_root");
        }
        $this->__out_file_root = $s_out_file_root;
        $this->__out_file_ng_root = $s_out_ng_root;
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
     * 删除表
     * @param type $s_table_name    表名
     */
    function _dropTempTable($s_table_name) {
        $this->dbforge->drop_table("temp_$s_table_name");
    }

    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up);
        $s_sql = "SELECT *,bs_shop_name sge_shop_name "
                . "FROM $this->__table_name LEFT JOIN base_shop_info ON sge_bs_e_id=bs_e_id "
                . "$s_where ORDER BY sge_bs_e_id ASC,sge_count_new DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($s_org_id, $s_goods_name, $s_barcode, $s_filter_storage, $s_filter_up) {
        $s_where = "WHERE 1=1 ";
        if ($s_org_id != '') {
            $s_where .= "AND sge_bs_e_id = $s_org_id ";
        }
        if ($s_goods_name != '') {
            $s_where .= "AND sge_gname LIKE '%$s_goods_name%' ";
        }
        if ($s_barcode != '') {
            $b_pos = strpos($s_barcode, ',');
            if ($b_pos) { 
                $s_barcode = str_replace(",", "','", $s_barcode);
                $s_where .= "AND sge_barcode IN ('$s_barcode') ";
            } else {
                $s_where .= "AND sge_barcode LIKE '%$s_barcode%' ";
            }
        }
        if ($s_filter_storage == 'NON_ZERO'){
            $s_where .= "AND sge_count > 0 ";
        }else if ($s_filter_storage == 'DIFF'){
            $s_where .= "AND sge_count <> sge_count_new ";
        }
        if ($s_filter_up == 'UP'){
            $s_where .= "AND sge_online='1' ";
        }else if ($s_filter_up == 'DOWN'){
            $s_where .= "AND sge_online='0' ";            
        }
        return $s_where;
    }

    function _getTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    function outputNewGoods($s_eb_id) {
        $s_sql = "SELECT bssy_org_name,bgs_name,bgs_barcode,bssy_count,"
                . "bbp_yj_sale_price,bbp_settlement_price FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_goods_yj ON bssy_barcode=bgs_barcode "
                . "LEFT JOIN base_balance_price ON bssy_barcode=bbp_bar_code "
                . "WHERE bs_e_id='$s_eb_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sge_barcode FROM shop_goods_eb "
                . "WHERE sge_bs_e_id='$s_eb_id')";
        $o_result = $this->db->query($s_sql);

        $s_filename = "$s_eb_id.csv";
        $s_csv_path = ".$this->__out_file_ng_root$s_filename";
        $o_res = array();
        $o_res['filename'] = $s_filename;
        $o_res['filepath'] = $s_csv_path;
        if (file_exists(".$this->__out_file_ng_root$s_filename")) {
            unlink(".$this->__out_file_ng_root$s_filename");
        }
        $s_result = $this->dbutil->csv_from_result($o_result);
        $this->load->helper('file');
        write_file($s_csv_path, $s_result);
        return $o_res;
    }
    
    function updateNewGoods($s_eb_id) {
        $s_update_dt = date("Y-m-d H:i:s", time());
        $this->db->trans_start();
        $s_sql_del = "DELETE FROM shop_goods_todo_eb WHERE sgte_bs_e_id=$s_eb_id ";
        $this->db->query($s_sql_del);
        $s_sql_add = "INSERT INTO shop_goods_todo_eb(sgte_bs_e_id,sgte_org_code,"
                . "sgte_shop_sn,sgte_barcode,sgte_gname,sgte_count,sgte_sale_price,"
                . "sgte_settlement_price,sgte_update_dt) "
                . "SELECT '$s_eb_id',bssy_org_code,bs_shop_sn,bssy_barcode,"
                . "bssy_goods_name,bssy_count,bbp_yj_sale_price,"
                . "bbp_settlement_price,'$s_update_dt' FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_balance_price ON bbp_bar_code=bssy_barcode "
                . "WHERE bs_e_id='$s_eb_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sge_barcode FROM shop_goods_eb "
                . "WHERE sge_bs_e_id='$s_eb_id') ";
        $this->db->query($s_sql_add);
        return $this->db->trans_complete();
    }
    
    function getNewGoods($s_eb_id) {
        $s_sql = "SELECT bssy_org_name,bgs_name,bgs_barcode,bssy_count,"
                . "bbp_yj_sale_price,bbp_settlement_price FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_goods_yj ON bssy_barcode=bgs_barcode "
                . "LEFT JOIN base_balance_price ON bssy_barcode=bbp_bar_code "
                . "WHERE bs_e_id='$s_eb_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sge_barcode FROM shop_goods_eb "
                . "WHERE sge_bs_e_id='$s_eb_id')";
        $o_result = $this->db->query($s_sql);        
        return $o_result->result();
    }
        
    /**
     * 获得店铺商品
     * @param type $s_shop_id
     * @return type
     */
    function getShopGoods($s_shop_id) {
        $s_sql = "SELECT sge_gid gid,sge_gname gname FROM $this->__table_name "
                . "WHERE sge_bs_e_id='$s_shop_id' ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
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
     * 获得文件列表
     * @param type $dir
     * @return string
     */
    function getFile($dir) {
        $fileArray[] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".." && strpos($file, ".")) {
                    $fileArray[$i] = $file;
                    if ($i == 100) {
                        break;
                    }
                    $i++;
                }
            }
            //关闭句柄
            closedir($handle);
        }
        return $fileArray;
    }

    function getOPFList() {
        $a_result = array();
        $a_file = $this->getFile(".$this->__out_file_root");
        foreach ($a_file as $s_file) {
            $o_line = array('opf_filename' => '', 'opf_url' => '');
            $o_line['opf_filename'] = $s_file;
            $o_line['opf_url'] = $this->__out_file_root . $s_file;
            if ($s_file) {
                array_push($a_result, $o_line);
            }
        }
        return $a_result;
    }

    function outPfCSV($s_e_id = '', $s_shop_name = '') {
        $a_result = array();
        if ($s_e_id != '') {
            $o_res = $this->_outPfShopCSV($s_e_id, $s_shop_name);
            array_push($a_result, $o_res);
            return $a_result;
        }
        $s_sql = "SELECT bs_e_id,bs_shop_sn FROM base_shop_info WHERE bs_e_id IN "
                . "(SELECT DISTINCT(sge_bs_e_id) FROM shop_goods_eb) ";
        $o_query = $this->db->query($s_sql);
        foreach ($o_query->result() as $o_line) {
            $o_res = $this->_outPfShopCSV($o_line->bs_e_id, "易捷( $o_line->bs_shop_sn 站)");
            array_push($a_result, $o_res);
        }
        return $a_result;
    }

    /**
     * 导出平台库存更新CSV
     * @param type $s_e_id
     * @param type $s_shop_name
     * @return type
     */
    function _outPfShopCSV($s_e_id, $s_shop_name) {
        $o_res = array('filename' => '', 'filepath' => '');
        $s_sql = "SELECT concat('',sge_barcode) `商品条形码（匹配）`,'' `商品ID`,"
                . "'' `商品自定义ID`,'' `商品名称`,concat('',sge_barcode) `商品条形码（选填）`,"
                . "'' `商品三级类目`,'' `重量`,'' `售价`,sge_count `商品库存（选填）` "
                . "FROM v_shop_goods_eb_unfreeze WHERE sge_bs_e_id='$s_e_id' ORDER BY sge_barcode ";

        $o_result = $this->db->query($s_sql);

        $s_result = $this->dbutil->csv_from_result($o_result);

        $this->load->helper('file');
        $s_datetime = date("ymd_His", time());
        $s_filename = "$s_datetime.$s_shop_name.csv";
        if (!file_exists(".$this->__out_file_root")) {
            mkdir(".$this->__out_file_root");
        }
        $s_csv_path = ".$this->__out_file_root$s_filename";
        write_file($s_csv_path, $s_result);
        $o_res['filename'] = $s_filename;
        $o_res['filepath'] = $s_csv_path;
        return $o_res;
    }

    /**
     * 更新库存
     * @param type $s_shop_id
     * @return string
     */
    function updateStorage($s_shop_id = '') {
        log_message('debug', "饿百店铺库存管理-更新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sge_bs_e_id='$s_shop_id' ";
        $s_sql = "UPDATE v_shop_goods_eb_unfreeze SET sge_count=sge_count_new $s_where";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $e) {
            log_message('error', '[饿百店铺库存管理-更相信库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[饿百店铺库存管理-刷新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "饿百店铺库存管理 <br>更新库存-完成<br>受影响记录数:$i_rows";
        return $o_result;
    }

    /**
     * 刷新库存
     * @param type $s_shop_id
     * @return string
     */
    function refreshStorage($s_shop_id = '') {
        log_message('debug', "饿百店铺库存管理-刷新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sge_bs_e_id='$s_shop_id' ";
        $s_sql_update0 = "UPDATE $this->__table_name SET sge_count_new=0 $s_where";
        log_message('debug', "SQL文:$s_sql_update0");
        try {
            $this->db->query($s_sql_update0);
        } catch (Exception $e) {
            log_message('error', '[饿百店铺库存管理-刷新库存-重置库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[饿百店铺库存管理-刷新库存-重置库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $s_sql_update = "UPDATE $this->__table_name INNER JOIN base_shop_storage_yj "
                . "ON bssy_org_code = sge_bs_org_sn AND bssy_barcode = sge_barcode "
                . "SET sge_count_new = bssy_count $s_where";
        log_message('debug', "SQL文:$s_sql_update");
        try {
            $this->db->query($s_sql_update);
        } catch (Exception $e) {
            log_message('error', '[饿百店铺库存管理-刷新库存-更新库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[饿百店铺库存管理-刷新库存-更新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "饿百店铺库存管理 <br>刷新库存-完成<br>受影响记录数:$i_rows";
        return $o_result;
    }

    /**
     * 导入CSV
     * @param type $o_result
     */
    function inputCSV($s_table_name, $o_result, $s_e_id) {
        set_time_limit(0);
        $this->_createTempTable($s_table_name);
        //$i_field_count = count($o_result->fields);
        $a_lines = $o_result->datas;

        $this->db->trans_start();
        for ($i = 0; $i < count($a_lines); $i++) {
            $o_line = $a_lines[$i];
            if ($o_line['0'] == '') {
                continue;
            }
            $s_sql = "INSERT INTO temp_$s_table_name (sge_gid,sge_cid,sge_barcode,"
                    . "sge_gname,sge_shelves,sge_band,sge_fclass1,sge_fclass2,"
                    . "sge_bclass1,sge_bclass2,sge_bclass3,sge_propety,sge_price,"
                    . "sge_count,sge_online,sge_limit,sge_type,sge_weight,sge_bs_e_id) VALUES " .
                    "('" . $this->_encodeUTF8($o_line['0']) .
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
                    "'," . $this->_encodeUTF8($o_line['12']) .
                    "," . $this->_encodeUTF8($o_line['13']) .
                    ",'" . $this->_encodeUTF8($o_line['14']) .
                    "','" . $this->_encodeUTF8($o_line['15']) .
                    "','" . $this->_encodeUTF8($o_line['16']) .
                    "'," . $this->_encodeUTF8($o_line['17']) .
                    ",'" . $s_e_id . "')";
            $this->db->query($s_sql);
        }
        return $this->db->trans_complete();
    }

    /**
     * 追加数据
     * @param type $s_table_name
     * @return type 受影响行数
     */
    function doCoverSQL($s_table_name) {
        $this->db->trans_start();
        //备份数据表
        $this->_backupTable($this->__table_name);
        $s_sql = "DELETE FROM $this->__table_name WHERE sge_bs_e_id = ("
                . "SELECT MAX(sge_bs_e_id) FROM temp_$s_table_name )";
        $this->db->query($s_sql);
        //执行覆盖
        $s_sql = "INSERT INTO $this->__table_name (sge_gid,sge_cid,sge_barcode,"
                . "sge_gname,sge_shelves,sge_band,sge_fclass1,sge_fclass2,"
                . "sge_bclass1,sge_bclass2,sge_bclass3,sge_propety,sge_price,"
                . "sge_count,sge_online,sge_limit,sge_type,sge_weight,"
                . "sge_bs_e_id,sge_bs_org_sn,sge_bs_sale_sn) "
                . "SELECT sge_gid,sge_cid,sge_barcode,sge_gname,sge_shelves,"
                . "sge_band,sge_fclass1,sge_fclass2,sge_bclass1,sge_bclass2,"
                . "sge_bclass3,sge_propety,sge_price,sge_count,sge_online,"
                . "sge_limit,sge_type,sge_weight,sge_bs_e_id,bs_org_sn,"
                . "bs_sale_sn FROM temp_$s_table_name "
                . "LEFT JOIN base_shop_info ON sge_bs_e_id = bs_e_id ";
        $this->db->query($s_sql);
        //删除临时表
        $this->_dropTempTable($s_table_name);
        return $this->db->trans_complete();
    }

    var $fields = array(
        'sge_gid' => array(
            'type' => 'VARCHAR',
            'constraint' => '25',
            'null' => TRUE,
        ), 'sge_cid' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => TRUE,
        ), 'sge_barcode' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => TRUE,
        ), 'sge_gname' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_shelves' => array(
            'type' => 'VARCHAR',
            'constraint' => '50',
            'null' => TRUE,
        ), 'sge_band' => array(
            'type' => 'VARCHAR',
            'constraint' => '50',
            'null' => TRUE,
        ), 'sge_fclass1' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_fclass2' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_bclass1' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_bclass2' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_bclass3' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_propety' => array(
            'type' => 'VARCHAR',
            'constraint' => '100',
            'null' => TRUE,
        ), 'sge_price' => array(
            'type' => 'decimal',
            'constraint' => [10, 2],
            'null' => TRUE,
        ), 'sge_count' => array(
            'type' => 'int',
            'constraint' => '11',
            'null' => TRUE,
        ), 'sge_online' => array(
            'type' => 'VARCHAR',
            'constraint' => '10',
            'null' => TRUE,
        ), 'sge_limit' => array(
            'type' => 'VARCHAR',
            'constraint' => '10',
            'null' => TRUE,
        ), 'sge_type' => array(
            'type' => 'VARCHAR',
            'constraint' => '10',
            'null' => TRUE,
        ), 'sge_weight' => array(
            'type' => 'int',
            'constraint' => '11',
            'null' => TRUE,
        ), 'sge_bs_e_id' => array(
            'type' => 'VARCHAR',
            'constraint' => '20',
            'null' => TRUE,
        ),
    );

}
