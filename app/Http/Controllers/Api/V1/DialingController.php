<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\DialingRequest;
use App\Model\Dialing;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use App\Model\ShopComment;

class DialingController extends Controller
{
    // 我的拨号
    public function index()
    {
        if(request('type')=='shop') {
            $res = auth('api')->user()->dialing()->orderBy('created_at','desc')->paginate();
//            foreach ($res as $k=>$v) {
//                $shop = Shop::where('id',$v->model_id)->first();
////                return $shop->id;
//                $res[$k]->shop=$shop;
//                // 几公里
//                $lat = \request('lat');
//                $lng = \request('lng');
//                if ($lat&&$lng) {
//                    $range = $this->getDistance($lat,$lng,$lat1=$shop->lat,$lng1=$shop->lng);
//                }else {
//                    $range = '未知';
//                }
//                $res[$k]->range=$range;
//                // 平均星级
//                $shopId = $shop->id;
//                $res[$k]->favoriteShopStarSvg = number_format(ShopComment::where('shop_id',$shopId)->avg('star'),1);
//            }// ShopComment::where('shop_id',$shopId)->avg('star'),1

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
