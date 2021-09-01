<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersAddCouponCodeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('own_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('own_coupon_code_id')->nullable()->after('paid_at');
            $table->foreign('own_coupon_code_id')->references('id')->on('own_coupon_codes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('own_orders', function (Blueprint $table) {
            $table->dropForeign(['own_coupon_code_id']);
            $table->dropColumn('own_coupon_code_id');
        });
    }
}
