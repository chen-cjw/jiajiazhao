<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('发布帖子/分类名称');
            $table->bigInteger('sort')->comment('排序');
            $table->boolean('is_display')->default(1)->comment('是否显示');
            $table->string('logo')->default('https://app-api.hanbinsite.top/storage/20210115/4ciZapXlBxYJYHSyBqwQwuIruFjc5AckWiKb81QJ.jpg')->comment('首页分类logo');
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
        Schema::dropIfExists('card_categories');
    }
}
