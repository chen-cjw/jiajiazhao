<?php

namespace App\Model;

class Shop extends Model
{
    // 商户
    protected $fillable = [
        'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
        'logo','service_price','merchant_introduction','platform_licensing','is_top','view',
        'no','amount','lng','lat','user_id'
    ];
}
