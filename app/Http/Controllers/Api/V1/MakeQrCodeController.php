<?php

namespace App\Http\Controllers\Api\V1;


use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MakeQrCodeController extends Controller
{

    public function makeShare(Request $request)
    {

        return $this->qrCode($request,$request->width);
    }

    public function qrCode($request,$width)
    {
        $config = [
            'app_id' => 'wx693aa465df66510b',
            'secret' => config('wechat.mini_program.default.secret'),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];
        $app = Factory::miniProgram($config);
        try {
            $response = $app->app_code->get($request->path, [
                'width' => $width,
                'line_color' => $request->line_color
            ]);
            // $response 成功时为 EasyWeChat\Kernel\Http\StreamResponse 实例，失败为数组或你指定的 API 返回类型
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {

                $img =  date("YmdHis", time()) . '-' . uniqid() . ".png";
                $filename = $response->saveAs(storage_path('app/public'),  $img);
                // return $filename;
                $data['code'] = 200;
                $data['url'] =   $filename;

                // 如果 image 字段本身就已经是完整的 url 就直接返回
                if (Str::startsWith($filename, ['http://', 'https://'])) {
                    Log::info(5);
                    return [
                        'code'=>200,
                        'msg'=>'ok',
                        'date'=>$filename
                    ];
//                    return $data['url'];
                }
                Log::info(6);
                return [
                    'code'=>200,
                    'msg'=>'ok',
                    'date'=>\Storage::disk('public')->url($filename)
                ];

            } else {
                Log::info('生成失败');
                return [
                    'code'=>422,
                    'msg'=>'生成失败',
                    'date'=>[]
                ];
            }
        } catch (\Exception $e) {
            return [
                'code'=>422,
                'msg'=>$e->getMessage(),
                'date'=>[]
            ];
        }
    }

    public function makeHaiBao(Request $request)
    {
//        return 123;
//        return config('app.url').'/WechatIMG78.jpeg';
        $fontPath = config('app.fontPath');//'/System/Library/Fonts/Hiragino Sans GB.ttc';
//        return $fontPath;
        $qrCodeImage = $this->qrCode($request,350)['date'];
        // 联系电话
        $phone = array(
            'text'=>'18361771533',
            'left'=>346,
            'top'=>-225,
            'fontPath'=>$fontPath,//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
            'fontSize'=>52,             //字号
            'fontColor'=>'0,0,0',       //字体颜色
            'angle'=>0,
        );
        // 店铺地址
        $shopArea = array(
            'text'=>'江苏省徐州市XXXXXXXXXXXX',
            'left'=>323,
            'top'=>-140,
            'fontPath'=>$fontPath,//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
            'fontSize'=>30,             //字号
            'fontColor'=>'0,0,0',       //字体颜色
            'angle'=>0,
        );

        $textStr = "据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于echo可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于echo可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于echo可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串据说这样输出速度要快一些，原因在于echo可以接受多个参数，并直接按顺序输出，实际上逗号不是拼接字符串";
        $aaaaa = "https://xinyidouyin.oss-cn-beijing.aliyuncs.com/WechatIMG10.jpeg?Expires=1612596622&OSSAccessKeyId=TMP.3KfedtDL8agAXKVkhsHzpWGSPFtgFwb56icMa9EPsU6n3wthPxeb8c2H5wXaEZPi2GiG2nydiNwJHKMRxkUcwHoEe1s7Cd&Signature=TqaW%2BFn4E39p%2BbUpyuELTz0XEf8%3D";
        $textLength = mb_strlen($textStr);// 字符串长度
        $ccvv  = 19;
//        return $textLength;
        $iFor =  ceil($textLength/$ccvv);
        $text1[] = $phone;
        $text1[] = $shopArea;
        $text1[] = array(
            'text'=>'原因在于可以接受',
            'left'=>377,
            'top'=>380,
            'fontPath'=>$fontPath,//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
            'fontSize'=>33,             //字号
            'fontColor'=>'0,0,0',       //字体颜色
            'angle'=>0,
        );
        $iFor = $iFor>4?4:$iFor;
        for ($i=0;$i < $iFor;$i++) {
            $text1[] = $this->content(mb_substr($textStr, $i*$ccvv,$ccvv),$i*70,$fontPath);
        }

        $config = array(
//            'text'=>array(
//                $phone,
//                $shopArea,
//            ),
            'text'=>$text1,
            'image'=>array(
                array(
                    //         return \Storage::disk('public')->url($image);
                    'url'=>$qrCodeImage,       //图片资源路径
                    'left'=>370,
                    'top'=>-370,
                    'stream'=>0,             //图片资源是否是字符串图像流
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>330,
                    'height'=>330,
                    'opacity'=>100
                ),
                array(
                    'url'=>$aaaaa,//\Storage::disk('public')->url("3uGY8r8y0v12gpgAqjUP0DzMnAYp3j5GSq12HKL5.jpg"),
                    'left'=>60,
                    'top'=>500,
                    'right'=>0,
                    'stream'=>0,
                    'bottom'=>0,
                    'width'=>970,
                    'height'=>550,
                    'opacity'=>100
                ),
                array(
                    'url'=>$aaaaa,//\Storage::disk('public')->url("3uGY8r8y0v12gpgAqjUP0DzMnAYp3j5GSq12HKL5.jpg"),
                    'left'=>60,
                    'top'=>1530,
                    'right'=>0,
                    'stream'=>0,
                    'bottom'=>0,
                    'width'=>970,
                    'height'=>550,
                    'opacity'=>100
                ),
            ),
            'background'=>config('app.url').'/WechatIMG78.jpeg',
        );
//        return $config;
        $filename = time().'.jpg';
//echo createPoster($config,$filename);
//        return config('app.url').'/'.$this->createPoster($config);
        return [
            'msg'=>'ok',
            'code'=>200,
            'date'=>config('app.url').'/'.$this->createPoster($config,$filename)
        ];

    }

    public function content($text,$top,$fontPath)
    {
        return array(
            'text'=>$text,
            'left'=>122,
            'top'=>1200+$top,
            'fontPath'=>$fontPath,//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
            'fontSize'=>33,             //字号
            'fontColor'=>'0,0,0',       //字体颜色
            'angle'=>0,
        );
    }
    /**
     * 生成宣传海报
     * @param array  参数,包括图片和文字
     * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
     * @return [type] [description]
     */
    public function createPoster($config=array(),$filename=""){
        //如果要看报什么错，可以先注释调这个header
        if(empty($filename)) header("content-type: image/png");
        $imageDefault = array(
            'left'=>0,
            'top'=>0,
            'right'=>0,
            'bottom'=>0,
            'width'=>100,
            'height'=>100,
            'opacity'=>100
        );
        $textDefault = array(
            'text'=>'123',
            'left'=>0,
            'top'=>0,
            'fontSize'=>32,       //字号
            'fontColor'=>'255,255,255', //字体颜色
            'angle'=>0,
        );
        $background = $config['background'];//海报最底层得背景
        //背景方法
        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);
        $backgroundWidth = imagesx($background);  //背景宽度
        $backgroundHeight = imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        // imageColorTransparent($imageRes, $color);  //颜色透明
        imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
        //处理了图片
        if(!empty($config['image'])){
            foreach ($config['image'] as $key => $val) {
                $val = array_merge($imageDefault,$val);
                $info = getimagesize($val['url']);
                $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
                if($val['stream']){   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
                //建立画板 ，缩放图片至指定尺寸
                $canvas=imagecreatetruecolor($val['width'], $val['height']);
                imagefill($canvas, 0, 0, $color);
                //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
                //放置图像
                imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
        }
        //处理文字
        if(!empty($config['text'])){
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault,$val);
                list($R,$G,$B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
                $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
                imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
            }
        }

        //生成图片
        if(!empty($filename)){
            $res = imagejpeg ($imageRes,$filename,90); //保存到本地
            imagedestroy($imageRes);
            if(!$res) return false;
            return $filename;
        }else{
            imagejpeg ($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
        }
    }


}
