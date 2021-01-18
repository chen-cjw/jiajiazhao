<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\PostTip;

class PostTipController extends Controller
{
    // 发帖提示
    public function index()
    {
        return $this->responseStyle('ok',200,PostTip::first());
    }
}
