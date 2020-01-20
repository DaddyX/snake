<?php

namespace app\admin\controller;

use app\admin\model\Node as NodeModel;
use app\common\controller\Backend;

class Index extends Backend {

    // 系统首页
    public function index() {
        $node = new NodeModel();
        $this -> assign([
            'menu' => $node -> getMenu(session('rule'))
        ]);
        return $this -> fetch('/index');
    }

    // 控制台首页
    public function main() {
        return $this -> fetch();
    }
}
