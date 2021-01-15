<?php

namespace App\Http\Requests;


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
            'content'=>'required'
        ];
    }
}
