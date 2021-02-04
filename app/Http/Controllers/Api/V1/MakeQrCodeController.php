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
        $config = [
            'app_id' => 'wx693aa465df66510b',
            'secret' => config('wechat.mini_program.default.secret'),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
        ];
        $app = Factory::miniProgram($config);
        Log::info(44444444444444444);
        Log::info($request->line_color);
        Log::info(44444444444444444);
        try {
            $response = $app->app_code->get($request->path, [
                'width' => $request->width,
                'line_color' => $request->line_color
            ]);
            Log::info(111111);
            Log::info($response);
            Log::info(111111);

            // $response 成功时为 EasyWeChat\Kernel\Http\StreamResponse 实例，失败为数组或你指定的 API 返回类型
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                Log::info(1);

                $img =  date("YmdHis", time()) . '-' . uniqid() . ".png";
//                $filename = $response->saveAs('uploads/images',  $img);
                Log::info(2);
                Log::info(storage_path('app/public'));
                $filename = $response->saveAs(storage_path('app/public'),  $img);
                Log::info(3);

                // return $filename;
                $data['code'] = 200;
                $data['url'] =   $filename;

                // 如果 image 字段本身就已经是完整的 url 就直接返回
                if (Str::startsWith($filename, ['http://', 'https://'])) {
                    Log::info(5);

                    return $data['url'];
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

    public function makeHaiBao()
    {
//        return \Storage::disk('public')->url("Avenir.ttc");
        $config = array(
            'text'=>array(
                array(
                    'text'=>'123',
                    'left'=>282,
                    'top'=>205,
                    'fontPath'=>'/System/Library/Fonts/Hiragino Sans GB.ttc',//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
                    'fontSize'=>18,             //字号
                    'fontColor'=>'255,0,0',       //字体颜色
                    'angle'=>0,
                ),
                array(
                    'text'=>'字体颜色',
                    'left'=>82,
                    'top'=>25,
                    'fontPath'=>'/System/Library/Fonts/Hiragino Sans GB.ttc',//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
                    'fontSize'=>18,             //字号
                    'fontColor'=>'255,0,0',       //字体颜色
                    'angle'=>0,
                ),
                array(
                    'text'=>'345',
                    'left'=>182,
                    'top'=>105,
                    'fontPath'=>'/System/Library/Fonts/Avenir.ttc',//\Storage::disk('public')->url("Avenir.ttc"),//'qrcode/simhei.ttf',     //字体文件
                    'fontSize'=>18,             //字号
                    'fontColor'=>'255,0,0',       //字体颜色
                    'angle'=>0,
                )

            ),
            'image'=>array(
                array(
                    //         return \Storage::disk('public')->url($image);
                    'url'=>\Storage::disk('public')->url('20210202171459-601918132a8f4.png'),       //图片资源路径
                    'left'=>370,
                    'top'=>-370,
                    'stream'=>0,             //图片资源是否是字符串图像流
                    'right'=>0,
                    'bottom'=>0,
                    'width'=>350,
                    'height'=>350,
                    'opacity'=>100
                ),
//                array(
//                    'url'=>'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eofD96opK97RXwM179G9IJytIgqXod8jH9icFf6Cia6sJ0fxeILLMLf0dVviaF3SnibxtrFaVO3c8Ria2w/0',
//                    'left'=>120,
//                    'top'=>70,
//                    'right'=>0,
//                    'stream'=>0,
//                    'bottom'=>0,
//                    'width'=>55,
//                    'height'=>55,
//                    'opacity'=>100
//                ),
            ),
            'background'=>\Storage::disk('public')->url('WechatIMG76.jpeg'),
        );
        $filename = 'qrcode/'.time().'.jpg';
//echo createPoster($config,$filename);
        return $this->createPoster($config,'admin.png');

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
