<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model {

    public function getRole() {
        return $this -> hasOne("Role", "id", "role_id");
    }

}