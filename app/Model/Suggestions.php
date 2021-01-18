<?php

namespace App\Model;

class Suggestions extends Model
{
    // 投诉建议
    protected $fillable = ['content','user_id','is_accept','localCarpooling_id'];

}
