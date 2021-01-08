<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image')->comment('图片');
            $table->text('link')->comment('跳转的链接');
            $table->boolean('is_display')->comment('是否显示');
            $table->integer('sort')->comment('排序');
            $table->enum('type',['index_one','index_two'])->comment('index_one(首页第一部分轮播图)|index_two(首页第二部分轮播图)');

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
        Schema::dropIfExists('banners');
    }
}
