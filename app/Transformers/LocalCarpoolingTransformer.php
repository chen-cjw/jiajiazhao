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
