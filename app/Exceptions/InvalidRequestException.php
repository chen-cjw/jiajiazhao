<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvalidRequestException extends Exception
{
    public function __construct($message = "",  $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            // json() 方法第二个参数就是 Http 返回码
            throw new HttpResponseException(response()->json(['code'=>$this->code,'msg'=>$this->message,'data'=>[]]));

//            return response()->json(['msg' => $this->message], $this->code);
        }
        throw new HttpResponseException(response()->json(['code'=>422,'msg'=>$this->message,'data'=>'CouponCodeUnavailableException']));

//        return view('pages.error', ['msg' => $this->message]);
    }
}
