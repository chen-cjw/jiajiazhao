<?php

namespace App\Model;

use Illuminate\Support\Str;

class Shop extends Model
{
    // 商户
    protected $fillable = [
        'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
        'logo','service_price','merchant_introduction','platform_licensing','is_top','view','top_amount',
        'no','amount','lng','lat','user_id'
    ];
    //             'logo' => json_decode($shop->logo),
    public function getLogoAttribute()
    {
        return json_decode($this->attributes['logo']);
    }

    public function getServicePriceAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }

    public function getPlatformLicensingAttribute()
    {
        return bcadd($this->attributes['amount'],$this->attributes['top_amount'],2);
    }
}
