<?php
include_once 'BaseController.php';

class CoreShopController extends BaseController
{
    public $_s_view = 'CoreShopView';
    public $_s_model = 'CoreShopModel';

    public function getList()
    {
        $getData = $this->input->get();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;
        $this->load->model($this->_s_model);
        $o_result = $this->{$this->_s_model}->getList($page, $rows, $rowOnly);
        echo json_encode($o_result);
    }
}