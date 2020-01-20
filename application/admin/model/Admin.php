<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model {

    public $statusArr = [
        '1' => '正常',
        '2' => '禁用'
    ];

    public function getRole() {
        return $this -> hasOne("Role", "id", "role_id");
    }

}