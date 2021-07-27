<?php

namespace App\Model\AB;


use App\Model\Model;

class ShopCommission extends Model
{
    protected $table = 'shop_commissions';

    public function getAmountAttribute()
    {
        return 1;
        return $this->attributes['commissions'];
    }
    public function getCommissionsAttribute()
    {
        return 2;

        return $this->attributes['amount'];
    }

}
