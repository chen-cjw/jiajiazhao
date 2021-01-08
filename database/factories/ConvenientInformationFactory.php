<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ConvenientInformation;
use Faker\Generator as Faker;

$factory->define(ConvenientInformation::class, function (Faker $faker) {
    return [
        'title'=>$faker->name,
        'content'=>$faker->text,
        'location'=>$faker->address,
        'lng'=>$faker->randomFloat(),
        'lat'=>$faker->randomFloat(),
        'view'=>rand(0,1000),
        'card_id'=>rand(1,5),
        'user_id'=>1,
        'no'=>\App\Model\LocalCarpooling::findAvailableNo(),
        'card_fee'=>rand(1,100),
        'top_fee'=>rand(1,10),
        'paid_at'=>date('Y-m-d H:i:s'),
    ];
});
