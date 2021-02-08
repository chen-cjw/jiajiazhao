<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerCardCategory;
use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\Model\Setting;
use App\Transformers\CardCategoryTransformer;
use Illuminate\Support\Facades\DB;

class CardCategoryController extends Controller
{
    // 发布帖子的分类
    public function index()
    {
        $cardCategory = CardCategory::where('is_display',1)->orderBy('sort','desc')->paginate(100);
        return $this->responseStyle('ok',200,$cardCategory);
    }

    // 帖子下便民信息
    public function cardInformation($id)
    {
//        $title = \request()->title;
//        $lat = \request('lat');
//        $lng = \request('lng');
//        $sql = "select * from convenient_information ";
//        $start = \request()->page ?: 1;
//        $limit = 16;
//        $sql = $sql." where paid_at is not null";
//        $sql = $sql." and is_display = 1";
//
//        if($id != 'new') {
//            $sql = $sql." and card_id = ".$id;
//        }
////        // 搜索
//        if($title!='') {
//            $sql = $sql." and title LIKE '%".$title."%'";
//
//        }
//        // 附近
//        if ($lat && $lng) {
//            $sql = $sql." and
//            (acos(sin(({$lat}*3.1415)/180)
//            * sin((lat*3.1415)/180)
//            + cos(({$lat}*3.1415)/180)
//            * cos((lat*3.1415)/180)
//            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
//            * 6370.996) <= ".Setting::where('key','radius')->value('value');
//        }
//
//        if ($id == 'new') {
//            $sql = $sql." order by created_at "."DESC";
//        }else {
//            $sql = $sql." order by sort,created_at "."DESC";
//        }
//
//        $limit = $sql." LIMIT ".($start-1)*$limit.",".$limit;
//        $information = DB::select($limit);
//        $banner = BannerCardCategory::where('is_display',1)->orderBy('sort','desc')->get();
//
//        return $this->responseStyle('ok',200,[
//                'information' => $information,
//                'banner' => $banner
//        ]);

        $query = ConvenientInformation::query()->whereNotNull('paid_at')->where('is_display',1);
        if($id != 'new') {
            $query = $query->where('card_id',$id);
        }
        if($title = request('title')) {
            $query->where('title','like','%'.$title.'%');
        }
        if ($id == 'new') {
            $information = $query->orderBy('created_at','desc')->paginate();
        }else {
            $information = $query->orderBy('sort','desc')->orderBy('created_at','desc')->paginate();
        }
        $banner = BannerCardCategory::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,[
            'information' => $information,
            'banner' => $banner
        ]);
    }
}
