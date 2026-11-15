<?php

namespace App\Http\Controllers\bdmegashop\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BdMegaShop\Category\Category;
use App\Models\BdMegaShop\Category\Color;
use App\Models\BdMegaShop\Category\Product;
use App\Models\BdMegaShop\Category\ProductDetail;
use App\Models\BdMegaShop\Category\SizeXL;
use App\Models\BdMegaShop\Customer\Customer;
use App\Models\BdMegaShop\Order\Order;
use App\Models\BdMegaShop\Order\OrderAddress;
use App\Models\BdMegaShop\Order\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        $data['page_title'] = 'Bd Mega Shop | Checkout';
        $cart = Session::get('cart', []);

        $subtotal = 0;
        $shippingTotal = 0;
        $totalQty = 0;
        $cartItems = [];
        $shippingSeenProducts = [];

        foreach ($cart as $itemKey => $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);
            $lineTotal = $price * $qty;

            $subtotal += $lineTotal;
            $totalQty += $qty;

            $prodId = $item['product_id'] ?? null;
            if ($prodId && !in_array($prodId, $shippingSeenProducts)) {
                $shippingTotal += (float) ($item['shipping_charge'] ?? 0);
                $shippingSeenProducts[] = $prodId;
            }

            $cartItems[] = [
                'key' => $itemKey,
                'product_id' => $prodId,
                'title' => $item['title'] ?? '',
                'slug' => $item['slug'] ?? $prodId,
                'image' => !empty($item['image']) ? asset($item['image']) : asset('bdmegashop/dist/images/product/05.jpg'),
                'price' => $price,
                'quantity' => $qty,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'line_total' => $lineTotal,
            ];
        }

        $data['cart_items'] = $cartItems;
        $data['total_qty'] = $totalQty;
        $data['subtotal'] = $subtotal;
        $data['shipping'] = $shippingTotal;
        $data['total'] = $subtotal + $shippingTotal;
        $data['customer'] = Auth::guard('customer')->user();

        if (Session::has('placed_order_id')) {
            $data['placed_order'] = Order::with(['orderProducts', 'orderAddress'])
                ->find(Session::get('placed_order_id'));
        }

        return view('bdmegashop/order/checkout', $data);
    }

    public function place_order(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            Session::flash('error', 'Your shopping cart is empty. Please add products before placing an order.');
            return redirect('checkout');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:30',
            'payment_method' => 'required|string|in:cod,bkash,bank_transfer',
        ];

        if (!$request->has('same_as')) {
            $rules['shipping_address'] = 'required|string';
            $rules['shipping_city'] = 'required|string|max:100';
            $rules['shipping_state'] = 'nullable|string|max:100';
            $rules['shipping_zipcode'] = 'nullable|string|max:30';
        }

        if ($request->has('create_account') && !Auth::guard('customer')->check()) {
            $rules['account_password'] = 'required|string|min:6';
        }

        if ($request->input('payment_method') === 'bkash') {
            $rules['bkash_number'] = 'required|string|max:30';
            $rules['bkash_reference_number'] = 'required|string|max:100';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $customer = Auth::guard('customer')->user();

            // Automatic customer account creation if checkbox is selected
            if (!$customer && $request->has('create_account')) {
                $existingCustomer = Customer::where('email', $request->input('email'))->first();
                if ($existingCustomer) {
                    $customer = $existingCustomer;
                } else {
                    $nameParts = explode(' ', trim($request->input('name')), 2);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = $nameParts[1] ?? '';

                    $customer = Customer::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'phone' => $request->input('phone'),
                        'password' => Hash::make($request->input('account_password')),
                        'address' => $request->input('address'),
                        'city' => $request->input('city'),
                        'state' => $request->input('state'),
                        'zip_code' => $request->input('zipcode'),
                        'role' => 'customer',
                        'slug' => Str::slug($request->input('name')) . '-' . rand(1000, 9999),
                        'active' => 1,
                    ]);
                }

                if ($customer && $customer->active == 1) {
                    Auth::guard('customer')->login($customer);
                }
            }

            // Calculate totals from session cart
            $subtotal = 0;
            $shippingTotal = 0;
            $shippingSeenProducts = [];

            foreach ($cart as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                $subtotal += ($price * $qty);

                $prodId = $item['product_id'] ?? null;
                if ($prodId && !in_array($prodId, $shippingSeenProducts)) {
                    $shippingTotal += (float) ($item['shipping_charge'] ?? 0);
                    $shippingSeenProducts[] = $prodId;
                }
            }

            $grandTotal = $subtotal + $shippingTotal;
            $orderNumber = 'BMS-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // 1. Save Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer ? $customer->id : null,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'payment_status' => 'unpaid',
                'payment_method' => $request->input('payment_method', 'cod'),
                'transaction_id' => $request->input('transaction_id') ?: $request->input('bkash_reference_number'),
                'currency' => 'BDT',
                'bkash_number' => $request->input('bkash_number'),
                'bkash_reference_number' => $request->input('bkash_reference_number'),
                'delivery_charge' => number_format($shippingTotal, 2, '.', ''),
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'discount_amount' => '0.00',
                'total' => number_format($grandTotal, 2, '.', ''),
                'order_status' => 'pending',
                'note' => $request->input('note'),
                'create_by' => $customer ? $customer->id : null,
                'update_by' => $customer ? $customer->id : null,
                'status' => 1,
            ]);

            // 2. Save Order Address (Billing & Shipping)
            $sameAs = $request->has('same_as');
            OrderAddress::create([
                'order_id' => $order->id,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zipcode' => $request->input('zipcode'),
                'same_as' => $sameAs ? 'yes' : 'no',
                'shipping_address' => $sameAs ? $request->input('address') : $request->input('shipping_address'),
                'shipping_city' => $sameAs ? $request->input('city') : $request->input('shipping_city'),
                'shipping_state' => $sameAs ? $request->input('state') : $request->input('shipping_state'),
                'shipping_zipcode' => $sameAs ? $request->input('zipcode') : $request->input('shipping_zipcode'),
            ]);

            // 3. Save Order Products & Update Product Stock
            foreach ($cart as $item) {
                $prodId = $item['product_id'] ?? null;
                $product = $prodId ? Product::find($prodId) : null;
                $qty = (int) ($item['quantity'] ?? 1);
                $unitPrice = (float) ($item['price'] ?? 0);
                $lineTotal = $unitPrice * $qty;

                $sizeTitle = $item['size'] ?? null;
                $colorTitle = $item['color'] ?? null;

                $sizeId = !empty($sizeTitle)
                    ? (SizeXL::where('title', $sizeTitle)->value('id') ?? (is_numeric($sizeTitle) ? (int) $sizeTitle : null))
                    : null;

                $colorId = !empty($colorTitle)
                    ? (Color::where('title', $colorTitle)->value('id') ?? (is_numeric($colorTitle) ? (int) $colorTitle : null))
                    : null;

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $prodId,
                    'size_id' => $sizeId,
                    'color_id' => $colorId,
                    'product_name' => $item['title'] ?? ($product->title ?? ''),
                    'sku' => $product->product_code ?? null,
                    'product_image' => $item['image'] ?? ($product->image ?? null),
                    'attributes' => json_encode([
                        'size' => $sizeTitle,
                        'color' => $colorTitle,
                    ]),
                    'unit_price' => number_format($unitPrice, 2, '.', ''),
                    'original_price' => number_format((float) ($product->price ?? $unitPrice), 2, '.', ''),
                    'quantity' => (string) $qty,
                    'discount_amount' => (string) ($product->discount ?? 0),
                    'tax_amount' => '0',
                    'line_total' => number_format($lineTotal, 2, '.', ''),
                    'order_product_status' => 'active',
                    'create_by' => $customer ? $customer->id : null,
                    'update_by' => $customer ? $customer->id : null,
                ]);

                if ($prodId) {
                    ProductDetail::create([
                        'product_id' => $prodId,
                        'size' => $sizeId ?? $sizeTitle,
                        'color' => $colorId ?? $colorTitle,
                        'purchase_price' => $product->purchase_price ?? 0,
                        'price' => $product->price ?? $unitPrice,
                        'discount' => $product->discount ?? 0,
                        'selling_price' => $unitPrice,
                        'shipping_charge' => $product->shipping_charge ?? 0,
                        'total_stock_in' => 0,
                        'total_stock_out' => $qty,
                        'stock_note' => 'Order #' . $orderNumber,
                        'status' => 0,
                    ]);
                }
            }

            DB::commit();

            // Clear session cart
            Session::forget('cart');

            $successMessage = 'Your order (' . $orderNumber . ') has been placed successfully!';
            if ($request->has('create_account') && $customer) {
                $successMessage .= ' Your customer account has also been created and signed in.';
            }

            Session::flash('success', $successMessage);
            Session::flash('placed_order_id', $order->id);

            return redirect('checkout');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error', 'Failed to place order: ' . $e->getMessage());
            return Redirect::back()->withInput();
        }
    }
}
