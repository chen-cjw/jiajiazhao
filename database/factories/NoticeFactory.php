<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Notice;
use Faker\Generator as Faker;

$factory->define(Notice::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'content'=>$faker->text,
        'is_display'=>$faker->boolean,
        'sort'=>rand(1,100),
    ];
});
