<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\AbbrTwoCategory;
use Faker\Generator as Faker;

$factory->define(AbbrTwoCategory::class, function (Faker $faker) {
    return [
        'abbr'=>$faker->name,
        'sort'=>rand(1,10),
        'logo'=>'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=1592300431,450815993&fm=26&gp=0.jpg'
    ];
});
