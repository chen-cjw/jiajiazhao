<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerLocalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_locals', function (Blueprint $table) {
            $table->id();
            $table->string('image')->comment('图片');
            $table->text('link')->nullable()->comment('跳转的链接');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->integer('sort')->default(0)->comment('排序');

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
        Schema::dropIfExists('banner_locals');
    }
}
