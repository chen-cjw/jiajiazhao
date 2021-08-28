<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('image')->comment('封面');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->unsignedInteger('sort')->default(0)->comment('排序');

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
        Schema::dropIfExists('own_categories');
    }
}
