<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ConvenientInformationRequest;
use App\Model\AbbrCategory;
use App\Model\AdvertisingSpace;
use App\Model\Banner;
use App\Model\BannerInformation;
use App\Model\CardCategory;
use App\Model\CityPartner;
use App\Model\Comment;
use App\Model\ConvenientInformation;
use App\Model\History;
use App\Model\InformationCommission;
use App\Model\PostDescription;
use App\Model\Setting;
use App\Model\Shop;
use App\Model\TransactionRecord;
use App\Transformers\ConvenientInformationTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ConvenientInformationController extends Controller
{
    public function __construct()
    {
        $this->app = app('wechat.payment');
    }
    // 便民信息列表
    public function index()
    {
        // 第一部分的轮播图
        $bannerOne = BannerInformation::where('is_display',1)->orderBy('sort','desc');
        if (request('area')) {
            $bannerOne = $bannerOne->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);

//                $query->where('area', \request('area'))->orWhere('area', null);
            });
        }
        $bannerOne = $bannerOne->get();
//        $bannerOne = Banner::where('type','index_one')->where('is_display',1)->orderBy('sort','desc')->get();

        // 发帖说明
        $post = PostDescription::first();

        //第一部分的商户
        //$shopOne = Shop::where('type','one')->where('is_accept',1)->get();
//        $shopOne = AbbrCategory::where('parent_id',null)->where('local','one')->orderBy('sort','desc')->take(10)->get();

        // 广告位
        $advertisingSpaceQuery = AdvertisingSpace::orderBy('sort','desc')->where('is_display',1);
        if (request('area')) {
            $advertisingSpaceQuery = $advertisingSpaceQuery->where(function ($query) {
                $query->where('area', 'like',\request('area').'%')->orWhere('area', null);
            });
        }
        $advertisingSpace = $advertisingSpaceQuery->take(3)->get();
        // 帖子分类
        if (config('app.city') == 0) {
            $cardCategory = CardCategory::orderBy('sort','desc')->where('is_display',1)->take(5)->get();
            foreach ($cardCategory as $k=>$v) {
                $cardCategory[$k]['is_value'] = 1;//ConvenientInformation::where('card_id',$v->id)->first() ? 1 : 0;
            }
        }else {
            $cardCategory = CardCategory::orderBy('sort','desc')->where('is_display',1)->get();
            foreach ($cardCategory as $k=>$v) {
                if (config('app.city') == 1) {
                    $cardCategory[$k]['is_value'] = 1;//ConvenientInformation::where('card_id',$v->id)->first() ? 1 : 0;
                }else {
                    $cardCategory[$k]['is_value'] = ConvenientInformation::where('card_id',$v->id)->first() ? 1 : 0;
                }
            }
        }

//        $cardIdDefault = \request('card_id')?:1;

