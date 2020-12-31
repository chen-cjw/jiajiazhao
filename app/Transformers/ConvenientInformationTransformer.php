<?php
namespace App\Transformers;
use App\Model\ConvenientInformation;
use League\Fractal\TransformerAbstract;

class ConvenientInformationTransformer extends TransformerAbstract
{

    public function transform(ConvenientInformation $convenientInformation)
    {
        //  'title','content','location','view','card_id','user_id','no',
        //        'card_fee','top_fee','paid_at','payment_method','payment_no'
        return [
            'id' => $convenientInformation->id,
            'title' => $convenientInformation->title,
            'content' => $convenientInformation->content,
            'location' => $convenientInformation->location,
            'view' => $convenientInformation->view,
            'card_id' => $convenientInformation->card_id,
            'user_id' => $convenientInformation->user_id,
            'no' => $convenientInformation->no,
            'card_fee' => $convenientInformation->card_fee,
            'top_fee' => $convenientInformation->top_fee,
            'paid_at' => $convenientInformation->paid_at,
            'payment_method' => $convenientInformation->payment_method,
            'payment_no' => $convenientInformation->payment_no,
            'created_at' => $convenientInformation->created_at->toDateTimeString(),
            'updated_at' => $convenientInformation->updated_at->toDateTimeString(),
        ];
    }
}