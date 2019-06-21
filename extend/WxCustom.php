<?php

namespace wechat;

//小程序客服消息接口

use think\Exception;

class WxCustom {

    public $appid;
    public $secret;
    public $token;

    public function __construct($option = []) {
        $this -> appid = $option['appid'] ?? "";
        $this -> secret = $option['secret'] ?? "";
        $this -> token = $option['token'] ?? "";
    }

    public function valid($wxParams) {
        @file_put_contents(LOG_PATH . "contactLog.log", json_encode($wxParams, true) . "-01\n\n", FILE_APPEND);
            $ToUserName = $wxParams['ToUserName'];
            $FromUserName = $wxParams['FromUserName'];
            $CreateTime = $wxParams['CreateTime'];
            $MsgType = $wxParams['MsgType'];

            $sendParams = [];
            $sendParams['touser'] = $FromUserName;
            switch ($MsgType) {
                case "event"://进入会话事件
                    $sendParams['msgtype'] = 'link';
                    $sendParams['link']['title'] = urlencode('四维黑科技，关注前沿第一线');
                    $sendParams['link']['description'] = urlencode('每日为您精选有趣有料的前沿黑科技，每天涨点姿势！');
                    $sendParams['link']['url'] = 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzIxMjY1ODM3MA==#wechat_redirect';
                    $sendParams['link']['thumb_url'] = 'https://mp.weixin.qq.com/misc/getheadimg?token=1130380500&fakeid=3212658370&r=66098';
                    break;
                case "text"://接受文本消息
                    $Content = $wxParams['Content'];
                    if ($Content === '1') {
                        $sendParams['msgtype'] = 'link';
                        $sendParams['link']['title'] = urlencode('四维黑科技，关注前沿第一线');
                        $sendParams['link']['description'] = urlencode('每日为您精选有趣有料的前沿黑科技，每天涨点姿势！');
                        $sendParams['link']['url'] = 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzIxMjY1ODM3MA==#wechat_redirect';
                        $sendParams['link']['thumb_url'] = 'https://mp.weixin.qq.com/misc/getheadimg?token=1130380500&fakeid=3212658370&r=66098';
                    } else if ($Content === '2') {
                        $sendParams['msgtype'] = 'image';
                        $sendParams['image']['media_id'] = 'q3oQpLPWOIB50tCKdR510qeNIGEQd9A0Ku7DJJmsTsXVeQZqq0sUMK2gbUyJZsYn';
                        $sendParams['image']['picurl'] = '这是一张图片';
                    } else {
                        $sendParams['msgtype'] = 'text';
                        $sendParams['text']['content'] = urlencode('关注四维黑科技，每天涨点姿势');//urlencode 解决中文乱码问题
                    }
                    break;
                case "image"://接受图片消息

                    break;
                case "miniprogrampage"://接受小程序卡片

                    break;
            }
        return $sendParams;
//             if ($MsgType === 'event') {//进入客服窗口事件
//                $Event = $wxParams['Event'];
//                $SessionFrom = $wxParams['SessionFrom'];  //得到开发者在客服会话按钮设置的session-from属性
//
//                $media_id = $this -> getMedia_id($SessionFrom);
//
//                if ($Event == 'user_enter_tempsession') {
//                    $sendParams['touser'] = $FromUserName;
//
//                    if (!$media_id) {
//                        //红娘二维码不为空，发送二维码
//                        $sendParams['msgtype'] = 'image';
//                        $sendParams['image']['media_id'] = $media_id;
//                        //$sendParams['image']['media_id'] = 'D6SA5xGFDlrxspT2LovHD2gbMHrUjhcji7B6WUXZ2lG7rhWi4K8ExT0_6FF4uvJY';
//                        $sendParams['image']['picurl'] = 'this is image';
//                    } else {
//                        //红娘二维码为空，则发送文字
//                        $sendParams['msgtype'] = 'text';
//                        $sendParams['text']['content'] = urlencode('您好，请回复1获取我的微信');//urlencode 解决中文乱码问题
//                    }
//
//                    $this -> send($sendParams);
//                    exit;
//                }
//            }
    }

    //官方提供的验证demo
    public function checkSignature($signature, $timestamp, $nonce) {
        $token = $this -> token;
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

    //发送消息接口调用
    public function send($data) {
        if (cache("access_token")) {
            $access_token = cache("access_token");
        } else {
            // 获取access_token
            $url_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
            $result = json_decode(file_get_contents($url_token), true);
            if (!$result) {
                @file_put_contents(LOG_PATH . "contactLog.log",  "获取AccessToken失败:" . $result['errmsg'] . "-01\n\n", FILE_APPEND);
                exit("获取AccessToken失败:" . $result['errmsg']);
            }
            $access_token = $result['access_token'];
            $expire_time = $result['expire_time'];
            cache("access_token", $access_token, ['expire' => $expire_time]);
        }
        // 发送消息URL
        $url_send = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $data = urldecode(json_encode($data));
        return $this -> curlPost($url_send, $data);
    }

    //post发送json数据
    public function curlPost($url, $post_data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $res = curl_exec($ch);
        $ch_info = curl_getinfo($ch);
        curl_close($ch);
        if(intval($ch_info["http_code"]) == 200) {
            return $res;
        } else {
            return false;
        }
    }

//    //进入客服窗口，如果需要主动发起客服消息类型为图片，则需要获取media_id
//    public function getMedia_id($imageurl) {
//        $foldername = date('Y-m-d', time()); //定义文件夹目录
//        $path = __DIR__ . '/static/image/Code/' . $foldername . '/'; //服务器存放目录
//        if (!is_dir($path)) {
//            mkdir($path, 0777, true);
//        } else {
//            chmod($path, 0777);
//        }
//
//        //下载二维码到本地
//        $imageInfo = $this -> getImage($imageurl, $path);
//        $imageurl = $imageInfo['save_path'];
//
//        $post_url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$this->getAccessToken()}&type=image";
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $post_url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, ['media' => '@' . $imageurl]);
//        $res = curl_exec($ch);
//        $a = json_decode($res);
//
//        $media_id = $a -> media_id;
//        return $media_id;
//    }

//    //下载二维码
//    public function getImage($url, $save_dir, $filename = '', $type = 0) {
//        if (trim($url) == '') {
//            return array('file_name' => '', 'save_path' => '', 'error' => 1);
//        }
//        if (trim($filename) == '') {//保存文件名
//            $ext = strrchr($url, '.');
//            if ($ext != '.gif' && $ext != '.jpg') {
//                return array('file_name' => '', 'save_path' => '', 'error' => 3);
//            }
//            $filename = time() . $ext;
//        }
//        //获取远程文件所采用的方法
//        if ($type) {
//            $ch = curl_init();
//            $timeout = 5;
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//            $img = curl_exec($ch);
//            curl_close($ch);
//        } else {
//            ob_start();
//            readfile($url);
//            $img = ob_get_contents();
//            ob_end_clean();
//        }
//
//        //下载文件
//        $fp2 = @fopen($save_dir . $filename, 'a');
//        fwrite($fp2, $img);
//        fclose($fp2);
//        unset($img, $url);
//        return array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
//    }
}
