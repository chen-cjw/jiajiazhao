<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\DialingRequest;
use App\Model\Dialing;
use App\Model\LocalCarpooling;
use App\Model\Shop;

class DialingController extends Controller
{
    // 我的拨号
    public function index()
    {
        if(request('type')=='shop') {
            $res = auth('api')->user()->dialing()->with('shops')->orderBy('created_at','desc')->paginate();
        }
        if(request('type')=='local') {
            $res = auth('api')->user()->dialing()->orderBy('created_at','desc')->paginate();
        }
        return $this->responseStyle('ok',200,$res);
    }

    public function store(DialingRequest $request)
    {
        // 商户/拼车两部分
        $res = Dialing::create([
            'phone'=>$request->phone,
            'user_id'=>auth('api')->id(),
            'model_type'=>$request->type=='shop'?Shop::class:LocalCarpooling::class,
            'model_id'=>$request->id
        ]);
        return $this->responseStyle('ok',200,$res);
    }

}
