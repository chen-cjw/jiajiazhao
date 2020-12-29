<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DriverCertification extends Model
{
    // 司机身份认证
    protected $fillable = ['id_card','driver','action','car'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
