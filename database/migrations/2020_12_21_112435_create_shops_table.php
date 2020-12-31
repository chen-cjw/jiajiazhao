<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     * 商铺注册
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // 由于后台可能会乱修改，目前就是存的文字，不是id
            $table->unsignedBigInteger('one_abbr')->comment('行业分类/一级分类');

            $table->string('two_abbr0')->comment('行业分类/二级分类()');
            $table->string('two_abbr1')->comment('行业分类/二级分类()');
            $table->string('two_abbr2')->comment('行业分类/二级分类()');
            $table->string('name')->comment('店铺名');
            $table->string('area')->comment('自动获取所在地区');// 这里等下会也会存坐标
            $table->string('detailed_address')->comment('详细地址');// 这里等下会也会存坐标
            $table->string('contact_phone')->comment('联系方式');// 验证手机号码
            $table->string('wechat')->comment('个人微信');// 验证手机号码
            $table->string('logo')->comment('商户认证');// 图片上传
            $table->string('service_price')->comment('服务价格');
            $table->string('merchant_introduction')->comment('商户介绍');
            $table->bigInteger('platform_licensing')->comment('平台使用费');
            $table->boolean('is_top')->default(0)->comment('是否置顶');

//            $table->string('no')->unique()->comment('订单流水号');
//            $table->decimal('amount', 10, 2)->comment('服务金额');

//            $table->dateTime('paid_at')->nullable()->comment('支付时间');
//            $table->string('payment_method')->default('wechat')->nullable()->comment('支付方式');
//            $table->string('payment_no')->nullable()->comment('支付平台订单号');
//            $table->dateTime('due_date')->nullable()->comment('到期时间');

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
        Schema::dropIfExists('shops');
    }
}
