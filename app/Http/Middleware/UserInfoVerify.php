<?php

namespace App\Http\Middleware;

use Closure;

class UserInfoVerify
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
        $nickname = auth()->user()->nickname;
        if(!$nickname) {
            return response()->json([
                'code'=> 422,
                'data'=>[],
                'msg'=>'未授权用户信息'
            ]);
        }
        return $next($request);
    }
}
