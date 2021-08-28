<?php

namespace App\Model\Shop;


use App\Model\Model;
use App\User;

class OwnCartItem extends Model
{

    protected $fillable = ['amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ownProductSku()
    {
        return $this->belongsTo(OwnProductSku::class);
    }
}
