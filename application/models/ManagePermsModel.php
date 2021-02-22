<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2021/2/3
 * Time: 3:42 PM
 */

include_once 'BaseModel.php';

class ManagePermsModel extends BaseModel
{
    public function getRulesByIds($ids)
    {
        $query = $this->db;

        if ($ids != '*') {
            $query->where_in('id', $ids);
        }

        $query->where('status', 1)
        ->select('id, name , name `text`, identity_code, is_open, parent_id, is_show, status, url ')
        ->order_by('parent_id', 'asc');

        $result = $query->get('manage_perms')->result_array();

        return $result;
    }

    public function getUserPerms()
    {
//        $this->load->model("ManageModel");
//        $userPerms = $this->ManageModel->getPermsByUID($uid);

        $userPermsList = $this->getRulesByIds('*');

        $treePerms = $this->getPermsTree($userPermsList);

        return $treePerms;

    }

    public function getAllPermsList()
    {
        $result = $this->db
            ->where('status', 1)
            ->get('manage_perms')
            ->result_array();

        return $result;
    }

    public function getPermsTree($userPermsList)
    {
        $navNode = [];
        foreach ($userPermsList as $node) {
            if ($node['parent_id'] == 0) { // 父类
                $navNode[] = $node;

            } else {

                $navNode = $this->getChildrenNode($navNode, $node);

            }
        }

        return $navNode;
    }

    public function getChildrenNode(&$navNode, $node)
    {

        foreach ($navNode as &$navItem) {
            if ($navItem['id'] == $node['parent_id']) {
                $navItem['children'][] = $node;
            } else {
                if (!empty($navItem['children'])) {
                    $this->getChildrenNode($navItem['children'], $node);
                }
            }
        }

        return $navNode;
    }
}