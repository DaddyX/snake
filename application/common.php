<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function httpGet($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

//参数1：访问的URL，参数2：post数据 ，参数3：是否文件上传
function httpPost($url, $param, $post_file = false) {
    $oCurl = curl_init();
    if (stripos($url, "https://") !== false) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (PHP_VERSION_ID >= 50500 && class_exists('\CURLFile')) {
        $is_curlFile = true;
    } else {
        $is_curlFile = false;
        if (defined('CURLOPT_SAFE_UPLOAD')) {
            curl_setopt($oCurl, CURLOPT_SAFE_UPLOAD, false);
        }
    }
    if (is_string($param)) {
        $strPOST = $param;
    } elseif ($post_file) {
        if ($is_curlFile) {
            foreach ($param as $key => $val) {
                if (substr($val, 0, 1) == '@') {
                    $param[$key] = new \CURLFile(realpath(substr($val, 1)));
                }
            }
        }
        $strPOST = $param;
    } else {
        $aPOST = [];
        foreach ($param as $key => $val) {
            $aPOST[] = $key . "=" . urlencode($val);
        }
        $strPOST = join("&", $aPOST);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}

/**
 * PHP生成随机字符串
 * @param int $length
 * @return string
 */
function randStr($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $strLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $strLength - 1)];
    }
    return $randomString;
}

/**
 * 生成订单号
 * @param $id
 * @return string
 */
function makeOrderNo($id = 0){
    if($id > 0) {
        // 14位
        $orderSn = '1' . substr(time(), -8) . substr(microtime(), 2, 3) . str_pad($id, 2, "0", STR_PAD_LEFT);
        return $orderSn;
    }
    // 16位
    $orderSn = intval(date('Y') - 2000) . date('mdHi') . substr(microtime(), 2, 3) . sprintf('%03d', rand(0, 999));
    // 带字母16位
    // $orderSn = $yCode[intval(date('Y') - 2020)] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}

/**
 * 格式化时间戳
 * @param $timestamp
 * @return string
 */
function dateString($timestamp) {
    return $timestamp == 0 ? '' : date("Y-m-d H:i:s", $timestamp);
}