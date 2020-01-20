<?php

namespace app\admin\controller;

use app\api;
use app\admin\model\Articles as ArticlesModel;
use app\common\controller\Backend;

class Articles extends Backend {

    public function index() {
        if (request() -> isAjax()) {
            $params = input("param.", "", "trim");
            $query = ArticlesModel ::order('id desc');
            if (!empty($params['keyword'])) {
                $query -> where('title', 'like', '%' . $params['keyword'] . '%');
            }
            $paginate = $query -> paginate($params['limit'], false, ['page' => $params['page']]);
            // 拼装参数
            foreach ($paginate as $key => &$value) {
                $value['thumbnail'] = '<img src="' . $value['thumbnail'] . '" width="40px" height="40px">';
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

            unset($params['file']);
            $params['add_time'] = date('Y-m-d H:i:s');

            $model = new ArticlesModel();
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
        $model = new ArticlesModel();
        if (request() -> isPost()) {
            unset($params['file']);
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
        $rs = ArticlesModel ::destroy($id);
        if (!$rs) {
            return api ::error('删除失败');
        }
        return api ::success('删除成功');
    }

    // 上传缩略图
    public function uploadImg() {
        if (request() -> isAjax()) {
            $file = request() -> file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file -> move(\think\facade\Env::get('root_path') . 'public/upload');
            if ($info) {
                $src = '/upload' . '/' . date('Ymd') . '/' . $info -> getFilename();
                return api ::success('ok', ['src' => $src]);
            } else {
                // 上传失败获取错误信息
                return api ::error($file -> getError());
            }
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
                'auth' => 'articles/edit',
                'href' => url('articles/edit', ['id' => $id]),
                'style' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'articles/del',
                'href' => "javascript:del(" . $id . ")",
                'style' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
