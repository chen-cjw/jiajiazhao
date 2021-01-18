<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ShopComment extends Model
{
    protected $fillable = ['content','star','shop_id','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
