<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('z_notices', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('通知标题');
            $table->text('content')->nullable()->comment('通知内容');
            $table->string('image')->comment('图片');
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
        Schema::dropIfExists('z_notices');
    }
}
