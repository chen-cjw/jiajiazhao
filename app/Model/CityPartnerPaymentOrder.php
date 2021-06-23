<?php

namespace App\Model;

class CityPartnerPaymentOrder extends Model
{
    protected $fillable = ['user_id', 'order_number', 'amount', 'type', 'status', 'intro'];
}
