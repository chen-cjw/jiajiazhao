<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['content','comment_user_id','reply_user_id','information_id','parent_reply_id'];

    public function getCommentReplyAttribute()
    {
        if (request('information_id')) {

        }else {
            $userReply = User::where('id',$this->attributes['reply_user_id'])->first();
            $userComment = User::where('id',$this->attributes['comment_user_id'])->first();

            if($this->attributes['parent_reply_id'] && $this->attributes['comment_user_id']) {
                return $userReply->nickname .'回复'.$userComment->nickname;
            }
            if($userComment) {
                return $userComment->nickname;
            }
            if($userReply) {
                return $userReply->nickname;
            }
        }

    }

//    public function getCommentUserIdAttribute()
//    {
//        return User::find($this->attributes['comment_user_id']);
//    }
//
//    public function getReplyUserIdAttribute()
//    {
//        return User::find($this->attributes['reply_user_id']);
//
//    }
    public function getSubcollectionAttribute()
    {
        $thisID =  $this->attributes['id'];
        return Comment::where('parent_reply_id',$thisID)->paginate(5);
    }
    protected $appends = ['comment_reply','subcollection'];
}
