<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingSpacesTable extends Migration
{
    /**
     * Run the migrations.
     * 广告位
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('image')->comment('图片');
            $table->text('link')->nullable()->comment('跳转的链接');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->integer('sort')->comment('排序');
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
        Schema::dropIfExists('advertising_spaces');
    }
}
