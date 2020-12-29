<?php
namespace App\Transformers;
use App\Model\DriverCertification;
use League\Fractal\TransformerAbstract;

class DriverCertificationTransformer extends TransformerAbstract
{

    public function transform(DriverCertification $driverCertification)
    {
        return [
            'id' => $driverCertification->id,
            'id_card' => $driverCertification->id_card,
            'driver' => $driverCertification->driver,
            'action' => $driverCertification->action,
            'car' => $driverCertification->car,
            'is_display' => $driverCertification->is_display,
            'created_at' => $driverCertification->created_at->toDateTimeString(),
            'updated_at' => $driverCertification->updated_at->toDateTimeString(),
        ];
    }
}