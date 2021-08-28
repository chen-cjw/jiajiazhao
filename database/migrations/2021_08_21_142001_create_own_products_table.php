<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('商品名称');
            $table->text('description')->comment('商品详情');
            $table->text('image')->comment('商品封面图片文件路径');
            $table->boolean('on_sale')->default(true)->comment('商品是否正在售卖');
            $table->float('rating')->default(5)->comment('商品平均评分');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->unsignedInteger('review_count')->default(0)->comment('评价数量');
            $table->decimal('price', 10, 2)->comment('SKU 最低价格');
            $table->unsignedBigInteger('own_category_id')->comment('所属商品 id');
            $table->unsignedInteger('sort')->comment('排序');
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
        Schema::dropIfExists('own_products');
    }
}
