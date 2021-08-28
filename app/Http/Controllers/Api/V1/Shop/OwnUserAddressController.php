<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Shop\OwnUserAddressRequest;
use App\Model\Shop\OwnUserAddress;
use Illuminate\Http\Request;

class OwnUserAddressController extends Controller
{
    public function index()
    {
        $res = auth('api')->user()->ownUserAddresses()->orderBy('last_used_at','desc')->get();
        return $this->responseStyle('ok',200,$res);

    }
    // 添加用户收获地址
    public function store(OwnUserAddressRequest $request)
    {
        $data = $request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone'
        ]);
        $data['user_id'] = auth('api')->id();
        $res = OwnUserAddress::create($data);

        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }

    public function updateDefault(Request $request,$id)
    {
        auth('api')->user()->ownUserAddresses()->update([
            'default'=>0
        ]);
        $res = auth('api')->user()->ownUserAddresses()->where('id',$id)->update([
            'default'=>1,
            'last_used_at'=>date('Y-m-d H:i:s')
        ]);
        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }
    public function update(Request $request,$id)
    {
        $res = auth('api')->user()->ownUserAddresses()->where('id',$id)->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return ['code'=>200,'msg'=>'ok','data'=>$res];

    }

    public function destroy($id)
    {
        $res = auth('api')->user()->ownUserAddresses()->where('id',$id)->delete();
        return ['code'=>200,'msg'=>'ok','data'=>$res];
    }
}
