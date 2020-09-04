<?php

include_once 'BaseModel.php';

class ProviderGoodsModel extends BaseModel
{
    public function getList($providerName, $providerGoodsName, $page, $rows, $rowsOnly)
    {

        $query = $this->db->join('provider', 'p_id = pg_provider_id', 'left');

        if (!empty($providerName)) {
            $query = $this->db->like('p_name', $providerName);
        }

        if (!empty($providerGoodsName)) {
            $query = $this->db->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('provider_goods')->result();
            if (empty($total['0']) || empty($total['0']->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('pg_id, pg_provider_id, p_name, pg_name, pg_create_time, pg_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('provider_goods')->result();

        foreach ($rows as &$row) {
            $row->provider_goods_format = "{$row->pg_name}({$row->p_name})";
        }

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total['0']->total,
                'rows' => $rows
            );
        }
    }

    public function getProviderGoodsInfo($id)
    {
        $this->db->select('pg_id, p_id, p_name, pg_name, pg_create_time, pg_update_time');
        $this->db->join('provider', 'p_id = pg_provider_id', 'left');
        $this->db->where('pg_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('provider_goods');
        $result = $query->result_array();
        if ($result && count($result) == 1) {
            return $result[0];
        }
        return array();
    }

    public function addProviderGoods($providerId, $providerGoodsName)
    {
        $insertData = [
            'pg_provider_id' => $providerId,
            'pg_name'        => $providerGoodsName
        ];

        $this->db->insert('provider_goods', $insertData);

        return array(
            'state' => 'true',
            'msg'   => "【{$providerGoodsName}】添加成功"
        );
    }

    public function editProviderGoods($params)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'pg_provider_id' => $params['p_id'],
            'pg_name' => $params['pg_name']
        ];
        $this->db->where('pg_id', $params['pg_id']);

        try {
            $this->db->update('provider_goods',$updateData);
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