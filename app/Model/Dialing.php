<?php

namespace App\Model;

class Dialing extends Model
{
    protected $fillable = ['phone','model_type','model_id','user_id'];

    public function shops()
    {
        return $this->morphTo(Shop::class,'model_type','model_id');
    }

    public function getModelAttribute()
    {
        if ($this->attributes['model_type']==Shop::class) {
            $res = Shop::where('id',$this->attributes['model_id'])->first();
            $lat = request('lat');
            $lng = request('lng');
            // 几公里
            if ($lat&&$lng) {

                $res['range'] = $this->getDistance($lat,$lng,$res['lat'],$res['lng']);
            }else {
                $res['range'] = '未知';
            }
            // 平均星级
            $res['favoriteShopStarSvg']=number_format(ShopComment::where('shop_id',$this->attributes['model_id'])->avg('star'),1) ;
            return $res;
        }
        if ($this->attributes['model_type']==LocalCarpooling::class) {
            return LocalCarpooling::where('id',$this->attributes['model_id'])->first();
        }

    }
    protected $appends = ['model'];
    /**
     * @desc 根据两点间的经纬度计算距离
     * @param float $lat 纬度值
     * @param float $lng 经度值
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2) {
        // 赤道半径(单位m)
        $earthRadius = 6378137;

        $lat1 = ($lat1 * pi() ) / 180;

        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;

        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;

        $calcLatitude = $lat2 - $lat1;

        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);

        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));

        $calculatedDistance = $earthRadius * $stepTwo;

        $res = abs(round($calculatedDistance/1000, 2));
        if($res>1000) {
            $res = floor($res/1000).'公里';
            return $res;
        }
        return $res;
    }
}
