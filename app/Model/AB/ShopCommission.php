<?php

namespace App\Model\AB;

use App\Model\Model;
use App\Model\Shop;

class ShopCommission extends Model
{
    protected $table = 'shop_commissions';
    public function getAmountAttribute()
    {
        return $this->attributes['commissions'];
    }
    public function getCommissionsAttribute()
    {
        return $this->attributes['amount'];
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
