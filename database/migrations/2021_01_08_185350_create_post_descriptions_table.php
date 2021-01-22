<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     * 发帖说明
     * @return void
     */
    public function up()
    {
        Schema::create('post_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('说明的标题');
            $table->text('content')->comment('说明的内容');
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
        Schema::dropIfExists('post_descriptions');
    }
}
