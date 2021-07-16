<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\PartnerBanner;
use Illuminate\Http\Request;

class PartnerBannerController extends Controller
{
    /**
     * 城市合伙人下面的广告轮播图-- 地域轮播图
     **/
    public function index()
    {
        // where('area',$area)->where('area',null);
        if (\request('area')) {
            $res = PartnerBanner::where(function ($query) {
                $query->where('area',\request('area'))->orWhere('area',null);
            })->where('is_display',1)->orderBy('updated_at','desc')->get();
        }else {
            $res = PartnerBanner::where('is_display',1)->orWhere('area',null)->orderBy('updated_at','desc')->get();
        }

        return $this->responseStyle('ok',200,$res);
    }
}
