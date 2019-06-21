<?php

namespace app\admin\controller;

use app\api;
use think\Controller;

class Base extends Controller {

    public function _initialize() {
        if (!session('username')) {

            $loginUrl = url('login/index');
            if (request() -> isAjax()) {
                return api ::response(api::SERVER_ERROR, '登录超时');
            }
            $this -> redirect($loginUrl);
        }

        // 检测权限
        $control = lcfirst(request() -> controller());
        $action = lcfirst(request() -> action());

        if (authCheck($control . '/' . $action) == false && session("id") != 1) {
            if (request() -> isAjax()) {
                return api ::response(api::AUTH_ERROR, '您没有权限');
            }
            $this -> error('403 您没有权限');
        }

        $this -> assign([
            'username' => session('username'),
            'rolename' => session('role')
        ]);
    }

}