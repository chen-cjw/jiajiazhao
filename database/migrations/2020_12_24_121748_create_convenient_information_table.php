<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvenientInformationTable extends Migration
{
    /**
     * Run the migrations.
     * 便民信息
     * @return void
     */
    public function up()
    {
        Schema::create('convenient_information', function (Blueprint $table) {
            $table->id();

            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('location')->comment('自动定位');
            $table->decimal('lng',20,10)->comment('当前纬度');
            $table->decimal('lat',20,10)->comment('当前经度');

            $table->string('view')->default(0)->comment('浏览量');
            $table->unsignedBigInteger('card_id')->comment('帖子分类');
            $table->foreign('card_id')->references('id')->on('card_categories');
            $table->unsignedBigInteger('user_id')->comment('发布人');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('no')->unique()->comment('订单流水号');
            $table->decimal('card_fee', 10, 2)->comment('发帖费用');
            $table->decimal('top_fee', 10, 2)->comment('置顶费用');
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
        Schema::dropIfExists('convenient_information');
    }
}
