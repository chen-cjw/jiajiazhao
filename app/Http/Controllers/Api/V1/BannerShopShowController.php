<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BannerShopShow;

class BannerShopShowController extends Controller
{
    public function index()
    {
        $resQuery = BannerShopShow::where('is_display',1)->orderBy('sort','desc');
        if (request('area')) {
            $resQuery = $resQuery->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);

//                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        $res = $resQuery->get();
        return $this->responseStyle('ok',200,$res);
    }
}
