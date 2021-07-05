<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *    // 发帖抽成/合伙人
     * @return void
     */
    public function up()
    {
        Schema::create('information_commissions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 4)->comment('发帖金额');
            $table->decimal('commissions', 10, 4)->comment('获取的佣金');
            $table->decimal('rate', 4, 2)->default(0)->comment('佣金比率');

            $table->unsignedBigInteger('user_id'); // 那个用户
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('parent_id'); // 那个用户

            $table->unsignedBigInteger('information_id'); // 那个商铺
            $table->foreign('information_id')->references('id')->on('convenient_information')->onDelete('cascade');

            $table->string('district')->comment('那个区域(城市合伙人)');
            $table->boolean('is_pay')->default(0)->comment('是否到账');
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
        Schema::dropIfExists('information_commissions');
    }
}
