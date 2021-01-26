<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/8
 * Time: 2:50 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'BaseController.php';

/**
 * 操作日志
 *
 * @author zongjun.lan
 */
class OperationLogController extends BaseController
{
    public $_s_view  = 'OperationLog';
    public $_s_model = 'OperationLogModel';

    public function index()
    {
        $data['c_name'] = 'OperationLogController';
        $this->load->helper('url');
        $this->load->view("admin/baseConfig/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $title = isset($getData['title']) ? $getData['title'] : '';
        $content = isset($getData['content']) ? $getData['content'] : '';
        $nickname = isset($getData['nickname']) ? $getData['nickname'] : '';
        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->getList(
            $this->user_id,
            $this->shop_id,
            $title,
            $content,
            $nickname,
            $startDate,
            $endDate,
            $page,
            $rows
        );

        echo json_encode($result);
    }
}