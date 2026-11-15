<?php

namespace App\Http\Controllers\admin\bdmegashop\order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\BdMegaShop\Order\Order;
use App\Models\BdMegaShop\Product\ProductNotification;

class OrderController extends Controller
{
    /**
     * Allowed order statuses (key => label).
     */
    public static function orderStatuses(): array
    {
        return [
            'pending'           => 'Pending',
            'confirmed'         => 'Confirmed',
            'processing'        => 'Processing',
            'handed_to_courier' => 'Handed to Courier',
            'on_the_way'        => 'On the Way',
            'delivered'         => 'Delivered',
            'cancelled'         => 'Cancelled',
            'returned'          => 'Returned',
        ];
    }

    public static function paymentStatuses(): array
    {
        return [
            'unpaid'         => 'Unpaid',
            'paid'           => 'Paid',
            'partially_paid' => 'Partially Paid',
            'failed'         => 'Failed',
            'refunded'       => 'Refunded',
        ];
    }

    public static function shippingMethods(): array
    {
        return [
            'pathao'    => 'Pathao',
            'steadfast' => 'Steadfast',
            'sundarban' => 'Sundarban',
            'redx'      => 'RedX',
            'myself'    => 'Self Delivery',
            'other'     => 'Other',
        ];
    }

    public static function deliveryChargePayBy(): array
    {
        return [
            'cash'  => 'Cash',
            'bkash' => 'bKash',
            'bank'  => 'Bank',
        ];
    }

