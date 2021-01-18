<?php

namespace App\Http\Requests;

use App\Model\Shop;

class ShopCommentRequest extends FormRequest
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
            'star'=>['required','in:star,1,2,3,4,5'],
            'shop_id'=>['required',
                function ($attribute, $value, $fail) {
                    if (!Shop::where('id',$value)->first()) {
                        return $fail('分类有问题！');
                    }
                },
            ]
        ];
    }
}
