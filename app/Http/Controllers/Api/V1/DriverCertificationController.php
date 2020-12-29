<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\DriverCertificationRequest;
use App\Model\DriverCertification;
use App\Transformers\DriverCertificationTransformer;
use Illuminate\Support\Facades\DB;

class DriverCertificationController extends Controller
{
    public function index()
    {
        return $this->response->item(auth('api')->user()->certification,new DriverCertificationTransformer());
    }
    //
    public function store(DriverCertificationRequest $certification)
    {

        DB::beginTransaction();

        try {
            $idCardFile = $this->upload_img($_FILES['id_card']);
            $driverFile = $this->upload_img($_FILES['driver']);
            $actionFile = $this->upload_img($_FILES['action']);
            $carFile = $this->upload_img($_FILES['car']);
            DriverCertification::create([
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
            return $this->response->created();

        } catch (\Exception $ex) {
            throw new \Exception('DriverCertificationController'.$ex); //
            DB::rollback();
        }
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
