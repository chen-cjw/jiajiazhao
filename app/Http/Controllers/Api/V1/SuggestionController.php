<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Requests\SuggestionRequest;
use App\Model\Suggestions;

class SuggestionController extends Controller
{
    // 投诉建议
    public function index()
    {
        return $this->responseStyle('ok',200,auth('api')->user()->suggestions()->paginate());
    }
    //  有id 就是举报的帖子，没有就是个人中心的举报
    public function store(SuggestionRequest $request)
    {
        $res = Suggestions::create([
            'content'=>$request->input('content'),
            'user_id'=>auth('api')->id(),
            'localCarpooling_id'=>$request->id?:0
        ]);
        return $this->responseStyle('ok',200,$res);
    }
}
