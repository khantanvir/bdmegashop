@extends('bdpanel')
@section('content')
@php
    $totalOrdersCount = $orders->count();
    $pendingOrdersCount = $orders->where('order_status', 'pending')->count();
    $completedOrdersCount = $orders->where('order_status', 'completed')->count();
    $wishlistCount = $wishlist_products->count();
    $initialTab = request('tab', $active_tab ?? 'dashboard');
    if (!in_array($initialTab, ['dashboard', 'orders', 'wishlist', 'profile', 'password'])) {
        $initialTab = 'dashboard';
    }
@endphp

<style>
    .bms-dash-wrap {
        padding: 40px 0 80px;
        background-color: #f7faf8;
        min-height: 650px;
    }

    /* Top Navigation Tabs */
    .bms-dash-nav-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 16px;
        padding: 8px 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 18px rgba(6, 36, 23, 0.04);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .bms-dash-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #48564f;
        text-decoration: none !important;
        border: 0;
        background: transparent;
        cursor: pointer;
        transition: all 0.22s ease;
    }

    .bms-dash-tab-btn:hover {
        background: #f1f5f2;
        color: #0b7a3d;
    }

    .bms-dash-tab-btn.active {
        background: linear-gradient(135deg, #0b7a3d 0%, #035c2d 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(11, 122, 61, 0.28);
    }

    .bms-dash-tab-badge {
        font-size: 11px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        background: rgba(6, 36, 23, 0.08);
        color: inherit;
    }

    .bms-dash-tab-btn.active .bms-dash-tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .bms-dash-tab-btn.logout-btn {
        margin-left: auto;
        color: #d81e23;
    }

    .bms-dash-tab-btn.logout-btn:hover {
        background: #fef2f2;
        color: #b5101a;
    }

    /* Stat Cards */
    .bms-stat-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 16px;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(6, 36, 23, 0.03);
        transition: all 0.25s ease;
    }

    .bms-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(6, 36, 23, 0.07);
        border-color: #cdd7d1;
    }

    .bms-stat-val {
        font-size: 28px;
        font-weight: 800;
        color: #062417;
        line-height: 1;
        margin-bottom: 4px;
    }

    .bms-stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #65736c;
        margin: 0;
    }

    .bms-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    /* Section Cards */
    .bms-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 18px;
        padding: 26px 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 18px rgba(6, 36, 23, 0.03);
    }

    .bms-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 16px;
        margin-bottom: 22px;
        border-bottom: 1.5px solid #f1f5f2;
    }

    .bms-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #062417;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .bms-card-title i {
        color: #0b7a3d;
        font-size: 16px;
    }

    /* Orders Styles */
    .bms-order-box {
        border: 1px solid #e2e9e4;
        border-radius: 14px;
        margin-bottom: 18px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.25s ease;
    }

    .bms-order-box:hover {
        border-color: #cdd7d1;
        box-shadow: 0 8px 24px rgba(6, 36, 23, 0.06);
    }

    .bms-order-top {
        background: #f7faf8;
        border-bottom: 1px solid #e2e9e4;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .bms-order-meta {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        font-size: 13.5px;
    }

    .bms-order-meta strong {
        color: #062417;
    }

    .bms-order-meta span {
        color: #65736c;
    }

    .bms-status-badge {
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
        padding: 5px 14px;
        border-radius: 50px;
    }

    .bms-status-pending {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .bms-status-processing {
        background: #e6f4ec;
        color: #035c2d;
        border: 1px solid #bfe3cb;
    }

    .bms-status-completed {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .bms-status-cancelled {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .bms-order-items {
        padding: 18px 20px;
    }

    .bms-order-item-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f2;
    }

    .bms-order-item-row:last-child {
        border-bottom: 0;
    }

    .bms-order-item-thumb {
        width: 54px;
        height: 54px;
        border-radius: 10px;
        border: 1px solid #e2e9e4;
        object-fit: cover;
        background: #f7faf8;
        flex-shrink: 0;
    }

    .bms-order-item-details {
        flex: 1;
        min-width: 0;
    }

    .bms-order-item-title {
        font-size: 14px;
        font-weight: 700;
        color: #062417;
        text-decoration: none !important;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .bms-order-item-title:hover {
        color: #0b7a3d;
    }

    .bms-order-item-sub {
        font-size: 12px;
        color: #65736c;
        margin-top: 2px;
    }

    .bms-order-footer {
        background: #f7faf8;
        border-top: 1px solid #e2e9e4;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 13.5px;
    }

    /* Wishlist Product Cards */
    .bms-wishlist-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.28s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .bms-wishlist-card:hover {
        transform: translateY(-5px);
        border-color: #cdd7d1;
        box-shadow: 0 14px 30px rgba(6, 36, 23, 0.08);
    }

    .bms-wishlist-thumb {
        position: relative;
        height: 200px;
        background: #f7faf8;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .bms-wishlist-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .bms-wishlist-card:hover .bms-wishlist-thumb img {
        transform: scale(1.06);
    }

    .bms-wishlist-remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e2e9e4;
        color: #d81e23;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        z-index: 3;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .bms-wishlist-remove-btn:hover {
        background: #d81e23;
        color: #ffffff;
        border-color: #d81e23;
        transform: scale(1.1);
    }

    .bms-wishlist-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .bms-wishlist-cat {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #0b7a3d;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .bms-wishlist-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #062417;
        text-decoration: none !important;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .bms-wishlist-title:hover {
        color: #0b7a3d;
    }

    .bms-wishlist-price-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-top: auto;
        margin-bottom: 14px;
    }

    .bms-wishlist-price {
        font-size: 17px;
        font-weight: 800;
        color: #062417;
    }

    .bms-wishlist-old {
        font-size: 12.5px;
        color: #97a39c;
        text-decoration: line-through;
        margin-left: 6px;
    }

    .bms-wishlist-cart-btn {
        width: 100%;
        padding: 9px 14px;
        border-radius: 10px;
        border: 1.5px solid #0b7a3d;
        background: rgba(11, 122, 61, 0.06);
        color: #0b7a3d;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        transition: all 0.22s ease;
    }

    .bms-wishlist-cart-btn:hover {
        background: #0b7a3d;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(11, 122, 61, 0.25);
    }

    /* Empty States */
    .bms-empty-box {
        text-align: center;
        padding: 55px 20px;
    }

    .bms-empty-box-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 16px;
    }

    .bms-empty-box-icon.heart {
        background: #fef2f2;
        color: #d81e23;
    }

    .bms-empty-box-icon.bag {
        background: rgba(11, 122, 61, 0.08);
        color: #0b7a3d;
    }

    /* Tab content toggle */
    .bms-tab-pane {
        display: none;
    }

    .bms-tab-pane.active {
        display: block;
        animation: bmsFadeIn 0.25s ease;
    }

    @keyframes bmsFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- Hero / Breadcrumb Bar -->
<section class="breadcrumb-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home mr-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('customer-dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item active" id="bmsBreadcrumbActive">Dashboard</li>
                    </ol>
                </nav>
                <h5>Welcome, {{ $customer->name ?? $customer->email }}</h5>
            </div>
        </div>
    </div>
</section>

<section class="bms-dash-wrap">
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Top Navigation Bar -->
        <div class="bms-dash-nav-card">
            <button type="button" class="bms-dash-tab-btn {{ $initialTab === 'dashboard' ? 'active' : '' }}" onclick="switchDashTab('dashboard', this)">
                <i class="fas fa-tachometer-alt"></i> My Dashboard
            </button>
            <button type="button" class="bms-dash-tab-btn {{ $initialTab === 'orders' ? 'active' : '' }}" onclick="switchDashTab('orders', this)">
                <i class="fas fa-shopping-bag"></i> My Orders
                <span class="bms-dash-tab-badge" id="bmsOrderBadgeCount">{{ $totalOrdersCount }}</span>
            </button>
            <button type="button" class="bms-dash-tab-btn {{ $initialTab === 'wishlist' ? 'active' : '' }}" onclick="switchDashTab('wishlist', this)">
                <i class="fas fa-heart"></i> My Wishlist
                <span class="bms-dash-tab-badge" id="bmsWishlistTabBadgeCount">{{ $wishlistCount }}</span>
            </button>
            <button type="button" class="bms-dash-tab-btn {{ $initialTab === 'profile' ? 'active' : '' }}" onclick="switchDashTab('profile', this)">
                <i class="fas fa-user-cog"></i> Profile Settings
            </button>
            <button type="button" class="bms-dash-tab-btn {{ $initialTab === 'password' ? 'active' : '' }}" onclick="switchDashTab('password', this)">
                <i class="fas fa-lock"></i> Change Password
            </button>
            <a href="javascript:void(0)" onclick="openCartSidebar()" class="bms-dash-tab-btn">
                <i class="fas fa-shopping-cart"></i> Cart Sidebar
            </a>
            <a href="{{ url('customer-logout') }}" class="bms-dash-tab-btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- TAB 1: DASHBOARD OVERVIEW -->
        <div class="bms-tab-pane {{ $initialTab === 'dashboard' ? 'active' : '' }}" id="tab-dashboard">
            <!-- Stat Cards Row -->
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-stat-card">
                        <div>
                            <div class="bms-stat-val">{{ $totalOrdersCount }}</div>
                            <p class="bms-stat-label">Total Orders Placed</p>
                        </div>
                        <div class="bms-stat-icon" style="background: rgba(11, 122, 61, 0.1); color: #0b7a3d;">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-stat-card">
                        <div>
                            <div class="bms-stat-val" style="color: #d97706;">{{ $pendingOrdersCount }}</div>
                            <p class="bms-stat-label">Pending Orders</p>
                        </div>
                        <div class="bms-stat-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-stat-card">
                        <div>
                            <div class="bms-stat-val" style="color: #16a330;">{{ $completedOrdersCount }}</div>
                            <p class="bms-stat-label">Delivered Orders</p>
                        </div>
                        <div class="bms-stat-icon" style="background: #dcfce7; color: #16a330;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-stat-card">
                        <div>
                            <div class="bms-stat-val" style="color: #d81e23;" id="bmsStatWishlistCount">{{ $wishlistCount }}</div>
                            <p class="bms-stat-label">Wishlist Products</p>
                        </div>
                        <div class="bms-stat-icon" style="background: #fef2f2; color: #d81e23;">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Snippet -->
            <div class="bms-card">
                <div class="bms-card-header">
                    <h5 class="bms-card-title"><i class="fas fa-history"></i> Recent Orders</h5>
                    @if($totalOrdersCount > 0)
                        <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 700;"
                                onclick="switchDashTab('orders', document.querySelectorAll('.bms-dash-tab-btn')[1])">
                            View All ({{ $totalOrdersCount }}) &rarr;
                        </button>
                    @endif
                </div>

                @if($orders->count() > 0)
                    @foreach($orders->take(3) as $ord)
                        <div class="bms-order-box">
                            <div class="bms-order-top">
                                <div class="bms-order-meta">
                                    <span>Order: <strong>#{{ $ord->order_number }}</strong></span>
                                    <span>Date: <strong>{{ $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : '' }}</strong></span>
                                    <span>Payment: <strong class="text-uppercase">{{ $ord->payment_method }}</strong></span>
                                </div>
                                <div>
                                    @php $st = strtolower($ord->order_status ?? 'pending'); @endphp
                                    <span class="bms-status-badge bms-status-{{ in_array($st, ['pending','processing','completed','cancelled']) ? $st : 'pending' }}">
                                        {{ $ord->order_status ?? 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            <div class="bms-order-items">
                                @forelse($ord->orderProducts as $item)
                                    <div class="bms-order-item-row">
                                        <img src="{{ !empty($item->product->image) ? asset($item->product->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                             class="bms-order-item-thumb" alt="Product Image">
                                        <div class="bms-order-item-details">
                                            <a href="{{ $item->product ? url('details/' . ($item->product->slug ?? $item->product->id)) : 'javascript:void(0)' }}" class="bms-order-item-title">
                                                {{ $item->product->title ?? 'Product' }}
                                            </a>
                                            <div class="bms-order-item-sub">
                                                Qty: <strong>{{ $item->quantity }}</strong> &bull; BDT {{ number_format((float)$item->price, 2) }}
                                                @if(!empty($item->size)) &bull; Size: {{ $item->size }} @endif
                                                @if(!empty($item->color)) &bull; Color: {{ $item->color }} @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <strong style="color: #062417; font-size: 14px;">BDT {{ number_format((float)$item->total, 2) }}</strong>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Order items details recorded.</p>
                                @endforelse
                            </div>
                            <div class="bms-order-footer">
                                <div>
                                    <span class="text-muted">Delivery Address:</span>
                                    <strong>{{ $ord->orderAddress->address ?? $customer->address ?? 'N/A' }}</strong>
                                </div>
                                <div>
                                    <span class="text-muted">Total Payable:</span>
                                    <strong style="color: #0b7a3d; font-size: 16px;">BDT {{ number_format((float)$ord->total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bms-empty-box">
                        <div class="bms-empty-box-icon bag">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 style="font-weight: 800; color: #062417;">No Orders Yet</h5>
                        <p class="text-muted mb-3">You haven't placed any orders yet. Discover our top deals and start shopping!</p>
                        <a href="{{ url('products') }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 24px;">
                            Browse Products
                        </a>
                    </div>
                @endif
            </div>

            <!-- Quick Wishlist Preview (If items exist) -->
            @if($wishlist_products->count() > 0)
            <div class="bms-card">
                <div class="bms-card-header">
                    <h5 class="bms-card-title"><i class="fas fa-heart text-danger"></i> Saved in My Wishlist ({{ $wishlistCount }})</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 700;"
                            onclick="switchDashTab('wishlist', document.querySelectorAll('.bms-dash-tab-btn')[2])">
                        View Full Wishlist &rarr;
                    </button>
                </div>
                <div class="row">
                    @foreach($wishlist_products->take(4) as $p)
                        @php
                            $pPrice = (float)($p->selling_price ?? $p->price ?? 0);
                            $pReg = (float)($p->price ?? 0);
                        @endphp
                        <div class="col-lg-3 col-md-6 mb-3 wishlist-grid-item-{{ $p->id }}">
                            <div class="bms-wishlist-card">
                                <div class="bms-wishlist-thumb">
                                    <button type="button" class="bms-wishlist-remove-btn" onclick="removeWishlistItem({{ $p->id }}, this)" title="Remove from Wishlist">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <a href="{{ url('details/' . ($p->slug ?? $p->id)) }}">
                                        <img src="{{ !empty($p->image) ? asset($p->image) : asset('bdmegashop/dist/images/product/05.jpg') }}" alt="{{ $p->title }}">
                                    </a>
                                </div>
                                <div class="bms-wishlist-body">
                                    <span class="bms-wishlist-cat">{{ $p->category->title ?? 'Collection' }}</span>
                                    <a href="{{ url('details/' . ($p->slug ?? $p->id)) }}" class="bms-wishlist-title">{{ $p->title }}</a>
                                    <div class="bms-wishlist-price-row">
                                        <div>
                                            <span class="bms-wishlist-price">BDT {{ number_format($pPrice, 0) }}</span>
                                            @if($pReg > $pPrice)
                                                <span class="bms-wishlist-old">BDT {{ number_format($pReg, 0) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="bms-wishlist-cart-btn" onclick="addToCart({ product_id: {{ $p->id }}, quantity: 1 })">
                                        <i class="fas fa-shopping-bag"></i> Add to Bag
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- TAB 2: MY ORDERS (FULL) -->
        <div class="bms-tab-pane {{ $initialTab === 'orders' ? 'active' : '' }}" id="tab-orders">
            <div class="bms-card">
                <div class="bms-card-header">
                    <h5 class="bms-card-title"><i class="fas fa-shopping-bag"></i> My Orders History</h5>
                    <span class="badge badge-primary" style="padding: 6px 12px; border-radius: 50px;">{{ $totalOrdersCount }} Orders Total</span>
                </div>

                @if($orders->count() > 0)
                    @foreach($orders as $ord)
                        <div class="bms-order-box">
                            <div class="bms-order-top">
                                <div class="bms-order-meta">
                                    <span>Order #: <strong>{{ $ord->order_number }}</strong></span>
                                    <span>Placed on: <strong>{{ $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : '' }}</strong></span>
                                    <span>Payment Method: <strong class="text-uppercase">{{ $ord->payment_method }}</strong></span>
                                </div>
                                <div>
                                    @php $st = strtolower($ord->order_status ?? 'pending'); @endphp
                                    <span class="bms-status-badge bms-status-{{ in_array($st, ['pending','processing','completed','cancelled']) ? $st : 'pending' }}">
                                        {{ $ord->order_status ?? 'Pending' }}
                                    </span>
                                </div>
                            </div>
                            <div class="bms-order-items">
                                @forelse($ord->orderProducts as $item)
                                    <div class="bms-order-item-row">
                                        <img src="{{ !empty($item->product->image) ? asset($item->product->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                             class="bms-order-item-thumb" alt="Product Image">
                                        <div class="bms-order-item-details">
                                            <a href="{{ $item->product ? url('details/' . ($item->product->slug ?? $item->product->id)) : 'javascript:void(0)' }}" class="bms-order-item-title">
                                                {{ $item->product->title ?? 'Product' }}
                                            </a>
                                            <div class="bms-order-item-sub">
                                                Quantity: <strong>{{ $item->quantity }}</strong> &bull; Unit Price: BDT {{ number_format((float)$item->price, 2) }}
                                                @if(!empty($item->size)) &bull; Size: <strong>{{ $item->size }}</strong> @endif
                                                @if(!empty($item->color)) &bull; Color: <strong>{{ $item->color }}</strong> @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <strong style="color: #062417; font-size: 15px;">BDT {{ number_format((float)$item->total, 2) }}</strong>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Order item records saved.</p>
                                @endforelse
                            </div>
                            <div class="bms-order-footer">
                                <div>
                                    <span class="text-muted">Delivery Address:</span>
                                    <strong>{{ $ord->orderAddress->address ?? $customer->address ?? 'N/A' }}</strong>
                                    @if(!empty($ord->orderAddress->city)) &bull; {{ $ord->orderAddress->city }} @endif
                                </div>
                                <div class="d-flex align-items-center" style="gap: 16px;">
                                    <div>
                                        <span class="text-muted">Shipping:</span>
                                        <strong>BDT {{ number_format((float)$ord->delivery_charge, 2) }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-muted">Total Paid / Payable:</span>
                                        <strong style="color: #0b7a3d; font-size: 16px;">BDT {{ number_format((float)$ord->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bms-empty-box">
                        <div class="bms-empty-box-icon bag">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 style="font-weight: 800; color: #062417;">No Orders Found</h5>
                        <p class="text-muted mb-3">You have not placed any orders yet. Visit our shop catalog to find exciting offers!</p>
                        <a href="{{ url('products') }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 24px;">
                            Start Shopping Now
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB 3: MY WISHLIST (FULL) -->
        <div class="bms-tab-pane {{ $initialTab === 'wishlist' ? 'active' : '' }}" id="tab-wishlist">
            <div class="bms-card">
                <div class="bms-card-header">
                    <h5 class="bms-card-title"><i class="fas fa-heart text-danger"></i> My Liked Products (Wishlist)</h5>
                    <span class="badge badge-danger" id="bmsWishlistTabHeaderBadge" style="padding: 6px 12px; border-radius: 50px;">
                        {{ $wishlistCount }} Items
                    </span>
                </div>

                <div class="row" id="bmsWishlistContainer">
                    @forelse($wishlist_products as $p)
                        @php
                            $pPrice = (float)($p->selling_price ?? $p->price ?? 0);
                            $pReg = (float)($p->price ?? 0);
                            $discPct = ($pReg > $pPrice && $pReg > 0)
                                ? round((($pReg - $pPrice) / $pReg) * 100)
                                : (int) ($p->discount ?? 0);
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 wishlist-grid-item-{{ $p->id }}">
                            <div class="bms-wishlist-card">
                                <div class="bms-wishlist-thumb">
                                    <button type="button" class="bms-wishlist-remove-btn"
                                            onclick="removeWishlistItem({{ $p->id }}, this)" title="Remove from Wishlist">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @if($discPct > 0)
                                        <span class="badge badge-danger" style="position: absolute; top: 10px; left: 10px; font-weight: 800; border-radius: 50px; padding: 4px 9px;">
                                            -{{ $discPct }}%
                                        </span>
                                    @endif
                                    <a href="{{ url('details/' . ($p->slug ?? $p->id)) }}">
                                        <img src="{{ !empty($p->image) ? asset($p->image) : asset('bdmegashop/dist/images/product/05.jpg') }}" alt="{{ $p->title }}">
                                    </a>
                                </div>
                                <div class="bms-wishlist-body">
                                    <span class="bms-wishlist-cat">{{ $p->category->title ?? 'Collection' }}</span>
                                    <a href="{{ url('details/' . ($p->slug ?? $p->id)) }}" class="bms-wishlist-title">{{ $p->title }}</a>
                                    <div class="bms-wishlist-price-row">
                                        <div>
                                            <span class="bms-wishlist-price">BDT {{ number_format($pPrice, 0) }}</span>
                                            @if($pReg > $pPrice)
                                                <span class="bms-wishlist-old">BDT {{ number_format($pReg, 0) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="bms-wishlist-cart-btn" onclick="addToCart({ product_id: {{ $p->id }}, quantity: 1 })">
                                        <i class="fas fa-shopping-bag"></i> Add to Bag
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12" id="bmsWishlistEmptyNotice">
                            <div class="bms-empty-box">
                                <div class="bms-empty-box-icon heart">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h5 style="font-weight: 800; color: #062417;">Your Wishlist is Empty</h5>
                                <p class="text-muted mb-4">Click the heart icon on any product to save it to your wishlist and revisit it anytime!</p>
                                <a href="{{ url('products') }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 24px;">
                                    <i class="fas fa-search mr-1"></i> Explore Products
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB 4: PROFILE SETTINGS -->
        <div class="bms-tab-pane {{ $initialTab === 'profile' ? 'active' : '' }}" id="tab-profile">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="bms-card">
                        <div class="bms-card-header">
                            <h5 class="bms-card-title"><i class="fas fa-user-edit"></i> Account Information</h5>
                        </div>
                        <form method="POST" action="{{ url('customer-profile-update') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Full Name *</label>
                                <input type="text" name="name" class="form-control" style="border-radius: 10px; height: 44px;"
                                       value="{{ old('name', $customer->name) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Email Address *</label>
                                <input type="email" name="email" class="form-control" style="border-radius: 10px; height: 44px;"
                                       value="{{ old('email', $customer->email) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Phone Number</label>
                                <input type="text" name="phone" class="form-control" style="border-radius: 10px; height: 44px;"
                                       value="{{ old('phone', $customer->phone) }}" placeholder="+8801...">
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">City</label>
                                <input type="text" name="city" class="form-control" style="border-radius: 10px; height: 44px;"
                                       value="{{ old('city', $customer->city) }}" placeholder="e.g. Dhaka">
                            </div>
                            <div class="form-group mb-4">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Delivery Address</label>
                                <textarea name="address" class="form-control" rows="3" style="border-radius: 10px;"
                                          placeholder="House / Road / Area details">{{ old('address', $customer->address) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 28px;">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: CHANGE PASSWORD -->
        <div class="bms-tab-pane {{ $initialTab === 'password' ? 'active' : '' }}" id="tab-password">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="bms-card">
                        <div class="bms-card-header">
                            <h5 class="bms-card-title"><i class="fas fa-key"></i> Change Password</h5>
                        </div>
                        <form method="POST" action="{{ url('customer-change-password') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Current Password *</label>
                                <input type="password" name="current_password" class="form-control" style="border-radius: 10px; height: 44px;" required>
                            </div>
                            <div class="form-group mb-3">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">New Password *</label>
                                <input type="password" name="password" class="form-control" style="border-radius: 10px; height: 44px;" required>
                            </div>
                            <div class="form-group mb-4">
                                <label style="font-weight: 700; color: #2f3d36; font-size: 13.5px;">Confirm New Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" style="border-radius: 10px; height: 44px;" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 28px;">
                                <i class="fas fa-lock mr-1"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function switchDashTab(tabName, btnEl) {
        document.querySelectorAll('.bms-dash-tab-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        if (btnEl) {
            btnEl.classList.add('active');
        }

        document.querySelectorAll('.bms-tab-pane').forEach(function(p) {
            p.classList.remove('active');
        });

        var targetPane = document.getElementById('tab-' + tabName);
        if (targetPane) {
            targetPane.classList.add('active');
        }

        var breadcrumbLabel = document.getElementById('bmsBreadcrumbActive');
        if (breadcrumbLabel) {
            if (tabName === 'orders') breadcrumbLabel.textContent = 'My Orders';
            else if (tabName === 'wishlist') breadcrumbLabel.textContent = 'My Wishlist';
            else if (tabName === 'profile') breadcrumbLabel.textContent = 'Profile Settings';
            else if (tabName === 'password') breadcrumbLabel.textContent = 'Change Password';
            else breadcrumbLabel.textContent = 'Dashboard';
        }

        if (history.pushState) {
            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
            window.history.pushState({path:newUrl}, '', newUrl);
        }
    }

    function removeWishlistItem(productId, btnEl) {
        $.post('{{ route("wishlist.toggle") }}', {
            product_id: productId,
            _token: '{{ csrf_token() }}'
        }, function(res) {
            if (res && res.status === 'success') {
                $('.wishlist-badge-count').text(res.count || 0);
                $('#bmsWishlistTabBadgeCount').text(res.count || 0);
                $('#bmsWishlistTabHeaderBadge').text((res.count || 0) + ' Items');
                $('#bmsStatWishlistCount').text(res.count || 0);
                showToast(res.message);

                $('.wishlist-grid-item-' + productId).fadeOut(300, function() {
                    $(this).remove();
                    if ((res.count || 0) === 0) {
                        $('#bmsWishlistContainer').html(`
                            <div class="col-12" id="bmsWishlistEmptyNotice">
                                <div class="bms-empty-box">
                                    <div class="bms-empty-box-icon heart">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <h5 style="font-weight: 800; color: #062417;">Your Wishlist is Empty</h5>
                                    <p class="text-muted mb-4">Click the heart icon on any product to save it to your wishlist and revisit it anytime!</p>
                                    <a href="{{ url('products') }}" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; padding: 10px 24px;">
                                        <i class="fas fa-search mr-1"></i> Explore Products
                                    </a>
                                </div>
                            </div>
                        `);
                    }
                });
            }
        });
    }

    // Auto activate tab if url hash or query param matches
    document.addEventListener('DOMContentLoaded', function() {
        var urlParams = new URLSearchParams(window.location.search);
        var tab = urlParams.get('tab');
        if (tab && ['dashboard', 'orders', 'wishlist', 'profile', 'password'].includes(tab)) {
            var matchBtn = document.querySelector(`.bms-dash-tab-btn[onclick*="'${tab}'"]`);
            if (matchBtn) {
                switchDashTab(tab, matchBtn);
            }
        }
    });
</script>
@endsection
