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

    public function store(SuggestionRequest $request)
    {
        $res = Suggestions::create([
            'content'=>$request->input('content'),
            'user_id'=>auth('api')->id()
        ]);
        return $this->responseStyle('ok',200,$res);
    }
}
