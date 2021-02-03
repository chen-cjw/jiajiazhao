<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_comments', function (Blueprint $table) {
            $table->id();
            $table->string('content')->comment('回复的内容');
            $table->unsignedInteger('star')->default(5)->comment('星级');
//            $table->unsignedBigInteger('user_id');
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('comment_user_id')->nullable()->comment('回复评论');
            $table->unsignedBigInteger('reply_user_id')->comment('发表评论');
            $table->foreign('reply_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('parent_reply_id')->nullable()->comment('父级发表评论');

            $table->unsignedBigInteger('shop_id')->comment('商铺');
            $table->foreign('shop_id')->references('id')->on('shops');
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
        Schema::dropIfExists('shop_comments');
    }
}
