<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\MerchantPrivacyAgreement;
use Faker\Generator as Faker;

$factory->define(MerchantPrivacyAgreement::class, function (Faker $faker) {
    return [
        'content'=>$faker->text.$faker->text
    ];
});
