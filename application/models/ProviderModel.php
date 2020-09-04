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
    public function getList($page, $rows, $providerName)
    {
        $this->db->select('p_id, p_name, p_create_time, p_update_time');

        if (!empty($providerName)) {
            $this->db->like('p_name', $providerName);
        }

        $offset = ($page - 1) * $rows;
        $this->db->limit($rows, $offset);

        $query = $this->db->get('provider');

        //dd($this->db->last_query());
        return $query->result();
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