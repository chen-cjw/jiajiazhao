<?php

namespace App\Model\Shop;

use Illuminate\Database\Eloquent\Model;

class OwnOrderItem extends Model
{
    protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
    protected $dates = ['reviewed_at'];
    public $timestamps = false;

    public function ownProduct()
    {
        return $this->belongsTo(OwnProduct::class);
    }

    public function ownProductSku()
    {
        return $this->belongsTo(OwnProductSku::class);
    }

    public function ownOrder()
    {
        return $this->belongsTo(OwnOrder::class);
    }
}
