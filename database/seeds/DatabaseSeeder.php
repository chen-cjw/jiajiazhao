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

        $this->call(OwnCouponCodeSeeder::class);
//        $this->call(OwnBannerIndex::class);
//        $this->call(OwnCategory::class);
//        $this->call(OwnProduct::class);
//        $this->call(OwnProductSku::class);
//        $this->call(UserAdminSeeder::class);
//        $this->call(CityPayOrderSeeder::class);
//         $this->call(UserSeeder::class);//3
//         $this->call(CardCategorySeeder::class);
//         $this->call(AbbrCategorySeeder::class);
//         $this->call(LocalCarpoolingSeeder::class);
//         $this->call(NoticeSeeder::class);
//         $this->call(BannerSeeder::class);
//         $this->call(ConvenientInformationSeeder::class);
//         $this->call(ShopSeeder::class);
//         $this->call(SettingSeeder::class);//2
//         $this->call(BannerSeeder::class);
//         $this->call(AdvertisingSpaceSeeder::class);
//         $this->call(PostDescriptionSeeder::class);
//         $this->call(CommentSeed::class);
//        $this->call(CarpoolingSeed::class);
//        $this->call(AdminTablesSeeder::class);//1
//        $this->call(DialingSeed::class);
//        $this->call(AbbrTwoCategorySeeder::class);
//        $this->call(UserFavoriteCardSeeder::class);
//        $this->call(UserFavoriteShopSeeder::class);
//        $this->call(PostTipSeeder::class);
//        $this->call(BannerInformationShowSeeder::class);
//        $this->call(BannerPersonSeeder::class);
//        $this->call(MerchantEnteringAgreementSeeder::class);
//        $this->call(MerchantPrivacyAgreementSeeder::class);
//        $this->call(BannerLocalSeeder::class);
    }
}
