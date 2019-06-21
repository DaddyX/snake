<?php

namespace app\admin\model;

use think\Model;

class Role extends Model {

    /**
     * 获取角色信息
     * @param $id
     * @return mixed
     * @throws
     */
    public function getRoleInfo($id) {
        $result = $this -> where('id', $id) -> find() -> toArray();
        // 超级管理员权限是 *
        if (empty($result['rule'])) {
            $result['action'] = '';
            return $result;
        } else if ('*' == $result['rule']) {
            $where = '';
        } else {
            $where = 'id in(' . $result['rule'] . ')';
        }

        // 查询权限节点
        $res = Node::field('control_name,action_name') -> where($where) -> select();

        foreach ($res as $key => $vo) {

            if ('#' != $vo['action_name']) {
                $result['action'][] = $vo['control_name'] . '/' . $vo['action_name'];
            }
        }

        return $result;
    }
}