<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AuthMlOpenidStoreRequest;
use App\Http\Requests\AuthPhoneStoreRequest;
use App\Http\Requests\AuthUserInfoRequest;
use App\Model\Withdrawal;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        
    }
    // 创建一个测试用户
    public function createTestUser()
    {
        $app = app('wechat.mini_program');
        Log::info($app);

        $user = User::findOrFail(1);
        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token,1,$user)->setStatusCode(201);
        return User::create([
            'ml_openid' => 1,
            'nickname' => 'nickName',
            'avatar' => 'avatarUrl',
            'sex' => 1,
            'parent_id' =>  null
        ]);
    }

    // 获取用户的openid
    public function mlOpenidStore(AuthMlOpenidStoreRequest $request)
    {

        $app = app('wechat.mini_program');
        Log::info(123);
        $code = $request->code;
        Log::info($code);
        $sessionUser = $app->auth->session($code);
        Log::info($sessionUser);
        if (!empty($sessionUser['errcode'])) {
            throw new \Exception('获取用户的openid操作失败!');
        }
        DB::beginTransaction();
        try {
            $openid = $sessionUser['openid'];
            Log::info($openid);

            $session_key = $sessionUser['session_key'];
            $user = User::where('ml_openid', $openid)->first();
            Log::info($user);

            Cache::put($code, ['session_key' => $session_key, 'ml_openid' => $openid], 3000);
            if ($user) { // 手机好存在直接登陆
                // 修改 code todo
                User::where('ml_openid', $openid)->update([
                    'sessionUserInformation'=>json_encode($sessionUser)
                ]);
                DB::commit();

                Log::info(1);
//                if($user->nickname) {
                $token = \Auth::guard('api')->fromUser($user);

                if($user->phone) {
                    Log::info(2);

                    return $this->response->array([
                        'code' => 200,
                        'msg'=>'ok',
                        'data' => [
                            'ml_openid' => $openid,
                            'access_token' => $token,
                            'token_type' => 'Bearer',
                            'phone'=>$user->phone,
                            'expires_in' => Auth::guard('api')->factory()->getTTL() * 1200
                        ]
                    ]);
                    return $this->respondWithToken($token, $openid, $user);
                }
                Log::info(3);
                return $this->response->array([
                    'code' => 200,
                    'msg'=>'未授权用户信息',
                    'data' => [
                        'ml_openid' => $openid,
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'phone'=>$user->phone,
                        'expires_in' => Auth::guard('api')->factory()->getTTL() * 1200
                    ]
                ]);
                return $this->oauthNo();
            }
            Log::error($request->all());
            Log::info('创建用户', $this->createUser($sessionUser, $request));

            User::create($this->createUser($sessionUser, $request));

            DB::commit();
            $token = \Auth::guard('api')->fromUser($user);

            return $this->response->array([
                'code' => 200,
                'msg'=>'未授权用户信息',
                'data' => [
                    'ml_openid' => $openid,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'phone'=>$user->phone,
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 1200
                ]
            ]);
            return $this->respondWithToken($token, $openid, $user);

            return $this->oauthNo();
        } catch (\Exception $ex) {
            DB::rollback();
            throw new \Exception($ex); // 报错原因大多是因为taskFlowCollections表，name和user_id一致
        }
    }
    //  获取手机号
    public function phoneStore(AuthPhoneStoreRequest $request)
    {
//        $session = Cache::get($request->code);// 解析的问题
//        if(!$session) {
//            Log::error('用户code：'.$request->code);
//            throw new \Exception('code 和第一次的不一致'.$request->code);
//        }
        Log::error(auth('api')->user());

        $session = auth('api')->user()->sessionUserInformation;
        Log::error('用户信息phoneStore：'.$session);
        Log::error('用户信息phoneStore：'.json_decode($session)->session_key);

        $app = app('wechat.mini_program');
//        $decryptedData = $app->encryptor->decryptData($session['session_key'], $request->iv, $request->encrypted_data);

        $decryptedData = $app->encryptor->decryptData(json_decode($session)->session_key, $request->iv, $request->encrypted_data);
        Log::info(111111111111);
        Log::error($decryptedData);
        Log::info(111111111111);

        if (empty($decryptedData)) {
            throw new \Exception('解析号码失败!321');
        }
//        $user = User::where('ml_openid',$session['ml_openid'])->firstOrFail();

        $user = User::where('ml_openid', json_decode($session)->openid)->firstOrFail();
        $phoneNumber = $decryptedData['phoneNumber'];
        $user->update([
            'phone'=>$phoneNumber,
//            'avatar'=>$request->avatar,
//            'nickname'=>$request->nickname,
//            'city'=>$request->city,
//            'sex'=>$request->sex,
        ]);

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token,'',$user)->setStatusCode(201);
    }

    // 获取用户信息
    public function userInfo(AuthUserInfoRequest $request)
    {
        Log::error(auth('api')->user());

        $session = auth('api')->user()->sessionUserInformation;
        Log::error('用户信息userInfo：'.$session);
        Log::error('用户信息userInfo：'.json_decode($session)->session_key);
//        $session = Cache::get($request->code);// 解析的问题
//        if(!$session) {
//            Log::error('用户code：'.$request->code);
//            throw new \Exception('code 和第一次的不一致'.$request->code);
//        }
        $app = app('wechat.mini_program');
//        $decryptedData = $app->encryptor->decryptData($session['session_key'], $request->iv, $request->encrypted_data);
        $decryptedData = $app->encryptor->decryptData(json_decode($session)->session_key, $request->iv, $request->encrypted_data);

        Log::info(22222222222);
        Log::error($decryptedData);
        Log::info(22222222222);

        if (empty($decryptedData)) {
            throw new \Exception('解析号码失败!321');
        }
        $user = User::where('ml_openid', json_decode($session)->openid)->firstOrFail();

//        $user = User::where('ml_openid',$session['ml_openid'])->firstOrFail();
        $user->update([
            'avatar'=>$decryptedData['avatarUrl'],
            'nickname'=>$decryptedData['nickName'],
            'city'=>$decryptedData['city'],
            'sex'=>$decryptedData['gender'],
        ]);
        return $this->responseStyle('ok',200,$user);

        $token = \Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token,'',$user)->setStatusCode(201);
    }
    public function refresh()
    {
        $token = auth('api')->refresh();
        $user = auth()->user();
        return $this->respondWithToken($token, $user->ml_openid,$user);
    }

    public function meShow()
    {
        $res = auth('api')->user();
        $res['with_balance']=Withdrawal::where('user_id',$res->id)->sum('amount');
        return $this->responseStyle('ok',200,$res);
    }
    protected function oauthNo()
    {

        return $this->response->array([
            'code'=> 200,
            'data'=>[],
            'msg'=>'未授权用户信息'
        ]);
    }

    protected function createUser($sessionUser,$request)
    {
        $user = new User();
        return [ // 不存在此用户添加
            'ml_openid' => $sessionUser['openid'],
            'nickname' => $request->nickName,
            'avatar' => $request->avatarUrl,
            'sex' => $request->sex,
            'parent_id' => $request->ref_code ? User::where('ref_code',$request->ref_code)->value('parent_id') : null,
            'ref_code' => $user->generateRefCode(),
            'sessionUserInformation'=>json_encode($sessionUser)
        ];
    }

    protected function respondWithToken($token,$mlOpenid,$user)
    {
        return $this->response->array([
            'code' => 200,
            'msg'=>'ok',
            'data' => [
                'ml_openid' => $mlOpenid,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'phone'=>$user->phone,
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 1200
            ]
        ]);
    }
}
