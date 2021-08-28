<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Shop\OwnProduct;
use Faker\Generator as Faker;

$factory->define(OwnProduct::class, function (Faker $faker) {
    $image = $this->faker->randomElement([
        "https://cdn.learnku.com/uploads/images/201806/01/5320/7kG1HekGK6.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/1B3n0ATKrn.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/r3BNRe4zXG.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/C0bVuKB2nt.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/82Wf2sg8gM.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/nIvBAQO5Pj.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/XrtIwzrxj7.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/uYEHCJ1oRp.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/2JMRaFwRpo.jpg",
        "https://cdn.learnku.com/uploads/images/201806/01/5320/pa7DrV43Mw.jpg",
    ]);

    return [
        'title'        => $this->faker->word,
        'description'  => $this->faker->sentence,
        'image'        => $image,
        'on_sale'      => true,
        'rating'       => $this->faker->numberBetween(0, 5),
        'sold_count'   => 0,
        'review_count' => 0,
        'price'        => '0.0'.rand(1,9),
        'own_category_id'=>rand(1,3)
    ];
});
