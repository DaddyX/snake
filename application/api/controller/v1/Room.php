<?php

namespace app\api\controller\v1;

use app\api;
use app\common\controller\ApiBase;
use GatewayWorker\Lib\Gateway;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamOnlineListRequest;
use think\Exception;
use think\facade\Request;

/**
 * 房间相关
 */
class Room extends ApiBase {

    /**
     * @api {post} /api/v1/room/all 获取房间列表{所有}
     * @apiGroup Room
     * @apiVersion 0.0.1
     *
     * @apiUse SUCCESS
     */
    public function all() {

        return api ::success('ok');
    }


    /**
     * @api {post} /api/v1/room/lives 获取房间列表{正在直播}
     * @apiGroup Room
     * @apiVersion 0.0.1
     *
     * @apiUse SUCCESS
     */
    public function lives() {
        try {

            $cred = new Credential("", "");
            $httpProfile = new HttpProfile();
            $httpProfile -> setEndpoint("live.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile -> setHttpProfile($httpProfile);
            $client = new LiveClient($cred, "", $clientProfile);

            $req = new DescribeLiveStreamOnlineListRequest();

            $params = '{}';
            $req -> fromJsonString($params);

            $resp = $client -> DescribeLiveStreamOnlineList($req);

            print_r($resp -> toJsonString());
        } catch (TencentCloudSDKException $e) {
            echo $e;
        }
    }

    /**
     * @api              {post}  /api/v1/room/info  获取房间信息
     * @apiGroup         Room
     *
     * @apiParam (请求参数) {String} uid           用户ID
     * @apiParam (请求参数) {String} room_id       房间号
     *
     * @apiSuccess (成功返回) {String} room_no 房间号
     * @apiSuccess (成功返回) {String} title 房间标题
     * @apiSuccess (成功返回) {String} notice 房间公告
     * @apiSuccess (成功返回) anchor 主播信息
     * @apiSuccess (成功返回) {String} anchor.id 主播信息
     * @apiSuccess (成功返回) {String} anchor.username 主播用户名
     * @apiSuccess (成功返回) {String} anchor.nickname 主播昵称
     * @apiSuccess (成功返回) {String} anchor.avatar 主播头像
     *
     * @apiSuccessExample 正常响应示例
     * {"code":200,"message":"ok","data":{"room_no":"888888","title":"官方直播间","notice":"欢迎大家来到我的直播间","anchor":{"id":1,"username":"","nickname":"亚投No1","avatar":"http:\/\/5b0988e595225.cdn.sohucs.com\/images\/20190829\/62abb0c560ac42ccba40a50d79f99830.jpeg"}},"timestamp":1572589072}
     */
    public function info() {

    }

    /**
     * @api              {post}  /api/v1/room/enter  进入直播间
     * @apiGroup         Room
     *
     * @apiParam (请求参数) {String} uid           用户ID
     * @apiParam (请求参数) {String} room_id       房间号
     * @apiParam (请求参数) {String} client_id     客户端ID
     *
     * @apiUse SUCCESS
     */

    public function enter() {
        $uid = input("param.uid", "", "trim");
        $room_id = input("param.room_id", "", "trim");
        $client_id = input("param.client_id", "", "trim");

        try {
            $userInfo = \app\common\model\User ::where("id", $uid) -> field("id,username,nickname,avatar") -> find();
            if(empty($userInfo)) {
                // 获取用户数据失败
//                throw new Exception("获取用户数据失败", api::ERROR);
            }

            $roomInfo = \app\common\model\Room ::with("anchor") -> where("room_no", $room_id) -> field("uid,room_no,title,notice") -> find();
            if(empty($roomInfo)) {
                // 获取房间数据失败
                throw new Exception("获取房间数据失败", api::ERROR);
            }

            // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
            Gateway::$registerAddress = '118.24.220.176:1238';

            // 设置Gateway的Session
            // Gateway::setSession($client_id, ['nickname' => $userInfo['nickname'], 'room_id' => $room_id]);

            // client_id与uid绑定
            Gateway::bindUid($client_id, $uid);
            // 加入某个群组（可调用多次加入多个群组）
            Gateway::joinGroup($client_id, $room_id);

            // 房间在线用户
            $uidList = Gateway::getUidListByGroup($room_id);
            $online_num = count($uidList);
            $online_users = \app\common\model\User ::where("id", "IN", $uidList) -> field("id,nickname,avatar") -> select();

            $push = [
                'MessageNo' => 2001,
                'MessageTime' => time(),
                'MessageData' => [
                    'online_num' => $online_num,
                    'online_users' => $online_users,
                    'nickname' => $userInfo['nickname']
                ]
            ];

            // 推送数据到群组
            Gateway::sendToGroup($room_id, json_encode($push));

        } catch (Exception $e) {
            return api ::response($e -> getCode(), $e -> getMessage());
        }
        return api ::success('ok');

        /**
         * @api              {通知码} 2001 2001-进入房间通知
         * @apiGroup         Websocket
         *
         * @apiSuccess (响应参数) {string}  online_num 在线人数
         * @apiSuccess (响应参数) {[]}  online_users  在线用户
         * @apiSuccess (响应参数) {string}  online_users.id  在线用户ID
         * @apiSuccess (响应参数) {string}  online_users.nickname  在线用户昵称
         * @apiSuccess (响应参数) {string}  online_users.avatar 在线用户头像
         * @apiSuccess (响应参数) {string}  nickname  用户昵称
         *
         * @apiSuccessExample 正常响应示例
         *  {
         *      "MessageNo": 2001,
         *      "MessageTime": 154153689,
         *      "MessageData":{
         *          "nickname": "张三",
         *          "online_num": "45",
         *          "online_users": [
         *              "id": 1,
         *              "nickname": "四哥",
         *              "avatar": "http://img.baidu.com/tieba/44364946464.png",
         *          ]
         *      }
         * }
         */
    }

    /**
     * @api              {post}  /api/v1/room/chat  发送弹幕消息
     * @apiGroup         Room
     *
     * @apiParam (请求参数) {String} uid           用户ID
     * @apiParam (请求参数) {String} room_id       房间号
     * @apiParam (请求参数) {String} client_id     客户端ID
     * @apiParam (请求参数) {String} message       弹幕消息
     *
     * @apiUse SUCCESS
     */
    public function chat () {
        $uid = input("param.uid", "", "trim");
        $room_id = input("param.room_id", "", "trim");
        $client_id = input("param.client_id", "", "trim");
        $message = input("param.message", "", "trim");

        try {
            $userInfo = \app\common\model\User ::where("id", $uid) -> field("id,nickname,avatar") -> find();
            if(empty($userInfo)) {
                // 获取用户数据失败
                throw new Exception("获取用户数据失败", api::ERROR);
            }

            $roomInfo = \app\common\model\Room ::where("room_no", $room_id) -> find();
            if(empty($roomInfo)) {
                // 获取房间数据失败
                throw new Exception("获取房间数据失败", api::ERROR);
            }

            $push = [
                'MessageNo' => 2005,
                'MessageTime' => date("Y-m-d H:i:s"),
                'MessageData' => [
                    'from_uid' => $uid,
                    'from_name' => $userInfo['nickname'],
                    'message' => $message
                ]
            ];
            // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
            Gateway ::$registerAddress = '127.0.0.1:1238';

            // 推送数据到群组
            Gateway ::sendToGroup($room_id, json_encode($push));
        } catch (Exception $e) {
            return api ::response($e -> getCode(), $e -> getMessage());
        }
        return api ::success('ok');

        /**
         * @api              {通知码}  2005  2005-弹幕消息通知
         * @apiGroup         Websocket
         *
         * @apiSuccess (响应参数) {string}  from_uid 用户ID
         * @apiSuccess (响应参数) {string}  from_name 用户昵称
         * @apiSuccess (响应参数) {string}  message  弹幕消息
         *
         * @apiSuccessExample 正常响应示例
         *  {
         *      "MessageNo": 2005,
         *      "MessageTime": 154153689,
         *      "MessageData":{
         *          "from_name": "张三",
         *          "from_uid": "45",
         *          "message": "你好啊，靓仔"
         *      }
         * }
         */
    }


}
