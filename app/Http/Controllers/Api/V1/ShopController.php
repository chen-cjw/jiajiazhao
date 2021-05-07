<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ShopRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Model\AbbrCategory;
use App\Model\History;
use App\Model\Setting;
use App\Model\Shop;
use App\Model\ShopComment;
use App\Model\TransactionRecord;
use App\Model\UserFavoriteShop;
use App\Transformers\ShopTransformer;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{



    public function __construct()
    {
        $this->app = app('wechat.payment');
    }
    // 商户列表
    public function index()
    {
        $name = \request()->name;
        $lat = \request('lat');
        $lng = \request('lng');
        $one_abbr = \request()->one_abbr;
        $two_abbr = \request()->two_abbr;
        $view = \request()->view;
        $lat = \request()->lat;
        $comment_count = \request()->comment_count;
        $start = \request()->page ?: 1;
        $limit = 15;
        $sql = "select *, (lat * 1000000 ) AS subtotal 
,ACOS(SIN(( $lat * 3.1415) / 180 ) *SIN((lat * 3.1415) / 180 ) +COS(( $lat* 3.1415) / 180 ) * COS((lat * 3.1415) / 180 ) *COS(( $lng* 3.1415) / 180 - (lng * 3.1415) / 180 ) ) * 6380*1000 AS juli

 from shops ";//,int (lat * 1000000 ) AS subtotal
        $dueDate = date('Y-m-d H:i:s');
        $area = \request()->area;
        Log::info('--------------该用户已经进入到商户列表中-------------------'.$area);

        // 一级
        if($one_abbr) {
            $sql = $sql."where (one_abbr0={$one_abbr} OR one_abbr1={$one_abbr} OR one_abbr2={$one_abbr})";
        }

        // 搜索
        if($name!='') {
            if (!$one_abbr) {
                $sql = $sql."where name LIKE '%".$name."%'";
            }else {
                $sql = $sql."and name LIKE '%".$name."%'";
            }
        }
        // 同城搜索
        if (config('app.city') == 1) {
            if ($area != '') {
                Log::info($area);
                $sql = $sql . "and area LIKE '%" . $area . "%'";
            }
        }
        // 时间搜索
        $sql = $sql."and  due_date>='{$dueDate}'" ;

        // 二级
        if($two_abbr!='') {
            $sql = $sql." and (two_abbr0={$two_abbr} OR two_abbr1={$two_abbr} OR two_abbr2={$two_abbr})";
        }
        // 附近
        if (config('app.city') == 1) {
            $lat = bcadd($lat, "0.00001", 6);
            if ($lat && $lng) {
                $sql = $sql . "and
            (acos(sin(({$lat}*3.1415)/180)
            * sin((lat*3.1415)/180)
            + cos(({$lat}*3.1415)/180)
            * cos((lat*3.1415)/180)
            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
            * 6370.996) <= " . Setting::where('key', 'radius')->value('value');
            }
        }
//        $sql = $sql." and paid_at is not null";
        $sql = $sql." and payment_no is not null";
        $sql = $sql." and is_accept = 1";
//        $sql = $sql." and due_date > ".date('Y-m-d H:i:s');
        // 人气
        if ($view) {
            $sql = $sql." order by view ".$view;
        }else if ($comment_count) { // 评论
            $sql = $sql." order by comment_count ".$comment_count;
        }else if (\request('lat_lng')) {
            $sql = $sql." order by juli ASC";//\request('lat_lng');

        }else{
            $sql = $sql." order by sort DESC,created_at DESC";//created_at
        }
//        if (\request('lat')) {
//            $sql = $sql." order by ROUND(subtotal) ".\request('lat');
//
//        }
//        if ($lat) {
//            $sql = $sql." order by lat ".$lat;
//        }else
//        $sql = $sql." and order by created_at "."DESC";
        $total = count(DB::select($sql));
//        return \request('page');
//        $limit = $sql." LIMIT ".$start.",".$limit;
        $limit = $sql." LIMIT ".(\request('page'))*$limit.",".$limit;
//        $sql = $sql.'order by sort desc';

        $query = DB::select($limit);
        foreach ($query as $item=>$value) {
            $lat1 = $value->lat;
            $lng1 = $value->lng;
            Log::info(123123123123);
            Log::info($lat.'/'.$lng.'/'.$lat1.'/'.$lng1);
            Log::info(123123123123);

            $range = $this->getDistance($lat,$lng,$lat1,$lng1);
            // 几公里
            $query[$item]->range=$range;
            // 平均星级
            $query[$item]->favoriteShopStarSvg=number_format(ShopComment::where('shop_id',$value->id)->avg('star'),1);
            $query[$item]->logo=$this->getLogo($value->logo);

        }

        $shop['data'] = $query;
        $shop['total'] = $total;
        $shop['image'] = AbbrCategory::where('id',$one_abbr)->value('image');
        return $this->responseStyle('ok',200,$shop);
    }
    // 身份证不显示出来
    public function getLogo($pictures)
    {
        if (!$pictures) {
            return $pictures;
        }
        $data = json_decode($pictures, true);
        $data['with_iD_card'] = '';
        return $data;
    }
    // 搜索
    public function searchInformation(Request $request)
    {
        $echostr = $request->title;
        $res = Shop::where('title','like','%'.$echostr.'%')->paginate();
        return $this->responseStyle('ok',200,$res);
    }

    // 入住 service_price 这个是一个图片
    public function store(ShopRequest $request)
    {
        //  商户认证必填
        if (!isset($request->logo['store_logo'])) {
            return $this->responseStyle('门店照/Logo必传',422,[]);
        }
        if (!isset($request->logo['with_iD_card'])) {
            return $this->responseStyle('持身份证照必传',422,[]);
        }
        $res = Shop::where('id',$request->id)->where('user_id',auth('api')->id())->first();
        // 编辑
        if($shopId = $request->id) {
//            $data['due_date'] = date($res->due_date,strtotime('+2year'));
            if(!$res) {
                return $this->responseStyle('请勿非法续费!',200,[]);
            }
        }else{
            if (auth('api')->user()->shop()->where('paid_at','!=',null)->first()) {
                Log::info('您已注册商户');
                return $this->responseStyle('您已注册商户！',422,"");
            }
        }
        DB::beginTransaction();
        try {
            $data = $request->only([
                'one_abbr0', 'one_abbr1', 'one_abbr2',
                'two_abbr0', 'two_abbr1', 'two_abbr2', 'name', 'area', 'detailed_address', 'contact_phone', 'wechat',
                'logo', 'service_price', 'merchant_introduction', 'is_top', 'lng', 'lat'
            ]);
            for ($i = 0; $i < count($request->one_abbr); $i++) {
                $data['one_abbr' . $i] = $request->one_abbr[$i];
            }
            for ($i = 0; $i < count($request->two_abbr); $i++) {
                $data['two_abbr' . $i] = $request->two_abbr[$i];
            }

            $data['no'] = Shop::findAvailableNo();
            $data['amount'] = $request->shop_fee == 0 ? Setting::where('key', 'shop_fee_two')->value('value') : Setting::where('key', 'shop_fee')->value('value');
            if ($request->shop_fee == 1) {
                $data['amount'] = Setting::where('key', 'shop_fee')->value('value');
//                if ($res) {
////                    $data['is_accept'] = 0; // 是否同意
////                    $data['due_date'] = date('Y-m-d H:i:s',strtotime("+1year",strtotime($res->due_date)));
////                    $data['due_date'] = date($res->due_date,strtotime('+1year'));
//                }else {
//                    $data['due_date'] = date('Y-m-d H:i:s',strtotime('+1year'));
//                }

            }else if ($request->shop_fee_two == 1){
                $data['amount'] = Setting::where('key', 'shop_fee_two')->value('value');
                // 编辑
//                if ($res) {
////                    $data['is_accept'] = 0; // 是否同意
//                    $data['due_date'] = date('Y-m-d H:i:s',strtotime("+2 year",strtotime($res->due_date)));
////                    $data['due_date'] = date($res->due_date,strtotime('+2year'));
//                }else {
//                    $data['due_date'] = date('Y-m-d H:i:s',strtotime('+2year'));
//                }

            }
            if ($request->shop_top_fee == 1) {
                $top_fee = Setting::where('key', 'shop_top_fee')->value('value');
                $data['is_top'] = 1;
                $data['top_amount'] = $top_fee;
            }else if ($request->shop_top_fee_two == 1) {
                $top_fee = Setting::where('key', 'shop_top_fee_two')->value('value');
                $data['is_top'] = 1;
                $data['top_amount'] = $top_fee;
            }else {
                $top_fee = 0;
            }
            // 多图片上传
            if ($request->images) {
                $data['images'] = json_encode($request->images);
            }
            $data['top_amount'] = $top_fee;// $request->shop_top_fee == 0 ? Setting::where('key', 'shop_top_fee_two')->value('value') : Setting::where('key', 'shop_top_fee')->value('value');
            $data['logo'] = json_encode($request->logo);
            $data['user_id'] = auth('api')->id();
            if ($request->shop_top_fee != 0 || $request->shop_top_fee_two != 0) {
                $shop = Shop::orderBy('sort', 'desc')->first();
                if ($shop) {
                    $data['sort'] = bcadd($shop->sort, 1);

                } else {
                    $data['sort'] = 1;
                }
            }
            // 入驻商户是否需要审核
            $data['is_accept'] = Setting::where('key','shop_verify')->value('value')?:0;
            Log::info($data);
            if ($res) {
                $shop = Shop::where('id',$request->id)->update($data);
            }else {
                $res = Shop::create($data);
            }

            // todo
            $parentId = auth('api')->user()->parent_id;
            if ($parentId) {
                $userParent = User::where('parent_id', $parentId)->first();
                // 邀请人获取积分
                if ($userParent) {
//            if($userParent->city_partner== 1) {
                    // 数据库的邀请人的额度就是增加百分之 50
                    $balanceCount = bcadd($data['amount'], $data['top_amount'], 3);
                    // 形成一个订单 ，支付成功修改这个订单状态，然后钱到会员余额
                    $res['record'] = TransactionRecord::create([
                        'amount' => $balanceCount,
                        'come_from' => auth('api')->user()->nickname . '入驻了商户',
                        'user_id' => auth()->id(),
                        'parent_id' => $parentId,
                        'model_id' => $res->id,
                        'model_type' => Shop::class
                    ]);
//            }
                }
            }

            DB::commit();
            return ['code'=>200,'msg'=>'ok','data'=>$res];
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('商户入驻失败:'.$ex);
            throw new \Exception($ex); // 报错原因大多是因为taskFlowCollections表，name和user_id一致
        }
    }
    // 续费
    public function xufei(Request $request,$id)
    {
        $res = Shop::where('id',$id)->where('user_id',auth('api')->id());
        if ($request->shop_fee == 1) {
            $data['amount'] = Setting::where('key', 'shop_fee')->value('value');
        }else if ($request->shop_fee_two == 1){
            $data['amount'] = Setting::where('key', 'shop_fee_two')->value('value');
        }else {
            $data['amount'] = 10000;

        }
        if ($request->shop_top_fee == 1) {
            $top_fee = Setting::where('key', 'shop_top_fee')->value('value');
            $data['is_top'] = 1;
            $data['top_amount'] = $top_fee;

        }else if ($request->shop_top_fee_two == 1) {
            $top_fee = Setting::where('key', 'shop_top_fee_two')->value('value');
            $data['is_top'] = 1;
            $data['top_amount'] = $top_fee;

        }else {
            $data['top_amount'] = 0;
        }
        $data['paid_at'] = null;
        $data['no'] = Shop::findAvailableNo();
//        $data['updated_at'] = date('Y-m-d H:i:s');
        $res->update($data);
        return ['code'=>200,'msg'=>'ok','data'=>$res->first()];

    }

    // 编辑
    public function update(ShopUpdateRequest $request,$id)
    {
        //  商户认证必填
//        if (!isset($request->logo['store_logo'])) {
//            return $this->responseStyle('门店照/Logo必传',422,[]);
//        }
//        if (!isset($request->logo['with_iD_card'])) {
//            return $this->responseStyle('持身份证照必传',422,[]);
//        }


        // 编辑
        $res = Shop::where('id',$id)->where('user_id',auth('api')->id())->first();
        if(!$res) {
            return $this->responseStyle('非法修改!',200,[]);
        }
//        $data['logo']['store_logo']=$res->logo['store_logo'];
//        $data['logo']['with_iD_card']=$res->logo['with_iD_card'];
//        $data['logo']['business_license']=$request->logo['business_license'];
//        $data['logo']['professional_qualification']=$request->logo['professional_qualification'];
//
//        $data['logo'] = json_encode($data['logo']);
//        return $request->logo;
//        return $data;


        DB::beginTransaction();
        try {
            $data = $request->only([
                'one_abbr0', 'one_abbr1', 'one_abbr2',
                'two_abbr0', 'two_abbr1', 'two_abbr2', 'name', 'area', 'detailed_address', 'contact_phone', 'wechat',
                'service_price', 'merchant_introduction', 'is_top', 'lng', 'lat'
            ]);
            for ($i = 0; $i < count($request->one_abbr); $i++) {
                $data['one_abbr' . $i] = $request->one_abbr[$i];
            }
            for ($i = 0; $i < count($request->two_abbr); $i++) {
                $data['two_abbr' . $i] = $request->two_abbr[$i];
            }

            $data['no'] = Shop::findAvailableNo();
//            $data['amount'] = $request->shop_fee == 0 ? Setting::where('key', 'shop_fee_two')->value('value') : Setting::where('key', 'shop_fee')->value('value');
//            if ($request->shop_fee == 1) {
//                $data['amount'] = Setting::where('key', 'shop_fee')->value('value');
//            }else if ($request->shop_fee_two == 1){
//                $data['amount'] = Setting::where('key', 'shop_fee_two')->value('value');
//            }
//            if ($request->shop_top_fee == 1) {
//                $top_fee = Setting::where('key', 'shop_top_fee')->value('value');
//                $data['is_top'] = 1;
//            }else if ($request->shop_top_fee_two == 1) {
//                $top_fee = Setting::where('key', 'shop_top_fee_two')->value('value');
//                $data['is_top'] = 1;
//            }else {
//                $top_fee = 0;
//            }
            // 多图片上传
            if ($request->images) {
                $data['images'] = json_encode($request->images);
            }
//            $data['top_amount'] = $top_fee;// $request->shop_top_fee == 0 ? Setting::where('key', 'shop_top_fee_two')->value('value') : Setting::where('key', 'shop_top_fee')->value('value');
            $data['logo']['store_logo']=$res->logo['store_logo'];
            $data['logo']['with_iD_card']=$res->logo['with_iD_card'];
            if(isset($request->logo['business_license'])) {
                $data['logo']['business_license']=$request->logo['business_license'];
            }
            if(isset($request->logo['professional_qualification'])) {
                $data['logo']['professional_qualification'] = $request->logo['professional_qualification'];
            }
            $data['logo'] = json_encode($data['logo']);

            $data['user_id'] = auth('api')->id();
            if ($request->shop_top_fee != 0 || $request->shop_top_fee_two != 0) {
                $shop = Shop::orderBy('sort', 'desc')->first();
                if ($shop) {
                    $data['sort'] = bcadd($shop->sort, 1);

                } else {
                    $data['sort'] = 1;
                }
            }
            Log::info($data);
//            return $data;
            Shop::where('id',$request->id)->update($data);
            DB::commit();
            return ['code'=>200,'msg'=>'ok','data'=>$res];
        } catch (\Exception $ex) {
            DB::rollback();
            throw new \Exception($ex); // 报错原因大多是因为taskFlowCollections表，name和user_id一致
        }
    }


    public function show($id)
    {
        Shop::where('id',$id)->increment('view');
        $shop = Shop::findOrFail($id);//,with('shopComments')->'shopComments.user'

        $user = auth('api')->user();
        if($user->favoriteShops()->where('shop_id',$shop->id)->first()) {
            $shop['favoriteShops'] = 1;
        }else {
            $shop['favoriteShops'] = 0;
        }
        if($lat = \request('lat') && $lng = \request('lng')) {
            $shop['range'] = $this->getDistance($shop->lat,$shop->lng,\request('lat'),$lng);
//            $range = $this->getDistance($lat,$lng,$lat1,$lng1);

        }else {
            $shop['range']="未知";
        }
        // 收藏数
        $shop['favoriteShopCounts'] = UserFavoriteShop::where('shop_id',$id)->count();
        // 平均星级
        $shop['favoriteShopStarSvg'] = number_format(ShopComment::where('shop_id',$id)->avg('star'),1) ;
//        if ($user->favoriteShops()->find($id)) {
//            UserFavoriteShop::where('id',$id)->update(['created_at'=>date('Y:m:d H:i:s')]);
//        }else {
//            $user->favoriteShops()->attach(Shop::find($id));
//        }
        $shopComment = ShopComment::where('shop_id',$id)->whereNull('parent_reply_id')->orderBy('created_at','desc')->paginate();

        // 浏览记录贴
        $this->history(Shop::class,$id,$user);

        return $this->responseStyle('ok',200,[
            'shop' => $shop,
            'shop_comment' => $shopComment
        ]);
    }

    public function uploadImg(Request $request)
    {
        return $this->uploadImages($request);
    }
    // 单图片上传
    public function uploadImages($request)
    {

        if ($request->isMethod('post')) {
            $file = $request->file('logo');
            Log::error('logo');
            Log::error($file);
            Log::error('logo');
            if($file->isValid()){
                $path = Storage::disk('public')->putFile(date('Ymd') , $file);
                if($path) {
                    return ['code' => 0 , 'msg' => '上传成功' , 'data' => $this->imagePath($path)];
                }
                else {
                    return ['code' => 400 , 'msg' => '上传失败'];
                }
            }
        } else {
            return ['code' => 400, 'msg' => '非法请求'];
        }
    }

    // 多图片上传
    public function upload()
    {
        $path = [];
        if (request()->hasFile('images')){
            foreach (request()->file('images') as $file){
                $path[] = Storage::disk('public')->putFile(date('Ymd') , $file);
            }
            $da = array();
            foreach ($path as $k=>$v) {
                if (Str::startsWith($v, ['http://', 'https://'])) {
                    $da[] = $v;
                }else {
                    $da[] = \Storage::disk('public')->url($v);
                }
            }
            return $da;
        }else{
            return $this->responseStyle('没有图片',422,"");
            return response()->json([
                'info'=>'没有图片'
            ]);
        }
    }

    public function imagePath($path)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return \Storage::disk('public')->url($path);
    }

    // （当前纬度,当前经度）
    public function lat_lng($lng,$lat)
    {
        $res = DB::select("select * from shops where 
            (acos(sin(({$lat}*3.1415)/180)
            * sin((lat*3.1415)/180)
            + cos(({$lat}*3.1415)/180)
            * cos((lat*3.1415)/180)
            * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))
            * 6370.996) <= 5"
        );
        return $this->responseStyle('ok',200,$res);

        // return $res;
    }


    /**
     * 唤起支付操作，
     * JSAPI--JSAPI支付（或小程序支付）、NATIVE--Native支付、APP--app支付，MWEB--H5支付，
     **/
    public function payByWechat($id) {
        try {
            // 校验权限
            $shop = auth('api')->user()->shop()->where('id', $id)->firstOrFail();
            // bcsub — 减法
            if (bcsub(time(), strtotime($shop->created_at)) > 3600) {
                throw new ResourceException('此订单已过期，请删除此订单重新付款！');
            }
            // 校验订单状态
            if ($shop->paid_at || $shop->closed) {
                throw new ResourceException('订单状态不正确');
            }

            $result = $this->app->order->unify([
                'body' => '支付会员版订单：' . $shop->no,
                'out_trade_no' => $shop->no,
                'total_fee' => $shop->platform_licensing * 100,//$wechatPay->total_fee * 100,
                'notify_url' => "https://api.jjz369.com/shop_wechat_notify", // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'openid' => auth('api')->user()->ml_openid,
                'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            ]);
            $jssdk = $this->app->jssdk;
            $json = $jssdk->bridgeConfig($result['prepay_id'], false);
            return response()->json([
                'code' => 200,
                'data' => $json,
                'order' => $shop,
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
            $order = Shop::where('no',$message['out_trade_no'])->first();
            if (!$order) {
                Log::error('订单不存在则告知微信支付');
                return 'fail';
            }
//                // 订单已支付
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
                    Log::info($order);
                    Log::info($order->amount == Setting::where('key','shop_fee')->value('value'));
                    Log::info($order->amount == Setting::where('key','shop_fee_two')->value('value'));

                    if ($order->amount == Setting::where('key','shop_fee')->value('value')) {
                        Log::info(111111111);
                        if ($order->due_date) {
                            $order->due_date = date('Y-m-d H:i:s', strtotime("+1year", strtotime($order->due_date)));
                        }else {
                            $order->due_date = date('Y-m-d H:i:s',strtotime('+1year'));
                        }
                    }else if ($order->amount == Setting::where('key','shop_fee_two')->value('value')){
                        Log::info(2222222222);
                        if ($order->due_date) {
                            $order->due_date = date('Y-m-d H:i:s',strtotime("+2 year",strtotime($order->due_date)));
                        }else {
                            $order->due_date = date('Y-m-d H:i:s',strtotime('+2 year'));
                        }
                        // 编辑
                    }

                    // 生成一条 邀请人获取佣金的记录
                    // todo 如果 已经生成了订单那么这里支付成功了，就给推广人员到账
                    if ($order->updated_at == $order->created_at) {
                        if ($record = TransactionRecord::where('model_id',$order->id)->where('model_type',Shop::class)->first()) {
                            User::where('id',$record->parent_id)->increment('balance',Setting::where('key','award')->value('value'));
                            TransactionRecord::where('model_id',$order->id)->where('model_type',Shop::class)->update([
                                'is_pay'=>1
                            ]);
                        }
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
//            order_wePay_success_notification($user->ml_openid,$order->payment_no,$order->paid_at,$order->amount,$order->name,'');
            return true; // 返回处理完成
        });
        return $response;

    }
}
