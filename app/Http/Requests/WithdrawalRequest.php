<?php

namespace App\Http\Requests;


use App\Model\Setting;

class WithdrawalRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'=>['required',
                function ($attribute, $value, $fail) {
                    if(bccomp($value,Setting::where('key','withdrawal_low')->value('value'),  3)!=-1) {
                        return $fail('当前金额不够最低提现！');
                    }
                }],
            'name'=>'required',
            'bank_of_deposit'=>'required',
            'bank_card_number'=>'required',
        ];
    }
}
