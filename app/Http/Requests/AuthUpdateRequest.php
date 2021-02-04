<?php

namespace App\Http\Requests;

class AuthUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar'=>'required',
            'username'=>'required',
            'sex'=>'required|in:0,1,2',
            'birthday'=>'date'
        ];
    }
}
