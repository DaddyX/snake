<?php

namespace app\admin\controller;

use app\admin\model\Node as NodeModel;

class Index extends Base {

    // 系统首页
    public function index() {
        $node = new NodeModel();
        $this -> assign([
            'menu' => $node -> getMenu(session('rule'))
        ]);
        return $this -> fetch('/index');
    }

    // 控制台首页
    public function indexPage() {
        return $this -> fetch('index');
    }
}
