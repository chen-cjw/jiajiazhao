<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     * 入住协议
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_agreements', function (Blueprint $table) {
            $table->id();
            $table->text('introduction')->comment('协议介绍');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlement_agreements');
    }
}
