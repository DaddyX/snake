<?php

namespace app\admin\controller;

use app\api;
use app\admin\model\Email as EmailModel;
use app\common\controller\Backend;

class Email extends Backend {

    public function index() {
        if (request() -> isAjax()) {
            $params = input("param.", "", "trim");
            $query = EmailModel ::order('id desc');
            if (!empty($params['keyword'])) {
                $query -> where('email', 'like', '%' . $params['keyword'] . '%');
            }
            $paginate = $query -> paginate($params['limit'], false, ['page' => $params['page']]);
            // 拼装参数
            foreach ($paginate as $key => &$value) {
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

            $model = new EmailModel();
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
        $model = new EmailModel();
        if (request() -> isPost()) {
            $rs = $model -> where('id', $params['id']) -> update($params);
            if (!$rs) {
                return api ::error('修改失败');
            }
            return api ::success('修改成功');
        }
        $this -> assign([
            'info' => $model -> get($params['id']),
        ]);
        return $this -> fetch();
    }

    // 删除
    public function del() {
        $id = input('param.id');
        $rs = EmailModel ::destroy($id);
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
                'auth' => 'email/edit',
                'href' => url('email/edit', ['id' => $id]),
                'style' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'email/del',
                'href' => "javascript:del(" . $id . ")",
                'style' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
