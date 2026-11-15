@extends('adminpanel')
@section('admincontent')
@php
    $st = $order->order_status ?: 'pending';
    $pst = $order->payment_status ?: 'unpaid';
    $addr = $order->orderAddress;
    $totalQty = $order->orderProducts->sum(fn($i) => (int) ($i->quantity ?? 0));

    $flowSteps = [
        'pending'           => ['Pending', 'ri-time-line'],
        'confirmed'         => ['Confirmed', 'ri-checkbox-circle-line'],
        'processing'        => ['Processing', 'ri-settings-4-line'],
        'handed_to_courier' => ['Handed to Courier', 'ri-Inbox-unarchive-line'],
        'on_the_way'        => ['On the Way', 'ri-truck-line'],
        'delivered'         => ['Delivered', 'ri-check-double-line'],
    ];
    $flowKeys = array_keys($flowSteps);
    $currentStepIdx = array_search($st, $flowKeys);
@endphp

<style>
    .od-header-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 22px 26px;
        margin-bottom: 20px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.12);
    }
    .od-header-title {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .od-header-meta {
        font-size: 13px;
        color: #cbd5e1;
        margin-top: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    /* Progress Stepper */
    .od-stepper-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
    }
    .od-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        gap: 8px;
        overflow-x: auto;
    }
    .od-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        min-width: 105px;
        position: relative;
        z-index: 2;
    }
    .od-step-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        border: 2px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        margin-bottom: 6px;
        transition: all 0.25s ease;
    }
    .od-step.done .od-step-dot {
        background: #dcfce7;
        color: #15803d;
        border-color: #86efac;
    }
    .od-step.current .od-step-dot {
        background: #4361ee;
        color: #ffffff;
        border-color: #4361ee;
        box-shadow: 0 0 0 5px rgba(67, 97, 238, 0.18);
    }
    .od-step-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .od-step.done .od-step-label,
    .od-step.current .od-step-label {
        color: #0f172a;
    }

    /* Info Cards */
    .od-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 20px;
        height: calc(100% - 20px);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.02);
    }
    .od-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .od-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .od-card-title i {
        color: #4361ee;
        font-size: 17px;
    }
    .od-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .od-info-list li {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        padding: 6px 0;
        font-size: 13px;
        border-bottom: 1px dashed #f1f5f9;
    }
    .od-info-list li:last-child {
        border-bottom: 0;
    }
    .od-info-list .k {
        color: #64748b;
        font-weight: 600;
        white-space: nowrap;
    }
    .od-info-list .v {
        color: #0f172a;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    /* Products Table */
    .od-prod-table th {
        background: #f8fafc;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #64748b;
        white-space: nowrap;
    }
    .od-prod-table td {
        vertical-align: middle;
        font-size: 13.5px;
    }
    .od-prod-thumb {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .od-attr-pill {
        display: inline-block;
        background: #f1f5f9;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        margin-right: 4px;
        margin-top: 3px;
    }

    .od-totals-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        max-width: 380px;
        margin-left: auto;
    }
    .od-totals-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13.5px;
        color: #475569;
    }
    .od-totals-row strong {
        color: #0f172a;
    }
    .od-totals-row.grand {
        border-top: 2px solid #cbd5e1;
        margin-top: 8px;
        padding-top: 10px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }
    .od-totals-row.grand strong {
        color: #4361ee;
        font-size: 18px;
    }

    @media print {
        .secondary-nav, .od-no-print, .app-sidebar, .app-header {
            display: none !important;
        }
        .od-header-card {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000;
        }
        .od-header-title, .od-header-meta {
            color: #000000 !important;
        }
    }
</style>

