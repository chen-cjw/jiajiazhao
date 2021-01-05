<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\DriverCertificationRequest;
use App\Model\DriverCertification;
use App\Transformers\DriverCertificationTransformer;
use Illuminate\Support\Facades\DB;

class DriverCertificationController extends Controller
{
    // 司机认证
    public function index()
    {
        return $this->response->item(auth('api')->user()->certification,new DriverCertificationTransformer());
    }
    //
    public function store(DriverCertificationRequest $certification)
    {

        DB::beginTransaction();

        try {
            $idCardFile = $this->upload_img($_FILES['id_card']); // 图片上传
            $driverFile = $this->upload_img($_FILES['driver']);
            $actionFile = $this->upload_img($_FILES['action']);
            $carFile = $this->upload_img($_FILES['car']);
            $res = DriverCertification::create([
                'id_card' => $idCardFile,
                'driver' => $driverFile,
                'action' => $actionFile,
                'car' => $carFile,
                'user_id' => auth('api')->id()
            ]);
            //
            auth('api')->user()->update([
                'is_certification' => true
            ]);
            DB::commit();
            return $this->responseStyle('ok',200,$res);

            return $this->response->created();

        } catch (\Exception $ex) {
            throw new \Exception('DriverCertificationController'.$ex); //
            DB::rollback();
        }
    }



}
