<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConvenientInformation extends Model
{

//$table->string('title')->comment('标题');
//$table->text('content')->comment('内容');
//$table->string('location')->comment('自动定位');
//$table->string('view')->comment('浏览量');
//$table->unsignedBigInteger('card_id')->comment('帖子分类');
//$table->foreign('card_id')->references('id')->on('card_categories');
//$table->unsignedBigInteger('user_id')->comment('发布人');
//$table->foreign('user_id')->references('id')->on('users');
//            $table->string('no')->unique()->comment('订单流水号');
//            $table->decimal('card_fee', 10, 2)->comment('发帖费用');
//            $table->decimal('top_fee', 10, 2)->comment('置顶费用');
//            $table->dateTime('paid_at')->nullable()->comment('支付时间');
//            $table->string('payment_method')->default('wechat')->nullable()->comment('支付方式');
//            $table->string('payment_no')->nullable()->comment('支付平台订单号');
    // 便民信息
    protected $fillable = [
        'title','content','location','lng','lat','view','card_id','user_id','no',
        'card_fee','top_fee','paid_at','payment_method','payment_no'
    ];
}
