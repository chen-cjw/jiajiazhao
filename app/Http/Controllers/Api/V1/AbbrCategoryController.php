<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\AbbrCategory;
use App\Transformers\AbbrCategoryTransformer;

class AbbrCategoryController extends Controller
{
    // 行业分类(后台)
    public function index()
    {
        if (config('app.city') == 1) {
            $abbrCategory = AbbrCategory::orderBy('sort','desc')->where('type','shop')->where('is_display',1)->where('parent_id',null);
        }else {
            $abbrCategory = AbbrCategory::orderBy('sort','desc')->where('type','shop')->where('is_display',1)->take(5)->where('parent_id',null);
            $abbrCategory = $abbrCategory->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);
            });
        }
        $abbrCategory = $abbrCategory->get();
        return $this->responseStyle('ok',200,$abbrCategory);
        return $this->response->collection($abbrCategory,new AbbrCategoryTransformer());
    }

    // 搜索二级，给前段返回一级/二级
    public function searchTwoCate()
    {
        if ($name = request('name')) {
            $abbrCategory = AbbrCategory::where('type','shop')->whereNotNull('parent_id')->where('is_display',1)->where('abbr','like','%'.request('name').'%')->get();
            return $this->responseStyle('ok',200,$abbrCategory);
        }
        return $this->responseStyle('ok',200,[]);
    }
}
