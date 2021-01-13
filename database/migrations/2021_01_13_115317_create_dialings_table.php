<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('phone')->comment('拨打的手机号');

            $table->string('model_type')->nullable()->comment('拨打的商户还是出租车');
            $table->string('model_id')->nullable()->comment('做详情用');

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
        Schema::dropIfExists('dialings');
    }
}
