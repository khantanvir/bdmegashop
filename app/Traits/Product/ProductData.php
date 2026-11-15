<?php 

namespace App\Traits\Product;

use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\ProductDetail;
use App\Models\BdMegaShop\Product\ProductDetailImage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Redirect;
use App\Models\GoverningBody\GoverningBody;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait ProductData{
    use Utility, Service;
    public static function add_product_data(Request $request){
        $product_id = $request->product_id;
        if(!empty($product_id)){
            $product = Product::where('id',$product_id)->first();
        }else{
            $product = new Product();
            $product->product_code = 'BMS-'.rand(10000,99999);
            $product->create_by = Auth::user()->id;
        }
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->brand_id = $request->brand_id;
        $product->product_owner = $request->product_owner;
        $product->update_by = Auth::user()->id;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->long_description = $request->long_description;
        if($request->hasFile('image')) {
            if (File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $image_directory = 'web/images/product/pic/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,340,370);
            $product->image = $image_path;
        }
        $product->purchase_price = $request->purchase_price;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->selling_price = $request->selling_price;
        $product->shipping_charge = $request->shipping_charge;
        $product->product_status = 1; //1 for pending 2 for publish 3 for draft
        $product->slug = Service::slug_create($request->title);
        $product->origin = $request->origin;
        $product->save();
        return $product->id;
    }
    public static function upload_details_page_image(Request $request){
        $image = new ProductDetailImage();
        $image->product_id = $request->product_id;
        if($request->hasFile('image')) {
            if (File::exists(public_path($image->image))) {
                File::delete(public_path($image->image));
            }
            $image_directory = 'web/images/product/pic/details/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,430,500);
            $image->image = $image_path;
        }
        $image->save();
        return $image->id;
    }
    public static function update_product_stock(Request $request){
        $product = Product::where('id', $request->product_id)->first();
        $product_detail = new ProductDetail();
        $product_detail->product_id = $request->product_id;
        $product_detail->size = $request->size;
        $product_detail->color = $request->color;
        $product_detail->purchase_price = $product->purchase_price ?? 0;
        $product_detail->price = $product->price ?? 0;
        $product_detail->discount = $product->discount ?? 0;
        $product_detail->selling_price = $product->selling_price ?? 0;
        $product_detail->shipping_charge = $product->shipping_charge ?? 0;
        if ($request->stock_type == 'remove') {
            $product_detail->total_stock_in = 0;
            $product_detail->total_stock_out = $request->quantity;
        } else {
            $product_detail->total_stock_in = $request->quantity;
            $product_detail->total_stock_out = 0;
        }
        $product_detail->stock_note = $request->stock_note ?? $request->note;
        $product_detail->create_by = Auth::user()->id;
        $product_detail->update_by = Auth::user()->id;
        $product_detail->status = 0;
        $product_detail->save();
        return $product_detail->id;
    }

}