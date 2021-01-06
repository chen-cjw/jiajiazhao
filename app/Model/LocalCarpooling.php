<?php

namespace App\Model;

class LocalCarpooling extends Model
{
    // 栏目 'person_looking_car','car_person_looking','good_looking_car','car_good_looking'
    const PERSON_LOOKING_CAR = 'person_looking_car';
    const CAR_LOOKING_PERSON = 'car_looking_person';
    const GOOD_LOOKING_CAR = 'good_looking_car';
    const CAR_LOOKING_GOOD = 'car_looking_good';

    public static $type = [
        self::PERSON_LOOKING_CAR => '人找车',
        self::CAR_LOOKING_PERSON => '车找人',
        self::GOOD_LOOKING_CAR => '货找车',
        self::CAR_LOOKING_GOOD    => '车找货',
    ];

    // 本地拼车
    protected $fillable = [
        'phone', 'name_car','capacity','go','end','departure_time','seat','user_id','lng','lat',
        'other_need','is_go','type','no','amount','paid_at','payment_method','payment_no','closed','area'
    ];

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

    public function getClosedAttribute()
    {
        return bcsub(time(),strtotime($this->attributes['created_at'])) > 3600 ? 1 : 0;
    }



}
