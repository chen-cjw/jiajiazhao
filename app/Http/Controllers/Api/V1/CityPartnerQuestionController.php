<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\CityPartnerQuestion;
use Illuminate\Http\Request;

class CityPartnerQuestionController extends Controller
{
    // 城市和人人常见问题
    public function index()
    {
        $cityPartnerQuestion = CityPartnerQuestion::orderBy('sort','desc')->first();
        return $this->responseStyle('ok',200,$cityPartnerQuestion);
    }
}
