<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvenientInformationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     * 便民信息帖子评论
     * @return void
     */
    public function up()
    {
        Schema::create('convenient_information_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('举报人ID');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('convenient_information_id')->comment('帖子ID');
            $table->foreign('convenient_information_id')->references('id')->on('convenient_information');
            $table->string('remarks')->comment('备注为何举报');
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
        Schema::dropIfExists('convenient_information_comments');
    }
}
