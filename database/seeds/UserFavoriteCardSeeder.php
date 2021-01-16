<?php

use Illuminate\Database\Seeder;

class UserFavoriteCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\UserFavoriteCard::class, 2)->create();

    }
}
