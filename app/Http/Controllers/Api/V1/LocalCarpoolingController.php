<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LocalCarpoolingRequest;
use App\Model\LocalCarpooling;
use App\Model\Setting;
use App\Transformers\LocalCarpoolingTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Factory;

class LocalCarpoolingController extends Controller
{
    public function __construct()
    {
        $this->app = app('wechat.payment');
    }
    // 本地拼车
    public function index()
    {
        $query = LocalCarpooling::orderBy('created_at','desc');
        if($type = request('type')) {
            $query = $query->where('type',$type);
        }
        if($go = request('go')) {
            $query = $query->where('go',$go);

        }
        if($end = request('end')) {
            $query = $query->where('end',$end);

        }
        if (config('app.city') == 1) {
            if (request('area')) {
                $query = $query->where('area', 'like', '%' . request('area') . '%');

            }
        }
        $query = $query->where('paid_at','!=',null)->paginate();
        return $this->responseStyle('ok',200,$query);
        return $this->response->paginator($local,new LocalCarpoolingTransformer());
    }

    // 发布(车找人和车找货是需要认证的) todo 后端配合
    public function store(LocalCarpoolingRequest $request)
    {

        if (auth('api')->user()->is_certification == 0 && $request->type == 'car_looking_person' || auth('api')->user()->is_certification == 0 && $request->type == 'car_looking_good') {
            $this->responseStyle('您尚未提交认证，请先去认证通过！',422,'');
        }else {

            if(auth('api')->user()->certification()->where('is_display',0)->first()) {
                $this->responseStyle('认证正在审核请耐心等待！',422,'');
            }
            $requestData = $request->only(['phone','name_car','capacity','go','end','departure_time','seat','other_need','is_go','type','lng','lat','area']);
            $requestData['user_id'] = auth('api')->id();
            // 流水订单号
            $requestData['no'] = LocalCarpooling::findAvailableNo();
            $requestData['amount'] = Setting::where('key','localCarpoolingAmount')->value('value');
            $res = LocalCarpooling::create($requestData);
            return $this->responseStyle('ok',200,$res);
        }
    }

    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {

            // 校验权限
            $localCarpool = auth('api')->user()->localCarpool()->where('id', $id)->firstOrFail();

            // bcsub — 减法
            if (bcsub(time(), strtotime($localCarpool->created_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($localCarpool->paid_at || $localCarpool->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '订单：' . $localCarpool->no,
                'out_trade_no' => $localCarpool->no,
                'total_fee' => $localCarpool->amount * 100,//$wechatPay->total_fee * 100,
                'notify_url' => config('wechat.payment.default.notify_url'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
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
        Log::info('进入发送通知');
        $response = $this->app->handlePaidNotify(function($message, $fail){
            Log::info('微信支付订单号');
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = LocalCarpooling::where('no',$message['out_trade_no'])->first();
            if (!$order) {
                Log::error('订单不存在则告知微信支付');
                return 'fail';
            }
            // 订单已支付
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
                    $order->paid_at = Carbon::now(); // 更新支付时间为当前时间
                    $order->payment_no = $message['transaction_id']; // 支付平台订单号
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
            // todo 订单支付成功通知,支付平台的订单号
            return true; // 返回处理完成
        });
        return $response;

    }


    // 车辆是否已经出发了
    public function update($id)
    {
        return auth('api')->user()->local()->where('id',$id)->update(['is_go'=>true]);
        return $this->response->created();
    }



}
