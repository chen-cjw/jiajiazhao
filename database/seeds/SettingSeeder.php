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
        \App\Model\Setting::create([
            'key'=>'shop_fee_two',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'shop_top_fee_two',
            'value'=>0.01,
        ]);
        \App\Model\Setting::create([
            'key'=>'radius',
            'value'=>5,
        ]);
        \App\Model\Setting::create([
            'key'=>'award',
            'value'=>88,
        ]);
        // DriverCertification
        \App\Model\Setting::create([
            'key'=>'driverCertification',
            'value'=>1,
        ]);
        // informationDisplay
        \App\Model\Setting::create([
            'key'=>'informationDisplay',
            'value'=>1,
        ]);
    }
}
