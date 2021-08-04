<?php

namespace App\Model;

class BannerShopCategory extends Model
{
    public function abbCategory()
    {
        return $this->belongsTo(AbbrCategory::class,'abbr_category_id','id');
    }
}
