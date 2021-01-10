<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalCarpoolingsTable extends Migration
{
    /**
     * Run the migrations.
     * 本地拼车
     * @return void
     */
    public function up()
    {
        Schema::create('local_carpoolings', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->comment('手机号');
            $table->string('name_car')->comment('车主姓名');
            $table->string('capacity')->nullable()->comment('承载重量');
            $table->string('go')->comment('出发地');
            $table->string('end')->comment('目的地');
            $table->string('departure_time')->comment('出发时间');
            $table->string('seat')->nullable()->comment('剩余座位');
            $table->string('other_need')->nullable()->comment('其他需求');
            $table->boolean('is_go')->default(0)->comment('是否出发');
            $table->enum('type',['person_looking_car','car_looking_person','good_looking_car','car_looking_good'])->comment('类目');

            $table->decimal('lng',20,10)->comment('当前纬度');
            $table->decimal('lat',20,10)->comment('当前经度');
            $table->string('area')->nullable()->comment('自动获取所在地区');
            // 支付
            $table->string('no')->unique()->comment('订单流水号');
            $table->decimal('amount', 10, 2)->comment('服务金额');
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_method')->default('wechat')->nullable()->comment('支付方式');
            $table->string('payment_no')->nullable()->comment('支付平台订单号');
            $table->boolean('closed')->default(false)->comment('收否关闭订单');
            // 发布人
            $table->unsignedBigInteger('user_id')->comment('发布人');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('local_carpoolings');
    }
}
