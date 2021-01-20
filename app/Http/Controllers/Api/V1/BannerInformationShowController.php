<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerInformationShow;

class BannerInformationShowController extends Controller
{
    public function index()
    {
        $bannerInformationShowOne = BannerInformationShow::where('is_display',1)->where('type','one')->orderBy('sort','desc')->get();
        $bannerInformationShowTwo = BannerInformationShow::where('is_display',1)->where('type','two')->orderBy('sort','desc')->get();

        return $this->responseStyle('ok',200,[
            'one'=>$bannerInformationShowOne,
            'two'=>$bannerInformationShowTwo,
        ]);
    }
}
