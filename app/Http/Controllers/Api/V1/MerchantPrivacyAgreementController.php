<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\MerchantPrivacyAgreement;

class MerchantPrivacyAgreementController extends Controller
{
    public function index()
    {
        $res = MerchantPrivacyAgreement::first();
        return $this->responseStyle('ok',200,$res);
    }
}
