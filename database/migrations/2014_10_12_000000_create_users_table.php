<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ml_openid')->unique()->nullable()->comment('小程序标识');
            $table->string('phone')->nullable()->unique()->comment('用户手机号');
            $table->string('avatar')->nullable()->comment('用户头像url');
            $table->dateTime('birthday')->nullable()->comment('出生日期');

            $table->string('nickname')->nullable()->comment('用户昵称');
            $table->boolean('sex')->nullable()->default(1)->comment('性别');
            $table->bigInteger('parent_id')->nullable()->comment('邀请人');
            //$table->nestedSet();
            $table->boolean('is_member')->default(1)->comment('商家0/会员1');
            $table->boolean('is_certification')->default(0)->comment('拼车司机是否认证！');

            $table->unsignedDecimal('balance',10,3)->default(0)->comment('城市合伙人可提现的额度');
            $table->boolean('city_partner')->default(0)->comment('城市合伙人');
            $table->string('ref_code')->unique()->comment('邀请码');
            $table->string('code')->nullable()->comment('邀请码');
            $table->string('sessionUserInformation')->nullable()->comment('session');

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
        Schema::dropIfExists('users');
    }
}
