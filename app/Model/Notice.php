<?php

namespace App\Model;

class Notice extends Model
{
    // 公告(首页)
    protected $fillable = ['title','content','is_display','sort'];
}
