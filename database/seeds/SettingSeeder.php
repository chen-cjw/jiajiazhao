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
            'localCarpoolingAmount'=>'0',
            ''=>'',
        ]);
    }
}
