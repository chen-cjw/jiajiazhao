<?php

namespace App\Http\Requests;

use App\Model\CityPartner;

class CityPartnerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>['required','min:2','max:3'],
            'phone'=>'required|regex:/^1[23456789][0-9]{9}$/',
            'IDCard'=>['required',function ($attribute, $value, $fail) {
                if(18 != strlen($value)) {
                    return $fail('身份证号不正确！');
                }
                if(CityPartner::where('user_id',auth('api')->id())->whereNotNull('paid_at')->first()) {
                    return $fail('您已申请城市合伙人！');
                }
            }],
            'in_city'=>'required|min:2|max:10',
            'agree'=>'required|in:1',
        ];
    }

    public function messages()
    {
        return  [
            'agree.required'=>'请阅读协议',
            'agree.in'=>'未同意协议',
        ];
    }
    public function attributes()
    {
        return [
            'name'=>'姓名',
            'phone'=>'手机号',
            'IDCard'=>'身份证号',
            'in_city'=>'所在城市',
            'agree'=>'请阅读协议'
        ];
    }
}
