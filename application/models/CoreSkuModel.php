<?php

include_once 'BaseModel.php';

class CoreSkuModel extends BaseModel
{
    public function getList($code, $name, $desc, $page, $rows, $rowsOnly)
    {
        $query = $this->db;

        if (!empty($code)) {
            $query = $this->db->like('cs_code', $code);
        }

        if (!empty($name)) {
            $query = $this->db->like('cs_name', $name);
        }

        if (!empty($desc)) {
            $query = $this->db->like('cs_description', $desc);
        }

        $queryTotal = clone $query;
        $queryList = clone $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('core_sku')->first_row();
            if (empty($total->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('*');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('core_sku')->result_array();

        foreach ($rows as &$row) {
            $row['show_name'] = $row['cs_name'].'-'.'('.$row['cs_code'].')';
        }

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total->total,
                'rows' => $rows
            );
        }
    }

    public function getSkuInfo($id)
    {
        $this->db->select('*');
        $this->db->where('cs_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('core_sku');
        $result = $query->result_array();
        if ($result && count($result) == 1) {
            return $result[0];
        }
        return array();
    }


    public function addSkuInfo($params)
    {
        // 校验唯一SKU
        $this->db->where('cs_code', $params['cs_code']);
        $this->db->select('cs_id');
        $query = $this->db->get('core_sku');
        $row = $query->result();
        if (!empty($row['0']->cs_id)) {
            return array(
                'state' => false,
                'msg'   => "sku:【{$params['cs_code']}】已存在，请重新添加"
            );
        }

        $insertData = [
            'cs_code' => $params['cs_code'],
            'cs_name' => $params['cs_name'],
            'cs_description' => $params['cs_description'],
        ];

        $this->db->insert('core_sku', $insertData);

        return array(
            'state' => true,
            'msg'   => "sku:【{$params['cs_code']}】添加成功"
        );
    }

    public function editSkuInfo($params)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'cs_code' => $params['cs_code'],
            'cs_name' => $params['cs_name'],
            'cs_description' => $params['cs_description']
        ];
        $this->db->where('cs_id', $params['cs_id']);

        try {
            $this->db->update('core_sku',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '编辑SKU信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑SKU信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
}