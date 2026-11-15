<?php

namespace App\Models\BdMegaShop\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\BdMegaShop\Category\Product;

class OrderProduct extends Model
{
    protected $table = 'order_products';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
