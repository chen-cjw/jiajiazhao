<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ShopRequest;
use App\Model\Setting;
use App\Model\Shop;
use App\Model\UserFavoriteShop;
use App\Transformers\ShopTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    // 商户列表
    public function index()
    {
        $shopQuery = Shop::query();

        $shopQuery->where(function ($query) {
                $query->orWhere('two_abbr0',\request()->two_abbr)
                    ->orWhere('two_abbr1',\request()->two_abbr)
                    ->orWhere('two_abbr2',\request()->two_abbr);
            });
        // 人气 == 浏览量
        $shopQuery->orderBy('view','desc');

        $shop = $shopQuery->get();
        return $this->responseStyle('ok',200,$shop);

        return $this->response->collection($shop,new ShopTransformer());
    }
    
    // 入住 service_price 这个是一个图片
    public function store(ShopRequest $request)
    {
        $data = $request->only([
            'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
            'logo','service_price','merchant_introduction','is_top','lng','lat'
        ]);
        for ($i=0;$i<count($request->two_abbr);$i++) {
            $data['two_abbr'.$i] = $request->two_abbr[$i];
        }
        $data['no'] = Shop::findAvailableNo();
        $data['amount'] = $request->shop_fee == 0 ? Setting::where('key','shop_fee_two')->value('value') : $request->shop_fee;
        $data['top_amount'] = $request->shop_top_fee == 0 ? $request->shop_top_fee_two : $request->shop_top_fee;
        $data['platform_licensing'] = 0.01;
        $data['logo'] = json_encode($request->logo);
        $data['user_id'] = auth('api')->id();
        $res = Shop::create($data);
        return $this->responseStyle('ok',200,$res);

        return $this->response->created();
    }

    public function show($id)
    {
        Shop::where('id',$id)->increment('view');
        $shop = Shop::findOrFail($id);
        $user = auth('api')->user();
        if ($user->favoriteShops()->find($id)) {
            UserFavoriteShop::where('id',$id)->update(['created_at'=>date('Y:m:d H:i:s')]);
        }else {
            $user->favoriteShops()->attach(Shop::find($id));
        }
        return $this->responseStyle('ok',200,$shop);
    }

    public function uploadImg(Request $request)
    {
        return $this->uploadImages($request);
    }
    // 单图片上传
    public function uploadImages($request)
    {

        if ($request->isMethod('post')) {
            $file = $request->file('logo');
            Log::error('logo');
            Log::error($file);
            Log::error('logo');
            if($file->isValid()){
                $path = Storage::disk('public')->putFile(date('Ymd') , $file);
                if($path) {
                    return ['code' => 0 , 'msg' => '上传成功' , 'data' => $this->imagePath($path)];
                }
                else {
                    return ['code' => 400 , 'msg' => '上传失败'];
                }
            }
        } else {
            return ['code' => 400, 'msg' => '非法请求'];
        }
    }

    public function imagePath($path)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return \Storage::disk('public')->url($path);
    }

    // （当前纬度,当前经度）
    public function lat_lng($lng,$lat)
    {
        $res = DB::select("select * from shops where 
            (acos(sin(({$lat}*3.1415)/180)
            * sin((lat*3.1415)/180)
            + cos(({$lat}*3.1415)/180)
            * cos((lat*3.1415)/180)
            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
            * 6370.996) <= 5"
        );
        return $this->responseStyle('ok',200,$res);

        // return $res;
    }


    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {
            // 校验权限
            $shop = auth('api')->user()->shop()->where('id', $id)->firstOrFail();
            // bcsub — 减法
            if (bcsub(time(), strtotime($shop->created_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($shop->paid_at || $shop->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '支付会员版订单：' . $shop->no,
                'out_trade_no' => $shop->no,
                'total_fee' => $shop->amount * 100,//$wechatPay->total_fee * 100,
                'notify_url' => "https://api.dengshishequ.com/shop_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'order' => $shop,
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
            $order = Shop::where('no',$message['out_trade_no'])->first();
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

//                    $order->status = 'paid';
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
            $user = User::find($order->user_id);
//            order_wePay_success_notification($user->ml_openid,$order->payment_no,$order->paid_at,$order->amount,$order->name,'');
            return true; // 返回处理完成
        });

    }
}
