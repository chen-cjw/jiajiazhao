<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Model\Setting;
use App\Model\ShopTop;
use App\Shop;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopTopController extends Controller
{
    // 置顶
    public function store($id)
    {

        $userId = auth('api')->id();
        $res = Shop::where('id',$id)->where('user_id',$userId)->firstOrFail();
        if (bcdiv(bcsub(strtotime($res->due_date),time()),86400,0) < 365) {
            $topAmount = Setting::where('key', 'shop_top_fee')->value('value');
        }else {
            $topAmount = Setting::where('key', 'shop_top_fee_two')->value('value');
        }
        $shopTop = ShopTop::where('shop_id',$res->id);
        if ($shopTop->first()) {
            $shopTop->update([
                'no'=>Shop::findAvailableNo(),
                'top_amount'=>$topAmount
            ]);
        }else {
            ShopTop::create([
                'shop_id' => $res->id,
                'no' => Shop::findAvailableNo(),
                'top_amount' => $topAmount,
                'user_id'=>$userId
            ]);
        }
        return ['code'=>200,'msg'=>'ok','data'=>ShopTop::where('shop_id',$res->id)->first()];
    }

    public function payByWechat($id) // shopTop 的ID
    {
        try {
            // 这个只是为了修改两个值，是否置顶(is_top)/置顶费(top_amount)
//            $shop = auth('api')->user()->shop()->where('id', $id)->firstOrFail();
            $shopTop = ShopTop::where('id',$id)->where('user_id',auth('api')->id())->firstOrFail();
            //              // bcsub — 减法 （到期时间-当前时间）/86400>365 ,就是两年，否则是一年
            if (bcsub(time(), strtotime($shopTop->updated_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            $result = $this->app->order->unify([
                'body' => '支付会员版订单：' . $shopTop->no,
                'out_trade_no' => $shopTop->no,
                'total_fee' => $shopTop->top_amount * 100,//$wechatPay->total_fee * 100,
                'notify_url' => config('app.app_pay_url')."shop_top_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'order' => $shopTop,
                'msg' => 'ok'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 422,
                "msg" => $e->getMessage(),
                'data' => ''
            ]);
        }
    }

    public function wechatNotify()
    {
        Log::info('进入发送通知/置顶');
        $response = $this->app->handlePaidNotify(function($message, $fail){
            Log::info('微信支付订单号');
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = ShopTop::where('no',$message['out_trade_no'])->first();
            // 这个只是为了修改两个值，是否置顶(is_top)/置顶费(top_amount)
            $shop = Shop::where('id',$order->id)->first();
            if (!$order) {
                Log::error('订单不存在则告知微信支付');
                return 'fail';
            }
//                // 订单已支付
            if ($order->paid_at) {
                Log::error('告知微信支付此订单已处理');
                return app('wechat_pay')->success();
            }
            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                Log::info('告诉微信，我已经处理完了，订单没找到，别再通知我了');
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// todo <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            Log::info('建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付');

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                Log::info('表示通信状态，不代表支付状态');

                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    Log::info('用户支付成功');

//                    $order->status = 'paid';
                    $order->paid_at = Carbon::now(); // 更新支付时间为当前时间
                    $order->payment_no = $message['transaction_id']; // 支付平台订单号
                    $shop->top_amount = $order->top_amount;
                    $shop->is_top = 1;

                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    Log::info('用户支付失败');
                    $order->status = 'paid_fail';
                }
            } else {
                Log::info('通信失败，请稍后再通知我');
                return $fail('通信失败，请稍后再通知我');
            }
            $order->save(); // 保存订单
            $shop->save();  // 修改店铺置顶
            // todo 订单支付成功通知,支付平台的订单号
            return true; // 返回处理完成
        });
        return $response;

    }

}
