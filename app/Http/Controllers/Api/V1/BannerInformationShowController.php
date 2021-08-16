<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerInformation;
use App\Model\BannerInformationShow;
use App\Model\ConvenientInformation;
use App\Model\Setting;
use Illuminate\Support\Facades\Log;

class BannerInformationShowController extends Controller
{
    public function index()
    {
        Log::info(request('card_id'));
        // 同类推荐
        $con = ConvenientInformation::where('id',request('card_id'))->first();
//        Log::info($con);
//        Log::info($con->card_id);
//        Log::info($con->card_id->id);
        // 这里的逻辑不能随意改
        $area = \request()->area;
        $day = strtotime("-".Setting::where('key','timeSearch')->value('value')." day");
//        $query = ConvenientInformationHtml::query()->whereNotNull('paid_at')->where('is_display',1);
//        $query = $query->whereBetween('created_at',[ date("Y-m-d H:i:s",$day),date('Y-m-d H:i:s')]);

        if ($con) {
             $query = ConvenientInformation::where('is_display',1)->where('card_id',$con->card_id->id)->orderBy('sort','desc')->whereNotNull('paid_at')->take(5);//->where('title','like','%'.request('title').'%')

            if (config('app.city') == 1) {
                if ($area != '') {
                    $query = $query->where('location', 'like', '%' . $area . '%');
                }
            }
//            if ($area) {
//                $query = $query->where(function ($query) {
//                    $query->where('area',\request('area'))->orWhere('area',null);
//                });
//            }
            $bannerInformationShowOne = $query->whereBetween('created_at',[ date("Y-m-d H:i:s",$day),date('Y-m-d H:i:s')])->get();
        }else {
            $bannerInformationShowOneQuery = ConvenientInformation::where('is_display',2)->whereBetween('created_at',[ date("Y-m-d H:i:s",$day),date('Y-m-d H:i:s')]);
//            if ($area) {
//                $bannerInformationShowOneQuery = $bannerInformationShowOneQuery->where(function ($query) {
//                    $query->where('area',\request('area'))->orWhere('area',null);
//                });
//            }
            $bannerInformationShowOne = $bannerInformationShowOneQuery->get();
        }



        $bannerInformationShowTwoQuery = BannerInformationShow::where('is_display',1)->where('type','two');
        Log::info('$bannerInformationShowTwoQuery');
        Log::info(request()->all());
        Log::info(request('area'));
        Log::info('$bannerInformationShowTwoQuery');
        Log::info('$bannerInformationShowTwoQuery');
        if ($area) {
            $bannerInformationShowTwoQuery = $bannerInformationShowTwoQuery->where(function ($query) {
                $query->where('area','like',\request('area').'%')->orWhere('area',null);
            });
        }
        $bannerInformationShowTwo = $bannerInformationShowTwoQuery->orderBy('sort','desc')->get();
        return $this->responseStyle('ok',200,[
            'one'=>$bannerInformationShowOne,
            'two'=>$bannerInformationShowTwo,
        ]);
    }
}
