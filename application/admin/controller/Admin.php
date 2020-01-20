<?php

namespace app\admin\controller;

use app\api;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Role as RoleModel;
use app\common\controller\Backend;

class Admin extends Backend {

    public function index() {
        if (request() -> isAjax()) {
            $params = input("param.", "", "trim");
            $query = AdminModel::order('id desc');
            if (!empty($params['keyword'])) {
                $query -> where('username', 'like', '%' . $params['keyword'] . '%');
            }
            $paginate = $query -> paginate($params['limit'], false, ['page' => $params['page']]);
            // 拼装参数
            foreach ($paginate as $key => &$value) {
                $value['last_login_time'] = date('Y-m-d H:i:s', $value['last_login_time']);
                $value['status_name'] = $value->statusArr[$value['status']];
                $value['role_name'] = $value -> getRole -> name;
                unset($value -> role);

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
        $model = new AdminModel();
        if (request() -> isPost()) {
            $params = input('param.');
            $params['password'] = md5($params['password']);

            $rs = $model -> strict(false) -> insert($params);
            if (!$rs) {
                return api ::error('添加失败');
            }
            return api ::success('添加成功');
        }
        $this -> assign([
            'role' => RoleModel::select(),
            'status' => $model -> statusArr
        ]);
        return $this -> fetch();
    }

    // 编辑
    public function edit() {
        $params = input('param.');
        $model = new AdminModel();
        if (request() -> isPost()) {
            if (empty($params['password'])) {
                unset($params['password']);
            } else {
                $params['password'] = md5($params['password']);
            }
            $rs = $model -> where('id', $params['id']) -> update($params);
            if (!$rs) {
                return api ::error('修改失败');
            }
            return api ::success('修改成功');
        }
        $this -> assign([
            'info' => $model -> get($params['id']),
            'status' => $model -> statusArr,
            'role' => RoleModel::select()
        ]);
        return $this -> fetch();
    }

    // 删除
    public function del() {
        $id = input('param.id');
        $rs = AdminModel::destroy($id);
        if (!$rs) {
            return api ::error('删除失败');
        }
        return api ::success('删除成功');
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id) {
        return [
            '编辑' => [
                'auth' => 'admin/edit',
                'href' => url('admin/edit', ['id' => $id]),
                'style' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'admin/del',
                'href' => "javascript:del(" . $id . ")",
                'style' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
