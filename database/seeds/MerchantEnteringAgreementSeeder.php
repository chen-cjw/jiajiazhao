<?php

use Illuminate\Database\Seeder;

class MerchantEnteringAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\MerchantEnteringAgreement::class, 1)->create();

    }
}
