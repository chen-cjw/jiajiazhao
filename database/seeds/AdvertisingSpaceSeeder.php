<?php

use Illuminate\Database\Seeder;

class AdvertisingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\AdvertisingSpace::class, 3)->create();

    }
}
