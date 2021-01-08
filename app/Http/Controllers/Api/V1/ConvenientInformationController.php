<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConvenientInformationRequest;
use App\Model\ConvenientInformation;
use App\Model\Setting;
use App\Transformers\ConvenientInformationTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Support\Facades\Log;

class ConvenientInformationController extends Controller
{
    public function __construct()
    {
        $this->app = app('wechat.payment');
    }
    // 便民信息列表
    public function index()
    {
        $res = ConvenientInformation::where('card_id',\request('card_id'))->paginate();
        return $this->responseStyle('ok',200,$res);
        return $this->response->paginator($res,new ConvenientInformationTransformer());
    }

    // 发布
    public function store(ConvenientInformationRequest $request)
    {
        $data = $request->only(['card_id','title','content','location','lng','lat']);
        $data['user_id'] = auth()->id();
        // 发帖的时候，有一部分的钱是到了邀请人哪里去了
        $parentId = auth('api')->user()->parent_id;

        $userParent = User::where('parent_id',$parentId)->first();
        // 邀请人获取积分
        if ($userParent) {
            if($userParent->city_partner== 1) {
                // 数据库的邀请人的额度就是增加百分之 50
                $balanceCount = bcadd($request->card_fee,$request->top_fee,3);
                $balance = bcdiv($balanceCount,2,3);
                $userParent->update(['balance'=>$balance]);// 分一半给邀请人，这个只是积分，其实所有的钱是到了商户里面。
            }
        }
        $data['no'] = ConvenientInformation::findAvailableNo();
        $data['card_fee'] = Setting::where('key','information_card_fee')->value('value');
        $data['top_fee'] = Setting::where('key','information_top_fee')->value('value');
        if (bccomp(bcadd($data['card_fee'],$data['top_fee'],2),0,2)!=1) {
            $data['paid_at'] = Carbon::now(); // 更新支付时间为当前时间
            $data['payment_no'] = ''; // 支付平台订单号
        }
        $res = ConvenientInformation::create($data);
        return $this->responseStyle('ok',200,$res);
    }



    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {
            // 校验权限
            $convenientInformation = auth('api')->user()->convenientInformation()->where('id', $id)->firstOrFail();
            // bcsub — 减法
            if (bcsub(time(), strtotime($convenientInformation->created_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($convenientInformation->paid_at || $convenientInformation->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '订单：' . $convenientInformation->no,
                'out_trade_no' => $convenientInformation->no,
                'total_fee' => bcadd($convenientInformation->card_fee,$convenientInformation->top_fee,2)  * 100,//$wechatPay->total_fee * 100,
                'notify_url' => "https://api.dengshishequ.com/information_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
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
            $order = ConvenientInformation::where('no',$message['out_trade_no'])->first();
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
//            order_wePay_success_notification($user->ml_openid,$order->payment_no,$order->paid_at,bcadd($order->card_fee,$order->top_fee,2) ,$order->title,'');
            return true; // 返回处理完成
        });

    }

    // 详情
    public function show($id)
    {
        $query = ConvenientInformation::where('id',$id);
        $query->increment('view');

        $user = auth('api')->user();
        if ($user->browseCards()->find($id)) {
            ConvenientInformation::where('id',$id)->update(['created_at'=>date('Y:m:d H:i:s')]);
        }else {
            $user->browseCards()->attach(ConvenientInformation::find($id));
        }

        $convenientInformation = $query->firstOrFail();
        return $this->responseStyle('ok',200,$convenientInformation);

        return $this->response->item($convenientInformation,new ConvenientInformationTransformer());
    }

}
