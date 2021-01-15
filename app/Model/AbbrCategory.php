<?php

namespace App\Model;

class AbbrCategory extends Model
{
    // 行业分类(后台)
    protected $fillable = ['abbr','sort','logo','parent_id','type','local'];

    public function abbr()
    {
        return $this->hasMany(AbbrCategory::class,'parent_id','id');
    }
    public function getSubCollectionAttribute()
    {
        $cardCategory = AbbrCategory::where('parent_id',$this->attributes['id'])->get();
        return $cardCategory;
    }
    protected $appends = ['sub_collection'];
}
