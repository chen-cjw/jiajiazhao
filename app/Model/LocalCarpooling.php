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



    public function getClosedAttribute()
    {
        return bcsub(time(),strtotime($this->attributes['created_at'])) > 3600 ? 1 : 0;
    }

    public function getIsGoAttribute()
    {
        return bcsub(time(),strtotime($this->attributes['departure_time'])) > 0 ? 1 : 0;
    }


}
