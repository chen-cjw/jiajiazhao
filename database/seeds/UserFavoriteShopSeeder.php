<?php

use Illuminate\Database\Seeder;

class UserFavoriteShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\UserFavoriteShop::class, 2)->create();

    }
}
