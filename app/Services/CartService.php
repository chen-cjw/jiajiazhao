<?php

namespace App\Services;

use App\Model\Shop\OwnCartItem;

class CartService
{
    public function get()
    {
        return auth('api')->user()->OwnCartItems()->with(['ownProductSku.OwnProduct'])->get();
    }

    public function add($skuId, $amount)
    {
        $user = auth('api')->user();
        $item = $user->OwnCartItems->where('own_product_sku_id', $skuId)->first();
        // 从数据库中查询该商品是否已经在购物车中
        if ($item) {
            // 如果存在则直接叠加商品数量
            $item->update([
                'amount' => $item->amount + $amount,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $item = new OwnCartItem(['amount' => $amount]);
            $item->user()->associate($user);
            $item->ownProductSku()->associate($skuId);
            $item->save();
        }

        return $item;
    }

    public function remove($skuIds)
    {
        // 可以传单个 ID，也可以传 ID 数组
//        if (!is_array($skuIds)) {
//            $skuIds = [$skuIds];
//        }
        auth('api')->user()->ownCartItems()->whereIn('own_product_sku_id', $skuIds)->delete();
    }
}
