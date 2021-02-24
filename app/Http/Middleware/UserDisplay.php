<?php

namespace App\Http\Middleware;

use Closure;

class UserDisplay
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
        $display = auth()->user()->display;
        if($display == 1) {
            return response()->json([
                'code'=> 422,
                'data'=>[],
                'msg'=>'您已被禁用！'
            ]);
        }
        return $next($request);
    }
}
