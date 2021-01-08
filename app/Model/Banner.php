<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // 轮播图(首页)
    protected $fillable = ['image','link','type','is_display','sort'];
}
