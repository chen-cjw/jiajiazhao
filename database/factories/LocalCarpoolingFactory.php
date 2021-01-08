<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\LocalCarpooling;
use Faker\Generator as Faker;
//$go = array("北京", "上海", "南京", "苏州", "杭州");
//$end = array;
//$type = array('person_looking_car','car_looking_person','good_looking_car','car_looking_good');
$factory->define(LocalCarpooling::class, function (Faker $faker) {

    return [
        'phone'=>rand(10000000000,19999999999),
        'name_car'=>$faker->name(),
        'capacity'=>rand(1,100),
        'go'=>array_rand(["北京"=>1, "上海"=>1, "南京"=>1, "苏州"=>1, "杭州"=>1], 1),
        'end'=>array_rand(["连云港"=>1, "沭阳"=>1, "新加坡"=>1, "无锡"=>1, "常州"=>1], 1),
        'departure_time'=>date('Y-m-d H:i:s'),
        'seat'=>'',
        'other_need'=>rand(1,4),
        'is_go'=>rand(0,1),
        'type'=>array_rand(['person_looking_car'=>1,'car_looking_person'=>1,'good_looking_car'=>1,'car_looking_good'=>1], 1),
        'lng'=>$faker->randomFloat(),
        'lat'=>$faker->randomFloat(),
        'area'=>$faker->name,
        'user_id'=>1,
        'no'=>LocalCarpooling::findAvailableNo(),
        'amount' => 0.01

    ];
});
