<?php

namespace app\admin\controller;

use app\api;
use app\admin\model\Node as NodeModel;
use app\admin\model\Role as RoleModel;

class Role extends Base {

    public function index() {
        if (request() -> isAjax()) {
            $params = input("param.", "", "trim");
            $query = RoleModel::order('id desc');
            if (!empty($params['keyword'])) {
                $query -> where('role_name', 'like', '%' . $params['keyword'] . '%');
            }
            $paginate = $query -> paginate($params['limit'], false, ['page' => $params['page']]);
            // 拼装参数
            foreach ($paginate as $key => &$value) {
                if ($value['id'] == 1) {
                    $value['operate'] = '';
                    continue;
                }
                $value['operate'] = showOperate($this -> makeButton($value['id']));
            }
            $return['rows'] = $paginate;
            return api ::success('ok', $return);
        }
        return $this -> fetch();
    }

    // 添加
    public function add() {
        if (request() -> isPost()) {
            $params = input('param.');
            $model = new RoleModel();
            $rs = $model -> strict(false) -> insert($params);
            if (!$rs) {
                return api ::error('添加失败');
            }
            return api ::success('添加成功');
        }
        return $this -> fetch();
    }

    // 编辑
    public function edit() {
        $params = input('param.');
        $model = new RoleModel();
        if (request() -> isPost()) {
            $rs = $model -> where('id', $params['id']) -> update($params);
            if (!$rs) {
                return api ::error('修改失败');
            }
            return api ::success('修改成功');
        }
        $this -> assign([
            'info' => $model -> get($params['id'])
        ]);
        return $this -> fetch();
    }

    // 删除
    public function del() {
        $id = input('param.id');
        $rs = RoleModel::destroy($id);
        if (!$rs) {
            return api ::error('删除失败');
        }
        return api ::success('删除成功');
    }

    // 分配权限
    public function giveAccess() {
        $param = input('param.');
        $node = new NodeModel();
        // 获取现在的权限
        if ($param['type'] == 'get') {
            $nodeStr = $node -> getNodeInfo($param['id']);
            return api ::success('ok', $nodeStr);
        }
        // 分配新权限
        if ($param['type'] == 'give') {
            $model = new RoleModel();
            $rs = $model -> where("id", $param['id']) -> setField("rule", $param['rule']);
            if (!$rs) {
                return api ::error('分配权限失败');
            }
            return api ::success('分配权限成功');
        }
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id) {
        return [
            '编辑' => [
                'auth' => 'role/edit',
                'href' => url('role/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'role/del',
                'href' => "javascript:del(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ],
            '分配权限' => [
                'auth' => 'role/giveaccess',
                'href' => "javascript:giveQx(" . $id . ")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-institution'
            ],
        ];
    }
}
