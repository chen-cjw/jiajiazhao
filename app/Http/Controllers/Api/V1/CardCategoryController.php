<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\Transformers\CardCategoryTransformer;

class CardCategoryController extends Controller
{
    // 发布帖子的分类
    public function index()
    {
        $cardCategory = CardCategory::where('is_display',1)->orderBy('sort','desc')->paginate(7);
        return $this->responseStyle('ok',200,$cardCategory);
    }

    // 帖子下便民信息
    public function cardInformation($id)
    {
        $information = ConvenientInformation::where('card_id',$id)->orderBy('sort','desc')->paginate();
        return $this->responseStyle('ok',200,$information);
    }
}
