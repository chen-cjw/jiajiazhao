<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ShopRequest;
use App\Model\Shop;
use App\Transformers\ShopTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    // 商户列表
    public function index()
    {
        $shop = Shop::orderBy('is_top','desc')->where('one_abbr', \request()->one_abbr)
            ->where(function ($query) {
                $query->orWhere('two_abbr0',\request()->two_abbr)
                    ->orWhere('two_abbr1',\request()->two_abbr)
                    ->orWhere('two_abbr2',\request()->two_abbr);
            })->get();
        return $this->response->collection($shop,new ShopTransformer());
    }
    
    // 入住
    public function store(ShopRequest $request)
    {
        $data = $request->only([
            'one_abbr' ,'two_abbr0','two_abbr1','two_abbr2','name','area','detailed_address','contact_phone','wechat',
            'logo','service_price','merchant_introduction','platform_licensing','is_top',
        ]);
        for ($i=0;$i<count($request->two_abbr);$i++) {
            $data['two_abbr'.$i] = $request->two_abbr[$i];
        }
        $data['logo'] = json_encode($request->logo);
        Shop::create($data);
        return $this->response->created();
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

}
