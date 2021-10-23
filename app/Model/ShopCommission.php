<?php

namespace App\Model;

class ShopCommission extends Model
{
    // 商户抽成 == 佣金
    protected $fillable = ['amount','commissions','rate','user_id','parent_id','shop_id','district','is_pay','market'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
