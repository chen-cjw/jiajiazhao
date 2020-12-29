<?php
namespace App\Transformers;
use App\Model\LocalCarpooling;
use League\Fractal\TransformerAbstract;

class LocalCarpoolingTransformer extends TransformerAbstract
{

    public function transform(LocalCarpooling $carpooling)
    {
        return [
            'id' => $carpooling->id,
            'phone' => $carpooling->phone,
            'name_car' => $carpooling->name_car,
            'capacity' => $carpooling->capacity,
            'go' => $carpooling->go,
            'end' => $carpooling->end,
            'departure_time' => $carpooling->departure_time,
            'seat' => $carpooling->seat,
            'other_need' => $carpooling->other_need,
            'is_go' => $carpooling->is_go,
            'type' => $carpooling->type,
            'no' => $carpooling->no,
            'amount' => $carpooling->amount,
            'paid_at' => $carpooling->paid_at,
            'payment_no' => $carpooling->payment_no,
            'created_at' => $carpooling->created_at->toDateTimeString(),
            'updated_at' => $carpooling->updated_at->toDateTimeString(),
        ];
    }
}
//$table->string('phone')->comment('手机号');
//$table->string('name_car')->comment('车主姓名');
//$table->string('capacity')->comment('承载重量');
//$table->string('go')->comment('出发地');
//$table->string('end')->comment('目的地');
//$table->string('departure_time')->comment('出发时间');
//$table->string('seat')->comment('剩余座位');
//$table->string('other_need')->comment('其他需求');
//$table->boolean('is_go')->comment('是否出发');
//
//$table->enum('type',['person_looking_car','car_person_looking','good_looking_car','car_good_looking'])->comment('');
//
//    // 支付
//$table->string('no')->unique()->comment('订单流水号');
//$table->decimal('amount', 10, 2)->comment('服务金额');
//$table->dateTime('paid_at')->nullable()->comment('支付时间');
//$table->string('payment_method')->default('wechat')->nullable()->comment('支付方式');
//$table->string('payment_no')->nullable()->comment('支付平台订单号');