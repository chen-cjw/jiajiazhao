<?php

namespace App;

use App\Model\AbbrCategory;
use App\Model\AdminShop;
use App\Model\AdminUser;
use App\Model\Model;
use App\Model\ShopComment;
use App\User;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class Shop extends Model
{
    // 商户
    protected $fillable = [
        'one_abbr0','one_abbr1','one_abbr2','sort','comment_count','good_comment_count','images','is_accept',
        'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
        'logo','service_price','merchant_introduction','platform_licensing','is_top','view','top_amount',
        'no','amount','lng','lat','user_id','due_date'
    ];

    public function getNicknameAttribute()
    {
        $userId = $this->attributes['user_id'];
        return User::where('id',$userId)->value('nickname');

    }
    public function getAvatarAttribute()
    {
        $userId = $this->attributes['user_id'];
        return User::where('id',$userId)->value('avatar');

    }
    protected $appends = ['nickname','avatar'];
    public function adminUser()
    {
        return $this->belongsToMany(AdminUser::class,'admin_shops','shop_id','admin_id');
    }
    public function adminShops()
    {
        return $this->hasMany(AdminShop::class);
    }
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

    public function getOneAbbr0Attribute()
    {
        $str = '';
        if ($this->attributes['one_abbr0']) {
            $one_abbr0 = AbbrCategory::where('id',$this->attributes['one_abbr0'])->value('abbr');
            $str .= $one_abbr0.'/';
        }
        if ($this->attributes['one_abbr1']) {
            $one_abbr1 = AbbrCategory::where('id',$this->attributes['one_abbr1'])->value('abbr');
            $str .= $one_abbr1.'/';

        }
        if($this->attributes['one_abbr2']) {
            $one_abbr2 = AbbrCategory::where('id',$this->attributes['one_abbr2'])->value('abbr');
            $str .= $one_abbr2.'/';

        }
        return $str;
    }
    // two_abbr0
    public function getTwoAbbr0Attribute()
    {
        $str = '';
        if ($this->attributes['two_abbr0']) {
            $two_abbr0 = AbbrCategory::where('id',$this->attributes['two_abbr0'])->value('abbr');
            $str .= $two_abbr0.'/';
        }
        if ($this->attributes['two_abbr1']) {
            $two_abbr1 = AbbrCategory::where('id',$this->attributes['two_abbr1'])->value('abbr');
            $str .= $two_abbr1.'/';
        }
        if($this->attributes['two_abbr2']) {
            $two_abbr2 = AbbrCategory::where('id',$this->attributes['two_abbr2'])->value('abbr');
            $str .= $two_abbr2.'/';
        }
        return $str;
    }
    public function getPlatformLicensingAttribute()
    {
        return bcadd($this->attributes['amount'],$this->attributes['top_amount'],2);
    }

//    public function getLogoAttribute($pictures)
//    {
//
//    }
    public static function boot()
    {
        parent::boot();


        // updating creating saving 这几个方法你自己选择，打印一下$model看看你就知道怎么取出数据了
//        static::creating(function ($model) {
//            $hostUrl= \env("ALIYUN_OSS_URL");
//            $model->store_logo = $hostUrl . $model->store_logo;
//            $model->with_iD_card = $hostUrl . $model->with_iD_card;
//        });
//
//        static::updating(function ($model) {
//            $hostUrl = \env("ALIYUN_OSS_URL");
//            $model->store_logo = $hostUrl . str_replace($hostUrl,"", $model->store_logo);
//            $model->with_iD_card = $hostUrl . str_replace($hostUrl,"", $model->with_iD_card);
//        });
        static::deleting(function ($model)
        {
            //这样可以拿到当前操作id
//            $model->id
            if (!Admin::user()->can('Administrator')) {
                if (Admin::user()->id != AdminShop::where('shop_id', $model->id)->value('admin_id')) {
                    throw new \Exception('请不要随意修改数据！');

                }
            }
        });
    }

}
