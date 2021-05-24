<?php

namespace App\Model\DS;

use Illuminate\Database\Eloquent\Model;

class ZBanner extends Model
{
    protected $fillable = [
        'image_url', 'href_url', 'sort_num', 'on_sale'
    ];

}
