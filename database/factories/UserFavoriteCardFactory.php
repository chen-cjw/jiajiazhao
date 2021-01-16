<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\UserFavoriteCard;
use Faker\Generator as Faker;

$factory->define(UserFavoriteCard::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'information_id'=>rand(1,10)
    ];
});
