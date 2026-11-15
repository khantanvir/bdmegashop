<?php

namespace App\Http\Controllers\bdmegashop\home;

use App\Http\Controllers\Controller;
use App\Models\BdMegaShop\Category\Brand;
use App\Models\BdMegaShop\Category\Category;
use App\Models\BdMegaShop\Category\Color;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\ProductDetail;
use App\Models\BdMegaShop\Category\ProductStatus;
use App\Models\BdMegaShop\Category\SizeM;
use App\Models\BdMegaShop\Category\SizeXL;
use App\Models\BdMegaShop\Category\SubCategory;
use App\Models\BdMegaShop\Product\ProductDetailImage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $data['page_title'] = 'BD Mega Shop | Home';
        $data['categories'] = Category::withCount(['products' => function ($q) {
            $q->where('status', 0);
        }])->where('status', 0)->orderBy('id', 'desc')->get();
        $data['sub_categories'] = SubCategory::where('status', 0)->orderBy('id', 'desc')->get();
        $data['brands'] = Brand::where('status', 0)->orderBy('id', 'desc')->get();
        $data['products'] = Product::with(['category', 'subCategory', 'brand', 'images'])
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->get();

        $featured_products = Product::with(['category', 'subCategory', 'brand', 'images'])
            ->where('status', 0)
            ->where('is_featured', 1)
            ->orderBy('id', 'desc')
            ->get();

        if ($featured_products->isEmpty()) {
            $featured_products = $data['products']->take(8);
        }
        $data['featured_products'] = $featured_products;

        $popular_products = Product::with(['category', 'subCategory', 'brand', 'images'])
            ->where('status', 0)
            ->where('is_popular', 1)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        if ($popular_products->isEmpty()) {
            $popular_products = $data['products']->take(8);
        }
        $data['popular_products'] = $popular_products;

        return view('bdmegashop.home.index', $data);
    }
    public function about(){
        $data['page_title'] = 'BD Mega Shop | About';
        return view('bdmegashop.home.about', $data);
    }
    public function help_and_support(){
        $data['page_title'] = 'BD Mega Shop | Help and Support';
        return view('bdmegashop.home.help_and_support', $data);
    }
    public function privacy_policy(){
        $data['page_title'] = 'BD Mega Shop | Privacy Policy';
        return view('bdmegashop.home.privacy_policy', $data);
    }
    public function terms_of_service(){
        $data['page_title'] = 'BD Mega Shop | Terms of Service';
        return view('bdmegashop.home.terms_of_service', $data);
    }
    public function sales_and_discount(){
        $data['page_title'] = 'BD Mega Shop | Sale and Discount';
        return view('bdmegashop.home.sales_and_discount', $data);
    }
}
