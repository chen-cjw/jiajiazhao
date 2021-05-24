<?php

namespace App\Model\DS;

use Illuminate\Database\Eloquent\Model;

class ZCategory extends Model
{
    protected $fillable = [
            'name', 'sort_num', 'on_sale'
        ];
}
