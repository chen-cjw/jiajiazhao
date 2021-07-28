<?php

namespace App\Model;

use App\User;

class CityPayOrder extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\Model\AB\ChinaArea::class,'intro','id');
        return $this->belongsTo(User::class);
    }
}
