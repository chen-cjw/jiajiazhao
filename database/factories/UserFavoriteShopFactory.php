<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\UserFavoriteShop;
use Faker\Generator as Faker;

$factory->define(UserFavoriteShop::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'shop_id'=>rand(1,10)
    ];
});
