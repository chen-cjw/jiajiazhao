<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Carpooling;
use Faker\Generator as Faker;

$factory->define(Carpooling::class, function (Faker $faker) {
    return [
        'content'=>$faker->text.$faker->text.$faker->text
    ];
});
