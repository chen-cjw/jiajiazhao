<?php

use Illuminate\Database\Seeder;

class OwnProductSku extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Shop\OwnProductSku::class, 10)->create();

    }
}
