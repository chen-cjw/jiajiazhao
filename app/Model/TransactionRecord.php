<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransactionRecord extends Model
{
//$table->unsignedDecimal('amount',10,2)->comment('收益金额');
//$table->boolean('is_pay')->default(0)->comment('收否到账');
//$table->string('come_from')->default(0)->comment('钱怎么来的');
    protected $fillable = [ 'amount','is_pay','come_from','user_id','parent_id','model_id','model_type' ];
}
