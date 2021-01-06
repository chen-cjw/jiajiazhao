<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use EasyWeChat\Factory;
use App\Jobs\CloseWechatPay;
use App\Models\Consult;
use App\Models\Inviter;
use App\Models\Product;
use App\Models\ProductType;
use Cache;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{

    //小程序配置
    protected $config = [
        // 必要配置
        'app_id' => 'wxa61dd6242f86f242',
        'mch_id'             => '1603122652',
        'key'                => 'ts09101eadbdabacebst2a29b401c0dt',   // 商户 API密钥

        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

        'notify_url'         => 'https://www.houtijun.com/api/order_pay_url',     // 你也可以在下单时单独设置来想覆盖它
    ];

    public function index(Request $request)
    {

        $user = $request->get('user');

        $type_id = $request->get('type_id');
        $product_id = $request->get('product_id');
        $is_pay = $request->get('is_pay');
        $where = function ($query) use ($product_id, $type_id, $is_pay, $user) {
            if ($product_id) $query->where('product_id', $product_id);
            if ($type_id) $query->where('type_id', $type_id);
            if ($is_pay) $query->where('is_pay', $is_pay);
            if ($user) $query->where('customer_id', $user['id']);
        };
        $columns = ['*'];
        $pageName = 'page';
        $currentPage = $request->get('page') ? (int)$request->get('page') : 1; //分页起始位置
        $perPage = $request->get('perPage') ? (int)$request->get('perPage') : 5; //每页数据量

        $result = Order::with(['type', 'product'])->where($where)->orderBy('id', 'desc')->paginate($perPage, $columns, $pageName, $currentPage);

        return response()->json([
            'code' => 200,
            'data' => $result,
            'msg' => '数据获取成功'
        ]);
    }

    public function detail(Request $request)
    {
        $user = $request->get('user');

        $id = $request->get('id');

        $result =  Order::with(['type', 'product'])->where([['id', $id], ['customer_id', $user['id']]])->first();

        return response()->json([
            'code' => 200,
            'data' => $result,
            'msg' => '数据获取成功'
        ]);
    }

    public function postOrder(Request $request)
    {
        $this->validate($request, [
            'openid' => 'required',
            'type_id' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1',
            'total_fee' => 'required|numeric',
        ], [
            'openid.required' => '请传参数openid',

            'type_id.required' => '请传参数type_id',
            'type_id.integer' => 'type_id必须为数字',
            'type_id.min' => 'type_id的值不小于1',
            'product_id.min' => 'product_id的值不小于1',
            'product_id.integer' => 'product_id必须为数字',
            'product_id.required' => '请传参数product_id',
            'total_fee.required' => '请传参数total_fee',
            'total_fee.numeric' => 'total_fee必须为数字',
        ]);

        $user = $request->get('user');
        $type = ProductType::where('id',  $request->get('type_id'))->first();
        if (!$type) {
            return response()->json([
                'code' => 401,
                'msg' => '分类不存在,请传正确的type_id'
            ]);
        }
        $product = Product::where('id', $request->get('product_id'))->first();
        if (!$product) {
            return response()->json([
                'code' => 401,
                'msg' => '产品不存在,请传正确的product_id'
            ]);
        }

        $data['customer_id'] = $user['id'];
        $data['type_id'] = $request->get('type_id');
        $data['product_id'] = $request->get('product_id');
        // $data['total_fee'] = $request->get('total_fee');
        $amount = $request->get('total_fee');
        $data['total_fee'] = $amount;
        $data['out_trade_no'] = $request->get('out_trade_no');
        $transaction_id = $this->getordernumber();
        $data['out_trade_no'] = $transaction_id;
        $data['is_pay'] = 2;
        $data['is_used'] = 2;
        try {
            $order = Order::create($data);
            $this->dispatch(new CloseWechatPay($order, 90 * 60));
            if ($order) {
                $app = Factory::payment($this->config);
                $result = $app->order->unify([
                    'body' => '图说密码-' . $type['title'] . '-' . $product['name'],
                    'attach'    => $order['id'],
                    'notify_url' => 'https://www.houtijun.com/api/order_pay_url', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'openid' => $user['openid'],
                    'out_trade_no' =>  $transaction_id,
                    'spbill_create_ip' => '42.194.169.187', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                    'total_fee' => $amount * 100,
                    'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
                ]);
                // return $result;
                $jssdk = $app->jssdk;
                $json = $jssdk->bridgeConfig($result['prepay_id'], false);
                return response()->json([
                    'code' => 200,
                    'data' => $json,
                    'order' => $order,
                    'msg' => '获取支付参数成功'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'code' => 402,
                "msg" => $e->getMessage()
            ]);
        }
    }

    //订单号
    private function getordernumber()
    {
        $num = time();
        $num = (date('YmdHis', $num)) . rand(1000, 9999);
        return $num;
    }

    public function order_pay_url()
    {

        $app    = Factory::payment($this->config);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where('out_trade_no', $message['out_trade_no'])->first();

            if (!$order || $order->is_pay == 1) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->is_pay = 1; // 更新支付时间为当前时间
                    $order->remark = '支付时间:' . date('Y-m-d H:i:s', time());
                    $this->changeUserStatus($order['customer_id'], $order->product_id);
                    if ($order->product_id == 2 || $order->product_id == 1) {
                        $order->is_used = 1;
                    }
                    $data['out_trade_no'] =  $order['out_trade_no'];
                    $data['updated_at'] =  $order['updated_at'];
                    $data['total_fee'] =  $order['total_fee'];
                    $product = Product::with('type')->where('id', $order['product_id'])->first();
                    $data['product'] =  $product['type']['title'] . '/' . $product['name'];
                    $this->sendMessage($data);
                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->is_pay = 4;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }

    private function changeUserStatus($user_id, $product_id)
    {
        $user = Customer::where('id', $user_id)->first();
        if ($product_id == 1) {
            $result = $user->increment('select_times');
        }
        if ($product_id == 2) {
            if ($user['is_vip'] == 2) {
                $user['is_vip'] = 1;
                $user['start_time'] = date('Y-m-d', time());
                $user['end_time'] = date('Y-m-d', strtotime("+1years", time()));
                $user->save();
            } else {
                $user['end_time'] = date('Y-m-d', strtotime("+1years", strtotime($user['end_time'])));
                $user['share_vip'] = 0;
                $user->save();
            }
        }
        $invitee = Inviter::where('invitee_id', $user['id'])->first();
        if ($invitee) {
            if ($invitee['is_payed'] != 2) {
                return;
            }
            $invitee['is_payed'] = 1;
            $invitee->save();
            $inviter = Customer::where('id', $invitee['inviter_id'])->first();
            $inviter->increment('pay_times');
            if ($inviter['share_times'] >= 100 && $inviter['pay_times'] >= 10 && $inviter['share_vip'] == 1) {
                if ($inviter['is_vip'] == 2) {
                    $inviter['is_vip'] = 1;
                    $inviter['start_time'] = date('Y-m-d', time());
                    $inviter['end_time'] = date('Y-m-d', strtotime("+1years", time()));
                    $inviter['share_vip'] = 0;
                    $inviter->save();
                } else {
                    $inviter['end_time'] = date('Y-m-d', strtotime("+1years", strtotime($inviter['end_time'])));
                    $inviter['share_vip'] = 0;
                    $inviter->save();
                }
            }
        }

        return;
    }

    public function postConsult(Request $request)
    {
        $this->validate($request, [
            'openid' => 'required',
            'phone' => 'required',
            'remark' => 'required',
            'order_id' => 'required|integer|min:1',
        ], [
            'openid.required' => '请传参数openid',
            'phone.required' => '请传参数phone',
            'remark.required' => '请传参数remark',
            'order_id.min' => 'order_id的值不小于1',
            'order_id.integer' => 'order_id必须为数字',
            'order_id.required' => '请传参数order_id',
        ]);

        $user = $request->get('user');

        $order = Order::where([['id', $request->get('order_id')], ['customer_id', $user['id']]])->first();

        if (!$order) {
            return response()->json([
                'code' => 40001,
                'msg' => '订单不存在'
            ]);
        }
        if ($order['is_used'] == 1) {
            return response()->json([
                'code' => 40002,
                'msg' => '订单资格已被使用'
            ]);
        }
        if ($order['is_pay'] != 1) {
            return response()->json([
                'code' => 40003,
                'msg' => '订单未支付'
            ]);
        }
        $data = [
            'order_id' => $order['id'],
            'customer_id' => $user['id'],
            'phone' => $request->get('phone'),
            'remark' => $request->get('remark'),
            'paid' => $order['total_fee'],
            'is_disposed' => 2,
            'admin_id' => 1,
        ];
        if (!in_array($order['product_id'], [3, 4])) {
            return response()->json([
                'code' => 40004,
                'msg' => '订单错误,请传咨询订单'
            ]);
        }

        try {
            if ($order['product_id'] == 3) {
                $data['type'] = 1;
            }
            if ($order['product_id'] == 4) {
                $data['type'] = 2;
            }
            $result = Consult::create($data);
            if ($result) {
                $order->is_used = 1;
                $order->save();
            }
            return response()->json([
                'code' => 200,
                'msg' => '咨询提交成功'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 40005,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function payAgain(Request $request)
    {
        $openid = $request->get('openid');
        $out_trade_no = $request->get('out_trade_no');
        if (!$openid || !$out_trade_no) {
            return response()->json([
                'code' => 401,
                'msg' => '请传正确的参数'
            ]);
        }
        $user = Customer::where('openid', $openid)->first();
        if (!$user) {
            return response()->json([
                'code' => 202,
                'msg' => '用户不存在'
            ]);
        }

        $order = Order::where([['customer_id', $user['id']], ['out_trade_no', $out_trade_no], ['created_at', '>', date('Y-m-d H:i:s', time() - 90 * 60)], ['is_pay', 2]])->first();
        if (!$order) {
            return response()->json([
                'code' => 203,
                'msg' => '订单不存在或已过期'
            ]);
        }
        try {
            $type = ProductType::where('id',  $order['type_id'])->first();
            $product = Product::where('id', $order['product_id'])->first();

            $app = Factory::payment($this->config);
            $result = $app->order->unify([
                'body' => '图说密码-' . $type['title'] . '-' . $product['name'],
                'attach'    => $order['id'],
                'notify_url' => 'https://www.houtijun.com/api/order_pay_url', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => $openid,
                'out_trade_no' =>  $order['out_trade_no'],
                'spbill_create_ip' => '42.194.169.187', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                'total_fee' => $order['total_fee'] * 100,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'order' => $order,
                'msg' => '获取支付参数成功'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 402,
                "msg" => $e->getMessage()
            ]);
        }
    }
    public function cancelOrder(Request $request)
    {
        $user = $request->get('user');
        $result = Order::where([['id', $request->get('id')], ['customer_id',  $user['id']], ['is_pay', 2]])->first();
        if ($result) {
            $result->is_pay = 3;
            $result->remark = '用户主动关闭订单,关闭时间:' . date('Y-m-d H:i:s', time());
            $result->save();

            if ($result) {
                return response()->json([
                    'code' => 200,
                    "msg" => '订单关闭成功'
                ]);
            }
            return response()->json([
                'code' => 401,
                "msg" => '订单关闭失败'
            ]);
        }
        return response()->json([
            'code' => 400,
            "msg" => '订单不存在'
        ]);
    }

    public function sendMessage($data)
    {
        $config = [
            'app_id' => 'wxda212b6089101abe',
            'secret' => '5d3316354b93c7bf67de654e600813a1',

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            //...
        ];

        $app = Factory::officialAccount($config);

        $result = $app->user->list($nextOpenId = null);


        return   $app->template_message->send([
            'touser' => 'opd8l6-NRVQyHbXhIY9ZPykl07vQ',
            'template_id' => 's9mojWHsiso-LKxYHr2Cv5Hk4TgL1UoCyRedlQS2ROs',
            'url' => '',
            'miniprogram' => [],
            'data' => [
                'first' => '您有新的订单,请及时处理!',
                'keyword1' => $data['out_trade_no'],
                'keyword2' =>  $data['updated_at'],
                'keyword3' => $data['product'],
                'keyword4' =>  $data['total_fee'],
                'remark' => '请及时查看后台，并处理！',

            ],
        ]);
    }
}
