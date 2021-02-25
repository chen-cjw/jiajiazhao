<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('用户user_id');
            $table->string('order_number')->comment('商户订单号');
            $table->integer('amount')->comment('金额');
            $table->string('payment_no')->nullable()->comment('金额');
//            $table->tinyInteger('type')->comment('1付款到微信,2付款到银行卡');
            $table->tinyInteger('status')->comment('1付款成功,2待付款,3付款失败')->default(2);
            $table->text('intro')->comment('备注')->nullable(2);
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
        Schema::dropIfExists('payment_orders');
    }
}
