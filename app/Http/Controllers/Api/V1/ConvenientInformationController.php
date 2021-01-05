<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConvenientInformationRequest;
use App\Model\ConvenientInformation;
use App\Transformers\ConvenientInformationTransformer;
use App\User;

class ConvenientInformationController extends Controller
{
    // 便民信息列表
    public function index()
    {
        $res = ConvenientInformation::where('card_id',\request('card_id'))->paginate();
        return $this->response->paginator($res,new ConvenientInformationTransformer());
    }

    // 发布
    public function store(ConvenientInformationRequest $request)
    {
        //  'title','content','location','view','card_id','user_id','no',
        //        'card_fee','top_fee','paid_at','payment_method','payment_no'
        $data = $request->only(['card_id','title','content','location','lng','lat']);
        $data['user_id'] = auth()->id();
        // 发帖的时候，有一部分的钱是到了邀请人哪里去了
        $parentId = $this->user()->parent_id;
        $userParent = User::where('parent_id',$parentId)->first();
        if ($userParent) {
            if($userParent->city_partner== 1) {
                // 数据库的邀请人的额度就是增加百分之 50
                $balanceCount = bcadd($request->card_fee,$request->top_fee,3);
                $balance = bcdiv($balanceCount,2,3);
                $userParent->update(['balance'=>$balance]);// 分一半给邀请人，这个只是积分，其实所有的钱是到了商户里面。
            }
        }


        // 支付 todo
        ConvenientInformation::create($data);


        return $this->response->created();
    }

    // 详情
    public function show($id)
    {
        $query = ConvenientInformation::where('id',$id);
        $query->increment('view');

        $user = auth('api')->user();
        if ($user->browseCards()->find($id)) {
            ConvenientInformation::where('id',$id)->update(['created_at'=>date('Y:m:d H:i:s')]);
        }else {
            $user->browseCards()->attach(ConvenientInformation::find($id));
        }

        $convenientInformation = $query->firstOrFail();
        return $this->response->item($convenientInformation,new ConvenientInformationTransformer());
    }

}
