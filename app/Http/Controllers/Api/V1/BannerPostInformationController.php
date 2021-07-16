<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerPostInformation;

class BannerPostInformationController extends Controller
{
    public function index()
    {
        $resQuery = BannerPostInformation::where('is_display',1)->orderBy('sort','desc');
        if (request('area')) {
            $resQuery = $resQuery->where(function ($query) {
                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        $res = $resQuery->get();
        return $this->responseStyle('ok',200,$res);
    }
}
