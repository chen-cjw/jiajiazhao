<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerCardCategory;
use App\Model\CardCategory;
use App\Model\Comment;
use App\Model\ConvenientInformation;
use App\Model\Setting;
use App\Transformers\CardCategoryTransformer;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
//        $area = \request()->area;
//
//        $sql = $sql."where created_at > DATE_SUB(CURDATE(), INTERVAL ".Setting::where('key','timeSearch')->value('value')." MONTH)";
////        return Setting::where('key','timeSearch')->value('value');
////        $sql = $sql."where DATE_SUB(CURDATE(), INTERVAL ".Setting::where('key','timeSearch')->value('value')." month) <= (created_at)";
////        $information = DB::select($sql);
////        return $information;
//        $sql = $sql."and  paid_at is not null";
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
//        // 同城搜索
//        if ($area!='') {
//            $sql = $sql."and location LIKE '%".$area."%'";
//        }
//        // 附近
////        if ($lat && $lng) {
////            $sql = $sql." and
////            (acos(sin(({$lat}*3.1415)/180)
////            * sin((lat*3.1415)/180)
////            + cos(({$lat}*3.1415)/180)
////            * cos((lat*3.1415)/180)
////            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
////            * 6370.996) <= ".Setting::where('key','radius')->value('value');
////        }
//
//        if ($id == 'new') {
//            $sql = $sql." order by created_at,sort "."DESC";
//        }else {
//            $sql = $sql." order by created_at,sort "."DESC";
//        }
//        $total = count(DB::select($sql));
//
//        $limit = $sql." LIMIT ".($start-1)*$limit.",".$limit;
//        $information = DB::select($limit);
//
//        foreach ($information as $item=>$value) {
//            $lat1 = $value->lat;
//            $lng1 = $value->lng;
//            $range = $this->getDistance($lat,$lng,$lat1,$lng1);
//            $information[$item]->range=$range; //几公里
//            $information[$item]->user_id=User::where('id',$value->user_id)->first();
//            $information[$item]->card_id=CardCategory::where('id',$value->card_id)->first();
//            $information[$item]->comment_count=Comment::where('information_id',$value->id)->count();
//            $information[$item]->images=$this->getImages($value->images);
//        }
//
//        $banner = BannerCardCategory::where('is_display',1)->orderBy('sort','desc')->get();
//        // 统计天数
////        $query = ConvenientInformation::query()->whereNotNull('paid_at')->where('is_display',1);
////        if($id != 'new') {
////            $query = $query->where('card_id',$id);
////        }
////        if($title = request('title')) {
////            $query->where('title','like','%'.$title.'%');
////        }
////        $total = $query->count();
//
//        return $this->responseStyle('ok',200,[
//                'information' => [
//                    'data'=>$information,
//                    'total'=>$total
//                ],
//                'banner' => $banner
//        ]);

        $query = ConvenientInformation::query()->whereNotNull('paid_at')->where('is_display',1);
        if($id != 'new') {
            $query = $query->where('card_id',$id);
        }
        if($title = request('title')) {
            $query = $query->where('title','like','%'.$title.'%');
        }
        $area = \request()->area;
        // 同城搜索
        //         $fontPath = config('app.fontPath');//'/System/Library/Fonts/Hiragino Sans GB.ttc';
        if (config('app.city') == 1) {
            if ($area != '') {
                $query = $query->where('area', 'like', '%' . $area . '%');
            }
        }
        if ($id == 'new') {
            $information = $query->orderBy('sort','desc')->orderBy('created_at','desc')->paginate();
        }else {
            $information = $query->orderBy('sort','desc')->orderBy('created_at','desc')->paginate();
        }

        $banner = BannerCardCategory::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,[
            'information' => $information,
            'banner' => $banner
        ]);
    }

    public function getImages($pictures)
    {
        if ($pictures==null) {
            return $pictures;
        }
        $data = json_decode($pictures, true);

        $da = array();

        foreach ($data as $k=>$v) {
            if (Str::startsWith($v, ['http://', 'https://'])) {
                $da[] = $v;
            }else {
                $da[] = \Storage::disk('public')->url($v);
            }
        }
        return $da;
        return json_decode($this->attributes['logo']);
    }
}
