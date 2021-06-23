<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\CityPartnerProfit;
use App\Model\Setting;
use Illuminate\Http\Request;

class CityPartnerProfitController extends Controller
{
    // 商户入住费 city_shop_fee
    // 便民发帖抽佣 information_fee
    // 商户交易流水 city_transition_flow_fee
    // 地接广告的 adv
    // 城市合伙人收益
    public function index()
    {
        $res = Setting::whereIn('key',['city_shop_fee','information_fee','city_transition_flow_fee','adv'])->get();
        return $this->responseStyle('ok',200,$res);

        $cityPartnerQuestion = CityPartnerProfit::orderBy('sort','desc')->first();
        return $this->responseStyle('ok',200,$cityPartnerQuestion);
    }
}
