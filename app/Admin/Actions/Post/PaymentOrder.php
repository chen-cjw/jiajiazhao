<?php

namespace App\Admin\Actions\Post;

use App\User;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class PaymentOrder extends RowAction
{
    public $name = '同意转账';

    public function handle(Model $model)
    {
        if ($model->status == 1) {
            return $this->response()->error('提现失败.')->refresh();

        }
        // $model ...
        $this->app = Factory::payment([
            // 必要配置
            'app_id' => env('WECHAT_PAYMENT_APPID'),
            'mch_id'             => env('WECHAT_PAYMENT_MCH_ID'),
            'key'                => env('WECHAT_PAYMENT_KEY'),   // API 密钥
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
            'partner_trade_no' => $model->order_number,
            'openid' => User::where('id',$model->user_id)->value(),
            'amount' => $model->amount * 100,//0.01 * 100,
            'desc' => '提现',
            're_user_name' => ""
        ];
        $balanceData['check_name'] = false ? 'FORCE_CHECK' : 'NO_CHECK';
        $result = $this->app->transfer->toBalance($balanceData);
        \Log::info('付款到微信返回:' . json_encode($result, JSON_UNESCAPED_UNICODE));
        if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
            $msg = data_get($result, 'err_code_des');
            \Log::error('付款失败:' . $msg);
            $model->status = 2;
            $model->intro = $msg;
            $model->save();

            throw new \Exception($result, $model);
        }

        $model->payment_no = data_get($result, 'payment_no');
        $model->status = 1;
        $model->save();

        $this->response()->success('Success message.')->refresh();
        return true;
    }

}