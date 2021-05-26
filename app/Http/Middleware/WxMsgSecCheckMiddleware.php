<?php

namespace App\Http\Middleware;

use Closure;

class WxMsgSecCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $response = file_get_contents( 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&'.'&appid='.config('wechat.mini_program.default.app_id').'&secret='.config('wechat.mini_program.default.secret'));

        $access_token =  \json_decode($response, true)['access_token'];

        $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token='.$access_token;

        if (!request('content')) {
            throw new \Exception('请输入内容！');
        }
        $data = ['content'=>request('content')];

        $res = $this->httpPost($url, json_encode($data));
        if (\json_decode($res, true)['errcode'] == 0) {
            return $next($request);
        }else {
            throw new \Exception('请输入健康积极向上的内容！');
        }
    }
    private function httpPost($url, $data) {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curl, CURLOPT_HEADER, 0); //设置header

        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);

        curl_close($curl);

        return $res;

    }
}
