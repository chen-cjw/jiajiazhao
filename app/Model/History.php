<?php

namespace App\Model;

class History extends Model
{
    protected $fillable = ['model_type','model_id','user_id'];

    public function getModelTypeAttribute()
    {
        if($this->attributes['model_type'] == Shop::class) {
            $res = Shop::where('id',$this->attributes['id'])->first();
            $lat1 = $res->lat;
            $lng1 = $res->lng;
            $lat = \request('lat');
            $lng = \request('lng');
            if ($lng && $lat) {
                $range = $this->getDistance($lat,$lng,$lat1,$lng1);
            }else {
                $range = '未知';
            }
            // 几公里
            $res->range = $range;
            // 平均星级
            $res->favoriteShopStarSvg=number_format(ShopComment::where('shop_id',$res->id)->avg('star'),1) ;
        }
        if($this->attributes['model_type'] == LocalCarpooling::class) {
            $res = LocalCarpooling::where('id',$this->attributes['id'])->first();
        }
        if($this->attributes['model_type'] == ConvenientInformation::class) {
            $res = ConvenientInformation::where('id',$this->attributes['id'])->first();
        }
        return $res;
    }
}