    public function order_list(Request $request)
    {
        $data['page_title'] = 'Order List';
        $data['order_menu'] = true;
        $data['all_order_menu'] = true;

        $query = Order::with(['processor', 'orderProducts.product.owner'])->withCount('orderProducts')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->input('order_status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $data['orders'] = $query->paginate(15)->withQueryString();

        // Status counters for the summary cards
        $data['status_counts'] = Order::selectRaw('order_status, COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status')
            ->toArray();
        $data['total_orders'] = array_sum($data['status_counts']);

        $data['order_statuses'] = self::orderStatuses();
        $data['payment_statuses'] = self::paymentStatuses();
        $data['shipping_methods'] = self::shippingMethods();
        $data['delivery_pay_by'] = self::deliveryChargePayBy();
        $data['users'] = \App\Models\User::select('id', 'name', 'email', 'role')->orderBy('name')->get();
        $data['current_user'] = Auth::user();

        return view('admin.bdmegashop.order.all_order', $data);
    }

    public function order_details($id = null)
    {
        $order = Order::with(['orderProducts.product.owner', 'orderAddress', 'customer', 'processor'])
            ->when($id, function ($query) use ($id) {
                $query->where(function ($q) use ($id) {
                    $q->where('id', $id)->orWhere('order_number', $id);
                });
            }, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->first();

        if (!$order) {
            Session::flash('error', 'Order Not Found');
            return redirect('order-list');
        }

        $data['page_title'] = 'Order Details - #' . $order->order_number;
        $data['order_menu'] = true;
        $data['all_order_menu'] = true;
        $data['order'] = $order;
        $data['order_statuses'] = self::orderStatuses();
        $data['payment_statuses'] = self::paymentStatuses();
        $data['shipping_methods'] = self::shippingMethods();
        $data['delivery_pay_by'] = self::deliveryChargePayBy();
        $data['users'] = \App\Models\User::select('id', 'name', 'email', 'role')->orderBy('name')->get();
        $data['current_user'] = Auth::user();
        $data['processing_data'] = $order->calculateProcessingData();
        $data['notifications'] = ProductNotification::with('creator')
            ->where('order_id', $order->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.bdmegashop.order.order_details', $data);
    }

    /**
     * AJAX: update order status (+ payment status, courier & tracking, admin note).
     */
    public function order_status_update(Request $request)
    {
        $request->validate([
            'order_id'           => 'required|integer',
            'order_status'       => 'required|string|in:' . implode(',', array_keys(self::orderStatuses())),
            'payment_status'     => 'nullable|string|in:' . implode(',', array_keys(self::paymentStatuses())),
            'shipping_method'    => 'nullable|string|in:' . implode(',', array_keys(self::shippingMethods())),
            'tracking_number'    => 'nullable|string|max:191',
            'admin_note'         => 'nullable|string|max:2000',
        ]);

        $order = Order::where('id', $request->input('order_id'))->first();
        if (!$order) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Order Not Found']]);
        }

        $order->order_status = $request->input('order_status');
        if ($request->filled('payment_status')) {
            $order->payment_status = $request->input('payment_status');
        }
        if ($request->has('shipping_method')) {
            $order->shipping_method = $request->input('shipping_method');
        }
        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->input('tracking_number');
        }
        if ($request->has('admin_note')) {
            $order->admin_note = $request->input('admin_note');
        }
        $order->update_by = Auth::id();
        $order->save();

        $userName = Auth::user()->name ?? 'Admin';
        $statusLabel = self::orderStatuses()[$order->order_status] ?? ucfirst($order->order_status);
        $desc = 'Order #' . ($order->order_number ?? $order->id) . ' status updated to "' . $statusLabel . '"';
        if ($request->filled('payment_status')) {
            $desc .= ', payment: ' . (self::paymentStatuses()[$order->payment_status] ?? $order->payment_status);
        }
        if ($request->has('shipping_method') && $order->shipping_method) {
            $desc .= ', courier: ' . (self::shippingMethods()[$order->shipping_method] ?? $order->shipping_method);
        }
        if ($request->has('tracking_number') && $order->tracking_number) {
            $desc .= ', tracking: ' . $order->tracking_number;
        }
        $desc .= ' by ' . $userName . '.';

        ProductNotification::addNotification(
            'Order Status Updated',
            $desc,
            $order->orderProducts()->first()?->product_id ?? 0,
            $order->id,
            $order->user_id ?? Auth::id(),
            url('order-details/' . $order->id)
        );

        return response()->json([
            'result' => [
                'key' => 200,
                'val' => 'Order status updated successfully',
                'order_status' => $order->order_status,
                'order_status_label' => self::orderStatuses()[$order->order_status] ?? ucfirst($order->order_status),
                'payment_status' => $order->payment_status,
                'payment_status_label' => self::paymentStatuses()[$order->payment_status] ?? ucfirst((string) $order->payment_status),
            ],
        ]);
    }

    /**
     * AJAX: update delivery charge and recalculate the order total.
     */
    public function order_delivery_charge_update(Request $request)
    {
        $request->validate([
            'order_id'                          => 'required|integer',
            'delivery_charge'                   => 'required|numeric|min:0',
            'delivery_charge_pay_by'            => 'nullable|string|in:' . implode(',', array_keys(self::deliveryChargePayBy())),
            'delivery_charge_reference_number'  => 'nullable|string|max:191',
        ]);

        $order = Order::where('id', $request->input('order_id'))->first();
        if (!$order) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Order Not Found']]);
        }

        $deliveryCharge = (float) $request->input('delivery_charge');
        $subtotal = (float) ($order->subtotal ?? 0);
        $discount = (float) ($order->discount_amount ?? 0);
        $total = max(0, $subtotal - $discount + $deliveryCharge);

        $order->delivery_charge = number_format($deliveryCharge, 2, '.', '');
        $order->delivery_charge_pay_by = $request->input('delivery_charge_pay_by');
        $order->delivery_charge_reference_number = $request->input('delivery_charge_reference_number');
        $order->total = number_format($total, 2, '.', '');
        $order->update_by = Auth::id();
        $order->save();

        $userName = Auth::user()->name ?? 'Admin';
        $desc = 'Delivery charge for Order #' . ($order->order_number ?? $order->id) . ' updated to BDT ' . number_format($deliveryCharge, 2) . ' (New grand total: BDT ' . number_format($total, 2) . ') by ' . $userName . '.';

        ProductNotification::addNotification(
            'Order Delivery Charge Updated',
            $desc,
            $order->orderProducts()->first()?->product_id ?? 0,
            $order->id,
            $order->user_id ?? Auth::id(),
            url('order-details/' . $order->id)
        );

        return response()->json([
            'result' => [
                'key' => 200,
                'val' => 'Delivery charge updated. New total: BDT ' . number_format($total, 2),
                'delivery_charge' => number_format($deliveryCharge, 2),
                'total' => number_format($total, 2),
            ],
        ]);
    }

    /**
     * AJAX: Get order processing breakdown, product owners, and commission.
     */
    public function order_processing_info($id)
    {
        $order = Order::with(['orderProducts.product.owner', 'processor'])->where('id', $id)->first();
        if (!$order) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Order Not Found']], 404);
        }

        $proc = $order->calculateProcessingData();

        $currentUserId = Auth::id();
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        $isProductOwner = in_array($currentUserId, $proc['product_owner_ids']);
        $canEditPercentage = $isProductOwner || $isAdmin;

        $proc['can_edit_percentage'] = $canEditPercentage;
        $proc['is_product_owner'] = $isProductOwner;
        $proc['is_admin'] = $isAdmin;
        $proc['current_user_id'] = $currentUserId;
        $proc['current_user_name'] = Auth::check() ? Auth::user()->name : '';
        $proc['processor_name'] = $order->processor ? $order->processor->name : ($order->order_processing_by ? 'User #' . $order->order_processing_by : 'Unassigned');

