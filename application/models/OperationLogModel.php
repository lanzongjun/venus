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

    public function getList($isRoot,
        $sId,
        $title,
        $content,
        $nickname,
        $startDate,
        $endDate,
        $page,
        $rows)
    {
        $query = $this->db;
        $query->join('admin_user', 'admin_user.uid = operation_log.uid', 'left');

        if (!$isRoot) {
            $query->where('operation_log.uid', $sId);
        }

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
        $queryList->select('operation_log.id, nickname, identify_code, ip, title, content, create_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('operation_log')->result_array();

        foreach ($rows as  &$row) {
            $row['ip'] = long2ip($row['ip']);
            $content = json_decode($row['content'], true);
            $row['content'] = empty($content['res']['msg']) ? $row['content'] : $content['res']['msg'];
        }

        return array(
            'total' => intval($total->total),
            'rows' => $rows
        );
    }
}