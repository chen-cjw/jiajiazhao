<?php

use Illuminate\Database\Seeder;

class OwnProduct extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Shop\OwnProduct::class, 10)->create();

    }
}
