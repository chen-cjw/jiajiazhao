<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\ConvenientInformation;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use App\Transformers\ConvenientInformationTransformer;
use App\Transformers\LocalCarpoolingTransformer;
use App\Transformers\ShopTransformer;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    // 我发布本地拼车管理-删除
    public function localCarpool()
    {
        $user = auth('api')->user();
        $user->localCarpool()->whereIn('id',request('ids'))->delete();
        return $this->response->noContent();
    }

    // 我发布本地拼车列表
    public function localCarpoolIndex()
    {
        $user = auth('api')->user();
        $localCarpool = $user->localCarpool()->orderBy('created_at','desc')->paginate();
        return $this->response->paginator($localCarpool,new LocalCarpoolingTransformer());
    }

    // 我收藏帖子列表
    public function userFavoriteCardIndex()
    {
        return $this->response->paginator(auth()->user()->favoriteCards()->paginate(),new ConvenientInformationTransformer());
    }
    // 我收藏商户列表
    public function userFavoriteShopIndex()
    {
        return $this->response->paginator(auth()->user()->favoriteShops()->paginate(),new ShopTransformer());
    }
    // 收藏帖子
    public function userFavoriteCard($id)
    {
        $user = auth('api')->user();
        if ($user->favoriteCards()->find($id)) {
            return $this->response->created();
        }

        $user->favoriteCards()->attach(ConvenientInformation::findOrFail($id));
        return $this->response->created();
    }

    // 收藏帖子-管理
    public function cardDel(Request $request)
    {
        $user = auth('api')->user();
        $user->favoriteCards()->detach($request->ids);
        return $this->response->noContent();
    }

    // 收藏商户
    public function userFavoriteShop($id)
    {
        $user = auth('api')->user();
        if ($user->favoriteShops()->find($id)) {
            return $this->response->created();
        }

        $user->favoriteShops()->attach(Shop::findOrFail($id));
        return $this->response->created();
    }

    // 收藏商户-管理
    public function shopDel(Request $request)
    {
        $user = auth('api')->user();

        $user->favoriteShops()->detach($request->ids);
        return $this->response->noContent();
    }
    //


    // 我的收藏(商品) todo 暂未开放
    public function favorite($id)
    {

    }

    // 我的收藏管理(商品) todo 暂未开放
    public function favoriteDel()
    {

    }

}
