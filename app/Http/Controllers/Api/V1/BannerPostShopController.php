<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\BannerPostShop;
use Illuminate\Support\Facades\Log;

class BannerPostShopController extends Controller
{
    public function index()
    {
        Log::info(123);
        Log::info(request('area'));
        Log::info(123);
        $resQuery = BannerPostShop::where('is_display',1)->orderBy('sort','desc');
        if (request('area')) {
            $resQuery = $resQuery->where(function ($query) {
                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        $res = $resQuery->get();
        return $this->responseStyle('ok',200,$res);
    }
}
