<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\PostDescription;
use Faker\Generator as Faker;

$factory->define(PostDescription::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'content'=>$faker->text
    ];
});
