<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopTopsTable extends Migration
{
    /**
     * Run the migrations.
     * 置顶订单
     * @return void
     */
    public function up()
    {
        Schema::create('shop_tops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('user_id');
            $table->string('no')->unique()->comment('订单流水号');
            $table->decimal('top_amount', 10, 2)->comment('置顶金额');
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_no')->nullable()->comment('支付平台订单号');
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
        Schema::dropIfExists('shop_tops');
    }
}
