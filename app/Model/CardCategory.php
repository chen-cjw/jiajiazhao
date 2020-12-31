<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CardCategory extends Model
{
    // 便民信息的分类
    protected $fillable = [
        'name','sort'
    ];
}
