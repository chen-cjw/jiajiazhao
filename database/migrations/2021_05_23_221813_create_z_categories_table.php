<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * 分类，有的一级分类下有图片
     * @return void
     */
    public function up()
    {
        Schema::create('z_categories', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('分类名');
            $table->unsignedInteger('sort_num')->default(0)->comment('排序');
            $table->boolean('on_sale')->default(true)->comment('此类型是否显示');
            $table->unsignedBigInteger('category_id')->comment('该地址所属的用户');
            $table->foreign('category_id')->references('id')->on('z_categories')->onDelete('cascade');
            $table->string('image')->comment('分类下显示图片');
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
        Schema::dropIfExists('z_categories');
    }
}
