<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/application/controllers/BaseController.php';
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

    /**
     * 上传CSV信息
     * 并预览
     */
    public function uploadInfo()
    {
        $this->load->helper('url');
        //手动创建文件上传目录
        $config['upload_path'] = './uploads/'; //根目录下的uploads文件(即相对于入口文件)
        $config['allowed_types'] = 'csv|csv';
        $config['max_size'] = '10000'; //允许上传文件大小的最大值（以K为单位）。该参数为0则不限制。
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        $result = $this->upload->do_upload('file_csv');
        //$error = $this->upload->display_errors();

        if (!$result) {
            $error = array(state => '0', 'error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = $this->upload->data();
            $o_result = $this->_loadCSV($data['full_path']);
            $this->load->model($this->_s_model);

            //获得临时表名
            $s_table_name_temp = $this->AdTmpCashPoolM->_getTempTableName();
            //导入临时表
            $i_rows = $this->AdTmpCashPoolM->inputCSV($s_table_name_temp, $o_result);
            $o_response = array('tbn' => $s_table_name_temp, 'rows' => $i_rows, 'state' => true);
            echo json_encode($o_response);
            /*
             * {"file_name":"5de4ebe704030.csv","file_type":"text\/plain","file_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/","full_path":"D:\/xampp\/htdocs\/CVSManager\/uploads\/5de4ebe704030.csv","raw_name":"5de4ebe704030","orig_name":"5de4ebe704030.csv","client_name":"\u6279\u91cf\u5efa\u5e97-\u5e10\u53f7\u4fe1\u606f\u6a21\u677f00000(1).csv","file_ext":".csv","file_size":6.45,"is_image":false,"image_width":null,"image_height":null,"image_type":"","image_size_str":""}}}Ok
             */
        }
    }

    /**
     * 加载解析CSV
     * @param type $_file_path
     * @return type
     */
    function _loadCSV($_file_path) {
        $this->load->library('CSVReader');
        return $this->csvreader->parse_file($_file_path);
    }

    public function importExcel2()
    {
        if ($_FILES['upload_file']['name']) {
            $tmp_file = $_FILES['upload_file']['tmp_name'];
            $file_types = explode('.', $_FILES['upload_file']['name']);
            $file_type = $file_types[count($file_types)-1];

            //判断是否为excel文件
            if (strtolower($file_type) != 'xlsx' && strtolower($file_type) != 'xls') {
                echo "不是excel文件，请重新上传！";
                exit();
            }

            //设置上传路径
            $savePath = "./uploads/";
            //文件命名
            $str = date('Ymdhis');
            $file_name = $str.".".$file_type;
            if (!copy($tmp_file,$savePath.$file_name)) {
                echo "上传失败";
                exit();
            }

            $this->load->library('PHPExcel');
            $inputFileName = "./uploads/".$file_name;

            /**  确定输入文件的格式  **/
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            /** 穿件相对应的阅读器  **/
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            /**  配置单元格数据都以字符串返回  **/
            $objReader->setReadDataOnly(true);
            /**  加载要读取的文件  **/
            $objPHPExcel = $objReader->load($inputFileName);
            $sheetData =$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
dd($sheetData);
            // 处理数据
            $insertData = [];
            foreach ($sheetData as $datum) {
                $insertData[$datum['E']] = [
                    'gso_shop_id' => 1,//TODO
                    'gso_date' => $datum['D'],
                    'gso_sku_code' => 'JFJSJ003XA',
                    'gso_num' => $datum['F']
                ];
            }


            echo json_encode(array(
                'state' => true,
            ));
            exit();

            echo "导入成功";
        }

        echo 'error';
    }

    public function importExcel()
    {
        $file = $_FILES['upload_file'];
        $excelData = $this->getExcelData($file);
        if (isset($excelData['state']) && !$excelData['state']) {
            echo json_encode($excelData);
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addSaleOnlineExcelData($excelData);

        echo json_encode($result);
    }

    public function output()
    {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}