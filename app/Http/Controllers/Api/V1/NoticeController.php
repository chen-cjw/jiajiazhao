<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Notice;
use App\Transformers\NoticeTransformer;

class NoticeController extends Controller
{
    // 公告有分页的
    public function index()
    {
        return $this->response->paginator(Notice::orderBy('sort','desc')->where('is_display',1)->paginate(),new NoticeTransformer());
    }

    public function show($id)
    {
        return $this->response->paginator(Notice::where('is_display',1)->findOrFail($id),new NoticeTransformer());
    }
}
