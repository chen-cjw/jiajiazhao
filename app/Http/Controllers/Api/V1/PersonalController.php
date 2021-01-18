<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\WithdrawalRequest;
use App\Model\ConvenientInformation;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use App\Model\Withdrawal;
use App\Transformers\ConvenientInformationTransformer;
use App\Transformers\LocalCarpoolingTransformer;
use App\Transformers\ShopTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    // 我发布本地拼车管理-删除
    public function localCarpool()
    {
        $user = auth('api')->user();
        $user->localCarpool()->whereIn('id',request('ids'))->delete();
        return $this->responseStyle('ok',200,'');
    }

    // 我发布本地拼车列表
    public function localCarpoolIndex()
    {
        $user = auth('api')->user();
        $localCarpool = $user->localCarpool()->orderBy('created_at','desc')->paginate();
        return $this->responseStyle('ok',200,$localCarpool);
    }

    // 我收藏帖子列表
    public function userFavoriteCardIndex()
    {
        $paginate = auth()->user()->favoriteCards()->paginate();
        return $this->responseStyle('ok',200,$paginate);
    }
    // 我收藏商户列表
    public function userFavoriteShopIndex()
    {
        $paginate = auth()->user()->favoriteShops()->paginate();
        return $this->responseStyle('ok',200,$paginate);
    }
    // 收藏帖子
    public function userFavoriteCard($id)
    {
        $user = auth('api')->user();
        if ($user->favoriteCards()->find($id)) {
            return $this->responseStyle('ok',200,'');
        }

        $user->favoriteCards()->attach(ConvenientInformation::findOrFail($id));
        return $this->responseStyle('ok',200,'');
    }

    // 收藏帖子-管理
    public function cardDel(Request $request)
    {
        $user = auth('api')->user();
        $user->favoriteCards()->detach($request->ids);
        return $this->responseStyle('ok',200,'');
    }

    // 收藏商户
    public function userFavoriteShop($id)
    {
        $user = auth('api')->user();
        if ($user->favoriteShops()->find($id)) {
            return $this->responseStyle('ok',200,'');
        }

        $user->favoriteShops()->attach(Shop::findOrFail($id));
        return $this->responseStyle('ok',200,'');
    }

    // 收藏商户-管理
    public function shopDel(Request $request)
    {
        $user = auth('api')->user();

        $user->favoriteShops()->detach($request->ids);
        return $this->responseStyle('ok',200,'');
    }
    // 我邀请的商户
    public function userInvitationShop()
    {
        $res = User::where('parent_id',auth('api')->id())->paginate();

    }
    // 我发布的帖子
    public function userCard()
    {
        $convenientInformation = auth('api')->user()->convenientInformation()->where('paid_at','!=',null)->orderBy('created_at','desc')->paginate();
        return $this->responseStyle('ok',200,$convenientInformation);
    }
    // 我发布帖子-管理
    public function userCardDel(Request $request)
    {
        foreach($request->ids as $v){
            auth('api')->user()->convenientInformation()->where('id',$v)->delete();
        }
        return $this->responseStyle('ok',200,[]);
    }
    // 提现
    public function userWithdrawal(WithdrawalRequest $request)
    {

        $user = auth('api')->user();
        $amount = $request->amount;
        DB::beginTransaction();
        try {
            if (bccomp($user->balance, $amount, 3) == -1) {
                return $this->responseStyle('余额不足', 200, []);
            }

            $res = Withdrawal::create([
                'amount' => $amount,
                'user_id' => auth()->id()
            ]);
            User::where('id', $user->id)->decrement('balance', $amount);
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error('提现出错', ['error' => $ex->getMessage()]);
            return $this->responseStyle('提现出错',200,[]);
        }
        return $this->responseStyle('ok',200,$res);

    }
    // 提现列表

    public function userWithdrawalIndex()
    {
        $query = auth('api')->user()->withdrawals();
        if($is_accept = \request('is_accept')) {
            $query = $query->where('is_accept',$is_accept);
        }
        $res = $query->paginate();
        return $this->responseStyle('ok',200,$res);
    }
    // 我邀请的人
    public function refUser()
    {
        $user = User::where('parent_id',auth('api')->id())->paginate();
        return $this->responseStyle('ok',200,$user);
    }
    // 我的收藏(商品) todo 暂未开放
    public function favorite($id)
    {

    }

    // 我的收藏管理(商品) todo 暂未开放
    public function favoriteDel()
    {

    }

}
