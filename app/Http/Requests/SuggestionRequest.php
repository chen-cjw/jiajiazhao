<?php

namespace App\Http\Requests;


use App\Model\ConvenientInformation;
use App\Model\LocalCarpooling;

class SuggestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'=>'required',
            'id'=>[
                function ($attribute, $value, $fail) {
                    if (!ConvenientInformation::where('id',$value)->first()) {
                        return $fail('举报不明确！');
                    }

                },
            ]
        ];
    }
}
