<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Exceptions\HttpResponseException;


class Controller extends BaseController
{
    use Helpers;

    // 统一返回的报错样式
    public function responseStyle($message,$code,$data)
    {
        throw new HttpResponseException(response()->json(['code'=>$code,'msg'=>$message,'data'=>$data]));
    }
    public function upload_img($file)
    {
        if (!empty($file)) {
            //获取扩展名
            $exename = $this->getExeName($file['name']);
            if ($exename == 'gif') {
                exit('不允许的扩展名');
            }
            $upload_name = '/img_' . date("YmdHis") . rand(0, 100) . '.' . $exename;//文件名加后缀
            $imageSavePath = storage_path() .'/app/public'. $upload_name;
            if (move_uploaded_file($file['tmp_name'], $imageSavePath)) {
                return  $upload_name;
            }
        }
    }

    public function getExeName($fileName) {
        $pathinfo = pathinfo($fileName);
        return strtolower($pathinfo['extension']);
    }


}
