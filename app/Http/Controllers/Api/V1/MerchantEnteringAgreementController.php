<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\MerchantEnteringAgreement;

class MerchantEnteringAgreementController extends Controller
{
    public function index()
    {
        $res = MerchantEnteringAgreement::first();
        return $this->responseStyle('ok',200,$res);
    }
}
