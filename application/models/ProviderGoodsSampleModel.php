<?php

include_once 'BaseModel.php';

class ProviderGoodsSampleModel extends BaseModel
{
    public function getList($page, $rows)
    {
        $this->db->select(
            'pgs_id, pgs_provider_goods_id, 
            pg_name, pgs_weight, pgs_create_time, 
            pgs_update_time'
        );

        $this->db->join('provider_goods', 'pg_id = pgs_provider_goods_id', 'left');

        $offset = ($page - 1) * $rows;
        $this->db->limit($rows, $offset);

        $query = $this->db->get('provider_goods_sample');

        return $query->result();
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

    public function addProviderGoodsSampleInfo($pgId, $pgsWeight)
    {
        $insertData = [
            'pgs_provider_goods_id' => $pgId,
            'pgs_weight' => $pgsWeight
        ];

        $o_result = $this->db->insert('provider_goods_sample', $insertData);

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
            'pgs_weight' => $params['pgs_weight']
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
}