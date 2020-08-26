<?php

/**
 * 饿百订单信息
 *
 * @author Vincent
 */
class AdEBOrderInfoM extends CI_Model {
    
    var $_tbn_order_info = 'order';
    var $_tbn_order_discount = 'order_discount';
    var $_tbn_order_ext = 'order_ext';
    var $_tbn_order_product = 'order_product';
    var $_tbn_order_shop = 'order_shop';
    var $_tbn_order_user = 'order_user';
    
    var $__ENUM_STATE_TODO = 1;//待确认
    var $__ENUM_STATE_CONFIRM = 5;//已确认
    var $__ENUM_STATE_RIDER_RECEIVE = 7;//骑士已接单
    var $__ENUM_STATE_RIDER_TAKE = 8;//骑士已取餐
    var $__ENUM_STATE_COMPLETE = 9;//已完成
    var $__ENUM_STATE_CANCEL = 10;//已取消
    var $__ENUM_STATE_REFUND = 15;//订单退款
    var $__ENUM_STATE_UNKNOW = -1;//未知

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('ele_if',true);        
        $this->_tbn_order_info = $this->db->dbprefix($this->_tbn_order_info);
        $this->_tbn_order_discount = $this->db->dbprefix($this->_tbn_order_discount);
        $this->_tbn_order_ext = $this->db->dbprefix($this->_tbn_order_ext);
        $this->_tbn_order_product = $this->db->dbprefix($this->_tbn_order_product);
        $this->_tbn_order_shop = $this->db->dbprefix($this->_tbn_order_shop);
        $this->_tbn_order_user = $this->db->dbprefix($this->_tbn_order_user);        
    }
    
    /**
     * 根据编码返回对应订单状态
     * @param type $enum_state
     * @return string
     */
    function getTextByStateNum($enum_state) {
        if ($enum_state == $this->__ENUM_STATE_TODO) {
            return '待确认';
        }
        if ($enum_state == $this->__ENUM_STATE_CONFIRM) {
            return '已确认';
        }
        if ($enum_state == $this->__ENUM_STATE_RIDER_RECEIVE) {
            return '骑士已接单';
        }
        if ($enum_state == $this->__ENUM_STATE_RIDER_TAKE) {
            return '骑士已取餐';
        }
        if ($enum_state == $this->__ENUM_STATE_COMPLETE) {
            return '已完成';
        }
        if ($enum_state == $this->__ENUM_STATE_CANCEL) {
            return '已取消';
        }
        if ($enum_state == $this->__ENUM_STATE_REFUND) {
            return '订单退款';
        }
        if ($enum_state == $this->__ENUM_STATE_UNKNOW) {
            return '未知';
        }
        return '未知';
    }
    
    function getRefundList(){
        $s_sql = "SELECT R.order_id,R.notify_type,I.wm_order_id_view,I.wm_poi_name,"
                . "R.refund_type,R.reason,R.money,R.res_type,R.is_appeal "
                . "FROM $this->_tbn_order_refund R LEFT JOIN $this->_tbn_order_info I "
                . "ON R.order_id=I.order_id ORDER BY R.update_datetime DESC, R.res_type ASC";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getRefundDetail($l_order_id){
        $s_sql = "SELECT order_id,food_name,upc,count,food_price,origin_food_price,refund_price "
                . "FROM $this->_tbn_order_refund_detail WHERE order_id=$l_order_id ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getToDoList() {
        $s_sql = "SELECT oi.order_id,oi.confirm_type,os.`name` shop_name,oi.user_fee,"
                . "oi.total_fee,oi.shop_fee,ou.`name` user_name,ou.privacy_phone,"
                . "ou.address,oi.delivery_phone,oi.`status`,oi.create_time,oi.remark,"
                . "oi.create_time udiff FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON os.order_id = oi.order_id "
                . "LEFT JOIN $this->_tbn_order_user ou ON ou.order_id = oi.order_id "
                . "WHERE oi.`status`=1 ORDER BY oi.create_time DESC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT oi.order_id,oi.confirm_type,os.`name` shop_name,oi.user_fee,"
                . "oi.total_fee,oi.shop_fee,ou.`name` user_name,ou.privacy_phone,"
                . "ou.address,oi.delivery_phone,oi.`status`,oi.create_time,oi.remark "
                . "FROM $this->_tbn_order_info oi "
                . "LEFT JOIN $this->_tbn_order_shop os ON os.order_id = oi.order_id "
                . "LEFT JOIN $this->_tbn_order_user ou ON ou.order_id = oi.order_id "
                . $s_where 
                . " ORDER BY oi.create_time DESC LIMIT $i_start,$i_rows";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }

    function getWhere($a_get){
        $s_where = "WHERE oi.`status`<>1 AND oi.`status`<>2 ";
        if (isset($a_get['s_db']) && $a_get['s_db']){
            $s_where .= " AND oi.create_time >= '".$a_get['s_db']."'";
        }
        if (isset($a_get['s_de']) && $a_get['s_de']){
            $s_where .= " AND oi.create_time <= '".$a_get['s_de']."'";
        }
        if (isset($a_get['s_sid']) && $a_get['s_sid']) {
            $s_where .= " AND os.shop_id='".$a_get['s_sid']."'";
        }
        return $s_where;
    }
    
    /**
     * 获得数据总数
     * @param type $s_where
     * @return type
     */
    function _getTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->_tbn_order_info oi $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }
    
    /**
     * 获得详细信息
     * @return type
     */
    function getDetail($s_order_id) {
        $s_sql = "SELECT product_name,upc,product_price,total_fee,discount,"
                . "product_amount,baidu_rate,shop_rate FROM $this->_tbn_order_product "
                . "WHERE order_id=$s_order_id";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getOrderToDo(){
        $a_unconfirm = $this->getUnconfirmOrder();
        $a_unrefund = $this->getUnrefundOrder();
        $i_result = 0;
        if (count($a_unconfirm) > 0){
            $i_result = 1;
        }
        if (count($a_unrefund) > 0){
            $i_result = 2;
        }
        if (count($a_unconfirm) > 0 && count($a_unrefund) > 0){
            $i_result = 3;
        }
        return $i_result;
    }
    
    function getUnconfirmOrder(){
        $s_sql = "SELECT order_id FROM $this->_tbn_order_info WHERE `status`=1";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getUnrefundOrder(){
        $s_sql = "SELECT refund_id,order_id FROM $this->_tbn_order_refund WHERE res_type=0";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
}
