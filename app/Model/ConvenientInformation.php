<?php

namespace App\Model;

class ConvenientInformation extends Model
{

    // 便民信息
    protected $fillable = [
        'title','content','location','lng','lat','view','card_id','user_id','no',
        'card_fee','top_fee','paid_at','payment_method','payment_no','sort'
    ];
}
