<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Carpooling;
use Illuminate\Http\Request;

class CarpoolingController extends Controller
{
    public function index()
    {
        $car = Carpooling::first();
        return $this->responseStyle('ok',200,$car);
    }
}
