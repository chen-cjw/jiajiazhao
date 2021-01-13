<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UserSeeder::class);
//         $this->call(CardCategorySeeder::class);
//         $this->call(AbbrCategorySeeder::class);
//         $this->call(LocalCarpoolingSeeder::class);
//         $this->call(NoticeSeeder::class);
//         $this->call(BannerSeeder::class);
//         $this->call(ConvenientInformationSeeder::class);
//         $this->call(ShopSeeder::class);
//         $this->call(SettingSeeder::class);
//         $this->call(BannerSeeder::class);
//         $this->call(AdvertisingSpaceSeeder::class);
//         $this->call(PostDescriptionSeeder::class);
//         $this->call(CommentSeed::class);
//        $this->call(CarpoolingSeed::class);
//        $this->call(AdminTablesSeeder::class);
        $this->call(DialingSeed::class);
    }
}
