<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Api\V1\Controller;
use App\Model\Shop\OwnOrderItem;
use App\Model\Shop\OwnProduct;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class OwnProductController extends Controller
{
    public function index(Request $request)
    {
        // 创建一个查询构造器
        if ($request->categoryId == 'all') {
            $builder = OwnProduct::query()->where('on_sale', true);
        }else {
            $builder = OwnProduct::query()->where('on_sale', true)->where('own_category_id',$request->categoryId);
        }
        // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
        // search 参数用来模糊搜索商品
        if ($search = $request->input('search', '')) {
            $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
//                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like);
//                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($price = $request->input('price')) {
            $builder->orderBy('price', $price);
        }elseif ($sold_count = $request->input('sold_count')) {
            $builder->orderBy('sold_count', $sold_count);
        } elseif ($rating = $request->input('rating')) {
            $builder->orderBy('rating', $rating);
        }else {
            $builder->orderBy('sort', 'desc');
        }

        $products = $builder->paginate(16);
        return $this->responseStyle('ok',200,$products);

    }

    public function show($id)
    {
        $product = OwnProduct::where('id',$id)->firstOrFail();
        if (!$product->on_sale) {
            throw new InvalidResourceException('商品未上架');
        }
        $product['favored'] = false;

        if (auth('api')->user()) {
            $product['favored'] = boolval(auth('api')->user()->favoriteProducts()->find($product->id));
        }
        return $this->responseStyle('ok',200,$product);

        // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
        if($user = $request->user()) {
            // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OwnOrderItem::query()
            ->with(['order.user', 'productSku']) // 预先加载关联关系
            ->where('product_id', $product->id)
            ->whereNotNull('reviewed_at') // 筛选出已评价的
            ->orderBy('reviewed_at', 'desc') // 按评价时间倒序
            ->limit(10) // 取出 10 条
            ->get();
        return $this->responseStyle('ok',200,[
            'product' => $product,
            'favored' => $favored,
            'reviews' => $reviews
        ]);

        // 最后别忘了注入到模板中
        return view('products.show', [
            'product' => $product,
            'favored' => $favored,
            'reviews' => $reviews
        ]);
    }

    public function favor($id)
    {
        $product = OwnProduct::where('id',$id)->firstOrFail();
        $user = auth('api')->user();
        if ($user->favoriteProducts()->find($product->id)) {
            return $this->responseStyle('ok',200,[]);
        }
        $user->favoriteProducts()->attach($product);
        return $this->responseStyle('ok',200,[]);
    }

    public function disfavor($id)
    {
        $product = OwnProduct::where('id',$id)->firstOrFail();
        $user = auth('api')->user();
        $user->favoriteProducts()->detach($product);
        return $this->responseStyle('ok',200,[]);
    }

    public function favorites()
    {
        $products = auth('api')->user()->favoriteProducts()->paginate(16);
        return $this->responseStyle('ok',200,$products);
    }
}
