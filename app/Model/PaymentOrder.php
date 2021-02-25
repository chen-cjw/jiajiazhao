<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    protected $fillable = ['user_id', 'order_number', 'amount', 'type', 'status', 'intro'];

}
