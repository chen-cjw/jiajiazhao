<?php

use Illuminate\Database\Seeder;

class PostTipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\PostTip::create([
            'content'=>'Run the database seeds.'
        ]);
    }
}
