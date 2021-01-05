<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Setting;
use Faker\Generator as Faker;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'key' => 'localCarpoolingAmount',
        'value' => '0.01'
    ];
});
