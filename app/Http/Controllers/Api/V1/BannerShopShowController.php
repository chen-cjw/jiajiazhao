<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BannerShopShow;

class BannerShopShowController extends Controller
{
    public function index()
    {
        $resQuery = BannerShopShow::where('is_display',1);
        if (request('area')) {
            $resQuery = $resQuery->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);

//                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        $res = $resQuery->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,$res);
    }
}
