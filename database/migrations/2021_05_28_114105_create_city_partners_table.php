<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityPartnersTable extends Migration
{
    /**
     * Run the migrations.
     * 城市合伙人
     * @return void
     */
    public function up()
    {
        Schema::create('city_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('姓名');
            $table->string('phone')->nullable()->comment('手机号');
            $table->string('IDCard')->nullable()->comment('身份证号');
            $table->string('in_city')->nullable()->comment('入住的城市');
            $table->unsignedInteger('is_partners')->default(0)->comment('是否关闭合伙人身份');
            $table->unsignedBigInteger('user_id')->unique(); // 不可以重复申请
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('no')->unique()->comment('订单流水号');
            $table->decimal('amount', 10, 2)->comment('费用');
            $table->decimal('balance', 10, 3)->default(0)->comment('可提金额');
            $table->decimal('total_balance', 10, 3)->default(0)->comment('总金额');
;
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_method')->default('wechat')->nullable()->comment('支付方式');
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
        Schema::dropIfExists('city_partners');
    }
}
