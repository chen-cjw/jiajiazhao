<?php

namespace App\Model\AB;


use App\Model\Model;

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

}
