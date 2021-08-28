<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\OwnCartRequest;
use App\Model\Shop\OwnProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;

class OwnCartController extends Controller
{
    protected $cartService;

    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->get();
        $addresses = auth('api')->user()->ownUserAddresses()->orderBy('last_used_at', 'desc')->get();
        return $this->responseStyle('ok',200,['cartItems' => $cartItems, 'addresses' => $addresses]);

        return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    public function add(OwnCartRequest $request)
    {
        $this->cartService->add($request->input('sku_id'), $request->input('amount'));
        return $this->responseStyle('ok',200,[]);

        return [];
    }

    public function remove($ids)
    {
        $idArr=explode(",",$ids);
        $skuIds = OwnProductSku::whereIn('id',$idArr)->pluck('id');
        $this->cartService->remove($skuIds);
        return $this->responseStyle('ok',200,[]);

        return [];
    }
}
