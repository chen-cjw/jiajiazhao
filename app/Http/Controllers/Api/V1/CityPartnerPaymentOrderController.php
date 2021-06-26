<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CityPartnerPaymentOrderRequest;
use App\Model\CityPartnerPaymentOrder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityPartnerPaymentOrderController extends Controller
{
    public function allIndex()
    {
        $query = CityPartnerPaymentOrder::with('user');
        if($status = \request('status')) {
            $query = $query->where('status',$status);
        }
        $res = $query->orderBy('id','desc')->paginate();
        return $this->responseStyle('ok',200,$res);
    }
    // 城市合伙人提现记录
    public function index()
    {
        $query = auth('api')->user()->cityPartnerPaymentOrders();
        if($status = \request('status')) {
            $query = $query->where('status',$status);
        }
        $res = $query->orderBy('id','desc')->paginate();
        return $this->responseStyle('ok',200,$res);
    }
    public function store(CityPartnerPaymentOrderRequest $request)
    {
        $user = auth('api')->user()->cityPartner;
        if (!$user) {
            return [
                'msg'=>'您未开通合伙人',
                'code'=>422,
                'date'=>[]
            ];
        }
        $amount = $request->amount;

//        $table->decimal('amount', 10, 2)->comment('费用');
//        $table->decimal('balance', 10, 3)->default(0)->comment('可提金额');
//        $table->decimal('total_balance', 10, 3)->default(0)->comment('总金额');
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
            $payOrder = new CityPartnerPaymentOrder();
            $payOrder->fill([
                'user_id' => $user->id,
                'order_number' => $this->getordernumber(),
                'amount' => $amount,
                'status' => 2,
                'intro' => ''
            ]);

            $payOrder->save();

            auth('api')->user()->cityPartner()->decrement('balance', $amount);
            Log::info(123);

            DB::commit();
            return [
                'msg'=>'ok',
                'code'=>200,
                'date'=>$payOrder
            ];

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error('提现出错', ['error' => $ex]);
            return [
                'msg'=>'提现出错',
                'code'=>422,
                'date'=>$ex
            ];
        }
    }
    //订单号
    private function getordernumber()
    {
        $num = time();
        $num = 'cp'.(date('YmdHis', $num)) . rand(1000, 9999);
        return $num;
    }

}
