<?php

namespace App\Model\Shop;

use Illuminate\Database\Eloquent\Model;

class OwnOrderItem extends Model
{
    protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
    protected $dates = ['reviewed_at'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(OwnProduct::class);
    }

    public function productSku()
    {
        return $this->belongsTo(OwnProductSku::class);
    }

    public function order()
    {
        return $this->belongsTo(OwnOrder::class);
    }
}
