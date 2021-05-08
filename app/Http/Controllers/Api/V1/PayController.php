<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Model\PaymentOrder;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    /**
     * @var \EasyWeChat\Payment\Application $app
     **/
    protected $app = null;

    //小程序配置
//    protected $config = [
//        // 必要配置
//        'app_id' => 'wx693aa465df66510b',
//        'mch_id'             => '1579420761',
//        'key'                => 'dhg0q824gnw34tur023hgfnpwef2q93y',   // API 密钥
//        //         $fontPath = config('app.fontPath');//'/System/Library/Fonts/Hiragino Sans GB.ttc';
//        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
//        'cert_path'          => config('app.cert'),
//        //'path/to/your/cert.pem', // XXX: 绝对路径！！！！
////        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
////        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
//        'key_path'           => config('app.key')      // XXX: 绝对路径！！！！
//
////        'notify_url'         => 'https://xxxxxx/api/order_pay_url',     // 你也可以在下单时单独设置来想覆盖它
//    ];

    /**
     * 付款到微信
     *  string $amount,
    string $openid,
    string $user_id,
    string $desc = '提现',
    bool $checkUserName = false,
    string $userName = ""
     */
    public function payment(

    ){
        $order = $this->attemptCreatePaymentOrder(\request('user_id'), \request('amount') , 1);
        Log::info($order);
        $this->app = Factory::payment([
            // 必要配置
            'app_id' => 'wx693aa465df66510b',
            'mch_id'             => '',
            'key'                => '',   // API 密钥
            //         $fontPath = config('app.fontPath');//'/System/Library/Fonts/Hiragino Sans GB.ttc';
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => "/www/wwwroot/jiajiazhao3/public//apiclient_cert.pem",
            //'path/to/your/cert.pem', // XXX: 绝对路径！！！！
//        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
//        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
            'key_path'           => "/www/wwwroot/jiajiazhao3/public//apiclient_key.pem"      // XXX: 绝对路径！！！！

//        'notify_url'         => 'https://xxxxxx/api/order_pay_url',     // 你也可以在下单时单独设置来想覆盖它
        ]);
//        return $this->app;
        $balanceData = [
            'partner_trade_no' => $order->order_number,
            'openid' => "oHIUO5BDkECawMJtgbbVmIzHyXMY",
            'amount' => 0.01 * 100,
            'desc' => '提现',
            're_user_name' => ""
        ];
        $balanceData['check_name'] = false ? 'FORCE_CHECK' : 'NO_CHECK';
        $result = $this->app->transfer->toBalance($balanceData);
        \Log::info('付款到微信返回:' . json_encode($result, JSON_UNESCAPED_UNICODE));
        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            $msg = data_get($result, 'err_code_des');
            \Log::error('付款失败:' . $msg);
            $order->status = 2;
            $order->intro = $msg;
            $order->save();

            throw new \Exception($result, $order);
        }

        $order->payment_no = data_get($result, 'payment_no');
        $order->status = 1;
        $order->save();

        return true;
    }
    /**
     * 创建付款订单
     *
     * @param string $user_id
     * @param string $amount
     *
     * @return \Modules\Pay\Entities\PaymentOrder
     */
    protected function attemptCreatePaymentOrder(
         $user_id,
         $amount,
         $type
    ){
        $payOrder = new PaymentOrder();
        $payOrder->fill([
            'user_id' => $user_id,
            'order_number' => $this->getordernumber(),
            'amount' => $amount,
//            'type' => $type,
            'status' => 2,
        ]);

        $payOrder->save();

        return $payOrder;
    }
    //订单号
    private function getordernumber()
    {
        $num = time();
        $num = (date('YmdHis', $num)) . rand(1000, 9999);
        return $num;
    }
}
