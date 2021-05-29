<?php

namespace App\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Model\CityPartner;
use Illuminate\Http\Request;

class CityPartnerController extends Controller
{

    /***
     * 第二次开发
     * 城市合伙人
     **/
    // 合伙人中心 = 今日收益+累计收益
    public function index()
    {

    }

    // 合伙人入住
    public function store(Request $request)
    {

        $res = CityPartner::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'IDCard'=>$request->IDCard,
            'in_city'=>$request->in_city,
            'user_id'=>auth('api')->id(),
            'agree'=>$request->agree, // 必须是同意
        ]);

        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }
    // -- todo 下面的功能是支付过后，可以看见的数据
    // 提现记录-- 合伙人的提现记录
    public function withdrawIndex()
    {
        
    }

    // 商户抽成 -- 合伙人的抽成
    public function shopIndex()
    {

    }

    // 发帖抽成 -- 合伙人的抽成
    public function informationIndex()
    {

    }

    // todo 交易流水抽成 == 商户商城抽成 目前未开放
    public function shopping()
    {

    }


}
