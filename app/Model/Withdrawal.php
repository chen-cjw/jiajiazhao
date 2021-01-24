<?php

namespace App\Model;

use App\User;

class Withdrawal extends Model
{
    protected $fillable = ['user_id','amount','is_accept'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
