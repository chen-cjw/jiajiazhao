<?php

use Illuminate\Database\Seeder;

class BannerInformationShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\BannerInformationShow::class, 10)->create();

    }
}
