<?php

namespace App\Http\Requests;

use App\User;

class AuthMlOpenidStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'=>'required',
            'ref_code'=>[
                function ($attribute, $value, $fail) {
                    if (!User::where('ref_code',$value)->first()) {
                        return $fail('邀请码不存在！');
                    }

                },
            ]
        ];
    }

    public function messages()
    {
        return [
            'code.required' => '参数code不能为空'
        ];
    }
}
