<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LocalCarpoolingRequest;
use App\Model\LocalCarpooling;
use App\Transformers\LocalCarpoolingTransformer;

class LocalCarpoolingController extends Controller
{
    // 本地拼车
    public function index()
    {
        $local= LocalCarpooling::paginate();
        return $this->response->paginator($local,new LocalCarpoolingTransformer());
    }

    // 发布(车找人和车找货是需要认证的) todo 后端配合
    public function store(LocalCarpoolingRequest $request)
    {
        if (auth('api')->user()->is_certification == 0 && $request->type == 'car_looking_person' || auth('api')->user()->is_certification == 0 && $request->type == 'car_looking_good') {
            return [
                'message' => '您尚未通过认证，请先去认证通过！',
                'status_code' => 4001
            ];
        }else {
            $requestData = $request->only(['phone','name_car','capacity','go','end','departure_time','seat','other_need','is_go','type']);
            $requestData['user_id'] = auth('api')->id();
            LocalCarpooling::create($requestData);
            return $this->response->created();
        }
    }

    // 车辆是否已经出发了
    public function update($id)
    {
        return auth('api')->user();//->local()->where('id',$id)->update(['is_go'=>true]);
        return $this->response->created();
    }
}
