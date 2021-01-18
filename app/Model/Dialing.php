<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dialing extends Model
{
    protected $fillable = ['phone','model_type','model_id','user_id'];

    public function shops()
    {
        return $this->morphTo(Shop::class);
    }

}
