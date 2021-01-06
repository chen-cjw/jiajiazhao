<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];//->toDateTimeString();
    }
    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'];//->toDateTimeString();
    }
}
