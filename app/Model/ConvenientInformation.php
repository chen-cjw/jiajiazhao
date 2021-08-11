<?php

namespace App\Model;

use App\User;
use Illuminate\Support\Str;

class ConvenientInformation extends Model
{

    // 便民信息
    protected $fillable = [
        'title','content','location','lng','lat','view','card_id','user_id','no','images','area',
        'card_fee','top_fee','paid_at','payment_method','payment_no','sort','is_display','is_top'
    ];
    public static function getSelectOptions()
    {
        return \App\Model\CardCategory::where('is_display',1)->orderBy('id','desc')->pluck('name','id');

    }

    public function getLocationAttribute()
    {
        $address =  $this->attributes['location'];

        preg_match('/(.*?(省|自治区|北京市|天津市))/', $address, $matches);

        if (count($matches) > 1) {
            $province = $matches[count($matches) - 2];

            $address = str_replace($province, '', $address);

        }

//        preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);
//
//        if (count($matches) > 1) {
//            $city = $matches[count($matches) - 2];
//
//            $address = str_replace($city, '', $address);
//
//        }
        return $address;
         preg_match('/(.*?(省|自治区|北京市|天津市))/', $this->attributes['location'], $matches);
return $matches;

    }
    public function getAreaAttribute()
    {
        $address =  $this->attributes['location'];

        preg_match('/(.*?(省|自治区|北京市|天津市))/', $address, $matches);

        if (count($matches) > 1) {
            $province = $matches[count($matches) - 2];

            $address = str_replace($province, '', $address);

        }

        preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);

        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];

            $address = str_replace($city, '', $address);

        }
        return $address;
        preg_match('/(.*?(省|自治区|北京市|天津市))/', $this->attributes['location'], $matches);
        return $matches;

    }


    public function comments()
    {
        return $this->hasMany(Comment::class,'information_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getUserIdAttribute()
    {
        return User::find( $this->attributes['user_id']);
    }

    public function getCardIdAttribute()
    {
        return CardCategory::find($this->attributes['card_id']);
    }

    public function getCommentCountAttribute()
    {
        return Comment::where('information_id',$this->attributes['id'])->count();
    }
    public function getImagesAttribute($pictures)
    {
        if ($pictures==null) {
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

//    public function getImageAttribute($image)
//    {
//        if ($this->attributes['images']) {
//            return json_decode($this->attributes['images'],true);
//        }
//        return null;
//    }
//    protected $appends = ['image'];
}
