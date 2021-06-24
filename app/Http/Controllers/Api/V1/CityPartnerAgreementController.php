<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\CityPartnerAgreement;
use Illuminate\Http\Request;

class CityPartnerAgreementController extends Controller
{
    public function index()
    {
        $res = CityPartnerAgreement::first();
        return $this->responseStyle('ok',200,$res);
    }
}
