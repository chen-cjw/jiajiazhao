<?php

namespace App\Http\Requests;


use App\Model\LocalCarpooling;
use App\Shop;

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
            'id'=>['required',
                function ($attribute, $value, $fail) {
                    if($this->type == 'shop') {
                        if (!Shop::where('id',$value)->first()) {
                            return $fail('非法拨号！');
                        }
                    }
                    if($this->type=='local') {
                        if (!LocalCarpooling::where('id',$value)->first()) {
                            return $fail('非法拨号！');
                        }
                    }
            }]
        ];
    }
}
