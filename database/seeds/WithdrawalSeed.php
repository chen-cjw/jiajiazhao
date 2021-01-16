<?php

use Illuminate\Database\Seeder;

class WithdrawalSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Model\Withdrawal::class, 2)->create();

    }
}
