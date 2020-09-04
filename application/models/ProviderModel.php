<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/8/27
 * Time: 9:33 AM
 */

include_once 'BaseModel.php';

class ProviderModel extends BaseModel
{
    public function getList($providerName, $page, $rows, $rowsOnly)
    {
        $query = $this->db;

        if (!empty($providerName)) {
            $query = $this->db->like('p_name', $providerName);
        }

        $queryTotal = clone $query;
        $queryList = clone  $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('provider')->result();
            if (empty($total['0']) || empty($total['0']->total)) {
                return array(
                    'total' => 0,
                    'rows'  => []
                );
            }
        }

        // 获取分页数据
        $queryList->select('p_id, p_name, p_create_time, p_update_time');

        if (!$rowsOnly) {
            $offset = ($page - 1) * $rows;
            $queryList->limit($rows, $offset);
        }

        $rows = $queryList->get('provider')->result();

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total['0']->total,
                'rows' => $rows
            );
        }
    }

    public function getProviderInfo($id)
    {
        $this->db->select('*');
        $this->db->where('p_id', $id);
        $this->db->limit(1);
        $query = $this->db->get('provider');
        $result = $query->result_array();
        if ($result && count($result) == 1) {
            return $result[0];
        }
        return array();
    }

    public function addProviderInfo($params)
    {
        $insertData = [
            'p_name' => $params['name']
        ];

        $this->db->insert('provider', $insertData);

        return array(
            'state' => true,
            'msg' => "【{$params['name']}】添加成功"
        );
    }

    public function editProviderInfo($params)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = ['p_name' => $params['name']];
        $this->db->where('p_id', $params['id']);

        try {
            $this->db->update('provider',$updateData);
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