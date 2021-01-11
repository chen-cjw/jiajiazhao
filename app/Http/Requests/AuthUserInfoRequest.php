<?php

namespace App\Http\Requests;


class AuthUserInfoRequest extends FormRequest
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
            'nickname'=>'required',
            'sex'=>'required',
            'avatar'=>'required',
        ];
    }
}
