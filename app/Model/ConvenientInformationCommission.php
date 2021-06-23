<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConvenientInformationCommission extends Model
{
    // 发帖抽成 == 佣金
    protected $fillable = ['amount','commissions','rate','user_id','information_id'];

}
