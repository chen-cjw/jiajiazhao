<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnOrderRequest;
use App\Model\Shop\OwnOrder;
use App\Model\Shop\OwnProductSku;
use App\Model\Shop\OwnUserAddress;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exceptions\InvalidRequestException;

class OwnOrdersController extends Controller
{
    public function store(OwnOrderRequest $request)
    {
        $user  = auth('api')->user();
        // 开启一个数据库事务
        $order = \DB::transaction(function () use ($user, $request) {
            $address = OwnUserAddress::find($request->input('address_id'));
            // 更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            // 创建一个订单
            $order = new OwnOrder([
                'address' => [ // 将地址信息放入订单中
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => $request->input('remark'),
                'total_amount' => 0,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            $items = $request->input('items');
            // 遍历用户提交的 SKU
            foreach ($items as $data) {
                $sku  = OwnProductSku::find($data['own_product_sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price' => $sku->price,
                ]);
                $item->ownProduct()->associate($sku->own_product_id);
                $item->ownProductSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('own_product_sku_id');
            $user->ownCartItems()->whereIn('own_product_sku_id', $skuIds)->delete();

            return $order;
        });

        return $order;
    }
}
