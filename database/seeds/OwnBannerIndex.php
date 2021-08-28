<?php

use Illuminate\Database\Seeder;

class OwnBannerIndex extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Shop\OwnBannerIndex::class, 5)->create();
    }
}
