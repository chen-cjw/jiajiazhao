<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbbrTwoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abbr_two_categories', function (Blueprint $table) {
            $table->id();
            $table->string('abbr')->nullable()->comment('分类');
            $table->bigInteger('sort')->default(0)->comment('排序大的在上');
            $table->text('logo')->comment('首页分类logo');
            $table->enum('type',['other','shop'])->comment('shop店铺');

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
        Schema::dropIfExists('abbr_two_categories');
    }
}
