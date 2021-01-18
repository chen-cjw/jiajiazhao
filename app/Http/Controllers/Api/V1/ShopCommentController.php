<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ShopCommentRequest;
use App\Model\Shop;
use App\Model\ShopComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopCommentController extends Controller
{
    public function store(ShopCommentRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only(['content', 'star','shop_id']);
            $data['user_id'] = auth('api')->id();

            if ($request->star > 3) {
                Shop::where('id', $request->shop_id)->increment('good_comment_count', 1);
            }
            Shop::where('id', $request->shop_id)->increment('comment_count', 1);

            $res = ShopComment::create($data);
            DB::commit();
            return [
                'msg'=>'ok',
                'code' => 200,
                'data'=>$res
            ];
        } catch (\Exception $ex) {
            Log::error('商铺评论'.$ex);
            throw new \Exception($ex); //

            DB::rollback();
        }
    }
}
