<?php

namespace App\Http\Requests;

use App\Model\Setting;

class CityPartnerPaymentOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'balance'=>['required',
                function ($attribute, $value, $fail) {
                    if(bccomp($value,1)==-1) {
                        return $fail('最低提现1元');
                    }
                    if(bccomp($value,Setting::where('key','city_partner_withdrawal_low')->value('value'),  3)==-1) {
                        return $fail('最低提现'. Setting::where('key','city_partner_withdrawal_low')->value('value').'元');
                    }
                }],
        ];
    }
}
