<?php

namespace App\Models\BdMegaShop\Category;

use App\Models\BdMegaShop\Product\ProductDetailImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function owner(){
        return $this->belongsTo(User::class,'product_owner');
    }
    public function images(){
        return $this->hasMany(ProductDetailImage::class,'product_id');
    }
    public function productDetails(){
        return $this->hasMany(ProductDetail::class,'product_id');
    }
    public function stockHistories(){
        return $this->hasMany(ProductDetail::class,'product_id')->orderBy('id','desc');
    }
}
