<?php

class BaseController extends CI_Controller
{
    /**
     * @var object 用户
     */
    public $user;

    /**
     * @var int 店铺
     */
    public $shop_id;

    /**
     * @var int 用户ID
     */
    public $user_id;

    public function __construct()
    {

        parent::__construct();

        $this->load->library('session');
        // 没有session需要重定向登录
        if (empty($this->session->s_user)) {
            redirect('admin/index');
        }

        $this->user = $this->session->s_user;

        $this->shop_id = $this->session->s_user->u_shop_id;

        $this->user_id = $this->session->s_user->u_id;
    }

    public function getPostData()
    {
        return $this->input->post();
    }

    public function getGetData()
    {
        return $this->input->get();
    }

    public function getExcelData($file)
    {
        $tmpFile = $file['tmp_name'];

        if (!file_exists($tmpFile)){
            $result['state'] = false;
            $result['msg']  = '文件不存在';
            return $result;
        }

        $fileTypes = explode('.', $file['name']);
        $fileType = $fileTypes[count($fileTypes)-1];


        //设置上传路径
        $savePath = "./uploads/";
        //文件命名
        $str = date('YmdHis');
        $fileName = $str.".".$fileType;
        if (!copy($tmpFile,$savePath.$fileName)) {
            $result['state'] = false;
            $result['msg']  = '上传失败';
            return $result;
        }
        $inputFileName = $savePath.$fileName;

        //加载类
        $this->load->library("PHPExcel");
        /**  确定输入文件的格式  **/
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        /** 穿件相对应的阅读器  **/
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        /**  配置单元格数据都以字符串返回  **/
        //$objReader->setReadDataOnly(true);
        /**  加载要读取的文件  **/
        $objPHPExcel = $objReader->load($inputFileName);

//        $sheet = $objPHPExcel->getSheet(0);                     //读取第一个工作表
//        $highestRow = $sheet->getHighestRow();                  //获取行数
//        $highestColumn = $sheet->getHighestColumn();            //获取列数
        $sheetData =$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        return $sheetData;
    }

    /**
     * @param $title string 标题
     * @param $column array 列名
     * @param $data array   数据
     * @author zongjun.lan
     */
    public function output($title, $column, $data)
    {
        $this->load->library("PHPExcel");

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

        // Add column
        $col = 0;
        $row = 1;
        foreach ($column as $colItem) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($col, $row, $colItem);
            $col++;
        }

        // Add data
        $row = 2;
        foreach ($data as $dItem) {
            $col = 0;
            foreach ($dItem as $value) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($title);


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$objPHPExcel->setActiveSheetIndex(0);

        $fileName = $title.'_'.strtotime('now');
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename={$fileName}.xls");
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
        exit();
    }
}