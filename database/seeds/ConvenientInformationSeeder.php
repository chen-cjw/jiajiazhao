<?php

use Illuminate\Database\Seeder;

class ConvenientInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\ConvenientInformation::class, 200)->create();
    }
}
