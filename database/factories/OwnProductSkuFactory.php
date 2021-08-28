<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Shop\OwnProductSku;
use Faker\Generator as Faker;

$factory->define(OwnProductSku::class, function (Faker $faker) {
    return [
        'title'       => $this->faker->word,
        'description' => $this->faker->sentence,
        'price'       => $this->faker->randomNumber(4),
        'stock'       => $this->faker->randomNumber(5),
        'own_product_id' => rand(1,10)
    ];
});
