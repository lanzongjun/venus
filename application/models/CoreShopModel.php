<?php
include_once 'BaseModel.php';

class CoreShopModel extends BaseModel
{
    public function getList($page, $rows, $rowsOnly)
    {
        $query = $this->db;

        $queryTotal = clone $query;
        $queryList = clone $query;

        if (!$rowsOnly) {
            // 获取总数
            $queryTotal->select('count(1) as total');
            $total = $queryTotal->get('core_shop')->result();
            if (empty($total['0']) || empty($total['0']->total)) {
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

        $rows = $queryList->get('core_shop')->result();

        if ($rowsOnly) {
            return $rows;
        } else {
            return array(
                'total' => $total['0']->total,
                'rows' => $rows
            );
        }
    }
}