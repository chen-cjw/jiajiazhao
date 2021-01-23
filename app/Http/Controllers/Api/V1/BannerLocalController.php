<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerLocal;

class BannerLocalController extends Controller
{
    public function index()
    {
        $res = BannerLocal::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,$res);
    }
}
