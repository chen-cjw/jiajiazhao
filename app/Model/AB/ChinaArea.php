<?php

namespace App\Model\AB;

use Illuminate\Database\Eloquent\Model;

class ChinaArea extends Model
{
    protected $table = 'china_areas';
    public function getNicknameAttribute()
    {
        $rand = rand(1,5);
        if ($rand==1) {
            return '****'.'市合伙人';
        }else if ($rand==2) {
            return '****'.'县合伙人';
        }else{
            return '****'.'区合伙人';
        }
//        return $this->attributes['name'].'合伙人';
    }
    protected $appends = ['nickname'];
}
