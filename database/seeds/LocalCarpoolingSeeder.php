<?php

use Illuminate\Database\Seeder;

class LocalCarpoolingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\LocalCarpooling::class, 200)->create();
    }
}
