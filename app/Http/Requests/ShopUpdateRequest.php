<?php

namespace App\Http\Requests;

use App\Model\AbbrCategory;

class ShopUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'one_abbr'=> ['required',
                function ($attribute, $value, $fail) {
                    $arrLength = count($value);
                    if($arrLength > 1) {
                        return $fail('只可以选择一个类目下面的分类！');
                    }
                }],
            'two_abbr' =>['required',
                function ($attribute, $value, $fail) {
                    $arrLength = count($value);
                    if($arrLength > 3) {
                        return $fail('最多只可选3个分类！');
                    }
                    for ($x=0; $x<=$arrLength-1; $x++) {
                        if (!AbbrCategory::where('id',$value[$x])->first()) {
                            return $fail('分类有问题！');
                        }
                    }

                },
            ], // 判断是否在 abbr_categories 数据库中，只可以选三个
            'name' =>'required',
//            'area' =>'required',
//            'detailed_address' =>'required',
            'contact_phone' =>'required',
//            'wechat' =>'required',
//            'logo' =>'required',
//            'service_price' =>'required',
//            'merchant_introduction' =>'required',
            'lng' =>'required',
            'lat' =>'required',
        ];
    }
}
