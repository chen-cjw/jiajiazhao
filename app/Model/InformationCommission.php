<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InformationCommission extends Model
{
    // 发帖抽成/合伙人
    protected $fillable = ['amount','commissions','rate','user_id','parent_id','information_id','district','is_pay'];

    public function information()
    {
        return $this->belongsTo(ConvenientInformation::class,'information_id','id');
    }
}
