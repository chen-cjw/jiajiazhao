<?php

namespace App\Http\Requests;

class DriverCertificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'id_card' => ['required',
                function ($attribute, $value, $fail) {
                    if(auth('api')->user()->is_certification == 1) {
                        return $fail('已经认证通过，无需二次认证！');
                    }
                }],
            'driver' => 'required',
            'action' => 'required',
            'car' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id_card.required' => ''
        ];
    }
}
