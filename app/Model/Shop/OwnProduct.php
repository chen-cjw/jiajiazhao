<?php

namespace App\Model\Shop;

use App\Model\Model;
use Illuminate\Support\Str;

class OwnProduct extends Model
{
    protected $fillable = [
        'title', 'description', 'image', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price'
    ];
    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];
    // 与商品SKU关联
    public function skus()
    {
        return $this->hasMany(OwnProductSku::class);
    }
    public function getImageAttribute($pictures)
    {
        if (!$pictures) {
            return $pictures;
        }
        $data = json_decode($pictures, true);
        $da = array();
        foreach ($data as $k=>$v) {
            if (Str::startsWith($v, ['http://', 'https://'])) {
                $da[] = $v;
            }else {
                $da[] = \Storage::disk('public')->url($v);
            }
        }
        return $da;
    }
    public function setImageAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['image'] = json_encode($pictures);
        }
    }
//    public function getImageUrlAttribute()
//    {
//        // 如果 image 字段本身就已经是完整的 url 就直接返回
//        if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
//            return $this->attributes['image'];
//        }
//        return \Storage::disk('public')->url($this->attributes['image']);
//    }
}
