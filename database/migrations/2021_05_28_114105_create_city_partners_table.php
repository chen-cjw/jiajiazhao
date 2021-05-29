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
            $table->string('is_partners')->default(0)->comment('是否关闭合伙人身份');
            $table->unsignedBigInteger('user_id')->unique(); // 不可以重复申请
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
