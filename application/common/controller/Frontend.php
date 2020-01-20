<?php

namespace app\common\controller;

use think\Controller;

class Frontend extends Controller {

    public $params = null;

    public function _initialize() {
        header("Access-Control-Allow-Origin:*");


    }

    public function wsSuccess($MessageNo = null, $MessageType = null, $MessageData = null) {
        $return = [
            'MessageNo' => $MessageNo,
            'MessageType' => $MessageType,
            'MessageData' => $MessageData
        ];
        return json_encode($return);
    }


}