<?php

namespace App\Model;

use Illuminate\Support\Str;

class AbbrCategory extends Model
{
    // 行业分类(后台)
    protected $fillable = ['abbr','sort','logo','parent_id','type','local'];

    public function abbrs()
    {
        return $this->hasMany(AbbrCategory::class,'parent_id','id');
    }
    public function getSubCollectionAttribute()
    {
        $cardCategory = AbbrCategory::where('parent_id',$this->attributes['id'])->get();
        return $cardCategory;
    }
    protected $appends = ['sub_collection'];
    public function getLogoAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }

    public function setImageAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['image'] = json_encode($pictures);
        }
    }

    public function getImageAttribute($pictures)
    {
        $data = json_decode($pictures, true);
        foreach ($data as $k=>$v) {
            if (Str::startsWith($v, ['http://', 'https://'])) {
                $da[] = $v;
            }
            $da[] = \Storage::disk('public')->url($v);
        }
        return $da;
    }
}
