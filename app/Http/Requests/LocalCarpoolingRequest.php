<?php

namespace App\Http\Requests;

class LocalCarpoolingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^1[23456789][0-9]{9}$/', // 一个车主可以发很多条信息
            'name_car' => 'required|string',
            'type' => ['required','in:type,person_looking_car,car_looking_person,good_looking_car,car_looking_good'],
//            'capacity' => 'required|string',
            'go' => 'required|string',
            'end' => 'required|string',
            'departure_time' => 'required|date',
            'seat' => 'integer',
            'other_need' => 'string',
            'is_go' => 'boolean',

        ];
    }
}
