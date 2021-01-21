<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CommentRequest;
use App\Model\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $data = $request->only('content','information_id');
        // 1、直接给帖子回复的 无需传任何参数
        // 2、给帖子下一级回复的，需要带上一级回复帖子的ID，作为 parent_reply_id 使用
        // 3、给二级回复的，需要带上一个二级的ID，根据二级查找是否携带 parent_reply__id，携带 comment_user_id 就应该有值,未携带就是第二种情况
        $data['reply_user_id'] = auth('api')->id(); // 回复人(登陆者)
        $comment = Comment::where('id',$request->comment_id)->first();
//        return $comment;
        if($comment) {
            if($comment->parent_reply_id) {
                // 有值说明是二层
                $data['comment_user_id'] = $comment->reply_user_id;
                $data['parent_reply_id'] = $comment->parent_reply_id;

            }else {
                $data['parent_reply_id'] = $comment->id;
            }

        }else {
//            $data['parent_reply_id'] = $comment->reply_user_id;
        }

        $res = Comment::create($data);
        return $this->responseStyle('ok',200,$res);
    }
}
