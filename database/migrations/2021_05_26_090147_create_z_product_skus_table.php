<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     * 每个商品下面有多个颜色,款式,可以有多个SKU
     * @return void
     */
    public function up()
    {
        Schema::create('z_product_skus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('z_products')->onDelete('cascade');
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
        Schema::dropIfExists('z_product_skus');
    }
}
