<?php

use Illuminate\Database\Seeder;

class AbbrTwoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\AbbrTwoCategory::class, 7)->create();

    }
}
