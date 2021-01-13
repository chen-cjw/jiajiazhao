<?php

use Illuminate\Database\Seeder;

class DialingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Dialing::class, 200)->create();
    }
}
