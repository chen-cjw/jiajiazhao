<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Model\Shop\OwnCouponCode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponCodesController extends Controller
{
    // 我的优惠券
    public function index()
    {

    }
    // 优惠券检测
    public function show($code)
    {
        // 我是否拥有优惠券
        
        // 判断优惠券是否存在
        if (!$record = OwnCouponCode::where('code', $code)->first()) {
            return $this->responseStyle('ok',404,['msg' => '优惠券不存在']);
        }

        $record->checkAvailable();

        return $record;
    }
}
