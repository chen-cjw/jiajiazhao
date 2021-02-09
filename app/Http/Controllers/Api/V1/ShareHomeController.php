<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ShareHome;

class ShareHomeController extends Controller
{
    public function index()
    {
        return $this->responseStyle('ok',200 , ShareHome::find(1));
    }

    public function shopShareIndex()
    {
        return $this->responseStyle('ok',200 , ShareHome::find(2));
    }
    public function informationShareIndex()
    {
        return $this->responseStyle('ok',200 , ShareHome::find(3));
    }
    public function LocalShareIndex()
    {
        return $this->responseStyle('ok',200 , ShareHome::find(4));
    }
    // 总的分享
    public function AllShareIndex()
    {
        return $this->responseStyle('ok',200 , ShareHome::find(5));
    }
}
