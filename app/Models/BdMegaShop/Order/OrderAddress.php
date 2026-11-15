<?php

namespace App\Models\BdMegaShop\Order;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $table = 'order_addresses';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
