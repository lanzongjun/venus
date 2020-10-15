<?php
include_once 'BaseController.php';

class CoreSkuController extends BaseController
{
    public $_s_view = 'CoreSkuView';
    public $_s_model = 'CoreSkuModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'CoreSkuController';
        $this->load->helper('url');
        $this->load->view("admin/sku/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $page = isset($getData['page']) ? $getData['page'] : 1;
        $rows = isset($getData['rows']) ? $getData['rows'] : 50;
        $code = isset($getData['code']) ? $getData['code'] : '';
        $name = isset($getData['name']) ? $getData['name'] : '';
        $desc = isset($getData['description']) ? $getData['description'] : '';
        $rowOnly = isset($getData['rows_only']) ? $getData['rows_only'] : false;
        $isDownload = isset($getData['is_download']) ? $getData['is_download'] : 0;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $code,
            $name,
            $desc,
            $page,
            $rows,
            $rowOnly
        );

        if ($isDownload) {
            $intersectKeys = [
                'cs_code' => true,
                'cs_name' => true,
                'cs_description' => true,
                'cs_create_time' => true,
                'cs_update_time' => true
            ];

            $intersectData = array_map(function ($item) use ($intersectKeys) {
                return array_intersect_key(array_merge($intersectKeys, $item), $intersectKeys);
            } ,$o_result['rows']);

            $this->output(
                'SKU管理-SKU列表',
                [
                    'SKU','名称','描述','创建时间','更新时间'
                ],
                $intersectData
            );
            exit();
        }

        echo json_encode($o_result);
    }

    public function getSkuInfo()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if ($id != '') {
            $this->load->model($this->_s_model);
            $o_result = $this->CoreSkuModel->getSkuInfo($id);
            echo json_encode($o_result);
        } else {
            echo '';
        }
    }

    public function addSkuInfo()
    {
        $postData = $this->input->post();

        $code = isset($postData['cs_code']) ? $postData['cs_code'] : '';
        $name = isset($postData['cs_name']) ? $postData['cs_name'] : '';
        $description = isset($postData['cs_description']) ? $postData['cs_description'] : '';

        // 参数校验
        if (empty($code) || empty($name) || empty($description)) {
            echo json_encode(array(
                'state' => false,
                'msg'   => '参数不能为空'
            ));
            exit();
        }

        $this->load->model($this->_s_model);
        $result = $this->{$this->_s_model}->addSkuInfo($postData);

        echo json_encode($result);
    }

    public function editSkuInfo()
    {
        $postData = $this->input->post();
    }
}