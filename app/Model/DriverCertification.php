<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DriverCertification extends Model
{
    // 司机身份认证
    protected $fillable = ['id_card','driver','action','car','user_id','is_display'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIdCardAttribute()
    {
        return $this->imageAttribute($this->attributes['id_card']);
    }
    public function getDriverAttribute()
    {
        return $this->imageAttribute($this->attributes['driver']);
    }
    public function getActionAttribute()
    {
        return $this->imageAttribute($this->attributes['action']);
    }
    public function getCarAttribute()
    {
        return $this->imageAttribute($this->attributes['car']);
    }

    public function imageAttribute($image)
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        return \Storage::disk('public')->url($image);
    }

}
