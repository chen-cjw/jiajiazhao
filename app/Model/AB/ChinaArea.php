<?php

namespace App\Model\AB;

use Illuminate\Database\Eloquent\Model;

class ChinaArea extends Model
{
    protected $table = 'china_areas';
    public function getNicknameAttribute()
    {
        return $this->attributes['name'];
    }
    protected $appends = ['nickname'];
}
