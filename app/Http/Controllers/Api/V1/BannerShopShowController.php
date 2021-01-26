<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BannerShopShow;

class BannerShopShowController extends Controller
{
    public function index()
    {
        $res = BannerShopShow::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,$res);
    }
}
