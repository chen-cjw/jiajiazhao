<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;
use App\Model\History;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Exceptions\HttpResponseException;


class Controller extends BaseController
{
    use Helpers;

    public function history($model,$id,$user)
    {
        // 浏览记录
        $historyQuery = History::where('model_type',$model)->where('model_id',$id)->where('user_id',$user->id);
        if ($historyQuery->first()) {
            $historyQuery->update([
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
        }else {
            // 浏览记录贴
            History::create([
                'model_type'=>$model,
                'model_id'=>$id,
                'user_id'=>$user->id
            ]);
        }
    }


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

    /**
     * @desc 根据两点间的经纬度计算距离
     * @param float $lat 纬度值
     * @param float $lng 经度值
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2) {
        // 赤道半径(单位m)
        $earthRadius = 6378137;

        $lat1 = ($lat1 * pi() ) / 180;

        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;

        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;

        $calcLatitude = $lat2 - $lat1;

        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);

        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));

        $calculatedDistance = $earthRadius * $stepTwo;

        $res = abs(round($calculatedDistance/1000, 2));
        if($res>1000) {
            $res = floor($res/1000).'公里';
            return $res;
        }
        return $res;
    }



}
