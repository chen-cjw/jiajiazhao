<?php

use Illuminate\Database\Seeder;

class MerchantPrivacyAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\MerchantPrivacyAgreement::class, 1)->create();

    }
}
