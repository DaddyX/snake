<?php

namespace app\common\controller;

use app\api;
use think\Controller;
use think\exception\HttpResponseException;
use think\facade\Response;

class Backend extends Controller {

    public $aid = 0;

    public function _initialize() {
        if (!session('aid') || !session('username')) {

            if (request() -> isAjax()) {
                return api ::response(api::SERVER_ERROR, '登录超时');
            }
            $this -> redirect(url('login/index'));
        }

        $this -> aid = session('aid');

        // 检测权限
        $control = lcfirst(request() -> controller());
        $action = lcfirst(request() -> action());

        if (authCheck($control . '/' . $action) == false && $this -> aid != 1) {
            if (request() -> isAjax()) {
                $response = Response ::create(['code' => api::AUTH_ERROR, 'message' => '您没有权限', 'timestamp' => time()], 'json');
                throw new HttpResponseException($response);
//                return api ::response(api::AUTH_ERROR, '您没有权限');
            }
            $this -> error('403 您没有权限');
        }

        $this -> assign([
            'username' => session('username'),
            'rolename' => session('role')
        ]);
    }

}