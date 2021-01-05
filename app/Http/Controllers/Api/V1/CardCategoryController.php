<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\CardCategory;
use App\Transformers\CardCategoryTransformer;

class CardCategoryController extends Controller
{
    // 发布帖子的分类
    public function index()
    {
        $cardCategory = CardCategory::where('is_display',1)->orderBy('sort','desc')->paginate(7);
        return $this->responseStyle('ok',200,$cardCategory);

        return $this->response->paginator(CardCategory::where('is_display',1)->orderBy('sort','desc')->paginate(7),new CardCategoryTransformer());
    }
}
