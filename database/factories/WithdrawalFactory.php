<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Withdrawal;
use Faker\Generator as Faker;

$factory->define(Withdrawal::class, function (Faker $faker) {
    return [
        'user_id'=>1,
        'amount'=>rand(10,100),
        'is_accept'=>rand(0,1)
    ];
});
