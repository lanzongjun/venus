<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 4:14 PM
 */

class Authdb
{
    public function __construct()
    {
        $this->MYCI = &get_instance();
        //$this->MYCI->db=$this->load->database();
        $this->MYCI->db=$this->MYCI->load->database('default', TRUE);
        $this->MYCI->load->model("OrdersModel");
        $this->MYCI->load->model("OrdersExtendModel");
    }

    public function getGroupsByUID($uid)
    {
        dd($this->db->get('managePerms')->result_array());
    }
}