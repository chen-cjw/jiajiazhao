<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConvenientInformationRequest;
use App\Model\ConvenientInformation;
use App\Transformers\ConvenientInformationTransformer;

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
        $data = $request->only(['card_id','title','content','location']);
        $data['user_id'] = auth()->id();

        // 支付 todo
        ConvenientInformation::create($data);


        return $this->response->created();
    }

    // 详情
    public function show($id)
    {
        $query = ConvenientInformation::where('id',$id);
        $query->increment('view');
        $convenientInformation = $query->firstOrFail();
        return $this->response->item($convenientInformation,new ConvenientInformationTransformer());
    }

}
