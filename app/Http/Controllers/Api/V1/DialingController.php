<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\DialingRequest;
use App\Model\Dialing;

class DialingController extends Controller
{
    // 我的拨号
    public function index()
    {
        $res = auth('api')->user()->dialing()->orderBy('created_at','desc')->paginate();
        return $this->responseStyle('ok',200,$res);
    }

    public function store(DialingRequest $request)
    {
        $res = Dialing::create([
            'phone'=>$request->phone,
            'user_id'=>auth('api')->id()
        ]);
        return $this->responseStyle('ok',200,$res);
    }

}
