<?php

namespace app\common\controller;

use app\api;
use think\Controller;
use think\exception\HttpResponseException;
use think\Response;

class Backend extends Controller {

    public $aid = 0;
    public $params = null;

    public function _initialize() {
        if (!session('aid') || !session('username')) {

            if (request() -> isAjax()) {
                return api ::response(api::SERVER_ERROR, '登录超时');
            }
            $this -> redirect(url('login/index'));
        }

        $this -> aid = session('aid');
        $this -> params = input('param.', "", "trim");

        // 检测权限
        $controller = lcfirst(request() -> controller());
        $action = lcfirst(request() -> action());

        if (authCheck($controller . '/' . $action) == false && $this -> aid != 1) {
            if (request() -> isAjax()) {
                $response = Response ::create(['code' => api::AUTH_ERROR, 'message' => '您没有权限', 'timestamp' => time()], 'json');
                throw new HttpResponseException($response);
            }
            $this -> error('403 您没有权限');
        }
        // 记录操作日志
        if(request() -> isAjax() && $controller != 'index') {
            $data = [
                'aid' => $this -> aid,
                'controller' => $controller,
                'action' => $action,
                'ip' => $this -> request -> ip(),
                'datetime' => date('Y-m-d H:i:s')
            ];
            if(!empty($this -> params)) $data['request'] = json_encode($this -> params, true);
            \think\Db ::name('admin_log') -> insert($data);
        }

        $this -> assign([
            'username' => session('username'),
            'rolename' => session('role')
        ]);
    }

}