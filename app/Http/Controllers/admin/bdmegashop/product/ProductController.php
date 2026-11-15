<?php

namespace App\Http\Controllers\admin\bdmegashop\product;

use App\Http\Controllers\Controller;
use App\Models\BdMegaShop\Category\Brand;
use Illuminate\Http\Request;
use App\Models\BdMegaShop\Category\Category;
use App\Models\BdMegaShop\Category\Color;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\ProductDetail;
use App\Models\BdMegaShop\Category\SizeXL;
use App\Models\BdMegaShop\Category\SubCategory;
use App\Models\BdMegaShop\Product\ProductNotification;
use App\Models\User;
use App\Traits\Product\ProductData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller{
    use ProductData;
    public function create_product($id=NULL){
        $data['page_title'] = 'Create Product';
        $data['categories'] = Category::where('status', 0)->get();
        $data['brands'] = Brand::where('status', 0)->get();
        $data['owner_list'] = User::where('role','admin')->get();
        $get_product_data = Product::where('id', $id)->first();
        $data['product_menu'] = true;
        $data['create_product_menu'] = true;
        if(!empty($get_product_data)){
            $data['product_data'] = $get_product_data;
            $data['sub_categories'] = SubCategory::where('category_id', $get_product_data->category_id)->where('status', 0)->get();
        }else{
            $data['sub_categories'] = [];
        }
        return view('admin.bdmegashop.product.create_product', $data);
    }
    public function create_product_data_post(Request $request){
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'product_owner' => 'required',
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'purchase_price' => 'required',
            'price' => 'required',
            'discount' => 'required|numeric|min:0|max:100',
            'selling_price' => 'required',
        ]);
        $isNew = empty($request->product_id);
        $product_id = ProductData::add_product_data($request);

        $userName = Auth::user()->name ?? 'Admin';
        if ($isNew) {
            ProductNotification::addNotification(
                'Product Created',
                'Product "' . $request->title . '" was created by ' . $userName . '.',
                $product_id,
                0,
                $request->product_owner ?? Auth::id(),
                url('create-product-details/' . $product_id)
            );
        } else {
            ProductNotification::addNotification(
                'Product Updated',
                'Product "' . $request->title . '" details were updated by ' . $userName . '.',
                $product_id,
                0,
                $request->product_owner ?? Auth::id(),
                url('create-product-details/' . $product_id)
            );
        }

        Session::flash('success','Product Data Saved');
        return redirect('create-product/'.$product_id);
    }
    public function get_sub_categories_by_category($category_id=NULL){
        $sub_categories = SubCategory::where('category_id', $category_id)->where('status', 0)->get();
        $select = '';
        if(!empty($sub_categories) && count($sub_categories) > 0){
            $select .= '<option value="">Select Sub Category</option>';
            foreach($sub_categories as $sub_category){
                $select .= '<option value="'.$sub_category?->id.'">'.$sub_category?->title.'</option>';
            }
        }
        $data['result'] = array(
            'key' => 200,
            'val' => $select
        );
        return response()->json($data);
    }
    public function all_products(){
        $data['page_title'] = 'All Products';
        $data['product_menu'] = true;
        $data['all_product_menu'] = true;
        $data['products'] = Product::orderBy('id','desc')->paginate(10);
        return view('admin.bdmegashop.product.all_products', $data);
    }
    public function product_status_change(Request $request){
        $product_id = $request->product_id;
        $product_data = Product::where('id', $product_id)->first();
        if(!$product_data){
            $data['result'] = array(
                'key' => 101,
                'val' => 'Product Not Found'
            );
            return response()->json($data);
        }
        if($product_data->status == 0){
            $product_data->status = 1;
            $newStatus = 'Inactive';
        }else{
            $product_data->status = 0;
            $newStatus = 'Active';
        }
        $product_data->save();

        ProductNotification::addNotification(
            'Product Status Changed',
            'Status of "' . $product_data->title . '" was changed to ' . $newStatus . ' by ' . (Auth::user()->name ?? 'Admin') . '.',
            $product_data->id,
            0,
            $product_data->product_owner ?? Auth::id(),
            url('create-product-details/' . $product_data->id)
        );

        $data['result'] = array(
            'key' => 200,
            'val' => 'Product Status Changed',
            'pro'=> $product_data
        );
        return response()->json($data);
    }
    public function product_details($id=NULL){
        $data['page_title'] = 'Create Product Details';
        $data['product'] = Product::with(['category', 'subCategory', 'brand', 'owner', 'images', 'productDetails', 'stockHistories.sizeData', 'stockHistories.colorData'])->where('id', $id)->first();
        if(!$data['product']){
            Session::flash('error','Product Not Found');
            return redirect('all-products');
        }
        $data['sizes'] = SizeXL::where('status', 0)->get();
        $data['colors'] = Color::where('status', 0)->get();
        $data['notifications'] = ProductNotification::with('creator')
            ->where('product_id', $data['product']->id)
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.bdmegashop.product.product_details', $data);
    }
    public function upload_details_page_image(Request $request){
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $get_product = Product::where('id', $request->product_id)->first();
        if(!$get_product){
            Session::flash('error','Product Not Found');
            return redirect('all-products');
        }
        $get_product_img = ProductData::upload_details_page_image($request);

        ProductNotification::addNotification(
            'Product Image Uploaded',
            'A new gallery image was uploaded for product "' . $get_product->title . '" by ' . (Auth::user()->name ?? 'Admin') . '.',
            $get_product->id,
            0,
            $get_product->product_owner ?? Auth::id(),
            url('create-product-details/' . $get_product->id)
        );

        Session::flash('success','Product Detail Image Saved Successfully!');
        return redirect()->back();
    }
    public function update_product_stock(Request $request){
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);
        $get_product = Product::where('id', $request->product_id)->first();
        if(!$get_product){
            Session::flash('error','Product Not Found');
            return redirect('all-products');
        }
        ProductData::update_product_stock($request);

        $actionType = $request->stock_type == 'remove' ? 'Removed' : ($request->stock_type == 'adjustment' ? 'Adjusted' : 'Added');
        ProductNotification::addNotification(
            'Product Stock ' . $actionType,
            $actionType . ' ' . $request->quantity . ' units for product "' . $get_product->title . '" by ' . (Auth::user()->name ?? 'Admin') . ($request->stock_note ? ' (Reason: ' . $request->stock_note . ')' : '') . '.',
            $get_product->id,
            0,
            $get_product->product_owner ?? Auth::id(),
            url('create-product-details/' . $get_product->id)
        );

        Session::flash('success','Product Stock Updated Successfully!');
        return redirect()->back();
    }
    //image upload 500 * 430
}
