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
}