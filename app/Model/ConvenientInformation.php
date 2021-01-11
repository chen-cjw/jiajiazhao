<?php

namespace App\Model;

use App\User;

class ConvenientInformation extends Model
{

    // 便民信息
    protected $fillable = [
        'title','content','location','lng','lat','view','card_id','user_id','no',
        'card_fee','top_fee','paid_at','payment_method','payment_no','sort'
    ];

    public function getUserIdAttribute()
    {
        return User::find( $this->attributes['user_id']);
    }

    public function getCardIdAttribute()
    {
        return CardCategory::find($this->attributes['card_id']);
    }

    public function getCommentCountAttribute()
    {
        return Comment::where('information_id',$this->attributes['id'])->count();
    }
}
