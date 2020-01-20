<?php
namespace app\admin\controller;

use app\api;
use think\Controller;
use think\captcha\Captcha;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Role as RoleModel;
use app\common\controller\Backend;

class Login extends Controller {

    // 登录页面
    public function index() {
        return $this -> fetch('/login');
    }

    // 登录操作
    public function doLogin() {
        $params = input("param.");

        $result = $this -> validate($params, [
            'username|用户名' => 'require',
            'password|密码' => 'require',
            'captcha|验证码' => 'require|captcha'
        ]);
        if ($result !== true) {
            return api::error($result);
        }

        $hasUser = AdminModel::where('username', $params['username']) -> find();
        if (empty($hasUser)) {
            return api::error('管理员不存在');
        }

        if (md5($params['password']) != $hasUser['password']) {
            return api::error('密码错误');
        }

        if ($hasUser['status'] != 1) {
            return api::error('该账号被禁用');
        }

        // 获取该管理员的角色信息
        $roleModel = new RoleModel();
        $info = $roleModel -> getRoleInfo($hasUser['role_id']);

        session('username', $params['username']);
        session('aid', $hasUser['id']);
        session('role', $info['name']);  // 角色名
        session('rule', $info['rule']);  // 角色节点
        session('action', $info['action']);  // 角色权限

        // 更新管理员状态
        $update = [
            'login_times' => $hasUser['login_times'] + 1,
            'last_login_ip' => request() -> ip(),
            'last_login_time' => time()
        ];
        $rs = AdminModel::where("id", $hasUser['id']) -> update($update);
        if (!$rs) {
            return api::error('更新登录信息失败');
        }
        return api::success('登录成功', url('index/index'));
    }

    // 退出操作
    public function loginOut() {
        session('username', null);
        session('aid', null);
        session('role', null);  // 角色名
        session('rule', null);  // 角色节点
        session('action', null);  // 角色权限
        $this -> redirect(url('index'));
    }

    // 验证码
    public function captcha() {
        $captcha = new Captcha([
            'fontSize' => 40,
            'length' => 4
        ]);
        return $captcha -> entry();
    }
}