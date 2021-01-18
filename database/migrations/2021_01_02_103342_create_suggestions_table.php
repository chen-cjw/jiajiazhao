<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('content')->comment('投诉的内容');
            // 申请人
            $table->unsignedBigInteger('user_id')->comment('申请人');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('is_accept')->default(0)->comment('是否采纳');
            $table->unsignedBigInteger('localCarpooling_id')->default(null)->comment('有这个id，就是举报帖子，没有就是个人中心的举报');
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
        Schema::dropIfExists('suggestions');
    }
}
