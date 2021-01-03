<?php

namespace App;

use App\Model\DriverCertification;
use App\Model\LocalCarpooling;
use App\Model\Shop;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    //这里省略n多代码...

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ml_openid','phone','avatar','nickname','sex','parent_id','is_member','is_certification'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 本地拼车
    public function local()
    {
        return $this->hasMany(LocalCarpooling::class);
    }
    //
    public function certification()
    {
        return $this->hasOne(DriverCertification::class);
    }
    //
    public function localCarpool()
    {
        return $this->hasMany(LocalCarpooling::class);
    }

    // 收藏商铺
    public function favoriteShops()
    {
        return $this->belongsToMany(Shop::class, 'user_favorite_shops')
            ->withTimestamps()
            ->orderBy('user_favorite_shops.created_at', 'desc');
    }

}
