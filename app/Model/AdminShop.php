<?php

namespace App\Model;

use Encore\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class AdminShop extends Model
{
    protected $fillable = ['admin_id','shop_id'];

    public function AdminUser()
    {
        return $this->hasMany(AdminUser::class,'id','admin_id');
    }
}
