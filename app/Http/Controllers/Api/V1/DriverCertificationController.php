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
        $userCertification = auth('api')->user()->certification;
        return $this->responseStyle('ok',200,$userCertification);
        return $this->response->item(auth('api')->user()->certification,new DriverCertificationTransformer());
    }
    //
    public function store(DriverCertificationRequest $certification)
    {

        DB::beginTransaction();

        try {
//            $idCardFile = $this->upload_img($_FILES['id_card']); // 图片上传
//            $driverFile = $this->upload_img($_FILES['driver']);
//            $actionFile = $this->upload_img($_FILES['action']);
//            $carFile = $this->upload_img($_FILES['car']);
            $data = $certification->only(['id_card','driver','action','car']);
            $data['user_id'] = auth('api')->id();
            $res = DriverCertification::create($data);
            //
            auth('api')->user()->update([
                'is_certification' => true
            ]);
//            $res = $this->responseStyle('ok',200,'');
            DB::commit();
            return [
                'msg'=>'ok',
                'code' => 200,
                'data'=>$res
            ];
            return $this->response->created();

        } catch (\Exception $ex) {
            throw new \Exception('DriverCertificationController'.$ex); //
            DB::rollback();
        }
    }
    // 认证修改
    public function update($id)
    {
        $idCardFile = $this->upload_img($_FILES['id_card']); // 图片上传
        $driverFile = $this->upload_img($_FILES['driver']);
        $actionFile = $this->upload_img($_FILES['action']);
        $carFile = $this->upload_img($_FILES['car']);
        $res = DriverCertification::where('id',$id)->where('user_id',auth('api')->id())->update([
            'id_card' => $idCardFile,
            'driver' => $driverFile,
            'action' => $actionFile,
            'car' => $carFile,
        ]);
        return $this->responseStyle('ok',200,$res);
    }


}
