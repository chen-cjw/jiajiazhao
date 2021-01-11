<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\Setting::create([
            'key'=>'localCarpoolingAmount',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'information_card_fee',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'information_top_fee',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'shop_fee',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'shop_top_fee',
            'value'=>0.01,
        ]);

    }
}
