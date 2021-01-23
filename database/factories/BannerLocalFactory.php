<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Model\BannerLocal::class, function (Faker $faker) {
    return [
        'image'=>array_rand([
            "https://img95.699pic.com/photo/50059/6665.jpg_wh860.jpg"=>1,
            "https://img95.699pic.com/photo/50044/1381.jpg_wh300.jpg"=>1,
            "https://img95.699pic.com/photo/40011/9541.jpg_wh300.jpg"=>1,
            "https://img95.699pic.com/photo/40006/5893.jpg_wh300.jpg"=>1,
            "https://img95.699pic.com/photo/40009/6106.jpg_wh300.jpg"=>1,
        ],1),
        'link'=>'www.baidu.com',
        'is_display'=>$faker->boolean,
        'sort'=>rand(1,10)
    ];
});
