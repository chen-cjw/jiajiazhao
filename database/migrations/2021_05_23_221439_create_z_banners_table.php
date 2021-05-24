<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZBannersTable extends Migration
{
    /**
     * Run the migrations.
     * 轮播图
     * @return void
     */
    public function up()
    {
        Schema::create('z_banners', function (Blueprint $table) {
            $table->id();
            $table->text('image_url')->comment('图片路径');
            $table->string('href_url')->nullable()->comment('外链');
            $table->unsignedInteger('sort_num')->default(0)->comment('排序');
            $table->boolean('on_sale')->default(true)->comment('是否显示');

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
        Schema::dropIfExists('z_banners');
    }
}
