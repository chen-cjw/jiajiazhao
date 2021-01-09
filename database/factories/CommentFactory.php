<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content'=>$faker->text,
        'reply_user_id'=>1,
        'information_id'=>1,
    ];
});
