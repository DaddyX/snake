<?php

namespace app\api\controller\v1;

use app\api;
use app\common\controller\ApiBase;
use app\common\model\User;
use think\facade\Validate;
use think\Exception;

/**
 * 通行证
 */
class Passport extends ApiBase {

    /**
     * @api {post} /api/v1/passport/login 用户登录-账号密码
     * @apiGroup Login
     * @apiVersion 0.0.1
     *
     * @apiParam (请求参数) {String} username           用户名
     * @apiParam (请求参数) {String} password           密码
     *
     * @apiSuccess (成功返回) {String} uid 用户ID
     * @apiSuccess (成功返回) {String} token token
     * @apiSuccess (成功返回) {String} username 用户名
     * @apiSuccess (成功返回) {String} nickname 昵称
     * @apiSuccess (成功返回) {String} avatar 头像
     *
     * @apiSuccessExample Success-Response:
     * {
     * "code": 200,
     * "message": "ok",
     * "data": {
     * "uid": "",
     * "token": "",
     * "username": "",
     * "nickname": "",
     * "avatar": ""
     * },
     * "timestamp": 1558073610
     * }
     */
    public function login() {
        try {
            $username = $this -> params['username'];
            $password = $this -> params['password'];
            if (empty($username) || empty($password)) {
                throw new Exception("缺少用户名或者密码", api::PARAM_ERROR);
            }
            $user = new User();
            $userInfo = $user -> where("username", $username) -> where("status", 1) -> find();
            if (empty($userInfo)) {
                throw new Exception("您还没有注册", api::NOT_FOUND);
            }
            $password_salt = config("app.password_salt");
            $md5Pwd = md5($password . $password_salt);
            if ($userInfo['password'] != $md5Pwd) {
                throw new Exception("用户名或密码不正确", api::ERROR);
            }
            $now = time();
            $token_salt = config("app.token_salt");
            $token = md5($token_salt . $now);
            $update = [
                "update_time" => $now,
                "token" => $token
            ];
            $user -> where('id', $userInfo['id']) -> update($update);
            $result = [
                'uid' => $userInfo['id'],
                'token' => $token,
                'username' => $userInfo['username'],
                'nickname' => $userInfo['nickname'],
                'avatar' => $userInfo['avatar'],
            ];
        } catch (Exception $e) {
            return api ::response($e -> getCode(), $e -> getMessage());
        }
        return api ::success('ok', $result);
    }

    /**
     * @api {post} /api/v1/passport/register 用户注册-账号密码
     * @apiGroup Login
     * @apiVersion 0.0.1
     *
     * @apiParam (请求参数) {String} username           用户名
     * @apiParam (请求参数) {String} password           密码
     *
     * @apiSuccess (成功返回) {String} username 用户名
     *
     * @apiSuccessExample Success-Response:
     * {
     * "code": 200,
     * "message": "ok",
     * "data": {
     * "username": ""
     * },
     * "timestamp": 1558073610
     * }
     */
    public function register() {
        try {
            $username = $this -> params['username'];
            $password = $this -> params['password'];
            if (empty($username) || empty($password)) {
                throw new Exception("缺少用户名或者密码", api::PARAM_ERROR);
            }
            $user = new User();
            $userInfo = $user -> where("username", $username) -> find();
            if (!empty($userInfo)) {
                throw new Exception("该用户名已存在", api::PARAM_ERROR);
            }

            $token_salt = config("app.token_salt");
            $password_salt = config("app.password_salt");

            $md5Pwd = md5($password . $password_salt);
            $token = md5($token_salt . time());
            $nickname = "MID_" . randStr(8);
            $insert = [
                'username' => $username,
                'password' => $md5Pwd,
                'nickname' => $nickname,
                'token' => $token,
                'create_time' => time()
            ];
            $rs = $user -> insert($insert);
            if (!$rs) {
                throw new Exception("注册失败", api::ERROR);
            }
            $result = [
                'username' => $username
            ];
        } catch (Exception $e) {
            return api ::response($e -> getCode(), $e -> getMessage());
        }
        return api ::success('ok', $result);
    }

    /**
     * {
     * "code": 200,
     * "message": "ok",
     * "data": {
     * "uid": "",
     * "token": "",
     * "username": "",
     * "nickname": "",
     * "avatar": ""
     * "device_no": ""
     * },
     * "timestamp": 1558073610
     * }
     */
    public function oAuthLogin() {
        $params = $this -> params;
        try {
            $rule = [
                'login_type' => 'require',
                'openid' => 'require',
            ];
            $validate = Validate ::make($rule);
            if (!$validate -> check($params)) {
                throw new Exception($validate -> getError(), api::PARAM_ERROR);
            }
            $now = time();
            $token_salt = config("app.token_salt");
            $token = md5($token_salt . $now);
            switch (strtolower($params['login_type'])) {
                case 'qq':
                    $field = "qq_openid";
                    break;
                case 'wx':
                    $field = "wx_openid";
                    break;
                default:
                    throw new Exception("参数校验失败", api::PARAM_ERROR);
            }
            $user = new User();
            $userInfo = $user -> where($field, $params['openid']) -> find();
            if (empty($userInfo)) {
                $expiration_time = strtotime("2020-01-01");
                $insert = [
                    $field => $params['openid'],
                    'nickname' => $params['nickname'],
                    'avatar' => $params['avatar'],
                    'sex' => $params['sex'],
                    'token' => $token,
                    'expiration_time' => $expiration_time,
                    'create_time' => $now,
                    'last_time' => $now,
                ];
                $uid = $user -> insertGetId($insert);
                $result = [
                    'uid' => $uid,
                    'token' => $token,
                    'nickname' => $params['nickname'],
                    'avatar' => $params['avatar'],
                    'username' => "",
                    'device_no' => "",
                ];
            } else {
                $update = [
                    "last_time" => $now,
                    "token" => $token
                ];
                $user -> where('id', $userInfo['id']) -> update($update);
                $result = [
                    'uid' => $userInfo['id'],
                    'token' => $token,
                    'nickname' => $userInfo['nickname'],
                    'avatar' => $userInfo['avatar'],
                    'username' => $userInfo['username'],
                    'device_no' => $userInfo['device_no'],
                ];
            }
        } catch (Exception $e) {
            return api ::response($e -> getCode(), $e -> getMessage());
        }
        return api ::success('ok', $result);
    }


}
