<?php
namespace App\Transformers;
use App\Model\AbbrCategory;
use App\Model\Shop;
use League\Fractal\TransformerAbstract;

class ShopTransformer extends TransformerAbstract
{
    public function transform(Shop $shop)
    {
        return [
            'id' => $shop->id,
            'one_abbr' => $shop->one_abbr,
            'two_abbr' => json_decode($shop->two_abbr),
            'name' => $shop->name,
            'area' => $shop->area,
            'detailed_address' => $shop->detailed_address,
            'contact_phone' => $shop->contact_phone,
            'wechat' => $shop->wechat,
            'logo' => json_decode($shop->logo),
            'service_price' => $shop->service_price,
            'merchant_introduction' => $shop->merchant_introduction,
            'platform_licensing' => $shop->platform_licensing,
            'is_top' => $shop->is_top,
            'no' => $shop->no,
            'amount' => $shop->amount,
            'created_at' => $shop->created_at->toDateTimeString(),
            'updated_at' => $shop->updated_at->toDateTimeString(),
        ];
    }

//    public function includeAbbr(AbbrCategory $abbrCategory)
//    {
//        return $this->item($abbrCategory->abbr,new AbbrCategoryTransformer());
//    }

}