<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];//->toDateTimeString();
    }
    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'];//->toDateTimeString();
    }
    // 支付订单的随机数
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!LocalCarpooling::where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }
    public function getImageAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }
    public function getLogoAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }
}
