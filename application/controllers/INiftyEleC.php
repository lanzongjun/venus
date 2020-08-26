<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class INiftyEleC extends CI_Controller {

    /**
     * 显示信息
     */
    function index() {
        $this->load->library('ThriftEle');
        $o_shop_sku_list = $this->thriftele->getShopSkuList('test_517934_61349');
        $a_sku_list = $o_shop_sku_list->sku_list;
        $i = 0;
        
        $a_upc_count = array();        
        foreach ($a_sku_list as $o_sku) {
            if ($i<10) {
                $o_upc_count = array(
                    'upc' => $o_sku->upc,
                    'stock' => $i+5
                );
                array_push($a_upc_count, $o_upc_count);
            }            
        }
        
        $this->thriftele->SkuStockUpdateBatch('test_517934_61349', $a_upc_count);
    }

}
