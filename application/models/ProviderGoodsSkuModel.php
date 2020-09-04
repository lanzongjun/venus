<?php

include_once 'BaseModel.php';

class ProviderGoodsSkuModel extends BaseModel
{
    public function getList($skuCode, $providerGoodsName, $page, $rows)
    {

        $query = $this->db->join('provider_goods', 'pgs_provider_goods_id = pg_id', 'left');

        if (!empty($skuCode)) {
            $query = $this->db->like('pgs_sku_code', $skuCode);
        }

        if (!empty($providerGoodsName)) {
            $query = $this->db->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('provider_goods_sku')->result();
        if (empty($total['0']) || empty($total['0']->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('pgs_id, pgs_sku_code, pg_name, pgs_num, pgs_create_time, pgs_update_time');
        $queryList->order_by("pgs_id", "desc");

        $offset    = ($page - 1) * $rows;
        $queryList = $queryList->limit($rows, $offset);

        $list = $queryList->get('provider_goods_sku');
        return array(
            'total' => $total['0']->total,
            'rows'  => $list->result()
        );
    }

    public function getProviderGoodsSkuInfo($id)
    {
        $this->db->select('*');
        $this->db->join('provider_goods', 'pgs_provider_goods_id = pg_id', 'left');
        $this->db->join('core_sku', 'cs_code = pgs_sku_code', 'left');
        $this->db->where('pgs_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('provider_goods_sku');
        $result = $query->result();
        foreach ($result as &$row) {
            $row->show_name = $row->cs_name.'-'.$row->cs_description.'('.$row->cs_code.')';
        }
        if ($result && count($result) == 1) {
            return $result[0];
        }
        return array();
    }

    public function addProviderGoodsSku($skuCode, $providerGoods, $num)
    {
        // 校验数据唯一
        $exit = $this->db->where('pgs_provider_goods_id', $providerGoods)
            ->where('pgs_sku_code', $skuCode)
            ->get('provider_goods_sku')
            ->result();

        if (!empty($exit)) {
            return array(
                'state' => false,
                'msg'   => '不能重复添加绑定关系'
            );
        }

        $insertData = [
            'pgs_provider_goods_id' => $providerGoods,
            'pgs_sku_code' => $skuCode,
            'pgs_num' => $num
        ];

        $this->db->insert('provider_goods_sku', $insertData);

        return array(
            'state' => true,
            'msg'   => '添加成功'
        );
    }

    public function editProviderGoodsSku($providerGoodsSkuId, $providerGoodsId, $skuCode, $num)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'pgs_provider_goods_id' => $providerGoodsId,
            'pgs_num' => $num,
            'pgs_sku_code' => $skuCode
        ];
        $this->db->where('pgs_id', $providerGoodsSkuId);

        try {
            $this->db->update('provider_goods_sku',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '编辑商铺信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
}