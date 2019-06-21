<?php

namespace app\admin\model;

use think\Model;

class Node extends Model {

    /**
     * 获取节点数据
     */
    public function getNodeInfo($id) {
        $result = $this -> field('id,node_name,pid') -> select();
        $str = '';

        $role = new Role();
        $rule = $role -> where('id', $id) -> value("rule");

        if (!empty($rule)) {
            $rule = explode(',', $rule);
        }

        foreach ($result as $key => $vo) {
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['node_name'] . '"';

            if (!empty($rule) && in_array($vo['id'], $rule)) {
                $str .= ' ,"checked":1';
            }

            $str .= '},';

        }

        return '[' . rtrim($str, ',') . ']';
    }

    /**
     * 根据节点数据获取对应的菜单
     * @param $nodeStr
     * @return mixed
     * @throws
     */
    public function getMenu($nodeStr = '') {
        if (empty($nodeStr)) {
            return [];
        }
        // 超级管理员没有节点数组 * 号表示
        $where = '*' == $nodeStr ? 'is_menu = 2' : 'is_menu = 2 and id in(' . $nodeStr . ')';

        $result = $this -> field('id,node_name,pid,control_name,action_name,icon')
            -> where($where) -> select();
        $menu = prepareMenu($result);

        return $menu;
    }

}