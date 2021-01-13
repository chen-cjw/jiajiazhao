<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Model\AbbrCategory;
use App\Model\Banner;
use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\Model\Notice;
use App\Model\Shop;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        // 轮播图
        $bannerOne = Banner::where('type','index_one')->where('is_display',1)->orderBy('sort','desc')->get();
        $bannerTwo = Banner::where('type','index_two')->where('is_display',1)->orderBy('sort','desc')->get();

        // 公告
        $notice = Notice::where('is_display',1)->orderBy('sort','desc')->get();

        // 商户
        $abbrCategory = AbbrCategory::where('parent_id',null)->orderBy('sort','desc')->get();
        $shopOne = Shop::where('type','one')->where('is_accept',1)->get();
        $shopTwo = Shop::where('type','two')->where('is_accept',1)->get();

        // 帖子分类
        $cardCategory = CardCategory::orderBy('sort','desc')->get();
//        $cardIdDefault = \request('card_id')?:1;
//        $cardIdDefault = CardCategory::orderBy('sort','desc')->first();
//        $cardId = $request->card_id ?: $cardIdDefault->id;
//        $convenientInformation = ConvenientInformation::where('card_id',$cardId)->paginate();
        return [
            'code'=>200,
            'msg'=>'ok',
            'data' => [
                'abbrCategory'=>$abbrCategory,
                'bannerOne'=>$bannerOne,
                'bannerTwo'=>$bannerTwo,
                'notice'=>$notice,
                'shopOne'=>$shopOne,
                'shopTwo'=>$shopTwo,
                'cardCategory'=>$cardCategory,
//                'convenientInformation'=>$convenientInformation,
            ]
        ];
    }
}
