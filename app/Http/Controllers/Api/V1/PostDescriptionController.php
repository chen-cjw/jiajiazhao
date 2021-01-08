<?php

namespace App\Http\Controllers\APi\V1;

use App\Model\PostDescription;

class PostDescriptionController extends Controller
{
    // 发帖说明
    public function index()
    {
        $post = PostDescription::first();
        return $this->responseStyle('ok',200,$post);
    }

}


