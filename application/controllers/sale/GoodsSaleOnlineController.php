<?php

require_once APPPATH . 'controllers/BaseController.php';
require_once APPPATH . 'libraries/PHPExcel/IOFactory.php';


class GoodsSaleOnlineController extends BaseController
{
    public $_s_view  = 'GoodsSaleOnlineView';
    public $_s_model = 'GoodsSaleOnlineModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'sale/GoodsSaleOnlineController';
        $this->load->helper('url');
        $this->load->view("admin/sale/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $rowsOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;

        $this->load->model($this->_s_model);

        $result = $this->{$this->_s_model}->getList($page, $rows, $rowsOnly);

        echo json_encode($result);
    }

    public function importExcel()
    {
        $file = $_FILES['upload_file'];
        $excelData = $this->getExcelData($file);
        if (isset($excelData['state']) && !$excelData['state']) {
            echo json_encode($excelData);
            exit();
        }

        // 去除空数据
        foreach ($excelData as $key => $val) {
            if (empty($val['B']) || empty($val['G'])) {
                unset($excelData[$key]);
                continue;
            }
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addSaleOnlineExcelData($excelData);

        echo json_encode($result);
    }
}