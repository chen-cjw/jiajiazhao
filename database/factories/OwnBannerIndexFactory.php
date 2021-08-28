<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Shop\OwnBannerIndex;
use Faker\Generator as Faker;

$factory->define(OwnBannerIndex::class, function (Faker $faker) {
    return [
        'image'=>"https://img95.699pic.com/photo/50063/6268.jpg_wh300.jpg",
    ];
});
