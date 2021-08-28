<?php

use Illuminate\Database\Seeder;

class OwnCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Shop\OwnCategory::class, 10)->create();

    }
}
