<?php

namespace App\Http\Controllers\bdmegashop\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BdMegaShop\Category\Brand;
use App\Models\BdMegaShop\Category\Category;
use App\Models\BdMegaShop\Category\Color;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\SizeXL;
use App\Models\BdMegaShop\Category\SubCategory;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $data['page_title'] = 'BD Mega Shop | All Products';
        $data['categories'] = Category::with([
            'subCategories' => function ($q) {
                $q->where('status', 0)
                  ->withCount(['products' => function ($pq) {
                      $pq->where('status', 0);
                  }])
                  ->orderBy('id', 'desc');
            }
        ])
            ->withCount(['products' => function ($q) {
                $q->where('status', 0);
            }])
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->get();

        $subCatQuery = SubCategory::with('category')
            ->withCount(['products' => function ($q) {
                $q->where('status', 0);
            }])
            ->where('status', 0);

        if ($request->filled('category')) {
            $subCatQuery->where('category_id', $request->input('category'));
        }
        $data['sub_categories'] = $subCatQuery->orderBy('id', 'desc')->get();

        $data['brands'] = Brand::withCount(['products' => function ($q) {
            $q->where('status', 0);
        }])
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->get();

        $maxCatalogPrice = (float) Product::where('status', 0)
            ->selectRaw('MAX(CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2))) as max_p')
            ->value('max_p');
        $data['max_catalog_price'] = $maxCatalogPrice > 0 ? ceil($maxCatalogPrice) : 50000;

        $query = Product::with(['category', 'subCategory', 'brand', 'images', 'productDetails'])
            ->where('status', 0);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('product_code', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('sub_category')) {
            $query->where('sub_category_id', $request->input('sub_category'));
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->input('brand'));
        }

        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $minPrice = (float) $request->input('min_price');
            $query->whereRaw('CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2)) >= ?', [$minPrice]);
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $maxPrice = (float) $request->input('max_price');
            $query->whereRaw('CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2)) <= ?', [$maxPrice]);
        }

        if ($request->boolean('on_sale')) {
            $query->where(function ($q) {
                $q->whereRaw('CAST(COALESCE(discount, 0) AS DECIMAL(10,2)) > 0')
                  ->orWhereRaw('CAST(COALESCE(price, 0) AS DECIMAL(10,2)) > CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2))');
            });
        }

        if ($request->boolean('in_stock')) {
            $query->whereHas('productDetails', function ($q) {
                $q->whereRaw('COALESCE(total_stock_in, 0) > COALESCE(total_stock_out, 0)');
            });
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderByRaw('CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2)) ASC');
        } elseif ($sort === 'price_desc') {
            $query->orderByRaw('CAST(COALESCE(selling_price, price, 0) AS DECIMAL(10,2)) DESC');
        } elseif ($sort === 'discount') {
            $query->orderByRaw('CAST(COALESCE(discount, 0) AS DECIMAL(10,2)) DESC');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $data['products'] = $query->paginate(12)->withQueryString();
        return view('bdmegashop.product.all', $data);
    }

    public function live_search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (strlen($q) < 1) {
            return response()->json(['items' => []]);
        }

        $products = Product::where('status', 0)
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%')
                      ->orWhere('product_code', 'like', '%' . $q . '%');
            })
            ->orderBy('id', 'desc')
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug ?? $p->id,
                    'price' => (float) ($p->selling_price ?? $p->price ?? 0),
                    'regular_price' => (float) ($p->price ?? 0),
                    'image' => !empty($p->image) ? asset($p->image) : asset('bdmegashop/dist/images/product/05.jpg'),
                    'url' => url('details/' . ($p->slug ?? $p->id)),
                ];
            });

        return response()->json(['items' => $products]);
    }

    public function toggle_wishlist(Request $request)
    {
        $productId = (int) $request->input('product_id');
        $sessionWishlist = (array) Session::get('wishlist', []);
        $cookieWishlist = json_decode($request->cookie('wishlist', '[]'), true) ?: [];
        $wishlist = array_values(array_unique(array_filter(array_merge($sessionWishlist, (array)$cookieWishlist))));

        if (in_array($productId, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $added = false;
            $message = 'Removed from wishlist.';
        } else {
            $wishlist[] = $productId;
            $wishlist = array_values(array_unique($wishlist));
            $added = true;
            $message = 'Added to wishlist!';
        }

        Session::put('wishlist', $wishlist);
        cookie()->queue('wishlist', json_encode($wishlist), 60 * 24 * 30);

        return response()->json([
            'status' => 'success',
            'added' => $added,
            'count' => count($wishlist),
            'message' => $message,
        ]);
    }

    public function details($id=NULL){
        $product = Product::with([
            'category',
            'subCategory',
            'brand',
            'images',
            'productDetails.sizeData',
            'productDetails.colorData',
        ])
            ->where('status', 0)
            ->when($id, function ($query) use ($id) {
                $query->where(function ($q) use ($id) {
                    $q->where('slug', $id)->orWhere('id', $id);
                });
            }, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->first();

        if (!$product) {
            Session::flash('error', 'Product Not Found');
            return redirect('/');
        }

        $data['page_title'] = 'BD Mega Shop | ' . ($product->title ?? 'Product Details');
        $data['product'] = $product;

        $totalStockIn = $product->productDetails->sum(fn($item) => (int) ($item->total_stock_in ?? 0));
        $totalStockOut = $product->productDetails->sum(fn($item) => (int) ($item->total_stock_out ?? 0));
        $data['current_stock'] = max(0, $totalStockIn - $totalStockOut);

        $data['available_sizes'] = $product->productDetails
            ->filter(fn($item) => !empty($item->size))
            ->unique('size')
            ->values();

        $data['available_colors'] = $product->productDetails
            ->filter(fn($item) => !empty($item->color))
            ->unique('color')
            ->values();

        $more_products = Product::with(['category', 'subCategory', 'brand', 'images', 'productDetails'])
            ->where('status', 0)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, function ($q) use ($product) {
                $q->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$product->category_id]);
            })
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $data['more_products'] = $more_products;

        $featured_products = Product::with(['category', 'subCategory', 'brand', 'images', 'productDetails'])
            ->where('status', 0)
            ->where('id', '!=', $product->id)
            ->where('is_featured', 1)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        if ($featured_products->isEmpty()) {
            $featured_products = Product::with(['category', 'subCategory', 'brand', 'images', 'productDetails'])
                ->where('status', 0)
                ->where('id', '!=', $product->id)
                ->orderBy('id', 'desc')
                ->take(8)
                ->get();
        }

        $data['featured_products'] = $featured_products;

        return view('bdmegashop.product.details', $data);
    }

    public function add_to_cart(Request $request)
    {
        $product = Product::with('productDetails')->where('id', $request->product_id)->where('status', 0)->first();
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        $totalStockIn = $product->productDetails->sum(fn($item) => (int) ($item->total_stock_in ?? 0));
        $totalStockOut = $product->productDetails->sum(fn($item) => (int) ($item->total_stock_out ?? 0));
        $currentStock = max(0, $totalStockIn - $totalStockOut);

        if ($currentStock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, this product is currently out of stock.',
            ], 422);
        }

        $size = trim((string) ($request->size ?? ''));
        $color = trim((string) ($request->color ?? ''));
        $quantity = max(1, (int) ($request->quantity ?? 1));

        $cart = Session::get('cart', []);
        $cartKey = $product->id . '_' . $size . '_' . $color;

        $existingQty = isset($cart[$cartKey]) ? (int) $cart[$cartKey]['quantity'] : 0;
        $newQty = min($currentStock, $existingQty + $quantity);

        $unitPrice = (float) ($product->selling_price ?? $product->price ?? 0);
        $regularPrice = (float) ($product->price ?? $unitPrice);
        $shippingCharge = (float) ($product->shipping_charge ?? 0);

        $cart[$cartKey] = [
            'key' => $cartKey,
            'product_id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug ?? $product->id,
            'image' => !empty($product->image) ? asset($product->image) : asset('bdmegashop/dist/images/product/05.jpg'),
            'price' => $unitPrice,
            'regular_price' => $regularPrice,
            'shipping_charge' => $shippingCharge,
            'size' => $size,
            'color' => $color,
            'quantity' => $newQty,
            'max_stock' => $currentStock,
        ];

        Session::put('cart', $cart);

        return response()->json($this->buildCartPayload($cart, 'Product added to cart.'));
    }

    public function update_cart(Request $request)
    {
        $cart = Session::get('cart', []);
        $key = $request->key;
        $quantity = (int) ($request->quantity ?? 1);

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $maxStock = (int) ($cart[$key]['max_stock'] ?? 999);
                $cart[$key]['quantity'] = min($maxStock, $quantity);
            }
            Session::put('cart', $cart);
        }

        return response()->json($this->buildCartPayload($cart, 'Cart updated.'));
    }

    public function remove_from_cart(Request $request)
    {
        $cart = Session::get('cart', []);
        $key = $request->key;

        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put('cart', $cart);
        }

        return response()->json($this->buildCartPayload($cart, 'Item removed from cart.'));
    }

    public function clear_cart()
    {
        Session::forget('cart');
        return response()->json($this->buildCartPayload([], 'Cart cleared.'));
    }

    public function get_cart_data()
    {
        $cart = Session::get('cart', []);
        return response()->json($this->buildCartPayload($cart));
    }

    private function buildCartPayload(array $cart, string $message = ''): array
    {
        $items = array_values($cart);
        $subtotal = 0;
        $totalQty = 0;
        $shippingByProduct = [];

        foreach ($items as &$item) {
            $item['line_total'] = round(((float) $item['price']) * ((int) $item['quantity']), 2);
            $subtotal += $item['line_total'];
            $totalQty += (int) $item['quantity'];
            $shippingByProduct[$item['product_id']] = (float) ($item['shipping_charge'] ?? 0);
        }

        $shipping = array_sum($shippingByProduct);
        $total = $subtotal + $shipping;

        return [
            'status' => 'success',
            'message' => $message,
            'items' => $items,
            'count' => count($items),
            'total_qty' => $totalQty,
            'subtotal' => round($subtotal, 2),
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
        ];
    }
}
