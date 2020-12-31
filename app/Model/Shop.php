<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
//$table->unsignedBigInteger('one_abbr')->comment('行业分类/一级分类');
//
//$table->json('two_abbr')->comment('行业分类/二级分类(数组序列化)');
//$table->string('name')->comment('店铺名');
//$table->string('area')->comment('自动获取所在地区');// 这里等下会也会存坐标
//$table->string('detailed_address')->comment('详细地址');// 这里等下会也会存坐标
//$table->string('contact_phone')->comment('联系方式');// 验证手机号码
//$table->string('wechat')->comment('个人微信');// 验证手机号码
//$table->string('logo')->comment('商户认证');// 图片上传
//$table->string('service_price')->comment('服务价格');
//$table->string('merchant_introduction')->comment('商户介绍');
//$table->bigInteger('platform_licensing')->comment('平台使用费');
//$table->boolean('is_top')->default(0)->comment('是否置顶');
//
//$table->string('no')->unique()->comment('订单流水号');
//$table->decimal('amount', 10, 2)->comment('服务金额');

    // 商户
    protected $fillable = [
        'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
        'logo','service_price','merchant_introduction','platform_licensing','is_top',
        'no','amount'
    ];
}
