<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\CardCategory;
use Faker\Generator as Faker;

$factory->define(CardCategory::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'sort'=>rand(1,10)
    ];
});
