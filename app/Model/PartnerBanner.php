<?php

namespace App\Model;


class PartnerBanner extends Model
{
    protected $fillable = ['area'];

    public function getLinkAttribute()
    {
        return $this->attributes['link_url'];
    }
    protected $appends = ['link'];
}
