<?php

namespace App\Http\Requests;


class DialingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone'=>'required',
            'type'=>['required','in:type,shop,local'],
            'id'=>['required']
        ];
    }
}
