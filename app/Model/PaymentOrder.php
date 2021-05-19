<?php

namespace App\Model;

use App\User;

class PaymentOrder extends Model
{
    protected $fillable = ['user_id', 'order_number', 'amount', 'type', 'status', 'intro'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
