<?php

include_once 'BaseModel.php';

class GoodsSaleOnlineModel extends BaseModel
{
    const IMPORT_GOODS_SALE_MAP = [
        'A' => 'shop_name',
        'B' => 'shop_no',
        'C' => 'shop_city',
        'D' => 'date',
        'E' => 'goods_title',
        'F' => 'goods_num',
        'G' => 'sku',
    ];

    public function addSaleOnlineExcelData($data)
    {
        // shop map
        $shopMap = $this->db->select('cs_id, cs_code')->where('cs_code !=', '')->get('core_shop')->result_array();
        $shopMap = array_column($shopMap, NULL, 'cs_code');

        //处理数据
        $formatData = [];
        if (!empty($data) && is_array($data)){
            // 删除表头
            array_shift($data);
            foreach ($data as $datum) {
                $shopId = isset($shopMap[strval($datum['B'])]) ? $shopMap[strval($datum['B'])]['cs_id'] : '';
                $date = date('Y-m-d', strtotime($datum['D']));
                $sku = $datum['G'];
                $uniKey = $shopId.'-'.$date.'-'.$sku;
                if (isset($formatData[$uniKey])) {
                    $formatData[$uniKey]['gso_num'] += intval($datum['F']);
                } else {
                    $formatData[$uniKey] = [
                        'gso_shop_id' => $shopId,
                        'gso_date' => $date,
                        'gso_sku_code' => $sku,
                        'gso_num' => intval($datum['F'])
                    ];
                }
            }
        }

        if (!empty($formatData)) {
            $result = $this->db->insert_batch('goods_sale_online', $formatData);

            return array(
                'state' => true,
                'msg'   => '线上销售数据导入成功'
            );
        } else {
            return array(
                'state' => false,
                'msg'   => '没有符合要求的数据插入'
            );
        }
    }

    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db->join('core_shop', 'cs_id = gso_shop_id', 'left');
        $query->join('core_sku', 'core_sku.cs_code = gso_sku_code', 'left');

        $queryTotal = clone $query;
        $queryList = clone  $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_sale_online')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('gso_id, core_shop.cs_name as shop_name, cs_city, gso_date, 
        gso_sku_code, core_sku.cs_name as sku_name, gso_num, gso_create_time, gso_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('goods_sale_online')->result_array();


        return array(
            'total' => $total->total,
            'rows' => $rows
        );

    }

    public function getSummaryList()
    {

    }
}