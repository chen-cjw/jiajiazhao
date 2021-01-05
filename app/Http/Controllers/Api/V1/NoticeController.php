<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Notice;
use App\Transformers\NoticeTransformer;

class NoticeController extends Controller
{
    // 公告有分页的
    public function index()
    {
        $notice = Notice::orderBy('sort','desc')->where('is_display',1)->paginate();
        return $this->responseStyle('ok',200,$notice);

    }

    public function show($id)
    {
        $notice = Notice::where('is_display',1)->findOrFail($id);
        return $this->responseStyle('ok',200,$notice);

    }
}
