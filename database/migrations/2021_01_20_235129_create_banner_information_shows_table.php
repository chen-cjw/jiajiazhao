<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerInformationShowsTable extends Migration
{
    /**
     * Run the migrations.
     * 列表形式
     * @return void
     */
    public function up()
    {
        Schema::create('banner_information_shows', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable()->comment('介绍');
            $table->string('image')->comment('图片');
            $table->text('link')->nullable()->comment('跳转的链接');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->integer('sort')->default(0)->comment('排序');
            $table->enum('type',['one','two'])->default('two')->comment('one(推荐位)|two(第二部分轮播图)');

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
        Schema::dropIfExists('banner_information_shows');
    }
}
