<?php

use Illuminate\Database\Seeder;

class BannerLocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\BannerLocal::class, 4)->create();

    }
}
