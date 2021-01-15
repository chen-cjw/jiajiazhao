<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Model\AbbrCategory;
use App\Model\AbbrTwoCategory;
use App\Model\Banner;
use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\Model\Notice;
use App\Model\Shop;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // 轮播图
        $bannerOne = Banner::where('type','index_one')->where('is_display',1)->orderBy('sort','desc')->get();
        $bannerTwo = Banner::where('type','index_two')->where('is_display',1)->orderBy('sort','desc')->get();

        // 公告
        $notice = Notice::where('is_display',1)->orderBy('sort','desc')->get();

        // 商户
        $shopOne = AbbrCategory::where('parent_id',null)->where('local','one')->orderBy('sort','desc')->take(10)->get();
        $shopTwo = AbbrCategory::where('parent_id',null)->where('local','two')->orderBy('sort','desc')->take(7)->get();

        // 帖子分类
        $cardCategory = CardCategory::where('is_display',1)->orderBy('sort','desc')->get();
        return [
            'code'=>200,
            'msg'=>'ok',
            'data' => [
                'bannerOne'=>$bannerOne,
                'bannerTwo'=>$bannerTwo,
                'notice'=>$notice,
                'shopOne'=>$shopOne,
                'shopTwo'=>$shopTwo,
                'cardCategory'=>$cardCategory,
            ]
        ];
    }
}
