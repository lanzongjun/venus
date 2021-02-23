<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 3:43 PM
 */

include_once 'BaseModel.php';

class ManageRoleModel extends BaseModel
{

    public function getList($name, $desc)
    {
        $query = $this->db->where('is_deleted', 0);

        if (!empty($name)) {
            $query->like('name', $name);
        }

        if (!empty($desc)) {
            $query->like('desc', $desc);
        }

        $result = $query->get('vms_manage_role')->result_array();

        foreach ($result as &$item) {
            $item['status'] = $item['status'] == 1 ? '启用' : '禁用';
        }

        return $result;
    }

    public function add($name, $desc, $permsIds, $status)
    {
        $insertData = [
            'name'   => $name,
            'desc'   => $desc,
            'perms'  => $permsIds,
            'status' => $status
        ];

        $this->db->insert('vms_manage_role', $insertData);

        return array(
            'state' => true,
            'msg'   => "角色:【{$name}】添加成功"
        );
    }

    public function edit($id, $name, $desc, $permsIds, $status)
    {
        $row = $this->db->where('id', $id)->where('is_deleted', 0)->get('manage_role')->first_row();

        if (empty($row)) {
            return array(
                'state' => true,
                'msg'   => '该条记录不存在'
            );
        }

        $updateData = [
            'name'   => $name,
            'desc'   => $desc,
            'perms'  => $permsIds,
            'status' => $status
        ];
        $this->db->where('id', $id)->update('manage_role', $updateData);

        // TODO 修改对应用户的权限缓存

        return [
            'state' => true,
            'msg'   => "角色：【{$name}】更新成功"
        ];
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('manage_role');

        return [
            'state' => true,
            'msg'   => "角色：删除成功"
        ];
    }

    public function getManageRoleInfo($id)
    {
        $row = $this->db->where('id', $id)->get('manage_role')->first_row();

        // 获取权限列表
        $this->load->model('ManagePermsModel');
        $permList = $this->ManagePermsModel->getUserPerms();

        $checkedPerm = explode(',', $row->perms);
        foreach ($permList as &$item) {
            $item = $this->addChecked($item, $checkedPerm);
        }

        $returnData = [
            'id' => $row->id,
            'name' => $row->name,
            'status' => $row->status,
            'desc' => $row->desc,
            'perm_list' => $permList
        ];

        return $returnData;
    }

    /**
     * 添加checked标识
     * @author zongjun.lan
     */
    private function addChecked($items, $checkedPerms)
    {
        $returnData = [];

        if (isset($items[0])) {
            foreach ($items as &$item) {
                if (in_array($item['id'], $checkedPerms)) {
                    $item['checked'] = true;
                }
            }
        } else {
            if (in_array($items['id'], $checkedPerms)) {
                $items['checked'] = true;
            }
        }

        if (!empty($items['children'])) {
            $items['children'] = $this->addChecked($items['children'], $checkedPerms);
        }

        return $items;
    }
}