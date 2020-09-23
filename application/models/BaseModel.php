<?php
require_once APPPATH . 'helpers/const_helper.php';

class BaseModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public static function unitMap($unit)
    {
        switch ($unit)
        {
            case '1':
                $unitMap = '个';
                break;
            case '2':
                $unitMap = '斤';
                break;
            default:
                $unitMap = '未知';

        }

        return $unitMap;
    }

    /**
     * 添加或删除库存
     * @param $shopId  int 店铺
     * @param $goodsId int 商品
     * @param $num int|float 数量
     * @param $unit int 单位
     * @param $type int 类型
     * @return array
     * @author zongjun.lan
     */
    public function modifyRepertory($shopId, $goodsId, $num, $unit, $type)
    {
        $insertRecordData = [
            'crr_shop_id'           => $shopId,
            'crr_provider_goods_id' => $goodsId,
            'crr_num'               => $num,
            'crr_unit'              => $unit,
            'crr_type'              => $type,
        ];

        $this->db->insert('core_repertory_record', $insertRecordData);

        // 判断是否是饺子
        $isDumplings = $this->db
            ->where('pg_id', $goodsId)
            ->get('provider_goods')
            ->row()->is_dumplings;

        $transferResult = $this->transferToGram($isDumplings, $unit, $goodsId, $num);
        if (!$transferResult) {
            return array(
                'state' => false,
                'msg'   => '请先给该商品取样商品重量'
            );
        }

        // 判断是有记录
        $existsRep = $this->db
            ->where('cr_shop_id', $shopId)
            ->where('cr_provider_goods_id', $goodsId)
            ->get('core_repertory')
            ->first_row();

        if (empty($existsRep)) {

            $crUnit = $isDumplings ? 2 : 1;

            $insertData = [
                'cr_shop_id'           => $shopId,
                'cr_provider_goods_id' => $goodsId,
                'cr_num'               => $transferResult,
                'cr_unit'              => $crUnit
            ];

            if ($transferResult < 0) {
                return array(
                    'state' => false,
                    'msg'   => '商品库存不足，请检查库存'
                );
            }

            $this->db->insert('core_repertory', $insertData);
        } else {

            $updateData = [
                'cr_num' => $existsRep->cr_num + $transferResult
            ];

            if ($existsRep->cr_num + $transferResult < 0) {
                return array(
                    'state' => false,
                    'msg'   => '商品库存不足，请检查库存'
                );
            }

            $this->db
                ->where('cr_shop_id', $shopId)
                ->where('cr_provider_goods_id', $goodsId)
                ->update('core_repertory', $updateData);
        }

        return array(
            'state' => true
        );
    }

    /**
     * 修改库存
     * @param $shopId int 店铺
     * @param $table  string 表名
     * @param $rowKey string 表名字段前缀
     * @param $rowId  string 主键唯一
     * @param $num    int|float 数量
     * @param $unit   int 单位
     * @param $type   int 类型
     * @param bool $isAdd 是否是添加
     * @return array
     * @author zongjun.lan
     */
    public function editRepertory($shopId, $table, $rowKey, $rowId, $num, $unit, $type, $isAdd = false)
    {
        // 查出之前的数据
        $row = $this->db->where($rowKey.'_id', $rowId)
            ->join('provider_goods', 'pg_id = '.$rowKey.'_provider_goods_id', 'left')
            ->get($table)->first_row();

        if (empty($row)) {
            return array(
                'state' => false,
                'msg'   => '记录不存在'
            );
        }

        $goodsId = $row->{$rowKey . '_provider_goods_id'};

        // 添加记录
        $insertRecordData = [
            'crr_shop_id'           => $shopId,
            'crr_provider_goods_id' => $goodsId,
            'crr_num'               => $isAdd ? $num : -$num,
            'crr_unit'              => $unit,
            'crr_type'              => $type,
        ];

        $this->db->insert('core_repertory_record', $insertRecordData);

        // 比较一下,求出差值
        $originNum    = $row->{$rowKey . '_num'};
        $originUnit   = $row->{$rowKey . '_unit'};
        $isDumplings  = $row->is_dumplings;
        $originWeight = $this->transferToGram($isDumplings, $originUnit, $goodsId, $originNum);
        $nowWeight    = $this->transferToGram($isDumplings, $unit, $goodsId, $num);

        $diffWeight   = round($nowWeight-$originWeight, 2);
        $diffWeight   = $isAdd ? $diffWeight : -$diffWeight;


        // 没有差值 直接退出
        if (empty($diffWeight)) {
            return true;
        }

        // 修改库存
        $existsRep = $this->db
            ->where('cr_shop_id', $shopId)
            ->where('cr_provider_goods_id', $goodsId)
            ->get('core_repertory')
            ->first_row();

        $updateData = [
            'cr_num' => $existsRep->cr_num + $diffWeight
        ];

        if ($existsRep->cr_num + $diffWeight < 0) {
            return array(
                'state' => false,
                'msg'   => '商品库存不足，请检查库存'
            );
        }

        $this->db
            ->where('cr_shop_id', $shopId)
            ->where('cr_provider_goods_id', $goodsId)
            ->update('core_repertory', $updateData);


        return array(
            'state' => true
        );

    }

    /**
     * 饺子个数转化为克
     * @param $goodsId
     * @param $num
     * @author zongjun.lan
     */
    public function transferToGram($isDumplings, $unit, $goodsId, $num)
    {

        if ($isDumplings && $unit == 1) {//单位为个转化
            $perWeight = $this->db
                ->where('pgs_provider_goods_id', $goodsId)
                ->get('provider_goods_sample')
                ->row();

            if (empty($perWeight) || empty($perWeight->pgs_weight)) {
                return false;
            }

            $perWeight = $perWeight->pgs_weight;

            $num = intval($num);

            $allTotal = round($num * $perWeight, 2);
        } elseif ($isDumplings && $unit == 2) {
            $allTotal = round($num * 500, 2);
        } else {
            $allTotal = intval($num);
        }

        return $allTotal;
    }
}