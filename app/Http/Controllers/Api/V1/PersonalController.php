<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\LocalCarpooling;

class PersonalController extends Controller
{
    // 我发布本地拼车管理
    public function localCarpool()
    {
        $this->user()->localCarpool()->whereIn('id',request('ids'))->delete();
        return $this->response->noContent();
    }

    // 我发布本地拼车列表
    public function localCarpoolIndex()
    {
        return $this->response->paginator($this->user()->localCarpool()->orderBy()->paginate(),new LocalCarpooling());
    }

    // 我的收藏
    public function favorite()
    {

    }

    // 我的收藏管理
    public function favoriteDel()
    {

    }


    // 收藏帖子
    public function userFavoriteCard()
    {
        
    }
    // 我发布的帖子

    // 我浏览的帖子

    // 收藏商户

    // 收藏商户-管理

    //
}
