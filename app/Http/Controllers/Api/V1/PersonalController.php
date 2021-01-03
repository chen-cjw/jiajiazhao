<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\LocalCarpooling;
use App\Model\Shop;
use Illuminate\Http\Request;

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

    // 我的收藏(商品)
    public function favorite($id)
    {

    }

    // 我的收藏管理(商品)
    public function favoriteDel()
    {

    }


    // 收藏帖子
    public function userFavoriteCard()
    {
        
    }
    // 我发布的帖子
    public function cardIndex()
    {
        
    }

    // 我浏览的帖子
    public function browseCard()
    {
        
    }

    // 收藏商户
    public function userFavoriteShop($id)
    {

        $user = auth('api')->user();
        if ($user->favoriteShops()->find($id)) {
            return [];
        }

        $user->favoriteShops()->attach(Shop::find($id));

        return [];
    }

    // 收藏商户-管理
    public function shopDel(Request $request)
    {
        $user = auth('api')->user();

        $user->favoriteShops()->detach($request->ids);

        return [];
    }
    //
}
