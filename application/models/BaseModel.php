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
     * 添加库存
     * @param $shopId  int 店铺
     * @param $goodsId int 商品
     * @param $date string 日期
     * @param $num int|float 数量
     * @param $unit int 单位
     * @param $type int 类型
     * @param $insertId int 关联插入ID
     * @return array
     * @author zongjun.lan
     */
    public function addRepertory($shopId, $goodsId, $date, $num, $unit, $type, $insertId)
    {
        $insertRecordData = [
            'crr_shop_id'           => $shopId,
            'crr_provider_goods_id' => $goodsId,
            'crr_date'              => $date,
            'crr_num'               => $num,
            'crr_unit'              => $unit,
            'crr_type'              => $type,
            'crr_ref_id'            => $insertId
        ];

        $this->db->insert('core_repertory_record', $insertRecordData);

        // 判断是否是饺子
        $isDumplings = $this->db
            ->where('pg_id', $goodsId)
            ->get('provider_goods')
            ->row()->pg_is_dumplings;

        $transferResult = $this->transferToGram($num, $unit, $goodsId, $isDumplings);

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

            $updateWhere = [
                'cr_shop_id' => $shopId,
                'cr_provider_goods_id' => $goodsId
            ];

            $this->db->update('core_repertory', $updateData, $updateWhere);
        }

        return array(
            'state' => true
        );
    }

    /**
     * 删除库存
     * @param $shopId int 店铺
     * @param $type int 类型
     * @param $insertId int 关联ID
     * @return array
     * @author zongjun.lan
     */
    public function deleteRepertory($shopId, $type, $insertId)
    {
        $row = $this->db
            ->join('provider_goods', 'pg_id = crr_provider_goods_id', 'left')
            ->where('crr_type', $type)
            ->where('crr_ref_id', $insertId)
            ->where('crr_shop_id', $shopId)
            ->select('crr_num, crr_unit, crr_shop_id, crr_provider_goods_id, pg_is_dumplings')
            ->get('core_repertory_record')
            ->first_row();

        if (empty($row)) {
            return array(
                'state' => false,
                'msg'   => '该记录不存在，无法删除'
            );
        }

        // 删除记录
        $deleteWhere = [
            'crr_type'    => $type,
            'crr_ref_id'  => $insertId,
            'crr_shop_id' => $shopId
        ];
        $this->db->delete('core_repertory_record', $deleteWhere);

        // 判断是否是饺子
        $isDumplings = $row->pg_is_dumplings;

        // 删除记录 数据负负得正 添加-
        $transferResult = $this->transferToGram(
            -$row->crr_num,
            $row->crr_unit,
            $row->crr_provider_goods_id,
            $isDumplings
        );
        if (!$transferResult) {
            return array(
                'state' => false,
                'msg'   => '请先给该商品取样商品重量'
            );
        }


        // 获取库存记录
        $existsRep = $this->db
            ->where('cr_shop_id', $shopId)
            ->where('cr_provider_goods_id', $row->crr_provider_goods_id)
            ->get('core_repertory')
            ->first_row();
        // 修改库存
        $updateData = [
            'cr_num' => $existsRep->cr_num + $transferResult
        ];

        if ($existsRep->cr_num + $transferResult < 0) {
            return array(
                'state' => false,
                'msg'   => '商品库存不足，请检查库存'
            );
        }

        $updateWhere = [
            'cr_shop_id' => $shopId,
            'cr_provider_goods_id' => $row->crr_provider_goods_id
        ];

        $this->db->update('core_repertory', $updateData, $updateWhere);

        return array(
            'state' => true
        );
    }

    /**
     * 修改库存
     * @param $shopId int 店铺
     * @param $type   int 类型
     * @param $insertId int 插入关联ID
     * @param $date string 日期
     * @param $num float 数量
     * @param $unit   int 单位
     * @return array
     * @author zongjun.lan
     */
    public function editRepertory($shopId, $type, $insertId, $date, $num, $unit)
    {
        // 查出之前的数据
        $row = $this->db
            ->join('provider_goods', 'pg_id = crr_provider_goods_id', 'left')
            ->where('crr_type', $type)
            ->where('crr_ref_id', $insertId)
            ->where('crr_shop_id', $shopId)
            ->select('crr_num, crr_unit, crr_shop_id, crr_provider_goods_id, pg_is_dumplings')
            ->get('core_repertory_record')
            ->first_row();

        if (empty($row)) {
            return array(
                'state' => false,
                'msg'   => '记录不存在'
            );
        }

        $goodsId = $row->crr_provider_goods_id;

        // 修改记录
        $updateRecordData = [
            'crr_date' => $date,
            'crr_num'  => $num,
            'crr_unit' => $unit,
        ];
        $updateRecordWhere = [
            'crr_type'    => $type,
            'crr_ref_id'  => $insertId,
            'crr_shop_id' => $shopId
        ];

        $this->db->update('core_repertory_record', $updateRecordData, $updateRecordWhere);

        // 比较一下,求出差值
        $originNum    = $row->crr_num;
        $originUnit   = $row->crr_unit;
        $isDumplings  = $row->pg_is_dumplings;
        $originWeight = $this->transferToGram($originNum, $originUnit, $goodsId, $isDumplings);
        $nowWeight    = $this->transferToGram($num, $unit, $goodsId, $isDumplings);

        $diffWeight   = round($nowWeight-$originWeight, 2);

        //$diffWeight   = $isAdd ? $diffWeight : -$diffWeight;


        // 没有差值 直接退出
        if (empty($diffWeight)) {
            return array(
                'state' => true
            );
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
    public function transferToGram($num, $unit, $goodsId, $isDumplings)
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