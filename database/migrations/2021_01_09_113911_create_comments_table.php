<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('content')->comment('回复的内容');
            $table->unsignedBigInteger('comment_user_id')->nullable()->comment('回复评论');
            $table->unsignedBigInteger('reply_user_id')->comment('发表评论');
//            $table->foreign('reply_user_id')->references('id')->on('users');

            $table->unsignedBigInteger('parent_reply_id')->nullable()->comment('父级发表评论');
            $table->unsignedBigInteger('information_id')->comment('便民信息');
            $table->foreign('information_id')->references('id')->on('convenient_information');


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
        Schema::dropIfExists('comments');
    }
}
