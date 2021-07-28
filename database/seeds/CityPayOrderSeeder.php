<?php

use Illuminate\Database\Seeder;

class CityPayOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x=0; $x<=1000; $x++) {
            if ($user = \App\User::where('id',$x)->first()) {
                \App\Model\CityPayOrder::create([
                    'user_id'=>$x,
                    'amount'=>rand(10,300).'0',
                    'intro'=>rand(36,3294),
                    'status'=>2
                ]);
            }
        }
    }
}
