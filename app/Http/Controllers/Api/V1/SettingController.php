<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // 配置参数

    public function index()
    {
        $res = Setting::get();
        $data = [];
        foreach ($res as $re) {
            $data[$re->key] = $re->value;
        }
        return $this->responseStyle('ok',200,$data);
    }
}
