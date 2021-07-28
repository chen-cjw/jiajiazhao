<?php

namespace App\Model\AB;

use Illuminate\Database\Eloquent\Model;

class ChinaArea extends Model
{
    protected $table = 'china_areas';
    public function getNicknameAttribute()
    {
//        return $this->attributes['name'].'合伙人';
        return 'xxxx'.'合伙人';
    }
    protected $appends = ['nickname'];
}
