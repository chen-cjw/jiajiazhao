<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Api\V1\Controller;
use App\Model\Shop\OwnBannerIndex;
use Illuminate\Http\Request;

class OwnBannerIndexController extends Controller
{
    // 商城首页轮播图
    public function index()
    {
        $resQuery = OwnBannerIndex::where('is_display',1)->orderBy('sort','desc');
        if (request('area')) {
            $resQuery = $resQuery->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);
            });
        }
        $res = $resQuery->get();
        return $this->responseStyle('ok',200,$res);
    }
}
