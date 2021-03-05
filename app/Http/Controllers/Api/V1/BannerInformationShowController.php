<?php

namespace App\Http\Controllers\Api\V1;


use App\Model\BannerInformation;
use App\Model\BannerInformationShow;
use App\Model\ConvenientInformation;
use Illuminate\Support\Facades\Log;

class BannerInformationShowController extends Controller
{
    public function index()
    {
        Log::info(request('card_id'));
        // 同类推荐
        $con = ConvenientInformation::where('id',request('card_id'))->first();
        Log::info($con);
        Log::info($con->card_id);
        Log::info($con->card_id->id);
        // 这里的逻辑不能随意改
        $area = \request()->area;

        if ($con) {
             $query = ConvenientInformation::where('is_display',1)->where('card_id',$con->card_id->id)->orderBy('sort','desc')->whereNotNull('paid_at')->take(5);//->where('title','like','%'.request('title').'%')

            if (config('app.city') == 1) {
                if ($area != '') {
                    $query = $query->where('location', 'like', '%' . $area . '%');
                }
            }
            $bannerInformationShowOne = $query->get();
        }else {
            $bannerInformationShowOne = ConvenientInformation::where('is_display',2)->get();
        }
        $bannerInformationShowTwo = BannerInformationShow::where('is_display',1)->where('type','two')->orderBy('sort','desc')->get();

        return $this->responseStyle('ok',200,[
            'one'=>$bannerInformationShowOne,
            'two'=>$bannerInformationShowTwo,
        ]);
    }
}
