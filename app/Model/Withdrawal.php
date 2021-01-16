<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['user_id','amount','is_accept'];

}
