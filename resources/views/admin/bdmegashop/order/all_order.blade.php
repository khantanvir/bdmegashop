@extends('adminpanel')
@section('admincontent')
<style>
    .ord-stat-card {
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none !important;
        transition: all 0.2s ease;
        height: 100%;
    }
    .ord-stat-card:hover, .ord-stat-card.active { border-color: #4361ee; box-shadow: 0 6px 18px rgba(67, 97, 238, 0.12); transform: translateY(-2px); }
    .ord-stat-card .val { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1; }
    .ord-stat-card .lbl { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 4px; }
    .ord-stat-card .ico { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; }

    .ord-filter-card { background: #fff; border: 1px solid #eef2f6; border-radius: 14px; padding: 16px; margin-bottom: 18px; }
    .ord-filter-card .form-control, .ord-filter-card .form-select { border-radius: 10px; min-height: 40px; font-size: 13.5px; }

    .ord-table th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.4px; color: #64748b; font-weight: 700; background: #f8fafc; white-space: nowrap; }
    .ord-table td { vertical-align: middle; font-size: 13.5px; }
    .ord-num { font-weight: 800; color: #4361ee; text-decoration: none; }
    .ord-num:hover { text-decoration: underline; }
    .ord-customer strong { display: block; color: #0f172a; }
    .ord-customer small { color: #64748b; }
    .ord-money { font-weight: 700; color: #0f172a; white-space: nowrap; }
    .ord-edit-chip {
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 8px;
        padding: 3px 8px;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .ord-edit-chip:hover { border-color: #16a34a; color: #16a34a; background: #f0fdf4; }
    .ord-status-btn { border: 0; background: transparent; padding: 0; cursor: pointer; }
    .ord-status-btn:hover .ord-badge { filter: brightness(0.95); box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.25); }
    .ord-action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
</style>

<div class="">
    <div class="">
        <div class="secondary-nav">
            <div class="breadcrumbs-container" data-page-heading="Analytics">
                <header class="header navbar navbar-expand-sm">
                    <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom"></a>
                    <div class="d-flex breadcrumb-content">
                        <div class="page-header">
                            <div class="page-title"></div>
                            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Order</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">All Orders</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </header>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        {{-- Status summary cards --}}
        @php
            $statCards = [
                ''                  => ['All Orders', $total_orders, 'ri-shopping-bag-3-line', '#4361ee', '#eef2ff'],
                'pending'           => ['Pending', $status_counts['pending'] ?? 0, 'ri-time-line', '#b45309', '#fef3c7'],
                'confirmed'         => ['Confirmed', $status_counts['confirmed'] ?? 0, 'ri-checkbox-circle-line', '#4338ca', '#e0e7ff'],
                'handed_to_courier' => ['With Courier', $status_counts['handed_to_courier'] ?? 0, 'ri-truck-line', '#7e22ce', '#f3e8ff'],
                'on_the_way'        => ['On the Way', $status_counts['on_the_way'] ?? 0, 'ri-road-map-line', '#0e7490', '#cffafe'],
                'delivered'         => ['Delivered', $status_counts['delivered'] ?? 0, 'ri-check-double-line', '#15803d', '#dcfce7'],
                'cancelled'         => ['Cancelled', $status_counts['cancelled'] ?? 0, 'ri-close-circle-line', '#b91c1c', '#fee2e2'],
            ];
        @endphp
        <div class="row g-3 my-3">
            @foreach($statCards as $key => [$label, $count, $icon, $color, $bg])
                <div class="col-xl col-lg-3 col-md-4 col-6">
                    <a href="{{ request()->fullUrlWithQuery(['order_status' => $key ?: null, 'page' => null]) }}"
                       class="ord-stat-card {{ (string) request('order_status') === (string) $key ? 'active' : '' }}">
                        <div>
                            <div class="val">{{ $count }}</div>
                            <div class="lbl">{{ $label }}</div>
                        </div>
                        <span class="ico" style="background: {{ $bg }}; color: {{ $color }};"><i class="{{ $icon }}"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ url('order-list') }}" class="ord-filter-card">
            <div class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Order #, name, phone, email">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold mb-1">Order Status</label>
                    <select name="order_status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($order_statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('order_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold mb-1">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        @foreach($payment_statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('payment_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-lg-1 col-md-6 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100" title="Filter"><i class="ri-filter-3-line"></i></button>
                    <a href="{{ url('order-list') }}" class="btn btn-light w-100" title="Reset"><i class="ri-refresh-line"></i></a>
                </div>
            </div>
        </form>

        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table class="table table-bordered table-hover ord-table mb-0">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th class="text-center">Items</th>
                            <th>Payment</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Delivery Charge</th>
                            <th class="text-end">Total</th>
                            <th>Order Status</th>
                            <th>Processing Commission</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $st = $order->order_status ?: 'pending';
                                $pst = $order->payment_status ?: 'unpaid';
                                $procUser = $order->processor ? $order->processor->name : ($order->order_processing_by ? 'User #' . $order->order_processing_by : 'Unassigned');
                                $procPct = (float)($order->processing_percentage !== null && $order->processing_percentage !== '' ? $order->processing_percentage : 25);
                                $procAmt = (float)($order->processing_amount ?? 0);
                                $isPaidProc = ($order->is_paid_processing_amount ?? 'No') === 'Yes';
                            @endphp
                            <tr>
                                <td>{{ $orders->firstItem() + $loop->index }}</td>
                                <td>
                                    <a href="{{ url('order-details/' . $order->id) }}" class="ord-num">#{{ $order->order_number ?? $order->id }}</a>
                                </td>
                                <td style="white-space: nowrap;">
                                    {{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}
                                    <br><small class="text-muted">{{ $order->created_at ? $order->created_at->format('h:i A') : '' }}</small>
                                </td>
                                <td class="ord-customer">
                                    <strong>{{ $order->name ?? 'Guest' }}</strong>
                                    <small>{{ $order->phone }}</small>
                                </td>
                                <td class="text-center">{{ $order->order_products_count }}</td>
                                <td>
                                    <span class="text-uppercase fw-bold small">{{ $order->payment_method ?? '-' }}</span><br>
                                    <span class="ord-badge pay-st-{{ $pst }} mt-1" data-payment-badge="{{ $order->id }}">
                                        {{ $payment_statuses[$pst] ?? ucfirst($pst) }}
                                    </span>
                                </td>
                                <td class="text-end ord-money">BDT {{ number_format((float) $order->subtotal, 2) }}</td>
                                <td class="text-end">
                                    <button type="button" class="ord-edit-chip"
                                            title="Update delivery charge"
                                            data-delivery-trigger="{{ $order->id }}"
                                            data-order-id="{{ $order->id }}"
                                            data-order-number="{{ $order->order_number }}"
                                            data-subtotal="{{ (float) $order->subtotal }}"
                                            data-discount="{{ (float) $order->discount_amount }}"
                                            data-delivery-charge="{{ (float) $order->delivery_charge }}"
                                            data-pay-by="{{ $order->delivery_charge_pay_by }}"
                                            data-reference="{{ $order->delivery_charge_reference_number }}"
                                            onclick="openDeliveryChargeModal(this)">
                                        BDT <span data-delivery-text="{{ $order->id }}">{{ number_format((float) $order->delivery_charge, 2) }}</span>
                                        <i class="ri-pencil-line ms-1"></i>
                                    </button>
                                </td>
                                <td class="text-end ord-money" style="color: #4361ee;">BDT <span data-total-text="{{ $order->id }}">{{ number_format((float) $order->total, 2) }}</span></td>
                                <td>
                                    <button type="button" class="ord-status-btn" title="Change order status"
                                            data-status-trigger="{{ $order->id }}"
                                            data-order-id="{{ $order->id }}"
                                            data-order-number="{{ $order->order_number }}"
                                            data-order-status="{{ $st }}"
                                            data-payment-status="{{ $pst }}"
                                            data-shipping-method="{{ $order->shipping_method }}"
                                            data-tracking-number="{{ $order->tracking_number }}"
                                            data-admin-note="{{ $order->admin_note }}"
                                            onclick="openOrderStatusModal(this)">
                                        <span class="ord-badge ord-st-{{ $st }}" data-status-badge="{{ $order->id }}">
                                            {{ $order_statuses[$st] ?? ucfirst($st) }}
                                        </span>
                                        <i class="ri-arrow-down-s-line text-muted"></i>
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="ord-edit-chip w-100 text-start"
                                            title="Calculate / Update Order Processing Commission"
                                            data-proc-trigger="{{ $order->id }}"
                                            data-order-id="{{ $order->id }}"
                                            onclick="openOrderProcessingModal('{{ $order->id }}')">
                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                            <span class="badge bg-primary-transparent text-primary fw-bold" data-proc-pct="{{ $order->id }}">{{ $procPct }}%</span>
                                            <strong style="color: #059669; font-size: 13px;" data-proc-amount="{{ $order->id }}">BDT {{ number_format($procAmt, 2) }}</strong>
                                            <i class="ri-pencil-line text-muted" style="font-size: 13px;"></i>
                                        </div>
                                        <div class="small text-muted d-flex align-items-center justify-content-between gap-1 mt-1" style="font-size: 11px;">
                                            <span class="text-truncate" style="max-width: 95px;" data-proc-name="{{ $order->id }}" title="{{ $procUser }}">
                                                <i class="ri-user-line me-1"></i>{{ $procUser }}
                                            </span>
                                            <span class="badge {{ $isPaidProc ? 'bg-success' : 'bg-secondary' }}" style="font-size: 9.5px; padding: 2px 5px;" data-proc-paid="{{ $order->id }}">
                                                {{ $isPaidProc ? 'Paid' : 'Unpaid' }}
                                            </span>
                                        </div>
                                    </button>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <a href="{{ url('order-details/' . $order->id) }}" class="btn btn-sm btn-outline-primary ord-action-btn" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-5">
                                    <i class="ri-inbox-line" style="font-size: 32px;"></i>
                                    <div class="mt-2">No orders found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center;" class="pagination-custom_solid mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

@include('admin.bdmegashop.order.partials.order_modals')
@endsection
