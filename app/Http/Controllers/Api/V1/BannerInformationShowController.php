<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerInformation;
use App\Model\BannerInformationShow;
use App\Model\ConvenientInformation;

class BannerInformationShowController extends Controller
{
    public function index()
    {
        // 同类推荐
        $cardId = ConvenientInformation::where('id',request('card_id'))->value('card_id');
        $bannerInformationShowOne = ConvenientInformation::where('is_display',1)->where('card_id',$cardId)->orderBy('sort','desc')->whereNotNull('paid_at')->take(5)->get();//->where('title','like','%'.request('title').'%')

        //        $bannerInformationShowOne = BannerInformationShow::where('is_display',1)->where('type','one')->orderBy('sort','desc')->get();
        $bannerInformationShowTwo = BannerInformationShow::where('is_display',1)->where('type','two')->orderBy('sort','desc')->get();

        return $this->responseStyle('ok',200,[
            'one'=>$bannerInformationShowOne,
            'two'=>$bannerInformationShowTwo,
        ]);
    }
}
