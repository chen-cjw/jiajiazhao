<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    public function adminShops()
    {
        return $this->hasMany(AdminShop::class,'admin_id','id');
    }

}
