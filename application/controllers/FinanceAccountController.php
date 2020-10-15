<?php

include_once 'BaseController.php';

class FinanceAccountController extends BaseController
{
    public $_s_view  = 'FinanceAccountView';
    public $_s_model = 'FinanceAccountModel';

    /**
     * 显示信息
     */
    public function index()
    {
        $data['c_name'] = 'FinanceAccountController';
        $this->load->helper('url');
        $this->load->view("admin/stock/$this->_s_view", $data);
    }

    public function getList()
    {
        $getData = $this->getGetData();

        $startDate = isset($getData['start_date']) ? $getData['start_date'] : '';
        $endDate = isset($getData['end_date']) ? $getData['end_date'] : '';
        $isDownload = isset($getData['is_download']) ? $getData['is_download'] : 0;

        $this->load->model($this->_s_model);

        $o_result = $this->{$this->_s_model}->getList(
            $this->shop_id,
            $startDate,
            $endDate
        );

        if ($isDownload) {

            $intersectData = $this->formatDownloadData($o_result['rows']);

            $this->output(
                '财务结算列表',
                [
                    '商品ID','商品名称','单位','入库数量',
                    '线下销售-石化结算','线下销售-现场结算','线上销售',
                    '异常情况','员工餐','损耗-店内破损','损耗-退单'
                ],
                $intersectData
            );
            exit();
        }

        echo json_encode($o_result);
    }

    private function formatDownloadData($data)
    {
        $returnData = [];
        foreach ($data as $item) {
            $returnData[] = [
                'goods_id' => $item['goods_id'],
                'goods_name' => $item['goods_name'],
                'unit' => $item['unit'],
                'stock' => $item['data'][REPERTORY_TYPE_GOODS_STOCK]['num'],
                'offline_shihua' => $item['data'][REPERTORY_TYPE_GOODS_SALE_OFFLINE_SHIHUA]['num'],
                'offline_locate' => $item['data'][REPERTORY_TYPE_GOODS_SALE_OFFLINE_LOCATE]['num'],
                'online' => $item['data'][REPERTORY_TYPE_GOODS_SALE_ONLINE]['num'],
                'exception' => $item['data'][REPERTORY_TYPE_GOODS_EXCEPTION_HANDLE]['num'],
                'staff' => $item['data'][REPERTORY_TYPE_STAFF_MEAL]['num'],
                'shop_loss' => $item['data'][REPERTORY_TYPE_GOODS_SHOP_LOSS]['num'],
                'order_loss' => $item['data'][REPERTORY_TYPE_GOODS_ORDER_LOSS]['num'],
            ];
        }

        return $returnData;
    }
}