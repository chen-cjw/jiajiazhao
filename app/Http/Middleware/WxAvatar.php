<?php

namespace App\Http\Middleware;

use Closure;

class WxAvatar
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
        $app = app('wechat.mini_program');
        if (request('avatar')) {
            $res = $app->content_security->checkImage(request('avatar'));
            if ($res['errcode'] == 0) {
                return $next($request);
            } else {
                throw new \Exception('图片文件内含有敏感内容！');
            }
        }
        return $next($request);
    }
}
