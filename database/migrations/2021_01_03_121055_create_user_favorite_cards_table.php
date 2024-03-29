<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFavoriteCardsTable extends Migration
{
    /**
     * Run the migrations.
     * 我收藏的帖子
     * @return void
     */
    public function up()
    {
        Schema::create('user_favorite_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('information_id');
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
        Schema::dropIfExists('user_favorite_cards');
    }
}
