<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\DialingRequest;
use App\Model\Dialing;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use App\Model\ShopComment;
use Illuminate\Http\Request;

class DialingController extends Controller
{
    // 我的拨号
    public function index()
    {
        if(request('type')=='shop') {
            $res = auth('api')->user()->dialing()->where('model_type',Shop::class)->orderBy('created_at','desc')->paginate();
        }
        if(request('type')=='local') {
            $res = auth('api')->user()->dialing()->where('model_type',LocalCarpooling::class)->orderBy('created_at','desc')->paginate();
        }
        return $this->responseStyle('ok',200,$res);
    }

    public function store(DialingRequest $request)
    {
        $diaing = Dialing::where('model_type',$request->type=='shop'?Shop::class:LocalCarpooling::class)->where('model_id',$request->id)->where('user_id',auth('api')->id());
        // 商户/拼车两部分
        if ($diaing->first()) {
            $diaing->update([
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
        }else {
            $diaing = Dialing::create([
                'phone' => $request->phone,
                'user_id' => auth('api')->id(),
                'model_type' => $request->type == 'shop' ? Shop::class : LocalCarpooling::class,
                'model_id' => $request->id
            ]);
        }
        return $this->responseStyle('ok',200,$diaing->first());
    }
    // 浏览管理
    public function delete(Request $request)
    {
        if(!$request->ids) {
            return $this->responseStyle('ok',200,[]);
        }
        foreach($request->ids as $v){
            $res = Dialing::where('id',$v)->where('user_id',auth('api')->id())->delete();
        }
        return $this->responseStyle('ok',200,$res);
    }
}