        return response()->json([
            'result' => [
                'key' => 200,
                'data' => $proc,
            ],
        ]);
    }

    /**
     * AJAX: Update order processing by, processing percentage, and recalculate commission.
     * 
     * Policy:
     * - Default processing percentage is 25%.
     * - Only product owner (or admin) can change processing percentage.
     * - If order_processing_by == product_owner, processing percentage amount is 0 BDT.
     * - If another user processes the order, they get the percentage automatically based on product benefit.
     */
    public function order_processing_update(Request $request)
    {
        $request->validate([
            'order_id'                  => 'required|integer',
            'order_processing_by'       => 'nullable|integer',
            'processing_percentage'     => 'nullable|numeric|min:0|max:100',
            'is_paid_processing_amount' => 'nullable|in:Yes,No',
        ]);

        $order = Order::with(['orderProducts.product.owner', 'processor'])->where('id', $request->input('order_id'))->first();
        if (!$order) {
            return response()->json(['result' => ['key' => 101, 'val' => 'Order Not Found']], 404);
        }

        $currentUserId = Auth::id();
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        $productOwnerIds = [];
        foreach ($order->orderProducts as $item) {
            if ($item->product && $item->product->product_owner) {
                $productOwnerIds[] = (int) $item->product->product_owner;
            }
        }
        $productOwnerIds = array_values(array_unique($productOwnerIds));
        $isProductOwner = in_array($currentUserId, $productOwnerIds);
        $canEditPercentage = $isProductOwner || $isAdmin;

        // Percentage determination: Only product owner or admin can update percentage
        if ($request->filled('processing_percentage')) {
            if ($canEditPercentage) {
                $newPercentage = floatval($request->input('processing_percentage'));
            } else {
                // If not product owner, keep existing percentage or default 25%
                $newPercentage = floatval(($order->processing_percentage !== null && $order->processing_percentage !== '') ? $order->processing_percentage : 25);
            }
        } else {
            $newPercentage = floatval(($order->processing_percentage !== null && $order->processing_percentage !== '') ? $order->processing_percentage : 25);
        }

        $newProcessingBy = $request->has('order_processing_by')
            ? ($request->input('order_processing_by') ? (int) $request->input('order_processing_by') : null)
            : ($order->order_processing_by ? (int) $order->order_processing_by : null);

        // Recalculate commission data using the order's business rules
        $calc = $order->calculateProcessingData($newProcessingBy, $newPercentage);

        $order->order_processing_by = $newProcessingBy;
        $order->processing_percentage = number_format($newPercentage, 2, '.', '');
        $order->processing_amount = number_format($calc['processing_amount'], 2, '.', '');
        if ($request->filled('is_paid_processing_amount')) {
            $order->is_paid_processing_amount = $request->input('is_paid_processing_amount');
        }
        $order->update_by = Auth::id();
        $order->save();

        $order->load('processor');
        $processorName = $order->processor ? $order->processor->name : ($newProcessingBy ? 'User #' . $newProcessingBy : 'Unassigned');

        $isOwnerProcessing = false;
        if ($newProcessingBy && in_array($newProcessingBy, $productOwnerIds)) {
            $isOwnerProcessing = true;
        }

        $userName = Auth::user()->name ?? 'Admin';
        $procDesc = 'Order #' . ($order->order_number ?? $order->id) . ' processing assigned to ' . $processorName . ' at ' . $newPercentage . '% commission (BDT ' . number_format($calc['processing_amount'], 2) . ')';
        if ($isOwnerProcessing) {
            $procDesc .= ' [Product Owner Processing - 0% Commission]';
        }
        $procDesc .= ' by ' . $userName . '.';

        ProductNotification::addNotification(
            'Order Processing Commission Updated',
            $procDesc,
            $order->orderProducts()->first()?->product_id ?? 0,
            $order->id,
            $order->user_id ?? Auth::id(),
            url('order-details/' . $order->id)
        );

        $noticeMsg = 'Order processing commission updated successfully';
        if ($isOwnerProcessing) {
            $noticeMsg = 'Order processor is the Product Owner. Processing commission is BDT 0.00 (No commission applied).';
        } else {
            $noticeMsg = 'Commission calculated automatically: ' . number_format($newPercentage, 1) . '% of benefit = BDT ' . number_format($calc['processing_amount'], 2);
        }

        return response()->json([
            'result' => [
                'key'                       => 200,
                'val'                       => $noticeMsg,
                'order_id'                  => $order->id,
                'order_processing_by'       => $order->order_processing_by,
                'processor_name'            => $processorName,
                'processing_percentage'     => (float) $order->processing_percentage,
                'processing_amount'         => number_format((float) $order->processing_amount, 2),
                'is_paid_processing_amount' => $order->is_paid_processing_amount ?: 'No',
                'total_order_benefit'       => number_format($calc['total_order_benefit'], 2),
                'total_eligible_benefit'    => number_format($calc['total_eligible_benefit'], 2),
                'is_owner_processing'       => $isOwnerProcessing,
                'can_edit_percentage'       => $canEditPercentage,
            ],
        ]);
    }
}
