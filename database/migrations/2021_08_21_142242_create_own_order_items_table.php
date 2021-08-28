<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('own_order_id')->comment('所属订单 ID');
            $table->foreign('own_order_id')->references('id')->on('own_orders')->onDelete('cascade');
            $table->unsignedBigInteger('own_product_id')->comment('对应商品 ID');
            $table->foreign('own_product_id')->references('id')->on('own_products')->onDelete('cascade');
            $table->unsignedBigInteger('own_product_sku_id')->comment('对应商品 SKU ID');
            $table->foreign('own_product_sku_id')->references('id')->on('own_product_skus')->onDelete('cascade');
            $table->unsignedInteger('amount')->comment('数量');
            $table->decimal('price', 10, 2)->comment('单价');
            $table->unsignedInteger('rating')->nullable()->comment('用户打分');
            $table->text('review')->nullable()->comment('用户打分');
            $table->timestamp('reviewed_at')->nullable()->comment('用户打分');
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
        Schema::dropIfExists('own_order_items');
    }
}
