<?php

use Illuminate\Database\Seeder;

class CarpoolingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Carpooling::class, 1)->create();

    }
}
