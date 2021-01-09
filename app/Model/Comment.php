<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content','comment_user_id','reply_user_id','information_id'];

    public function getCommentReplyAttribute()
    {
        $userReply = User::where('id',$this->attributes['reply_user_id'])->first()->phone;
        if($this->attributes['comment_user_id']) {
            $userComment = User::where('id',$this->attributes['comment_user_id'])->first()->phone;
        }else {
            return null;
        }

        return $this->attributes['comment_user_id'] ? $userReply .'回复'.$userComment : $userReply;
    }
    protected $appends = ['comment_reply'];
}
