<?php

namespace app\common\controller;

use think\Controller;

class ApiBase extends Controller {

    public $params = null;

    public function _initialize() {
        $this -> params = input("param.", "", "trim");
    }

    public function ajaxSuccess($message, $data = null, $code = 200) {
        return $this -> ajaxResponse($message, $data, $code);
    }

    public function ajaxError($message, $data = null, $code = 400) {
        return $this -> ajaxResponse($message, $data, $code);
    }

    public function ajaxResponse($message, $data = null, $code) {
        return json([
            'code' => (int)$code,
            'message' => $message,
            'data' => $data
        ]);
    }

    // 微信验证签名方法
    public function checkSignature($params) {
        $signature = $params["signature"] ?? "";
        $timestamp = $params["timestamp"] ?? "";
        $nonce = $params["nonce"] ?? "";

        $token = "lovesky";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

}