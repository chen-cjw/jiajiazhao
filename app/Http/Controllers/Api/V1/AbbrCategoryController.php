<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\AbbrCategory;
use App\Transformers\AbbrCategoryTransformer;

class AbbrCategoryController extends Controller
{
    public function index()
    {
        return $this->response->collection(AbbrCategory::orderBy('sort','desc')->where('parent_id',null)->get(),new AbbrCategoryTransformer());
    }
}
