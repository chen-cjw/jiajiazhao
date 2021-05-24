<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZProductsTable extends Migration
{
    /**
     * Run the migrations.
     * 商品
     * @return void
     */
    public function up()
    {
        Schema::create('z_products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('商品名称');
            // todo 轮播图，一对多
            $table->decimal('discounted_price', 10, 2)->default(0)->comment('折后价格');
            $table->decimal('price', 10, 2)->default(0)->comment('价格');
            $table->unsignedInteger('sales_volume')->default(0)->comment('销量');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->unsignedInteger('page_view')->default(0)->comment('浏览量');
            $table->unsignedInteger('buy_type')->default(0)->comment('购买类型');
            $table->boolean('is_coupon')->default(0)->comment('优惠券');


            $table->string('server')->nullable()->comment('服务');
            $table->boolean('on_sale')->default(true)->comment('是否显示');
            $table->boolean('on_ship')->default(true)->comment('是否包邮');
            $table->boolean('on_hot')->default(true)->comment('是否色们推荐');
            $table->unsignedInteger('sort_num')->default(0)->comment('排序');
            $table->text('description')->nullable()->comment('商品详情');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('z_categories')->onDelete('cascade');

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
        Schema::dropIfExists('z_products');
    }
}
