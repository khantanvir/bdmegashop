<?php

namespace App\Http\Controllers\admin\bdmegashop\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\BdMegaShop\Category\Brand;
use App\Models\BdMegaShop\Category\Category;
use App\Models\BdMegaShop\Category\Color;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\ProductDetail;
use App\Models\BdMegaShop\Category\SizeXL;
use App\Models\BdMegaShop\Category\SubCategory;
use App\Models\BdMegaShop\Order\Order;
use App\Models\BdMegaShop\Order\OrderAddress;
use App\Models\BdMegaShop\Order\OrderProduct;
use App\Models\BdMegaShop\Product\ProductNotification;

class AdminOrderController extends Controller
{
    /**
     * Admin Sale POS Interface.
     */
    public function admin_sale()
    {
        $data['page_title'] = 'Admin Sale (POS)';
        $data['order_menu'] = true;
        $data['sale_menu'] = true;

        $data['categories'] = Category::where('status', 0)->orderBy('title')->get();
        $data['brands'] = Brand::where('status', 0)->orderBy('title')->get();
        $data['sizes'] = SizeXL::where('status', 0)->get();
        $data['colors'] = Color::where('status', 0)->get();

        $data['order_statuses'] = OrderController::orderStatuses();
        $data['payment_statuses'] = OrderController::paymentStatuses();
        $data['shipping_methods'] = OrderController::shippingMethods();
        $data['delivery_pay_by'] = OrderController::deliveryChargePayBy();

        $products = Product::with(['category', 'brand', 'images', 'productDetails.sizeData', 'productDetails.colorData'])
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->paginate(12);

        $data['products'] = $products;

        return view('admin.bdmegashop.order.admin_sale', $data);
    }

