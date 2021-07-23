<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerLocal;

class BannerLocalController extends Controller
{
    public function index()
    {
        $resQuery = BannerLocal::where('is_display',1)->orderBy('sort','desc');
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
