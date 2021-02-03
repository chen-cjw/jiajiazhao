<?php

namespace App\Model;

use App\User;

class Withdrawal extends Model
{
    protected $fillable = ['user_id','amount','is_accept','name','bank_of_deposit','bank_card_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
