<?php

namespace App\Models\BdMegaShop\Category;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sizeData()
    {
        return $this->belongsTo(SizeXL::class, 'size');
    }

    public function colorData()
    {
        return $this->belongsTo(Color::class, 'color');
    }
}
