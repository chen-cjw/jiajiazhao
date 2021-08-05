<?php

namespace App\Model;

use Illuminate\Support\Str;

class AbbrCategory extends Model
{
    // 行业分类(后台)
    protected $fillable = ['abbr','sort','logo','parent_id','type','local','area'];


    public function abbrs()
    {
        return $this->hasMany(AbbrCategory::class,'parent_id','id');
    }
    public function getSubCollectionAttribute()
    {
        $cardCategory = AbbrCategory::where('parent_id',$this->attributes['id'])->orderBy('sort','desc')->get();
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

    public function setImageAttribute($images)
    {
        return BannerShopCategory::where('abbr_category_id',$this->attributes['id'])->get();
//        dd($images);
        if (is_array($images)) {
            $this->attributes['image'] = json_encode($images);
        }
    }

    public function getImageAttribute($pictures)
    {
        if (BannerShopCategory::where('abbr_category_id',$this->attributes['id'])->first()){
            return [];
        }
        $bannerShopCategoryQuery = BannerShopCategory::where('abbr_category_id',$this->attributes['id']);
        if (request('area')) {
            $bannerShopCategoryQuery = $bannerShopCategoryQuery->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);

//                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        return $bannerShopCategoryQuery->pluck('image');
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


}
