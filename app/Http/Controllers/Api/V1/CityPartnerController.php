<?php

namespace App\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityPartnerRequest;
use App\Model\CityPartner;
use App\Model\CityPartnerPaymentOrder;
use App\Model\InformationCommission;
use App\Model\PaymentOrder;
use App\Model\ShopCommission;
use App\Model\TransactionRecord;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityPartnerController extends Controller
{

    public function __construct()
    {
        $this->app = app('wechat.payment');
    }
    // 发帖抽成明细
    public function informationHistory()
    {
        $res = InformationCommission::where('is_pay',1)->where('parent_id',auth('api')->id())->with('user','information')->orderBy('id','desc')->paginate(10);
        return ['code'=>200,'msg'=>'ok','data'=>$res];
    }
    // 商户抽成明细
    public function shopHistory()
    {
        $res = ShopCommission::where('is_pay',1)->where('parent_id',auth('api')->id())->with('shop')->orderBy('id','desc')->paginate(10);
        return ['code'=>200,'msg'=>'ok','git'=>$res];
    }
    // 商户入住费 city_shop_fee
    // 便民发帖抽佣 information_fee
    // 商户交易流水 city_transition_flow_fee
    // 地接广告的 adv
    /***
     * 第二次开发
     * 城市合伙人
     **/
    // 合伙人中心 = 今日收益+累计收益
    public function index()
    {
        $res = auth('api')->user()->cityPartner()->whereNotNull('paid_at')->first();
        if (!$res) {
            return ['code'=>200,'msg'=>'ok','data'=>['is_city_partner'=>0]];
        }
        // 今日收益
        $start_time=Carbon::now()->startOfDay();
        $end_time=Carbon::now()->endOfDay();
        $nowDaySh = ShopCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->whereBetWeen('created_at',[
            $start_time,$end_time
        ])->sum('commissions');
        $nowDayInf = InformationCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->whereBetWeen('created_at',[
            $start_time,$end_time
        ])->sum('commissions');
        // 累计收益
        $sunDaySh = ShopCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->sum('commissions');
        $sunDayInf = InformationCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->sum('commissions');
        $nowDay = bcadd($nowDaySh,$nowDayInf,3);
        $sunDay = bcadd($sunDaySh,$sunDayInf,3);

        $res['nowDay'] = $nowDay; // 今日收益
        $res['allDay'] = $sunDay; // 累计收益
        $res['is_city_partner'] = 1;
        $res['shop_commission'] = ShopCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->sum('commissions');// 商户抽成
        $res['information_commission'] = InformationCommission::where('parent_id',auth('api')->id())->where('is_pay',1)->sum('commissions');// 发帖抽成
        $res['transaction_flow_commission'] = 0;// 交易流水抽成
        $res['cash_withdrawn'] = CityPartnerPaymentOrder::where('user_id',auth('api')->id())->sum('amount');// 已提现金额
        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }

    public function updatePartner()
    {
        $q = auth('api')->user()->cityPartner()->whereNotNull('paid_at');
        if($q->value('is_partners') == 2) {
            $q->update([
                'is_partners' => 3 // 通过审核,2=>3 说明审核已经通过已经给前端提示过了
            ]);
            return ['code'=>200,'msg'=>'ok','data'=>'操作成功'];

        }else {
            return ['code'=>200,'msg'=>'error','data'=>'非法操作'];

        }
//        $res = auth('api')->user()->cityPartner()->whereNotNull('paid_at')->update([
//            'is_partners' => 3 // 通过审核,2=>3 说明审核已经通过已经给前端提示过了
//        ]);
//        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }

    // 合伙人入住
    public function store(CityPartnerRequest $request)
    {
        // 判断这个城市是否已经有了合伙人 todo 这里用is_partners
        if(CityPartner::where('in_city',$request->in_city)->whereNotNull('paid_at')->first()) {
            throw new ResourceException('该地区已有合伙人，请联系我们！');
        }
        $data = $request->only('name','phone','IDCard','in_city');
        $data['no'] = CityPartner::findAvailableNo();
        $data['user_id'] = auth('api')->id();
        $data['amount'] =  Setting::where('key','city_partner_amount')->value('value')?:0.01;// todo 获取默认配置
        if ($id = CityPartner::where('user_id',$data['user_id'])->whereNull('paid_at')->value('id')) {
            $res =  CityPartner::where('id',$id)->update($data);
            return ['code'=>200,'msg'=>'ok','data'=>CityPartner::where('id',$id)->first()];
        }else {
            $res = CityPartner::create($data);
            return ['code'=>200,'msg'=>'ok','data'=>$res];
        }

    }

    // 展示自己提交入住的信息
    public function show()
    {
        $res = auth('api')->user()->cityPartner;
        return ['code'=>200,'msg'=>'ok','data'=>$res];
    }
    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {
            //

            // 校验权限
            $partner = auth('api')->user()->cityPartner()->where('id', $id)->firstOrFail();
            if ($partner->is_pay) {
                throw new ResourceException('此订单已支付！');
            }
            // 判断这个城市是否已经有了合伙人
            if(CityPartner::where('in_city',$partner->in_city)->whereNotNull('paid_at')->first()) {
                throw new ResourceException('该地区已有合伙人，请联系我们！');
            }

            // bcsub — 减法
            if (bcsub(time(), strtotime($partner->updated_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($partner->paid_at || $partner->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '加入城市合伙人：' . $partner->no,
                'out_trade_no' => $partner->no,
                'total_fee' => $partner->amount * 100,//$wechatPay->total_fee * 100,
                'notify_url' => config('app.app_pay_url')."partner_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'order' => $partner,
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
            $order = CityPartner::where('no',$message['out_trade_no'])->first();
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
                    $order->is_partners = 2; // 支付平台订单号
                    Log::info($order);

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

    // -- todo 下面的功能是支付过后，可以看见的数据
    // 发帖抽成-- 团长抽成不是合伙人抽成
    public function informationIndex()
    {
        $res = TransactionRecord::where('model_type','App\Model\TransactionRecord')->where('parent_id',auth('api')->id())->paginate();
        return ['code'=>200,'msg'=>'ok','data'=>$res];
    }

    // 商户抽成 -- 合伙人的抽成
    public function shopIndex()
    {
        $res = ShopCommission::where('is_pay',1)->where('parent_id',auth('api')->id())->paginate();
        return ['code'=>200,'msg'=>'ok','data'=>$res];
    }

    // todo 交易流水抽成 == 商户商城抽成 目前未开放
    public function shopping()
    {

    }


}
