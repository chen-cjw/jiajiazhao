<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerBannersTable extends Migration
{
    /**
     * Run the migrations.
     * 合伙人下广告轮播图
     * @return void
     */
    public function up()
    {
        Schema::create('partner_banners', function (Blueprint $table) {
            $table->id();
            $table->string('link_url')->default(null)->comment('可跳转的链接');
            $table->string('image')->default(null)->comment('图片');
            $table->string('area')->default(null)->comment('城市下面的区域');
            $table->boolean('is_display')->default(1)->comment('是否显示 0/1');
            $table->string('province_id');
            $table->string('city_id');
            $table->string('district_id');
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
        Schema::dropIfExists('partner_banners');
    }
}
