<?php

namespace App\Model\DS;

use Illuminate\Database\Eloquent\Model;

class ZNotice extends Model
{
    protected $fillable = [
        'name', 'sort_num', 'on_sale'
    ];
}
