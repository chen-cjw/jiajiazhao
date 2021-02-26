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
use Illuminate\Support\Facades\Log;

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
        $shopOne = AbbrCategory::where('parent_id',null)->where('is_display',1)->where('local','one')->orderBy('sort','desc')->get();//->take(15)
        $shopTwo = AbbrCategory::where('parent_id',null)->where('is_display',1)->where('local','two')->orderBy('sort','desc')->get();//->take(7)
        foreach ($shopOne as $k=>$v) {
            if (config('app.city') == 1) {
                $shopOne[$k]['is_value'] = 1;

            }else {
                $shopOne[$k]['is_value'] = Shop::where('one_abbr0',$v->id)->orWhere('one_abbr1',$v->id)->orWhere('one_abbr2',$v->id)->first() ? 1 : 0;
            }
        }
        foreach ($shopTwo as $k=>$v) {
            if($v->type != 'other') {
                if (config('app.city') == 1) {
                    $shopTwo[$k]['is_value'] = 1;//Shop::where('one_abbr0',$v->id)->first() ? 1 : 0;
                }else {
                    $shopTwo[$k]['is_value'] = Shop::where('one_abbr0',$v->id)->first() ? 1 : 0;
                }
               // Log::info($v->id.'/is_value'.$shopTwo[$k]['is_value']);
               // Log::info(Shop::where('one_abbr0',$v->id)->first());

            }else {
                $shopTwo[$k]['is_value'] = 1;
            }
        }
        // 帖子分类
        $cardCategory = CardCategory::where('is_display',1)->orderBy('sort','desc')->get();

        foreach ($cardCategory as $k=>$v) {
            if (config('app.city') == 1) {
                $cardCategory[$k]['is_value'] = 1;//ConvenientInformation::where('card_id',$v->id)->first() ? 1 : 0;
            }else {
                $cardCategory[$k]['is_value'] = ConvenientInformation::where('card_id',$v->id)->first() ? 1 : 0;
            }
        }
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
