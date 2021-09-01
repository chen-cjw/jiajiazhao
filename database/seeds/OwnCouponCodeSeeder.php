<?php

use Illuminate\Database\Seeder;

class OwnCouponCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Shop\OwnCouponCode::class, 10)->create();

    }
}
