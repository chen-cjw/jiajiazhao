<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\AbbrCategory;
use App\Transformers\AbbrCategoryTransformer;

class AbbrCategoryController extends Controller
{
    // 行业分类(后台)
    public function index()
    {
        $abbrCategory = AbbrCategory::orderBy('sort','desc')->where('parent_id',null)->get();
        return $this->responseStyle('ok',200,$abbrCategory);
        return $this->response->collection($abbrCategory,new AbbrCategoryTransformer());
    }
}
