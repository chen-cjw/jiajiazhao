<?php

namespace App\Model;

use App\User;

class ShopComment extends Model
{
    protected $fillable = ['content','star','shop_id','reply_user_id','parent_reply_id','comment_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCommentReplyAttribute()
    {
        if (request('content')) {

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

    public function getCommentReplyAvatarAttribute()
    {
        if (request('information_id')) {

        }else {
            return User::where('id',$this->attributes['reply_user_id'])->value('avatar');
        }

    }

    public function getSubcollectionAttribute()
    {
        $thisID =  $this->attributes['id'];
        return ShopComment::where('parent_reply_id',$thisID)->paginate(5);
    }
    protected $appends = ['comment_reply','comment_reply_avatar','subcollection'];
}
