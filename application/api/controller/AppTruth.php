<?php

namespace app\api\controller;

use app\common\controller\ApiBase;
use app\common\model\TruthQuestion;
use app\common\model\TruthRecord;
use app\common\model\TruthRefer;

class AppTruth extends ApiBase {

    public $appid = "wxa16fcfa7e605fd28";
    public $secret = "b617be475c5e22917e1ebf0e149ecd92";

    // 题库列表
    public function questionList() {
        $params = $this -> params;
        if ($params['state'] != "V20181218" && $params['state'] != "loveksy") {
            $return['words'] = ["rows" => ["该小程序盗版自\n「男生女生真心话大冒险」\n请立即停止侵权并下线\n否则将采取法律措施维权\n合作QQ：450183439"], "total" => 1];
            $return['crazy'] = ["rows" => ["该小程序盗版自\n「男生女生真心话大冒险」\n请立即停止侵权并下线\n否则将采取法律措施维权\n合作QQ：450183439"], "total" => 1];
            return $this -> ajaxSuccess("ok", $return);
        }

        $words = TruthQuestion ::where("type", 1) -> column("content");
        $crazy = TruthQuestion ::where("type", 2) -> column("content");

        $return['words'] = ["rows" => $words, "total" => count($words)];
        $return['crazy'] = ["rows" => $crazy, "total" => count($crazy)];
        return $this -> ajaxSuccess("ok", $return);
    }

    // 提交题库
    public function submitQuestion() {
        $params = $this -> params;
        $model = new TruthRefer();
        $userInfo = json_decode($params['userInfo'], true);
        $params['nickname'] = $userInfo['nickName'] ?? "unknow";
        $params['gender'] = $userInfo['gender'] ?? "unknow";
        $params['create_time'] = time();
        $rs = $model -> save($params);
        return $this -> ajaxSuccess("ok", $rs);
    }

    // 分享记录
    public function shareRecord() {
        $params = $this -> params;
        $model = new TruthRecord();
        $userInfo = json_decode($params['userInfo'], true);
        $params['nickname'] = $userInfo['nickName'] ?? "unknow";
        $params['gender'] = $userInfo['gender'] ?? "unknow";
        $params['create_time'] = time();
        $rs = $model -> save($params);
        return $this -> ajaxSuccess("ok", $rs);
    }

    // 客服接口
    public function contact() {
        $params = $this -> params;
        // 验证签名
        $check = $this -> checkSignature($params);
        if (!$check) {
            exit("signature error");
        }
        //exit($params['echostr']); //第一次接入使用，用完注释

        $ToUserName = $params['ToUserName'];
        $FromUserName = $params['FromUserName'];
        $CreateTime = $params['CreateTime'];
        $MsgType = $params['MsgType'];

        $sendParams = [];
        $sendParams['touser'] = $FromUserName;
        switch ($MsgType) {
            case "event"://进入会话事件
                $sendParams['msgtype'] = 'link';
                $sendParams['link']['title'] = urlencode('四维黑科技，关注前沿第一线');
                $sendParams['link']['description'] = urlencode('每日为您精选有趣有料的前沿黑科技，每天涨点姿势！');
                $sendParams['link']['url'] = urlencode('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzIxMjY1ODM3MA==#wechat_redirect');
                $sendParams['link']['thumb_url'] = urlencode('https://mp.weixin.qq.com/misc/getheadimg?token=1130380500&fakeid=3212658370&r=66098');
                break;
            case "text"://接受文本消息
                $Content = $params['Content'];
                if ($Content == '0') {
                    $sendParams['msgtype'] = 'link';
                    $sendParams['link']['title'] = urlencode('四维黑科技，关注前沿第一线');
                    $sendParams['link']['description'] = urlencode('每日为您精选有趣有料的前沿黑科技，每天涨点姿势！');
                    $sendParams['link']['url'] = urlencode('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzIxMjY1ODM3MA==#wechat_redirect');
                    $sendParams['link']['thumb_url'] = urlencode('https://mp.weixin.qq.com/misc/getheadimg?token=1130380500&fakeid=3212658370&r=66098');
                } else if ($Content == '1') {
                    $sendParams['msgtype'] = 'link';
                    $sendParams['link']['title'] = urlencode('四维黑科技，关注前沿第一线');
                    $sendParams['link']['description'] = urlencode('每日为您精选有趣有料的前沿黑科技，每天涨点姿势！');
                    $sendParams['link']['url'] = urlencode('https://mp.weixin.qq.com/s/cs6FK59pCOKmaZasJ-d8lQ');
                    $sendParams['link']['thumb_url'] = urlencode('https://mp.weixin.qq.com/misc/getheadimg?token=1130380500&fakeid=3212658370&r=66098');
                } else if ($Content == '2') {
                    $sendParams['msgtype'] = 'image';
                    $sendParams['image']['media_id'] = 'q3oQpLPWOIB50tCKdR510qeNIGEQd9A0Ku7DJJmsTsXVeQZqq0sUMK2gbUyJZsYn';
                    $sendParams['image']['picurl'] = '这是一张图片';
                } else {
                    $sendParams['msgtype'] = 'text';
                    $sendParams['text']['content'] = urlencode('关注四维黑科技，每天涨点姿势');
                }
                break;
            case "image"://接受图片消息

                break;
            case "miniprogrampage"://接受小程序卡片

                break;
        }
        @file_put_contents(LOG_PATH . "contactLog.log", urldecode(json_encode($sendParams)) . "-02\n\n", FILE_APPEND);
        // 获取access_token
        if (cache("access_token")) {
            $access_token = cache("access_token");
        } else {
            $url_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
            $rs_token = json_decode(file_get_contents($url_token), true);
            if (empty($rs_token['access_token'])) {
                exit("获取AccessToken失败:" . $rs_token['errmsg']);
            }
            $access_token = $rs_token['access_token'];
            cache("access_token", $access_token, ['expire' => $rs_token['expires_in']]);
        }
        // 发送消息URL
        $url_send = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $rs = httpPost($url_send, urldecode(json_encode($sendParams)));
        @file_put_contents(LOG_PATH . "contactLog.log", json_encode($rs) . "-04\n\n", FILE_APPEND);
        exit("success");
    }

}
