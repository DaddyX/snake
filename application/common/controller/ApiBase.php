<?php

namespace app\common\controller;

use think\Controller;

class ApiBase extends Controller {

    public $params = null;

    public function _initialize() {
        header("Access-Control-Allow-Origin:*");

        $this->params = input("param.", "", "trim");

    }

}