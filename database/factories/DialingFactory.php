<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Dialing;
use Faker\Generator as Faker;

$factory->define(Dialing::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'phone'=>$faker->phoneNumber
    ];
});
