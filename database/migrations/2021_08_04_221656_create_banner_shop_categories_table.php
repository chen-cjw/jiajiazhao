<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerShopCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_shop_categories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->comment('图片');
            $table->text('link')->nullable()->comment('跳转的链接');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('area')->nullable()->comment('城市下面的区域');
            $table->string('province_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('district_id')->nullable();
            $table->unsignedBigInteger('abbr_category_id')->comment('发布人');
            $table->foreign('abbr_category_id')->references('id')->on('abbr_categories');


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
        Schema::dropIfExists('banner_shop_categories');
    }
}
