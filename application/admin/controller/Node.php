<?php

namespace app\admin\controller;

use app\api;
use app\admin\model\Node as NodeModel;

class Node extends Base {

    public function index() {
        if (request() -> isAjax()) {
            $nodes = NodeModel::field('id,node_name name,pid pid,is_menu,icon,control_name,action_name') -> select();

            $nodes = getTree(objToArray($nodes), false);
            return api ::success('ok', $nodes);
        }
        return $this -> fetch();
    }

    // 添加
    public function add() {
        $params = input('param.');
        $model = new NodeModel();
        $rs = $model -> strict(false) -> insert($params);
        if (!$rs) {
            return api ::error('添加失败');
        }
        return api ::success('添加成功');
    }

    // 编辑
    public function edit() {
        $params = input('param.');
        $model = new NodeModel();
        $rs = $model -> where('id', $params['id']) -> update($params);
        if (!$rs) {
            return api ::error('修改失败');
        }
        return api ::success('修改成功');
    }

    // 删除
    public function del() {
        $id = input('param.id');
        $rs = NodeModel::destroy($id);
        if (!$rs) {
            return api ::error('删除失败');
        }
        return api ::success('删除成功');
    }
}