<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerPostInformation;

class BannerPostInformationController extends Controller
{
    public function index()
    {
        $res = BannerPostInformation::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,$res);
    }
}
