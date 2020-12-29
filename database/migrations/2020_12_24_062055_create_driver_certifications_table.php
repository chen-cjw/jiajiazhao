<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     * 司机身份认证
     * @return void
     */
    public function up()
    {
        Schema::create('driver_certifications', function (Blueprint $table) {
            $table->id();
            $table->string('id_card')->comment('身份证正面照');
            $table->string('driver')->comment('驾驶证');
            $table->string('action')->comment('行驶证');
            $table->string('car')->comment('车辆照片');
            $table->boolean('is_display')->comment('是否通过审核');

            $table->unsignedBigInteger('user_id')->comment('认证人');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * ConvenientInformation
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_certifications');
    }
}
