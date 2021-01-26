<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/1/18
 * Time: 5:15 PM
 */

class OperationLogModel extends BaseModel
{
    public function write($uid, $identifyCode, $title, $content = '')
    {

        $insertData = [
            'uid'           => $uid,
            'identify_code' => $identifyCode,
            'ip'            => ip2long($this->input->ip_address()),
            'title'         => $title,
            'content'       => !empty($content) ? json_encode($content) : ''
        ];

        $this->db->insert('operation_log', $insertData);

        return true;

    }

    public function getList(
        $uid,
        $shopId,
        $title,
        $content,
        $nickname,
        $startDate,
        $endDate,
        $page,
        $rows)
    {
        $query = $this->db;
        $query->join('user', 'user.u_id = operation_log.uid', 'left');
        $query->where('u_id', $uid);
        $query->where('u_shop_id', $shopId);

        if (!empty($title)) {
            $query->like('title', $title);
        }

        if (!empty($content)) {
            $query->like('content', $content);
        }

        if (!empty($nickname)) {
            $query->like('nickname', $nickname);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('create_time', '>=', $startDate);
            $query->where('create_time', '<=', $endDate);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('operation_log')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('operation_log.id, u_name as nickname, identify_code, ip, title, content, create_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('operation_log')->result_array();

        foreach ($rows as  &$row) {
            $row['ip'] = long2ip($row['ip']);
            $content = json_decode($row['content'], true);
            $row['params'] = empty($content['params']) ? '--' : json_encode($content['params']);
            $row['message'] = empty($content['result']['msg']) ? '--' : $content['result']['msg'];
        }

        return array(
            'total' => intval($total->total),
            'rows' => $rows
        );
    }
}