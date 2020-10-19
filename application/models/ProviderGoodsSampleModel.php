<?php

include_once 'BaseModel.php';

class ProviderGoodsSampleModel extends BaseModel
{
    public function getList($providerGoodsName, $page, $rows, $rowsOnly)
    {
        $query = $this->db->join('provider_goods', 'pg_id = pgs_provider_goods_id', 'left');

        if (!empty($providerGoodsName)) {
            $query = $this->db->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('provider_goods_sample')->first_row();
            if (empty($total->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select(
            'pgs_id, pgs_provider_goods_id, 
            pg_name, pgs_weight, pgs_create_time, 
            pgs_update_time'
        );

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('provider_goods_sample')->result_array();

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => intval($total->total),
                'rows' => $rows
            );
        }
    }

    public function getProviderGoodsSampleInfo($id)
    {
        $this->db->select(
            'pgs_id, pgs_provider_goods_id, pg_id,
            pg_name, pgs_weight, pgs_create_time, 
            pgs_update_time'
        );

        $this->db->join('provider_goods', 'pg_id = pgs_provider_goods_id', 'left');
        $this->db->where('pgs_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('provider_goods_sample');
        $result = $query->result_array();
        if ($result && count($result) == 1) {
            return $result[0];
        }
        return array();
    }

    public function addProviderGoodsSampleInfo($pgId, $weight, $num)
    {
        $insertRecordData = [
            'pgsr_provider_goods_id' => $pgId,
            'pgsr_weight' => $weight,
            'pgsr_num' => $num
        ];

        $o_r_result = $this->db->insert('provider_goods_sample_record', $insertRecordData);

        // 计算合计取样数据统计插入provider_goods_sample
        $this->db->select('sum(pgsr_weight) as weight, sum(pgsr_num) as num');
        $this->db->where('pgsr_provider_goods_id', $pgId);
        $statistics = $this->db->get('provider_goods_sample_record')->result_array();
        $statistics = $statistics['0'];
        $weight = $statistics['weight'] * 1000; //转化成g
        $avgWeight = empty($statistics['num']) ? 0 : round($weight/$statistics['num'], 3);
        // 判断是否有取样记录
        $exists = $this->db->where('pgs_provider_goods_id', $pgId)->get('provider_goods_sample')->first_row();

        if (empty($exists)) {
            $insertData = [
                'pgs_provider_goods_id' => $pgId,
                'pgs_weight' => $avgWeight
            ];
            $o_result = $this->db->insert('provider_goods_sample', $insertData);
        } else {
            $updateData = [
                'pgs_weight' => $avgWeight
            ];
            $o_result = $this->db->where('pgs_provider_goods_id', $pgId)->update('provider_goods_sample', $updateData);
        }

        $result = ['state' => $o_result, 'msg' => "添加成功"];

        return $result;
    }

    public function editProviderGoodsSampleInfo($params)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'pgs_provider_goods_id' => $params['pg_id'],
            'pgs_weight'            => $params['pgs_weight']
        ];
        $this->db->where('pgs_id', $params['pgs_id']);

        try {
            $this->db->update('provider_goods_sample',$updateData);
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

    public function loadDetailData($goodsId)
    {
        $this->db->join('provider_goods', 'pgsr_provider_goods_id = pg_id', 'left');

        $this->db->where('pgsr_provider_goods_id', $goodsId);

        // 获取分页数据
        $this->db->select('pgsr_id, pg_name, pgsr_provider_goods_id, pgsr_weight,
        pgsr_num, pgsr_create_time, pgsr_update_time');

        $result = $this->db->get('provider_goods_sample_record')->result_array();

        return $result;
    }
}