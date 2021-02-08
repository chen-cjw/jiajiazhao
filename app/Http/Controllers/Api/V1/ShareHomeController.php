<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ShareHome;

class ShareHomeController extends Controller
{
    public function index()
    {
        return $this->responseStyle('ok',200 , ShareHome::first());

    }
}
