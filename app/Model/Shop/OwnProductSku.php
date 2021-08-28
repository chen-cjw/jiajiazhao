<?php

namespace App\Model\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

class OwnProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function OwnProduct()
    {
        return $this->belongsTo(OwnProduct::class);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new \Exception('减库存不可小于0');
        }

        return $this->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('stock', $amount);
    }
}