    /**
     * AJAX: Live search and filter products for admin sale catalog.
     */
    public function search_products(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'productDetails.sizeData', 'productDetails.colorData'])
            ->where('status', 0);

        if ($request->filled('search')) {
            $term = trim($request->input('search'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('product_code', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        $perPage = 12;
        $products = $query->orderBy('id', 'desc')->paginate($perPage);

        $formatted = [];
        foreach ($products as $prod) {
            $stockIn = $prod->productDetails->sum('total_stock_in');
            $stockOut = $prod->productDetails->sum('total_stock_out');
            $stock = max(0, (int)$stockIn - (int)$stockOut);

            // Filter by stock if requested
            if ($request->input('stock_status') === 'in_stock' && $stock <= 0) {
                continue;
            } elseif ($request->input('stock_status') === 'out_of_stock' && $stock > 0) {
                continue;
            }

            $img = !empty($prod->image)
                ? (str_starts_with($prod->image, 'http') ? $prod->image : asset($prod->image))
                : asset('bdmegashop/dist/images/product/05.jpg');

            $sizes = $prod->productDetails->map(function ($d) {
                return $d->sizeData ? ['id' => $d->sizeData->id, 'title' => $d->sizeData->title] : null;
            })->filter()->unique('id')->values()->toArray();

            $colors = $prod->productDetails->map(function ($d) {
                return $d->colorData ? ['id' => $d->colorData->id, 'title' => $d->colorData->title] : null;
            })->filter()->unique('id')->values()->toArray();

            $sellingPrice = (float)($prod->selling_price ?: ($prod->price ?: 0));
            $originalPrice = (float)($prod->price ?: $sellingPrice);

            $formatted[] = [
                'id'             => $prod->id,
                'title'          => $prod->title,
                'code'           => $prod->product_code ?: 'PRD-' . $prod->id,
                'image'          => $img,
                'category_name'  => $prod->category->title ?? 'General',
                'brand_name'     => $prod->brand->title ?? '',
                'selling_price'  => $sellingPrice,
                'original_price' => $originalPrice,
                'discount'       => (float)($prod->discount ?? 0),
                'stock'          => $stock,
                'has_variants'   => (count($sizes) > 0 || count($colors) > 0),
                'sizes'          => $sizes,
                'colors'         => $colors,
            ];
        }

        return response()->json([
            'result' => [
                'key'          => 200,
                'products'     => $formatted,
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    /**
     * AJAX: Get detailed product info and variants for modal quick view.
     */
    public function product_quick_view($id)
    {
        $prod = Product::with(['category', 'brand', 'images', 'productDetails.sizeData', 'productDetails.colorData'])
            ->where('id', $id)
            ->first();

        if (!$prod) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Product Not Found']], 404);
        }

        $stockIn = $prod->productDetails->sum('total_stock_in');
        $stockOut = $prod->productDetails->sum('total_stock_out');
        $stock = max(0, (int)$stockIn - (int)$stockOut);

        $img = !empty($prod->image)
            ? (str_starts_with($prod->image, 'http') ? $prod->image : asset($prod->image))
            : asset('bdmegashop/dist/images/product/05.jpg');

        $gallery = [];
        if (!empty($prod->image)) {
            $gallery[] = $img;
        }
        foreach ($prod->images as $gImg) {
            if (!empty($gImg->image)) {
                $gallery[] = str_starts_with($gImg->image, 'http') ? $gImg->image : asset($gImg->image);
            }
        }
        $gallery = array_values(array_unique($gallery));

        $sizes = $prod->productDetails->map(function ($d) {
            return $d->sizeData ? ['id' => $d->sizeData->id, 'title' => $d->sizeData->title] : null;
        })->filter()->unique('id')->values()->toArray();

        $colors = $prod->productDetails->map(function ($d) {
            return $d->colorData ? ['id' => $d->colorData->id, 'title' => $d->colorData->title] : null;
        })->filter()->unique('id')->values()->toArray();

        // Fallbacks if not mapped in productDetails
        if (empty($sizes)) {
            $sizes = SizeXL::where('status', 0)->take(6)->get(['id', 'title'])->toArray();
        }
        if (empty($colors)) {
            $colors = Color::where('status', 0)->take(6)->get(['id', 'title'])->toArray();
        }

        $sellingPrice = (float)($prod->selling_price ?: ($prod->price ?: 0));
        $originalPrice = (float)($prod->price ?: $sellingPrice);
        $purchasePrice = (float)($prod->purchase_price ?: 0);

        return response()->json([
            'result' => [
                'key'            => 200,
                'id'             => $prod->id,
                'title'          => $prod->title,
                'code'           => $prod->product_code ?: 'PRD-' . $prod->id,
                'image'          => $img,
                'gallery'        => $gallery,
                'category_name'  => $prod->category->title ?? 'General',
                'brand_name'     => $prod->brand->title ?? '',
                'selling_price'  => $sellingPrice,
                'original_price' => $originalPrice,
                'purchase_price' => $purchasePrice,
                'profit_margin'  => max(0, $sellingPrice - $purchasePrice),
                'discount'       => (float)($prod->discount ?? 0),
                'stock'          => $stock,
                'sizes'          => $sizes,
                'colors'         => $colors,
            ],
        ]);
    }

    /**
     * Complete admin sale and create new Order with Customer Address (Guest / No account created).
     */
    public function admin_sale_complete(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:30',
            'customer_email'   => 'nullable|email|max:255',
            'delivery_address' => 'required|string',
            'delivery_city'    => 'required|string|max:100',
            'delivery_state'   => 'nullable|string|max:100',
            'delivery_zipcode' => 'nullable|string|max:30',
            'payment_method'   => 'required|string',
            'payment_status'   => 'required|string',
            'order_status'     => 'required|string',
            'delivery_charge'  => 'nullable|numeric|min:0',
            'discount_amount'  => 'nullable|numeric|min:0',
            'cart_items'       => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.quantity'   => 'required|integer|min:1',
            'cart_items.*.price'      => 'required|numeric|min:0',
        ]);

        $cartItems = $request->input('cart_items', []);
        if (empty($cartItems)) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Sale cart is empty. Add products to proceed.']], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $qty = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['price'] ?? 0);
                $subtotal += ($qty * $price);
            }

            $deliveryCharge = (float)($request->input('delivery_charge') ?? 0);
            $discountAmount = (float)($request->input('discount_amount') ?? 0);
            $grandTotal = max(0, $subtotal - $discountAmount + $deliveryCharge);

            $orderNumber = 'BMS-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $adminUser = Auth::user();
            $adminId = Auth::id();

            // 1. Create Order: customer_id is NULL (no account created as requested)
            $order = Order::create([
                'order_number'          => $orderNumber,
                'customer_id'           => null,
                'user_id'               => null,
                'name'                  => $request->input('customer_name'),
                'email'                 => $request->input('customer_email') ?: 'guest_' . date('Ymd_His') . '@bdmegashop.com',
                'phone'                 => $request->input('customer_phone'),
                'payment_status'        => $request->input('payment_status', 'unpaid'),
                'payment_method'        => $request->input('payment_method', 'cod'),
                'transaction_id'        => $request->input('transaction_id') ?: null,
                'order_processing_by'   => $adminId,
                'processing_percentage' => '25.00',
                'processing_amount'     => '0.00',
                'is_paid_processing_amount' => 'No',
                'currency'              => 'BDT',
                'delivery_charge'       => number_format($deliveryCharge, 2, '.', ''),
                'delivery_charge_pay_by'=> $request->input('payment_method', 'cash'),
                'subtotal'              => number_format($subtotal, 2, '.', ''),
                'discount_amount'       => number_format($discountAmount, 2, '.', ''),
                'total'                 => number_format($grandTotal, 2, '.', ''),
                'shipping_method'       => $request->input('shipping_method') ?: 'myself',
                'tracking_number'       => $request->input('tracking_number') ?: null,
                'order_status'          => $request->input('order_status', 'confirmed'),
                'note'                  => 'Created directly from Admin Sale (POS).',
                'admin_note'            => $request->input('admin_note') ?: 'Admin POS Sale by ' . ($adminUser ? $adminUser->name : 'Admin'),
                'create_by'             => $adminId,
                'update_by'             => $adminId,
                'status'                => 1,
            ]);

            // 2. Create OrderAddress with customer's address
            OrderAddress::create([
                'order_id'         => $order->id,
                'name'             => $request->input('customer_name'),
                'email'            => $request->input('customer_email'),
                'phone'            => $request->input('customer_phone'),
                'address'          => $request->input('delivery_address'),
                'city'             => $request->input('delivery_city'),
                'state'            => $request->input('delivery_state'),
                'zipcode'          => $request->input('delivery_zipcode'),
                'same_as'          => 'yes',
                'shipping_address' => $request->input('delivery_address'),
                'shipping_city'    => $request->input('delivery_city'),
                'shipping_state'   => $request->input('delivery_state'),
                'shipping_zipcode' => $request->input('delivery_zipcode'),
            ]);

            // 3. Save Order Products & Deduct Stock
            foreach ($cartItems as $item) {
                $prodId = (int)$item['product_id'];
                $product = Product::find($prodId);
                $qty = max(1, (int)$item['quantity']);
                $unitPrice = (float)$item['price'];
                $lineTotal = $unitPrice * $qty;

                $sizeTitle = $item['size'] ?? null;
                $colorTitle = $item['color'] ?? null;

                $sizeId = !empty($sizeTitle)
                    ? (SizeXL::where('title', $sizeTitle)->value('id') ?? (is_numeric($sizeTitle) ? (int)$sizeTitle : null))
                    : null;

                $colorId = !empty($colorTitle)
                    ? (Color::where('title', $colorTitle)->value('id') ?? (is_numeric($colorTitle) ? (int)$colorTitle : null))
                    : null;

                $itemImg = !empty($item['image'])
                    ? $item['image']
                    : (!empty($product->image) ? $product->image : null);

                OrderProduct::create([
                    'order_id'             => $order->id,
                    'product_id'           => $prodId,
                    'size_id'              => $sizeId,
                    'color_id'             => $colorId,
                    'product_name'         => $item['title'] ?? ($product->title ?? 'Product #' . $prodId),
                    'sku'                  => $product->product_code ?? null,
                    'product_image'        => $itemImg,
                    'attributes'           => json_encode([
                        'size'  => $sizeTitle,
                        'color' => $colorTitle,
                    ]),
                    'unit_price'           => number_format($unitPrice, 2, '.', ''),
                    'original_price'       => number_format((float)($product->price ?? $unitPrice), 2, '.', ''),
                    'quantity'             => (string)$qty,
                    'discount_amount'      => (string)($product->discount ?? 0),
                    'tax_amount'           => '0',
                    'line_total'           => number_format($lineTotal, 2, '.', ''),
                    'order_product_status' => 'active',
                    'create_by'            => $adminId,
                    'update_by'            => $adminId,
                ]);

                // Deduct stock in ProductDetail
                if ($product) {
                    ProductDetail::create([
                        'product_id'      => $prodId,
                        'size'            => $sizeId ?? $sizeTitle,
                        'color'           => $colorId ?? $colorTitle,
                        'purchase_price'  => $product->purchase_price ?? 0,
                        'price'           => $product->price ?? $unitPrice,
                        'discount'        => $product->discount ?? 0,
                        'selling_price'   => $unitPrice,
                        'shipping_charge' => $product->shipping_charge ?? 0,
                        'total_stock_in'  => 0,
                        'total_stock_out' => $qty,
                        'stock_note'      => 'Admin POS Sale #' . $orderNumber,
                        'create_by'       => $adminId,
                        'update_by'       => $adminId,
                        'status'          => 0,
                    ]);
                }
            }

            // 4. Recalculate commission automatically
            $calc = $order->calculateProcessingData($adminId, 25);
            $order->processing_amount = number_format($calc['processing_amount'], 2, '.', '');
            $order->save();

            // 5. Log ProductNotification
            ProductNotification::addNotification(
                'Admin Direct Sale Completed',
                'New order #' . $orderNumber . ' for BDT ' . number_format($grandTotal, 2) . ' was completed directly via Admin Sale (POS) by ' . ($adminUser ? $adminUser->name : 'Admin') . ' for customer ' . $request->input('customer_name') . '.',
                $cartItems[0]['product_id'] ?? 0,
                $order->id,
                $adminId,
                url('order-details/' . $order->id)
            );

            DB::commit();

            return response()->json([
                'result' => [
                    'key'               => 200,
                    'val'               => 'Sale completed successfully! Order #' . $orderNumber . ' has been created.',
                    'order_id'          => $order->id,
                    'order_number'      => $orderNumber,
                    'total'             => number_format($grandTotal, 2),
                    'order_details_url' => url('order-details/' . $order->id),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'result' => [
                    'key' => 500,
                    'val' => 'Failed to complete sale: ' . $e->getMessage(),
                ],
            ], 500);
        }
    }
}
