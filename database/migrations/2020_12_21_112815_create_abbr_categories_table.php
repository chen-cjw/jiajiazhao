<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbbrCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * 行业分类
     * @return void
     */
    public function up()
    {
        Schema::create('abbr_categories', function (Blueprint $table) {
            $table->id();
            $table->string('abbr')->nullable()->comment('分类');
            $table->bigInteger('sort')->default(0)->comment('排序大的在上');
            $table->text('logo')->nullable()->comment('首页分类logo');
            $table->text('image')->nullable()->comment('首页分类logo');
            $table->bigInteger('parent_id')->nullable()->comment('父级');
            $table->enum('type',['other','shop'])->default('shop')->comment('shop店铺');
            $table->enum('local',['one','two'])->default('one')->comment('首页店铺位置');
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
        Schema::dropIfExists('abbr_categories');
    }
}
