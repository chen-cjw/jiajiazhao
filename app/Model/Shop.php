<?php

namespace App\Model;

use App\User;
use Illuminate\Support\Str;

class Shop extends Model
{
    // 商户
    protected $fillable = [
        'one_abbr0','one_abbr1','one_abbr2','sort','comment_count','good_comment_count','images','is_accept',
        'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
        'logo','service_price','merchant_introduction','platform_licensing','is_top','view','top_amount',
        'no','amount','lng','lat','user_id','due_date','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shopComments()
    {
        return $this->hasMany(ShopComment::class);
    }
    //             'logo' => json_decode($shop->logo),
    public function getLogoAttribute($pictures)
    {
        if (!$pictures) {
            return $pictures;
        }
        $data = json_decode($pictures, true);
        if (auth('api')->id() != $this->attributes['user_id']) {
            $data['with_iD_card'] = '';
        }
        return $data;
        $da = array();
        foreach ($data as $k=>$v) {
//            if (Str::startsWith($v, ['http://', 'https://'])) {
//                $da[] = $v;
//            }
            $da[] = \Storage::disk('public')->url($v);

        }
        return $da;
        return json_decode($this->attributes['logo']);
    }

    public function getImagesAttribute($pictures)
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

    public function getFavoriteShopStarSvgAttribute()
    {
        return number_format(ShopComment::where('shop_id',$this->attributes['id'])->avg('star'),1) ;

    }
    protected $appends = ['favoriteShopStarSvg'];
//    public function getLogoAttribute($pictures)
//    {
//
//    }

}
