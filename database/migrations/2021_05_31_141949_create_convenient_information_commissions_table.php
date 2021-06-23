<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvenientInformationCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     * 发帖抽成 == 佣金
     * @return void
     */
    public function up()
    {
        Schema::create('convenient_information_commissions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 4, 2)->comment('发帖金额');
            $table->decimal('commissions', 4, 2)->comment('佣金');
            $table->decimal('rate', 4, 2)->comment('佣金比率');

            $table->unsignedBigInteger('user_id'); // 那个用户
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('information_id'); // 那个商铺
            $table->foreign('information_id')->references('id')->on('convenient_information')->onDelete('cascade');

            $table->boolean('is_display')->default(1)->comment('默认显示');
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
        Schema::dropIfExists('convenient_information_commissions');
    }
}