//        $convenientInformation = ConvenientInformation::where('card_id',$cardIdDefault)->paginate();

        return $this->responseStyle('ok',200,[
            'bannerOne'=>$bannerOne,
            'post'=>$post,
//            'shopOne'=>$shopOne,
            'advertisingSpace'=>$advertisingSpace,
            'cardCategory'=>$cardCategory,
//            'convenientInformation'=>$convenientInformation,
        ]);
    }
    // 搜索
    public function searchInformation(Request $request)
    {
//        $title = \request()->title;
//        $lat = \request('lat');
//        $lng = \request('lng');
//        $sql = "select * from convenient_information ";
//        $start = \request()->page ?: 1;
//        $limit = 16;
//        $area = \request()->area;
//
//        $sql = $sql."where created_at > DATE_SUB(CURDATE(), INTERVAL ".Setting::where('key','timeSearch')->value('value')." MONTH)";
//
//        $sql = $sql." and paid_at is not null";
//        $sql = $sql." and is_display = 1";
//        // 搜索
//        if($title!='') {
//            $sql = $sql." and title LIKE '%".$title."%'";
//        }
//        // 同城搜索
//        if ($area!='') {
//            $sql = $sql."and location LIKE '%".$area."%'";
//        }
//        // 附近
//        if ($lat && $lng) {
//            $sql = $sql." and
//            (acos(sin(({$lat}*3.1415)/180)
//            * sin((lat*3.1415)/180)
//            + cos(({$lat}*3.1415)/180)
//            * cos((lat*3.1415)/180)
//            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
//            * 6370.996) <= ".Setting::where('key','radius')->value('value');
//        }
//        $sql = $sql."created_at > DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
//
//        $sql = $sql." order by sort,created_at "."DESC";
//
//        $limit = $sql." LIMIT ".($start-1)*$limit.",".$limit;
//        $information = DB::select($limit);
//
//        foreach ($information as $item=>$value) {
//            $lat1 = $value->lat;
//            $lng1 = $value->lng;
//            $range = $this->getDistance($lat,$lng,$lat1,$lng1);
//            $information[$item]->range=$range; //几公里
//            $information[$item]->user_id=User::where('id',$value->user_id)->first();
//            $information[$item]->card_id=CardCategory::where('id',$value->card_id)->first();
//            $information[$item]->comment_count=Comment::where('information_id',$value->id)->count();
//            $information[$item]->images=$this->getImages($value->images);
//        }
//
//        return $this->responseStyle('ok',200,[
//            'information' => ['data'=>$information],
//        ]);

        $echostr = $request->title;
        Log::info($request->all());
        $res = ConvenientInformation::whereNotNull('paid_at')->where('location','like','%'.$request->area.'%')->where('is_display',1)->where('title','like','%'.$echostr.'%')->orderBy('sort','desc')->paginate();
        return $this->responseStyle('ok',200,$res);
    }
    // 发布
    public function store(ConvenientInformationRequest $request)
    {
        DB::beginTransaction();
        try {
            // todo 测试版本必须，测试阶段是无法获取 区域的
            Log::info(config('app.env'));
            if(config('app.env') == 'test') {
                $data = $request->only(['card_id', 'title', 'content', 'location', 'lng', 'lat']);

                $data['area'] = '新沂';
            }else {
                $data = $request->only(['card_id', 'title', 'content', 'location', 'lng', 'lat','area']);
            }
            $data['user_id'] = auth()->id();
            // 发帖的时候，有一部分的钱是到了邀请人哪里去了

            $data['no'] = ConvenientInformation::findAvailableNo();
            if ($request->card_fee == 1) {
                $data['card_fee'] = Setting::where('key', 'information_card_fee')->value('value');
            } else {
                $data['card_fee'] = 0;
            }
            // 多图片上传
            if ($request->images) {
                $data['images'] = json_encode($request->images);
            }
            if ($request->top_fee == 1) {
                $data['is_top'] = 1;
                $data['top_fee'] = Setting::where('key', 'information_top_fee')->value('value');
                if ($convenientInformation = ConvenientInformation::orderBy('sort','desc')->first()) {
                    $data['sort'] = bcadd($convenientInformation->sort,1);
                }
            } else {
                $data['top_fee'] = 0;
            }
            $data['is_display'] = Setting::where('key','informationDisplay')->value('value');
//        if (bccomp(bcadd($data['card_fee'],$data['top_fee'],2),0,2)!=1) {
//            $data['paid_at'] = Carbon::now(); // 更新支付时间为当前时间
//            $data['payment_no'] = ''; // 支付平台订单号
//        }
            $res = ConvenientInformation::create($data);
            // todo 第二期项目----------- 发布便民信息得到的分佣
            $parentId = auth('api')->user()->parent_id;
            Log::info(123);
            Log::info($request->district);
            Log::info(123);
            Log::info($parentId);

            if ($parentId) {
                $userParent = User::where('id', $parentId)->first(); // todo 这里应该是id不是parent_id
                Log::info($userParent);
                Log::info($parentId);

                // 邀请人获取积分
                if ($userParent) {
//            if($userParent->city_partner== 1) {
                    // 数据库的邀请人的额度就是增加百分之 50
//                    $balanceCount = bcadd($request->card_fee, $request->top_fee, 3);
//                    $balance = bcdiv($balanceCount, 2, 3);
                    Log::info(123);

                    // 形成一个订单 ，支付成功修改这个订单状态，然后钱到会员余额
                    TransactionRecord::create([
                        'amount' => Setting::where('key', 'information_fee')->value('value')?:0,
                        'come_from' => auth('api')->user()->nickname . '发布了一条便民信息',
                        'user_id' => auth()->id(),
                        'parent_id' => $parentId,
                        'model_id' => $res->id,
                        'model_type' => ConvenientInformation::class
                    ]);
                    Log::info(123);

                    //$userParent->update(['balance'=>$balance]);// 分一半给邀请人，这个只是积分，其实所有的钱是到了商户里面。
//            }
                }
            }

            // todo 合伙人获得收入
            Log::info('新沂0');
            Log::info($request->district);
            // todo 这里最好是模糊查找城市 // todo ->where('market',$request->market)
            $market =  $this->market($request->area);
            Log::info(111);

            Log::info($request->district.'-'.$market);

            Log::info(111);

            if ($cityPartner = CityPartner::where('in_city',$request->district)->where('is_partners',3)->whereNotNull('paid_at')->where('market',$market)->first()) {
                Log::info(13);

                Log::info('新沂1');
                $amount = bcadd($request->card_fee, $request->top_fee, 3);

//                $amount = bcadd($res->amount,$res->top_amount,4);
                InformationCommission::create([
                    'amount'=>$amount,// 商户入住金额
                    'commissions'=>Setting::where('key', 'city_information_fee')->value('value')?:0,//bcmul($rate,$amount,4),// 佣金
                    'rate'=>0,// 比例
                    'user_id'=>auth('api')->id(), // 用户
                    'parent_id'=>$cityPartner->user_id,// 城市合伙人ID
                    'information_id'=>$res->id,// 那个店铺
//                    'district'=>$request->district// 区域(例如：新沂市)
                    'district'=>$request->district,// 区域(例如：新沂市) todo
                    'market'=>$market// 区域(例如：新沂市)

                ]);
            }

            DB::commit();
            return ['code'=>200,'msg'=>'ok','data'=>$res];
        } catch (\Exception $ex) {
            DB::rollback();
            throw new \Exception($ex); // 报错原因大多是因为taskFlowCollections表，name和user_id一致
        }
    }

    public function market($address)
    {
        preg_match('/(.*?(省|自治区|北京市|天津市))/', $address, $matches);
        if (count($matches) > 1) {
            $province = $matches[count($matches) - 2];
            $address = str_replace($province, '', $address);
        }
        preg_match('/(.*?(市|自治州|地区|区划|县))/', $address, $matches);
        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];
            $address = str_replace($city, '', $address);
        }
        preg_match('/(.*?(区|县|镇|乡|街道))/', $address, $matches);
        if (count($matches) > 1) {
            $area = $matches[count($matches) - 2];
            $address = str_replace($area, '', $address);
        }
        return isset($city) ? $city : '';
        return [
            'province' => isset($province) ? $province : '',
            'city' => isset($city) ? $city : '',
            'area' => isset($area) ? $area : '',
        ];
    }



    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {
            // 校验权限
            $convenientInformation = auth('api')->user()->convenientInformation()->where('id', $id)->firstOrFail();
            // bcsub — 减法
            if (bcsub(time(), strtotime($convenientInformation->created_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($convenientInformation->paid_at || $convenientInformation->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '订单：' . $convenientInformation->no,
                'out_trade_no' => $convenientInformation->no,
                'total_fee' => bcadd($convenientInformation->card_fee,$convenientInformation->top_fee,2)  * 100,//$wechatPay->total_fee * 100,
                'notify_url' => config('app.app_pay_url')."information_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'msg' => 'ok'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 422,
                "msg" => $e->getMessage(),
                'data' => ''
            ]);
        }
    }


    public function wechatNotify()
    {
        Log::info('进入发送通知');
        $response = $this->app->handlePaidNotify(function($message, $fail){
            Log::info('微信支付订单号');
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = ConvenientInformation::where('no',$message['out_trade_no'])->first();
            if (!$order) {
                Log::error('订单不存在则告知微信支付');
                return 'fail';
            }
            // 订单已支付
            if ($order->paid_at) {
                Log::error('告知微信支付此订单已处理');
                return app('wechat_pay')->success();
            }
            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                Log::info('告诉微信，我已经处理完了，订单没找到，别再通知我了');
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            ///////////// todo <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            Log::info('建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付');

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                Log::info('表示通信状态，不代表支付状态');

                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    Log::info('用户支付成功');

//                    $order->status = 'paid';
                    $order->paid_at = Carbon::now(); // 更新支付时间为当前时间
                    $order->payment_no = $message['transaction_id']; // 支付平台订单号
                    // todo 第二期项目---------如果 已经生成了订单那么这里支付成功了，就给推广人员到账
                    Log::info(TransactionRecord::where('model_id',$order->id)->where('model_type',ConvenientInformation::class)->first());
                    Log::info($order->id);
                    Log::info(ConvenientInformation::class);
                    if ($record = TransactionRecord::where('model_id',$order->id)->where('model_type',ConvenientInformation::class)->first()) {
                        Log::info(99999);
                        User::where('id',$record->parent_id)->increment('balance',Setting::where('key','information_fee')->value('value')?:0);
                        TransactionRecord::where('model_id',$order->id)->where('model_type',ConvenientInformation::class)->update([
                             'is_pay'=>1
                        ]);

                    }
                    Log::info(1111111);
                    Log::info($order);
                    Log::info(2222222);
                    Log::info(InformationCommission::where('information_id',$order->id)->first());
                    Log::info(2222222);
                    if ($cityPartner = InformationCommission::where('information_id',$order->id)->first()) {
                        Log::info(333333333);
                        // ->where('market',$cityPartner->market)
                        CityPartner::where('in_city','like',$cityPartner->district.'%')->where('market',$cityPartner->market)->where('is_partners',3)->whereNotNull('paid_at')->increment('balance',$cityPartner->commissions);

                        InformationCommission::where('information_id',$order->id)->update([
                            'is_pay'=>1
                        ]);
                    }
                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    Log::info('用户支付失败');
                    $order->status = 'paid_fail';
                }
            } else {
                Log::info('通信失败，请稍后再通知我');
                return $fail('通信失败，请稍后再通知我');
            }
            $order->save(); // 保存订单
            // todo 订单支付成功通知,支付平台的订单号
//            $user = User::find($order->user_id);
//            order_wePay_success_notification($user->ml_openid,$order->payment_no,$order->paid_at,bcadd($order->card_fee,$order->top_fee,2) ,$order->title,'');
            return true; // 返回处理完成
        });
        return $response;
    }

    // 详情
    public function show($id)
    {
        $query = ConvenientInformation::where('id',$id);
        $query->increment('view');
        $user = auth('api')->user();

        $convenientInformation = $query->firstOrFail();
        if($user->favoriteCards()->where('information_id',$convenientInformation->id)->first()) {
            $convenientInformation['favoriteCards'] = 1;
        }else {
            $convenientInformation['favoriteCards'] = 0;
        }

        if (config('app.city') == 1) {
            $comment = Comment::where('information_id',$convenientInformation->id)->whereNull('parent_reply_id')->orderBy('created_at','desc')->paginate();
        }else {
            $comment = Comment::where('information_id',$convenientInformation->id)->whereNull('parent_reply_id')->orderBy('created_at','desc')->where('id','>',90)->paginate();
        }

        $this->history(ConvenientInformation::class,$id,$user);

        return $this->responseStyle('ok',200,[
            'convenientInformation'=>$convenientInformation,
            'comment'=>$comment
        ]);

    }

}
