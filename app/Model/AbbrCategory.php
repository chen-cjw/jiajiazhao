<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AbbrCategory extends Model
{
    // 行业分类(后台)
    protected $fillable = [];

    public function abbr()
    {
        return $this->hasMany(AbbrCategory::class,'parent_id','id');
    }
}
