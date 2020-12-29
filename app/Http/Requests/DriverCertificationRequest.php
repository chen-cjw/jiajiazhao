<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'id_card' => 'required',
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