<div>
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Analytics">
            <header class="header navbar navbar-expand-sm">
                <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom"></a>
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"></div>
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('order-list') }}">Orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number ?? $order->id }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </header>
        </div>
    </div>

    <!-- Top Order Banner -->
    <div class="od-header-card mt-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="od-header-title">
                    <span>Order #{{ $order->order_number ?? $order->id }}</span>
                    <span class="ord-badge ord-st-{{ $st }}" data-status-badge="{{ $order->id }}">
                        {{ $order_statuses[$st] ?? ucfirst($st) }}
                    </span>
                    <span class="ord-badge pay-st-{{ $pst }}" data-payment-badge="{{ $order->id }}">
                        {{ $payment_statuses[$pst] ?? ucfirst($pst) }}
                    </span>
                </h4>
                <div class="od-header-meta">
                    <span><i class="ri-calendar-event-line me-1"></i> Placed: <strong>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</strong></span>
                    <span><i class="ri-shopping-basket-line me-1"></i> Total Items: <strong>{{ $totalQty }} ({{ $order->orderProducts->count() }} products)</strong></span>
                    <span><i class="ri-bank-card-line me-1"></i> Payment Method: <strong class="text-uppercase">{{ $order->payment_method ?? 'COD' }}</strong></span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 od-no-print">
                <a href="{{ url('order-list') }}" class="btn btn-light btn-sm fw-bold">
                    <i class="ri-arrow-left-line me-1"></i> Back to Orders
                </a>

                <button type="button" class="btn btn-success btn-sm fw-bold"
                        data-delivery-trigger="{{ $order->id }}"
                        data-order-id="{{ $order->id }}"
                        data-order-number="{{ $order->order_number }}"
                        data-subtotal="{{ (float) $order->subtotal }}"
                        data-discount="{{ (float) $order->discount_amount }}"
                        data-delivery-charge="{{ (float) $order->delivery_charge }}"
                        data-pay-by="{{ $order->delivery_charge_pay_by }}"
                        data-reference="{{ $order->delivery_charge_reference_number }}"
                        onclick="openDeliveryChargeModal(this)">
                    <i class="ri-truck-line me-1"></i> Delivery Charge
                </button>

                <button type="button" class="btn btn-primary btn-sm fw-bold"
                        data-status-trigger="{{ $order->id }}"
                        data-order-id="{{ $order->id }}"
                        data-order-number="{{ $order->order_number }}"
                        data-order-status="{{ $st }}"
                        data-payment-status="{{ $pst }}"
                        data-shipping-method="{{ $order->shipping_method }}"
                        data-tracking-number="{{ $order->tracking_number }}"
                        data-admin-note="{{ $order->admin_note }}"
                        onclick="openOrderStatusModal(this)">
                    <i class="ri-refresh-line me-1"></i> Change Order Status
                </button>

                <button type="button" class="btn btn-warning btn-sm fw-bold text-dark"
                        onclick="openOrderProcessingModal('{{ $order->id }}')">
                    <i class="ri-percent-line me-1"></i> Processing Commission
                </button>

                <button type="button" class="btn btn-outline-light btn-sm fw-bold" onclick="window.print()">
                    <i class="ri-printer-line me-1"></i> Print Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Visual Order Progress Tracker -->
    <div class="od-stepper-card od-no-print">
        @if(in_array($st, ['cancelled', 'returned']))
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="ord-badge ord-st-{{ $st }}" data-status-badge="{{ $order->id }}">
                        {{ $order_statuses[$st] ?? ucfirst($st) }}
                    </span>
                    <span class="text-muted small">This order is currently marked as <strong>{{ $order_statuses[$st] ?? $st }}</strong>. Use "Change Order Status" to update.</span>
                </div>
            </div>
        @else
            <div class="od-stepper">
                @foreach($flowSteps as $stepKey => [$stepLabel, $stepIcon])
                    @php
                        $stepIdx = array_search($stepKey, $flowKeys);
                        $stateClass = '';
                        if ($currentStepIdx !== false) {
                            if ($stepIdx < $currentStepIdx) $stateClass = 'done';
                            elseif ($stepIdx === $currentStepIdx) $stateClass = 'current';
                        }
                    @endphp
                    <div class="od-step {{ $stateClass }}">
                        <span class="od-step-dot"><i class="{{ $stepIcon }}"></i></span>
                        <span class="od-step-label">{{ $stepLabel }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 4 Top Information Cards -->
    <div class="row">
        <!-- 1. Customer Summary -->
        <div class="col-xl-3 col-md-6">
            <div class="od-card">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-user-3-line"></i> Customer Details</h6>
                    @if($order->customer_id)
                        <span class="badge bg-primary-transparent text-primary">Registered</span>
                    @else
                        <span class="badge bg-secondary-transparent text-secondary">Guest</span>
                    @endif
                </div>
                <ul class="od-info-list">
                    <li><span class="k">Name</span><span class="v">{{ $order->name ?? ($order->customer->name ?? 'N/A') }}</span></li>
                    <li><span class="k">Phone</span><span class="v"><a href="tel:{{ $order->phone }}" class="text-decoration-none">{{ $order->phone ?? 'N/A' }}</a></span></li>
                    <li><span class="k">Email</span><span class="v">{{ $order->email ?? ($order->customer->email ?? 'N/A') }}</span></li>
                    <li><span class="k">Customer ID</span><span class="v">{{ $order->customer_id ? '#' . $order->customer_id : 'Guest Checkout' }}</span></li>
                </ul>
            </div>
        </div>

        <!-- 2. Payment & Transaction Details -->
        <div class="col-xl-3 col-md-6">
            <div class="od-card">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-secure-payment-line"></i> Payment Information</h6>
                    <span class="ord-badge pay-st-{{ $pst }}" data-payment-badge="{{ $order->id }}">
                        {{ $payment_statuses[$pst] ?? ucfirst($pst) }}
                    </span>
                </div>
                <ul class="od-info-list">
                    <li><span class="k">Method</span><span class="v text-uppercase">{{ $order->payment_method ?? 'COD' }}</span></li>
                    <li><span class="k">Transaction ID</span><span class="v">{{ $order->transaction_id ?: '—' }}</span></li>
                    <li><span class="k">bKash Number</span><span class="v">{{ $order->bkash_number ?: '—' }}</span></li>
                    <li><span class="k">bKash Ref No.</span><span class="v">{{ $order->bkash_reference_number ?: '—' }}</span></li>
                </ul>
            </div>
        </div>

        <!-- 3. Delivery Charge Details -->
        <div class="col-xl-3 col-md-6">
            <div class="od-card">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-money-dollar-circle-line"></i> Delivery Charge</h6>
                    <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 od-no-print"
                            data-delivery-trigger="{{ $order->id }}"
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-subtotal="{{ (float) $order->subtotal }}"
                            data-discount="{{ (float) $order->discount_amount }}"
                            data-delivery-charge="{{ (float) $order->delivery_charge }}"
                            data-pay-by="{{ $order->delivery_charge_pay_by }}"
                            data-reference="{{ $order->delivery_charge_reference_number }}"
                            onclick="openDeliveryChargeModal(this)">
                        <i class="ri-pencil-line"></i> Edit
                    </button>
                </div>
                <ul class="od-info-list">
                    <li>
                        <span class="k">Delivery Charge</span>
                        <span class="v text-success">BDT <span data-delivery-text="{{ $order->id }}">{{ number_format((float) $order->delivery_charge, 2) }}</span></span>
                    </li>
                    <li>
                        <span class="k">Paid By</span>
                        <span class="v" data-delivery-payby-text="{{ $order->id }}">
                            {{ !empty($order->delivery_charge_pay_by) ? ($delivery_pay_by[$order->delivery_charge_pay_by] ?? ucfirst($order->delivery_charge_pay_by)) : 'Not Paid Yet' }}
                        </span>
                    </li>
                    <li>
                        <span class="k">Charge Ref No.</span>
                        <span class="v" data-delivery-ref-text="{{ $order->id }}">{{ $order->delivery_charge_reference_number ?: '—' }}</span>
                    </li>
                    <li>
                        <span class="k">Currency</span>
                        <span class="v">{{ $order->currency ?? 'BDT' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 4. Courier & Shipping Tracking -->
        <div class="col-xl-3 col-md-6">
            <div class="od-card">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-truck-line"></i> Courier &amp; Tracking</h6>
                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 od-no-print"
                            data-status-trigger="{{ $order->id }}"
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-order-status="{{ $st }}"
                            data-payment-status="{{ $pst }}"
                            data-shipping-method="{{ $order->shipping_method }}"
                            data-tracking-number="{{ $order->tracking_number }}"
                            data-admin-note="{{ $order->admin_note }}"
                            onclick="openOrderStatusModal(this)">
                        <i class="ri-pencil-line"></i> Update
                    </button>
                </div>
                <ul class="od-info-list">
                    <li>
                        <span class="k">Courier Service</span>
                        <span class="v" data-shipping-text="{{ $order->id }}">
                            {{ !empty($order->shipping_method) ? ($shipping_methods[$order->shipping_method] ?? ucfirst($order->shipping_method)) : 'Not Assigned' }}
                        </span>
                    </li>
                    <li>
                        <span class="k">Tracking Number</span>
                        <span class="v" data-tracking-text="{{ $order->id }}">{{ $order->tracking_number ?: '—' }}</span>
                    </li>
                    <li>
                        <span class="k">Last Updated</span>
                        <span class="v">{{ $order->updated_at ? $order->updated_at->format('d M Y, h:i A') : '—' }}</span>
                    </li>
                    <li>
                        <span class="k">Order Status</span>
                        <span class="v">
                            <span class="ord-badge ord-st-{{ $st }}" data-status-badge="{{ $order->id }}">
                                {{ $order_statuses[$st] ?? ucfirst($st) }}
                            </span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Row: Ordered Products (Left) + Addresses & Notes (Right) -->
    <div class="row">
        <!-- Left 8 Cols: Ordered Items & Totals -->
        <div class="col-lg-8">
            <div class="od-card">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-shopping-cart-2-line"></i> Ordered Items ({{ $order->orderProducts->count() }})</h6>
                    <span class="badge bg-light text-dark">Total Qty: {{ $totalQty }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered od-prod-table mb-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Attributes</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderProducts as $idx => $item)
                                @php
                                    $attrs = [];
                                    if (!empty($item->attributes)) {
                                        $decoded = is_string($item->attributes) ? json_decode($item->attributes, true) : (array) $item->attributes;
                                        if (is_array($decoded)) {
                                            $attrs = array_filter($decoded);
                                        }
                                    }
                                    $imgSrc = !empty($item->product_image)
                                        ? (str_starts_with($item->product_image, 'http') ? $item->product_image : asset($item->product_image))
                                        : (!empty($item->product->image) ? asset($item->product->image) : asset('bdmegashop/dist/images/product/05.jpg'));
                                    $prodTitle = $item->product_name ?: ($item->product->title ?? 'Product #' . $item->product_id);
                                    $unitPrice = (float) ($item->unit_price ?? $item->price ?? 0);
                                    $origPrice = (float) ($item->original_price ?? 0);
                                    $lineTotal = (float) ($item->line_total ?? ($unitPrice * (int) $item->quantity));
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $imgSrc }}" alt="{{ $prodTitle }}" class="od-prod-thumb">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $prodTitle }}</div>
                                                @if($item->product_id)
                                                    <div class="small od-no-print">
                                                        <a href="{{ url('create-product-details/' . $item->product_id) }}" class="text-primary me-2">Admin View</a>
                                                        <a href="{{ url('details/' . ($item->product->slug ?? $item->product_id)) }}" target="_blank" class="text-muted">Store View ↗</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $item->sku ?: ($item->product->product_code ?? '—') }}</code></td>
                                    <td>
                                        @if(!empty($attrs))
                                            @foreach($attrs as $k => $v)
                                                <span class="od-attr-pill">{{ ucfirst($k) }}: {{ $v }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Standard</span>
                                        @endif
                                    </td>
                                    <td class="text-end" style="white-space: nowrap;">
                                        <strong>BDT {{ number_format($unitPrice, 2) }}</strong>
                                        @if($origPrice > $unitPrice)
                                            <br><del class="text-muted small">BDT {{ number_format($origPrice, 2) }}</del>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold" style="white-space: nowrap;">BDT {{ number_format($lineTotal, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No product rows found for this order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Order Totals Breakdown -->
                <div class="od-totals-box">
                    <div class="od-totals-row">
                        <span>Subtotal</span>
                        <strong>BDT {{ number_format((float) $order->subtotal, 2) }}</strong>
                    </div>
                    <div class="od-totals-row">
                        <span>Discount</span>
                        <strong>- BDT {{ number_format((float) $order->discount_amount, 2) }}</strong>
                    </div>
                    <div class="od-totals-row">
                        <span>
                            Delivery Charge
                            <a href="javascript:void(0)" class="ms-1 text-success od-no-print"
                               title="Update Delivery Charge"
                               data-delivery-trigger="{{ $order->id }}"
                               data-order-id="{{ $order->id }}"
                               data-order-number="{{ $order->order_number }}"
                               data-subtotal="{{ (float) $order->subtotal }}"
                               data-discount="{{ (float) $order->discount_amount }}"
                               data-delivery-charge="{{ (float) $order->delivery_charge }}"
                               data-pay-by="{{ $order->delivery_charge_pay_by }}"
                               data-reference="{{ $order->delivery_charge_reference_number }}"
                               onclick="openDeliveryChargeModal(this)">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </span>
                        <strong>+ BDT <span data-delivery-text="{{ $order->id }}">{{ number_format((float) $order->delivery_charge, 2) }}</span></strong>
                    </div>
                    <div class="od-totals-row grand">
                        <span>Grand Total</span>
                        <strong>BDT <span data-total-text="{{ $order->id }}">{{ number_format((float) $order->total, 2) }}</span></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 4 Cols: Billing Address, Shipping Address & Notes -->
        <div class="col-lg-4">
            <!-- Order Processing & Benefit Commission -->
            <div class="od-card" style="height: auto;">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-percent-line text-warning"></i> Processing Commission</h6>
                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 od-no-print"
                            onclick="openOrderProcessingModal('{{ $order->id }}')">
                        <i class="ri-pencil-line"></i> Calculate / Edit
                    </button>
                </div>
                <ul class="od-info-list">
                    <li>
                        <span class="k">Order Processed By</span>
                        <span class="v" data-od-proc-name>
                            {{ $order->processor ? $order->processor->name : ($order->order_processing_by ? 'User #' . $order->order_processing_by : 'Unassigned') }}
                        </span>
                    </li>
                    <li>
                        <span class="k">Commission Rate</span>
                        <span class="v">
                            <span class="badge bg-primary-transparent text-primary fw-bold" data-od-proc-pct>{{ (float)($order->processing_percentage ?? 25) }}%</span>
                        </span>
                    </li>
                    <li>
                        <span class="k">Total Product Benefit</span>
                        <span class="v">BDT {{ number_format($processing_data['total_order_benefit'] ?? 0, 2) }}</span>
                    </li>
                    <li>
                        <span class="k">Commission Amount</span>
                        <span class="v text-success fw-bold">
                            BDT <span data-od-proc-amount>{{ number_format((float)($order->processing_amount ?? 0), 2) }}</span>
                        </span>
                    </li>
                    <li>
                        <span class="k">Commission Paid</span>
                        <span class="v">
                            @php $isPaidProc = ($order->is_paid_processing_amount ?? 'No') === 'Yes'; @endphp
                            <span class="badge {{ $isPaidProc ? 'bg-success-transparent text-success' : 'bg-warning-transparent text-warning' }}" data-od-proc-paid>
                                {{ $isPaidProc ? 'Paid' : 'Unpaid' }}
                            </span>
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Billing Address -->
            <div class="od-card" style="height: auto;">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-map-pin-user-line"></i> Billing Address</h6>
                </div>
                <ul class="od-info-list">
                    <li><span class="k">Name</span><span class="v">{{ $addr->name ?? $order->name ?? 'N/A' }}</span></li>
                    <li><span class="k">Phone</span><span class="v">{{ $addr->phone ?? $order->phone ?? 'N/A' }}</span></li>
                    <li><span class="k">Email</span><span class="v">{{ $addr->email ?? $order->email ?? 'N/A' }}</span></li>
                    <li><span class="k">Address</span><span class="v">{{ $addr->address ?? 'N/A' }}</span></li>
                    <li><span class="k">City</span><span class="v">{{ $addr->city ?? '—' }}</span></li>
                    <li><span class="k">State / Area</span><span class="v">{{ $addr->state ?? '—' }}</span></li>
                    <li><span class="k">Zip Code</span><span class="v">{{ $addr->zipcode ?? '—' }}</span></li>
                </ul>
            </div>

            <!-- Shipping Address -->
            <div class="od-card" style="height: auto;">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-map-pin-2-line"></i> Shipping Address</h6>
                    @if(($addr->same_as ?? 'yes') === 'yes')
                        <span class="badge bg-success-transparent text-success">Same as Billing</span>
                    @endif
                </div>
                <ul class="od-info-list">
                    <li><span class="k">Recipient</span><span class="v">{{ $addr->name ?? $order->name ?? 'N/A' }}</span></li>
                    <li><span class="k">Phone</span><span class="v">{{ $addr->phone ?? $order->phone ?? 'N/A' }}</span></li>
                    <li><span class="k">Shipping Address</span><span class="v">{{ $addr->shipping_address ?: ($addr->address ?? 'N/A') }}</span></li>
                    <li><span class="k">City</span><span class="v">{{ $addr->shipping_city ?: ($addr->city ?? '—') }}</span></li>
                    <li><span class="k">State / Area</span><span class="v">{{ $addr->shipping_state ?: ($addr->state ?? '—') }}</span></li>
                    <li><span class="k">Zip Code</span><span class="v">{{ $addr->shipping_zipcode ?: ($addr->zipcode ?? '—') }}</span></li>
                </ul>
            </div>

            <!-- Customer & Admin Notes -->
            <div class="od-card" style="height: auto;">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-sticky-note-line"></i> Order Notes</h6>
                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 od-no-print"
                            data-status-trigger="{{ $order->id }}"
                            data-order-id="{{ $order->id }}"
                            data-order-number="{{ $order->order_number }}"
                            data-order-status="{{ $st }}"
                            data-payment-status="{{ $pst }}"
                            data-shipping-method="{{ $order->shipping_method }}"
                            data-tracking-number="{{ $order->tracking_number }}"
                            data-admin-note="{{ $order->admin_note }}"
                            onclick="openOrderStatusModal(this)">
                        <i class="ri-pencil-line"></i> Edit Note
                    </button>
                </div>
                <div class="mb-3">
                    <div class="small fw-bold text-muted mb-1">Customer Note:</div>
                    <div class="p-2 rounded bg-light small text-dark">
                        {{ $order->note ?: 'No special instructions from customer.' }}
                    </div>
                </div>
                <div>
                    <div class="small fw-bold text-muted mb-1">Admin Internal Note:</div>
                    <div class="p-2 rounded bg-light small text-dark" data-admin-note-text="{{ $order->id }}">
                        {{ $order->admin_note ?: 'No admin note yet.' }}
                    </div>
                </div>
            </div>

            <!-- Order Change Notifications & Activity History -->
            <div class="od-card" style="height: auto;">
                <div class="od-card-head">
                    <h6 class="od-card-title"><i class="ri-notification-3-line text-primary"></i> Order Change Notifications</h6>
                    <span class="badge bg-primary-transparent text-primary" data-od-notif-count>{{ count($notifications ?? []) }} Updates</span>
                </div>
                <div class="od-notif-list" style="max-height: 380px; overflow-y: auto;">
                    @forelse($notifications ?? [] as $notif)
                        <div class="p-2 border rounded mb-2 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-primary-transparent text-primary fw-bold" style="font-size: 11px;">{{ $notif->title }}</span>
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="ri-time-line me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                                </small>
                            </div>
                            <div class="small text-dark mb-1">{{ $notif->description }}</div>
                            <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 11px;">
                                <i class="ri-user-follow-line text-primary"></i>
                                <span>Changed by: <strong>{{ $notif->creator_name ?? ($notif->creator->name ?? 'Admin') }}</strong></span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-notification-off-line fs-3 text-muted"></i>
                            <div class="mt-2 small">No change notifications recorded for this order yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.bdmegashop.order.partials.order_modals')
@endsection

