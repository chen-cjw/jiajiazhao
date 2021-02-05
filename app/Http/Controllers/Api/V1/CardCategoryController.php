<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerCardCategory;
use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\Transformers\CardCategoryTransformer;

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
            $information = $query->orderBy('sort','desc')->paginate();
        }
        $banner = BannerCardCategory::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,[
            'information' => $information,
            'banner' => $banner
        ]);
    }
}
