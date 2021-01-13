<?php

namespace App\Http\Middleware;

use Closure;

class PhoneVerify
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
        $phone = auth()->user()->phone;
        if(!$phone) {
            return response()->json([
                'code'=> 200,
                'data'=>[],
                'msg'=>'未授权手机号'
            ]);
        }
        return $next($request);
    }
}
