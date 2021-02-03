<?php

namespace App\Http\Requests;


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
            'amount'=>'required',
            'name'=>'required',
            'bank_of_deposit'=>'required',
            'bank_card_number'=>'required',
        ];
    }
}
