<?php

namespace App\Model;

use Illuminate\Support\Str;

class CardCategory extends Model
{
    // 便民信息的分类
    protected $fillable = [
        'name','sort'
    ];

    public function getLogoAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }
}
