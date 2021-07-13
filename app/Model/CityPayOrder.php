<?php

namespace App\Model;

use App\User;

class CityPayOrder extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
