<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CommentRequest;
use App\Model\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        // 判断两个人是否是一个团队的
        $data = $request->only('content','information_id');
        $data['comment_user_id'] = $request->comment_user_id; // Task::findOrFail($request->task_id)->value('user_id'); // 发表人
        $data['reply_user_id'] = auth('api')->id(); // 回复人(登陆者)

        $res = Comment::create($data);
        return $this->responseStyle('ok',200,$res);
    }
}
