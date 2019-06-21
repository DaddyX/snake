<?php

namespace app;

class api {
    /**
     * 通用错误代码定义
     */
    const OK = 200;             // 响应成功
    const ERROR = 400;          // 响应错误
    const PARAM_ERROR = 401;    // 校验错误
    const AUTH_ERROR = 403;     // 权限错误
    const SERVER_ERROR = 500;   // 内部错误

    /**
     * API 响应数据 (JSON格式)
     *
     * @param  integer $code 响应代码
     * @param  string $message 响应消息
     * @param  mixed $data 响应数据
     * @return mixed
     */
    public static function response($code, $message = null, $data = null) {
        header('Content-Type:application/json; charset=utf-8');
        $result = [
            'code' => (int)$code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time(),
        ];
        return $result;
    }

    /**
     * 响应错误信息
     *
     * @param  string $message
     * @param  array $data
     * @return string
     */
    public static function error($message = null, $data = null) {
        return self::response(self::ERROR, $message, $data);
    }

    /**
     * 响应成功信息
     *
     * @param  string $message
     * @param  array $data
     * @return string
     */
    public static function success($message = null, $data = null) {
        return self::response(self::OK, $message, $data);
    }

    /**
     * 数据秘钥
     */
    const IV = "12345678901234567890";
    const KEY = '20190101000000';

    /**
     * 解密字符串
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    function decrypt($data, $key = self::KEY, $iv = self::IV) {
        return openssl_decrypt(base64_decode($data), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 加密字符串
     *
     * @param $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    function encrypt($data, $key = self::KEY, $iv = self::IV) {
        return base64_encode(openssl_encrypt($data, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv));
    }
}
