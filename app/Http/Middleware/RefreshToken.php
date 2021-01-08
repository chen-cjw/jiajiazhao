<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class RefreshToken extends BaseMiddleware
{   
    protected $guard = 'apijwt';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        // 使用 try 包裹，以捕捉 token 过期所抛出的 TokenExpiredException  异常
        // $this->checkForToken($request);
        $authToken = JWTAuth::getToken();
//        $authToken = JWTAuth::getToken();
        // dd($authToken);
        // dd(Auth::factory()->buildclaimsCollection()->toPlainArray());
        if(!$authToken){
            	return response(['code'=>401,'msg'=>'Token不存在']);
        }
        try {
            if (JWTAuth::parseToken()->authenticate()) {
                return $next($request);
            }
            return response()->json(['code'=>404,'data'=>[],'msg'=>'未登录']);
        } catch (TokenExpiredException $exception) {
            // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
            try {
                $token = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($token);
                $request->user = JWTAuth::authenticate($token);
                $request->headers->set('Authorization','Bearer'.$token);
                // 刷新用户的 token
            } catch (JWTException $exception) {
                // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                //throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
                return response()->json(['code'=>402,'data'=>[],'msg'=>$exception->getMessage()]);
            }
        }
        catch (JWTException $e)
        {
            // dd($e);
            return response()->json(['code'=>402,'data'=>[],'msg'=>$e->getMessage()]);
        }

        // 在响应头中返回新的 token
        return $this->setAuthenticationHeader($next($request), $token);
    }
}
