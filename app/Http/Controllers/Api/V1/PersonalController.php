<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\WithdrawalRequest;
use App\Model\BannerPerson;
use App\Model\ConvenientInformation;
use App\Model\History;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use App\Model\UserFavoriteCard;
use App\Model\UserFavoriteShop;
use App\Model\Withdrawal;
use App\Transformers\ConvenientInformationTransformer;
use App\Transformers\LocalCarpoolingTransformer;
use App\Transformers\ShopTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $res = $user->favoriteCards()->detach($id);
            return $this->responseStyle('ok',200,$res);
        }

        $res = $user->favoriteCards()->attach(ConvenientInformation::findOrFail($id));
        return $this->responseStyle('ok',200,$res);
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
            $res = $user->favoriteShops()->detach($id);
            return $this->responseStyle('ok',200,$res);
        }

        $res = $user->favoriteShops()->attach(Shop::findOrFail($id));
        return $this->responseStyle('ok',200,$res);
    }

    // 收藏商户-管理
    public function shopDel(Request $request)
    {
        $user = auth('api')->user();
        foreach($request->ids as $v) {
            UserFavoriteShop::where('shop_id', $v)->delete();
        }
        $res = $user->favoriteShops()->detach($request->ids);
        return $this->responseStyle('ok',200,$res);
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
            // 删除别人收藏的
            UserFavoriteCard::where('information_id',$v)->delete();
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
                return [
                    'msg'=>'余额不足',
                    'code'=>422,
                    'date'=>[]
                ];
                return $this->responseStyle('余额不足', 422, []);
            }

            $res = Withdrawal::create([
                'amount' => $amount,
                'name' =>$request->name,// 姓名
                'bank_of_deposit' =>$request->bank_of_deposit,// 开户行
                'bank_card_number' =>$request->bank_card_number, //银行卡号
                'user_id' => auth()->id()
            ]);
            Log::info($res);
            User::where('id', $user->id)->decrement('balance', $amount);
            Log::info(123);

            DB::commit();
            return [
                'msg'=>'ok',
                'code'=>200,
                'date'=>$res
            ];
//            return $this->responseStyle('ok',200,$res);

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error('提现出错', ['error' => $ex]);
            return [
                'msg'=>'提现出错',
                'code'=>422,
                'date'=>$ex
            ];
//            return $this->responseStyle('提现出错',422,$ex);
        }

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
        $user = User::where('parent_id',auth('api')->id())->whereHas('shop',function ($query) {
            $query->whereNotNull('paid_at');
        })->paginate();
        $user['ref_user_count']=User::where('parent_id',auth('api')->id())->count();

        return $this->responseStyle('ok',200,$user);
    }

    public function banner()
    {
        $res = BannerPerson::where('is_display',1)->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,$res);
    }

    // 我的浏览
    public function historyIndex(Request $request)
    {
        $query = History::query();
        if($request->type == 'shop') {
            $query = $query->where('model_type',Shop::class);
        }
        if($request->type == 'local') {
            $query = $query->where('model_type',LocalCarpooling::class);

        }
        if ($request->type == 'information') {
            $query = $query->where('model_type',ConvenientInformation::class);
        }
        $res = $query->orderBy('updated_at','desc')->paginate();
        return $this->responseStyle('ok',200,$res);
    }

    // 浏览管理
    public function historyDel(Request $request)
    {
        foreach($request->ids as $v){
            $res = History::where('id',$v)->where('user_id',auth('api')->id())->delete();
        }
        return $this->responseStyle('ok',200,$res);
    }
    // 商铺管理不可以删除
    public function shopManage()
    {
        $res = auth('api')->user()->shop()->where('paid_at','!=',null)->first();
        return $this->responseStyle('ok', 200,$res);
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
