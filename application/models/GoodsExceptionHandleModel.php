<?php
include_once 'BaseModel.php';

class GoodsExceptionHandleModel extends BaseModel
{

    /**
     * 索赔单
     */
    const EXCEPTION_HANDLE_TYPE_CHAIM = 1;

    public function getList($startDate, $endDate, $providerGoodsName, $page, $rows)
    {
        $query = $this->db;

        $query->join('provider_goods', 'geh_provider_goods_id = pg_id', 'left');
        $query->join('user', 'u_id = geh_operator', 'left');
        $query->join('core_shop', 'cs_id = geh_shop_id', 'left');

        if (!empty($startDate) && !empty($endDate)) {
            $query->where('geh_date >=', $startDate);
            $query->where('geh_date <=', $endDate);
        }

        if (!empty($providerGoodsName)) {
            $query->like('pg_name', $providerGoodsName);
        }

        $queryTotal = clone $query;
        $queryList  = clone $query;

        // 获取总数
        $queryTotal->select('count(1) as total');
        $total = $queryTotal->get('goods_exception_handle')->first_row();
        if (empty($total->total)) {
            return array(
                'total' => 0,
                'rows'  => []
            );
        }

        // 获取分页数据
        $queryList->select('geh_id, cs_name as shop_name, pg_name as goods_name, 
        geh_order, geh_date, geh_unit, geh_num, geh_type, u_name as operator, geh_create_time, geh_update_time');

        $offset = ($page - 1) * $rows;
        $queryList->limit($rows, $offset);

        $rows = $queryList->get('goods_exception_handle')->result_array();

        foreach ($rows as &$row) {
            $row['num_unit'] = $row['geh_num'].'('. self::unitMap($row['geh_unit']) .')';
            $row['geh_type_text'] = $row['geh_type'] == 1 ? '索赔单' : '';
        }

        return array(
            'total' => $total->total,
            'rows' => $rows
        );
    }

    public function addExceptionHandle(
        $userId,
        $shopId,
        $goodsId,
        $date,
        $unit,
        $num,
        $order)
    {
        $insertData = [
            'geh_shop_id'           => $shopId,
            'geh_provider_goods_id' => $goodsId,
            'geh_date'              => $date,
            'geh_unit'              => $unit,
            'geh_num'               => $num,
            'geh_operator'          => $userId,
            'geh_order'             => $order,
            'geh_type'              => self::EXCEPTION_HANDLE_TYPE_CHAIM
        ];

        $this->db->insert('goods_exception_handle', $insertData);

        return array(
            'state' => true,
            'msg' => '添加成功'
        );
    }

    public function getExceptionHandleInfo($id)
    {
        $this->db->select('geh_id, geh_provider_goods_id as goods_id, geh_date as date,
        geh_unit as unit, geh_num as num, geh_order as order');
        $this->db->where('geh_id', $id);
        $query = $this->db->get('goods_exception_handle');
        $result = $query->first_row();
        if (empty($result)) {
            return array();
        }

        return $result;
    }

    public function editExceptionHandle($id, $userId, $goodsId, $date, $unit, $num, $order)
    {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );

        $updateData = [
            'geh_operator'          => $userId,
            'geh_provider_goods_id' => $goodsId,
            'geh_date'              => $date,
            'geh_unit'              => $unit,
            'geh_num'               => $num,
            'geh_order'             => $order
        ];
        $this->db->where('geh_id', $id);

        try {
            $this->db->update('goods_exception_handle',$updateData);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', "编辑商铺信息-异常中断！\r\n" . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }

    public function deleteExceptionHandleRecord($id)
    {
        $result = $this->db->delete('goods_exception_handle', array('geh_id' => $id));

        return array(
            'state' => true,
            'msg'   => $result ? 1 : 0
        );
    }
}