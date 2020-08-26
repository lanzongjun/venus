<?php
/**
 * Description of AdMTShopGoodsM
 *
 * @author Vincent
 */
class AdMTShopGoodsM extends CI_Model {

    var $__table_name = 'shop_goods_mt';


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
        $s_sql = "SELECT *,bs_shop_name sgm_shop_name "
                . "FROM $this->__table_name LEFT JOIN base_shop_info ON sgm_bs_m_id=bs_m_id "
                . "$s_where ORDER BY sgm_bs_m_id ASC,sgm_count_new DESC LIMIT $i_start,$i_rows";
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
            $s_where .= "AND sgm_bs_m_id = $s_org_id ";
        }
        if ($s_goods_name != '') {
            $s_where .= "AND sgm_gname LIKE '%$s_goods_name%' ";
        }
        if ($s_barcode != '') {
            $b_pos = strpos($s_barcode, ',');
            if ($b_pos) { 
                $s_barcode = str_replace(",", "','", $s_barcode);
                $s_where .= "AND sgm_barcode IN ('$s_barcode') ";
            } else {
                $s_where .= "AND sgm_barcode LIKE '%$s_barcode%' ";
            }
        }
        if ($s_filter_storage == 'NON_ZERO'){
            $s_where .= "AND sgm_count > 0 ";
        }else if ($s_filter_storage == 'DIFF'){
            $s_where .= "AND sgm_count <> sgm_count_new ";
        }
        if ($s_filter_up == '1'){
            $s_where .= "AND sgm_online=1 ";
        }else if ($s_filter_up == '0'){
            $s_where .= "AND sgm_online=0 ";            
        }
        return $s_where;
    }

    function _getTotal($s_where = '') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num - 0;
    }

    function outputNewGoods($s_mt_id) {
        $s_sql = "SELECT bssy_org_name,bgs_name,bgs_barcode,bssy_count,"
                . "bbp_yj_sale_price,bbp_settlement_price FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_goods_yj ON bssy_barcode=bgs_barcode "
                . "LEFT JOIN base_balance_price ON bssy_barcode=bbp_bar_code "
                . "WHERE bs_e_id='$s_mt_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sgm_barcode FROM shop_goods_mt "
                . "WHERE sgm_bs_m_id='$s_mt_id')";
        $o_result = $this->db->query($s_sql);

        $s_filename = "$s_mt_id.csv";
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
    
    function updateNewGoods($s_mt_id) {
        $s_update_dt = date("Y-m-d H:i:s", time());
        $this->db->trans_start();
        $s_sql_del = "DELETE FROM shop_goods_todo_mt WHERE sgte_bs_e_id=$s_mt_id ";
        $this->db->query($s_sql_del);
        $s_sql_add = "INSERT INTO shop_goods_todo_mt(sgte_bs_e_id,sgte_org_code,"
                . "sgte_shop_sn,sgte_barcode,sgte_gname,sgte_count,sgte_sale_price,"
                . "sgte_settlement_price,sgte_update_dt) "
                . "SELECT '$s_mt_id',bssy_org_code,bs_shop_sn,bssy_barcode,"
                . "bssy_goods_name,bssy_count,bbp_yj_sale_price,"
                . "bbp_settlement_price,'$s_update_dt' FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_balance_price ON bbp_bar_code=bssy_barcode "
                . "WHERE bs_e_id='$s_mt_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sgm_barcode FROM shop_goods_mt "
                . "WHERE sgm_bs_m_id='$s_mt_id') ";
        $this->db->query($s_sql_add);
        return $this->db->trans_complete();
    }
    
    function getNewGoods($s_mt_id) {
        $s_sql = "SELECT bssy_org_name,bgs_name,bgs_barcode,bssy_count,"
                . "bbp_yj_sale_price,bbp_settlement_price FROM base_shop_storage_yj "
                . "LEFT JOIN base_shop_info ON bs_org_sn = bssy_org_code "
                . "LEFT JOIN base_goods_yj ON bssy_barcode=bgs_barcode "
                . "LEFT JOIN base_balance_price ON bssy_barcode=bbp_bar_code "
                . "WHERE bs_e_id='$s_mt_id' AND bgs_sale_online=1 "
                . "AND bssy_barcode NOT IN (SELECT sgm_barcode FROM shop_goods_mt "
                . "WHERE sgm_bs_m_id='$s_mt_id')";
        $o_result = $this->db->query($s_sql);        
        return $o_result->result();
    }
        
    /**
     * 获得店铺商品
     * @param type $s_shop_id
     * @return type
     */
    function getShopGoods($s_shop_id) {
        $s_sql = "SELECT sgm_gid gid,sgm_gname gname FROM $this->__table_name "
                . "WHERE sgm_bs_m_id='$s_shop_id' ";
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

    /**
     * 更新库存
     * @param type $s_shop_id
     * @return string
     */
    function updateStorage($s_shop_id = '') {
        log_message('debug', "美团店铺库存管理-更新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sgm_bs_m_id='$s_shop_id' ";
        $s_sql = "UPDATE v_shop_goods_mt_unfreeze SET sgm_count=sgm_count_new $s_where";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-更相信库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "美团店铺库存管理 <br>更新库存-完成<br>受影响记录数:$i_rows";
        return $o_result;
    }

    /**
     * 刷新库存
     * @param type $s_shop_id
     * @return string
     */
    function refreshStorage($s_shop_id = '') {
        log_message('debug', "美团店铺库存管理-刷新库存");
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        $s_where = $s_shop_id == '' ? '' : "WHERE sgm_bs_m_id='$s_shop_id' ";
        $s_sql_update0 = "UPDATE $this->__table_name SET sgm_count_new=0 $s_where";
        log_message('debug', "SQL文:$s_sql_update0");
        try {
            $this->db->query($s_sql_update0);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存-重置库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存-重置库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $s_sql_update = "UPDATE $this->__table_name INNER JOIN base_shop_storage_yj "
                . "ON bssy_org_code = sgm_bs_org_sn AND bssy_barcode = sgm_barcode "
                . "SET sgm_count_new = bssy_count $s_where";
        log_message('debug', "SQL文:$s_sql_update");
        try {
            $this->db->query($s_sql_update);
        } catch (Exception $e) {
            log_message('error', '[美团店铺库存管理-刷新库存-更新库存]时发生错误！\r\n' . $e->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "[美团店铺库存管理-刷新库存-更新库存]时发生错误！\r\n" . $e->getMessage();
            return $o_result;
        }
        $i_rows = $this->db->affected_rows();
        log_message('debug', "受影响记录数:" . $i_rows);
        $o_result['state'] = true;
        $o_result['msg'] = "美团店铺库存管理 <br>刷新库存-完成<br>受影响记录数:$i_rows";
        return $o_result;
    }

}
