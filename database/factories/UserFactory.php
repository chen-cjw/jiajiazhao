<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'phone' => 18361771543,
        'ml_openid' => $faker->name,
        'nickname' => $faker->name,
        'avatar' => 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1654242150,297019303&fm=26&gp=0.jpg',
        'sex' => 1,
        'parent_id' =>  null,
        'city_partner' => 0
    ];
});
