<?php

use Illuminate\Database\Seeder;

class CardCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\CardCategory::class, 20)->create();
    }
}
