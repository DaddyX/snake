<?php

namespace app\api\controller\v1;

use app\api;
use app\common\controller\ApiBase;

class Test extends ApiBase {

    public function index() {
        return api ::success('ok', ['version' => 'v1']);
    }

}
