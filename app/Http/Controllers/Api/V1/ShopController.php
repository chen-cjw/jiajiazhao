<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ShopRequest;
use App\Model\Shop;
use App\Transformers\ShopTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    // 商户列表
    public function index()
    {
        $shopQuery = Shop::query();

        $shopQuery->where('one_abbr', \request()->one_abbr)
            ->where(function ($query) {
                $query->orWhere('two_abbr0',\request()->two_abbr)
                    ->orWhere('two_abbr1',\request()->two_abbr)
                    ->orWhere('two_abbr2',\request()->two_abbr);
            });
        // 人气 == 浏览量
        $shopQuery->orderBy('view','desc');

        $shop = $shopQuery->get();
        return $this->responseStyle('ok',200,$shop);

        return $this->response->collection($shop,new ShopTransformer());
    }
    
    // 入住
    public function store(ShopRequest $request)
    {
        $data = $request->only([
            'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
            'logo','service_price','merchant_introduction','platform_licensing','is_top','lng','lat'
        ]);
        for ($i=0;$i<count($request->two_abbr);$i++) {
            $data['two_abbr'.$i] = $request->two_abbr[$i];
        }
        $data['logo'] = json_encode($request->logo);
        $data['user_id'] = auth('api')->id();
        $res = Shop::create($data);
        return $this->responseStyle('ok',200,$res);

        return $this->response->created();
    }

    public function show($id)
    {
        Shop::where('id',$id)->increment('view');
        $shop = Shop::findOrFail($id);
        return $this->responseStyle('ok',200,$shop);
    }

    public function uploadImg(Request $request)
    {
        return $this->uploadImages($request);
    }
    // 单图片上传
    public function uploadImages($request)
    {
        if ($request->isMethod('post')) {
            $file = $request->file('logo')['store_logo'];
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
        return $res;
    }
}
