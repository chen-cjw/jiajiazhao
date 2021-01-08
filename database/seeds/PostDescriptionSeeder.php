<?php

use Illuminate\Database\Seeder;

class PostDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\PostDescription::class, 1)->create();

    }
}
