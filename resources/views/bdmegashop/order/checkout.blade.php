@extends('bdpanel')
@section('content')
<style>
    .checkout-section {
        padding: 45px 0 90px;
        background-color: #f7faf8;
    }

    .checkout-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(6, 36, 23, 0.03);
    }

    .checkout-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #17261e;
        margin-bottom: 22px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .checkout-form-group {
        margin-bottom: 18px;
    }

    .checkout-form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #2f3d36;
        margin-bottom: 6px;
    }

    .checkout-form-group label .req {
        color: #d81e23;
    }

    .checkout-input {
        width: 100%;
        height: 46px;
        padding: 10px 14px;
        font-size: 14px;
        color: #17261e;
        background-color: #fff;
        border: 1.5px solid #cdd7d1;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .checkout-input:focus {
        border-color: #0b7a3d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(11, 122, 61, 0.12);
    }

    textarea.checkout-input {
        height: auto;
        min-height: 90px;
    }

    .checkout-checkbox-box {
        background: #f7faf8;
        border: 1.5px solid #e2e9e4;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
        transition: all 0.2s ease;
    }

    .checkout-checkbox-box:hover {
        border-color: #cdd7d1;
    }

    .checkout-checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        color: #17261e;
        margin: 0;
        user-select: none;
    }

    .checkout-checkbox-label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #0b7a3d;
        cursor: pointer;
    }

    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .payment-option-card {
        border: 1.5px solid #e2e9e4;
        border-radius: 10px;
        padding: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        transition: all 0.2s ease;
        margin: 0;
    }

    .payment-option-card:hover {
        border-color: #0b7a3d;
    }

    .payment-option-card.active {
        border-color: #0b7a3d;
        background: rgba(11, 122, 61, 0.05);
        box-shadow: 0 3px 10px rgba(11, 122, 61, 0.12);
    }

    .payment-option-card input[type="radio"] {
        accent-color: #0b7a3d;
        width: 17px;
        height: 17px;
    }

    .payment-option-card span {
        font-size: 14px;
        font-weight: 600;
        color: #17261e;
    }

    .checkout-product-list {
        max-height: 420px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .checkout-product-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f2;
    }

    .checkout-product-item:last-child {
        border-bottom: 0;
    }

    .checkout-product-img {
        width: 68px;
        height: 68px;
        border-radius: 8px;
        border: 1px solid #e2e9e4;
        object-fit: cover;
        flex-shrink: 0;
        background: #f7faf8;
    }

    .checkout-product-info {
        flex: 1;
        min-width: 0;
    }

    .checkout-product-title {
        font-size: 15px;
        font-weight: 600;
        color: #17261e;
        text-decoration: none;
        display: block;
        margin-bottom: 4px;
    }

    .checkout-product-title:hover {
        color: #0b7a3d;
    }

    .checkout-product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 6px;
    }

    .checkout-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        background: #f1f5f2;
        color: #48564f;
    }

    .checkout-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        font-size: 15px;
        color: #48564f;
    }

    .checkout-summary-row.grand-total {
        border-top: 2px dashed #cdd7d1;
        margin-top: 8px;
        padding-top: 16px;
        font-size: 18px;
        font-weight: 700;
        color: #17261e;
    }

    .checkout-submit-btn {
        width: 100%;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 700;
        border-radius: 10px;
        background-color: #0b7a3d;
        color: #ffffff !important;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 16px;
        box-shadow: 0 4px 14px rgba(11, 122, 61, 0.28);
    }

    .checkout-submit-btn:hover {
        background-color: #035c2d;
        transform: translateY(-1px);
    }

    .order-success-banner {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1.5px solid #6ee7b7;
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 30px;
    }
</style>

<section class="breadcrumb-area mt-15">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Checkout
                        </li>
                    </ol>
                </nav>
                <h5>Checkout</h5>
            </div>
        </div>
    </div>
</section>

<section class="checkout-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($placed_order))
            <div class="order-success-banner">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="mb-2" style="color: #065f46; font-weight: 700;">
                            <i class="fas fa-check-circle mr-1"></i> Thank you! Your Order Has Been Placed.
                        </h4>
                        <p class="mb-1" style="color: #047857; font-size: 15px;">
                            Order Number: <strong>{{ $placed_order->order_number }}</strong> |
                            Payment Method: <strong class="text-uppercase">{{ $placed_order->payment_method }}</strong> |
                            Total Payable: <strong>BDT {{ number_format((float)$placed_order->total, 2) }}</strong>
                        </p>
                        @if(!empty($placed_order->orderAddress))
                            <p class="mb-0 text-muted" style="font-size: 13px;">
                                Shipping To: {{ $placed_order->orderAddress->shipping_address }}, {{ $placed_order->orderAddress->shipping_city }}
                            </p>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ url('/') }}" class="btn bg-primary" style="color: #fff;">Continue Shopping</a>
                        @if(Auth::guard('customer')->check())
                            <a href="{{ url('customer-dashboard') }}" class="btn btn-outline-dark ml-2">My Account</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($cart_items) && count($cart_items) > 0)
            @if(!Auth::guard('customer')->check())
                <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap mb-4" style="border-radius: 10px;">
                    <span>Already have a customer account? Sign in for faster checkout.</span>
                    <a href="{{ url('customer-login') }}" class="btn btn-sm bg-primary" style="color: #fff; padding: 6px 16px;">Sign In</a>
                </div>
            @endif

            <form method="POST" action="{{ route('order.place') }}" id="checkoutForm">
                @csrf
                <div class="row">
                    <!-- Left Column: Billing, Account Creation, Shipping & Payment -->
                    <div class="col-lg-7 col-md-12">
                        <!-- Billing Address Card -->
                        <div class="checkout-card">
                            <div class="checkout-card-title">
                                <span>1. Billing Address</span>
                                @if(Auth::guard('customer')->check())
                                    <span class="badge badge-success" style="font-size: 12px;">Signed in as {{ $customer->name ?? $customer->email }}</span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12 checkout-form-group">
                                    <label for="billing_name">Full Name <span class="req">*</span></label>
                                    <input type="text" id="billing_name" name="name" class="checkout-input"
                                        placeholder="Enter your full name"
                                        value="{{ old('name', $customer->name ?? '') }}" required>
                                </div>

                                <div class="col-md-6 checkout-form-group">
                                    <label for="billing_email">Email Address <span class="req">*</span></label>
                                    <input type="email" id="billing_email" name="email" class="checkout-input"
                                        placeholder="example@email.com"
                                        value="{{ old('email', $customer->email ?? '') }}" required>
                                </div>

                                <div class="col-md-6 checkout-form-group">
                                    <label for="billing_phone">Phone Number <span class="req">*</span></label>
                                    <input type="text" id="billing_phone" name="phone" class="checkout-input"
                                        placeholder="01XXXXXXXXX"
                                        value="{{ old('phone', $customer->phone ?? '') }}" required>
                                </div>

                                <div class="col-md-12 checkout-form-group">
                                    <label for="billing_address">Billing Street Address <span class="req">*</span></label>
                                    <input type="text" id="billing_address" name="address" class="checkout-input"
                                        placeholder="House #, Road #, Area / Thana"
                                        value="{{ old('address', $customer->address ?? '') }}" required>
                                </div>

                                <div class="col-md-4 checkout-form-group">
                                    <label for="billing_city">City / District <span class="req">*</span></label>
                                    <input type="text" id="billing_city" name="city" class="checkout-input"
                                        placeholder="e.g. Dhaka"
                                        value="{{ old('city', $customer->city ?? '') }}" required>
                                </div>

                                <div class="col-md-4 checkout-form-group">
                                    <label for="billing_state">State / Division</label>
                                    <input type="text" id="billing_state" name="state" class="checkout-input"
                                        placeholder="e.g. Dhaka Division"
                                        value="{{ old('state', $customer->state ?? '') }}">
                                </div>

                                <div class="col-md-4 checkout-form-group">
                                    <label for="billing_zipcode">Zip / Postal Code</label>
                                    <input type="text" id="billing_zipcode" name="zipcode" class="checkout-input"
                                        placeholder="e.g. 1207"
                                        value="{{ old('zipcode', $customer->zip_code ?? '') }}">
                                </div>
                            </div>

                            <!-- Automatic Customer Account Creation Option -->
                            @if(!Auth::guard('customer')->check())
                                <div class="checkout-checkbox-box mt-2 mb-0">
                                    <label class="checkout-checkbox-label" for="createAccountCheck">
                                        <input type="checkbox" id="createAccountCheck" name="create_account" value="1"
                                            {{ old('create_account') ? 'checked' : '' }}
                                            onchange="toggleCreateAccountPassword()">
                                        <span>Create a customer account automatically with these details?</span>
                                    </label>

                                    <div id="createAccountPasswordWrap" class="mt-3" style="display: {{ old('create_account') ? 'block' : 'none' }};">
                                        <label for="account_password" style="font-size: 13px; font-weight: 600; color: #2f3d36;">
                                            Account Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" id="account_password" name="account_password"
                                            class="checkout-input mt-1" placeholder="Enter a password (min 6 characters)"
                                            {{ old('create_account') ? 'required' : '' }}>
                                        <small class="text-muted d-block mt-1">
                                            Your account will be created in our system and signed in automatically when your order is placed.
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Shipping Address Card -->
                        <div class="checkout-card">
                            <div class="checkout-card-title">
                                <span>2. Shipping Address</span>
                            </div>

                            @php
                                $sameAsChecked = old('_token') ? (bool) old('same_as') : true;
                            @endphp

                            <div class="checkout-checkbox-box mb-3">
                                <label class="checkout-checkbox-label" for="sameAsBillingCheck">
                                    <input type="checkbox" id="sameAsBillingCheck" name="same_as" value="1"
                                        {{ $sameAsChecked ? 'checked' : '' }}
                                        onchange="toggleShippingAddressFields()">
                                    <span>Shipping address is the same as my billing address</span>
                                </label>
                            </div>

                            <div id="shippingAddressFieldsWrap" style="display: {{ $sameAsChecked ? 'none' : 'block' }};">
                                <div class="row">
                                    <div class="col-md-12 checkout-form-group">
                                        <label for="shipping_address">Shipping Street Address <span class="req">*</span></label>
                                        <input type="text" id="shipping_address" name="shipping_address" class="checkout-input"
                                            placeholder="Delivery House #, Road #, Area"
                                            value="{{ old('shipping_address') }}">
                                    </div>

                                    <div class="col-md-4 checkout-form-group">
                                        <label for="shipping_city">Shipping City / District <span class="req">*</span></label>
                                        <input type="text" id="shipping_city" name="shipping_city" class="checkout-input"
                                            placeholder="e.g. Dhaka"
                                            value="{{ old('shipping_city') }}">
                                    </div>

                                    <div class="col-md-4 checkout-form-group">
                                        <label for="shipping_state">Shipping State / Division</label>
                                        <input type="text" id="shipping_state" name="shipping_state" class="checkout-input"
                                            placeholder="e.g. Dhaka Division"
                                            value="{{ old('shipping_state') }}">
                                    </div>

                                    <div class="col-md-4 checkout-form-group">
                                        <label for="shipping_zipcode">Shipping Zip Code</label>
                                        <input type="text" id="shipping_zipcode" name="shipping_zipcode" class="checkout-input"
                                            placeholder="e.g. 1207"
                                            value="{{ old('shipping_zipcode') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method & Order Note Card -->
                        <div class="checkout-card mb-lg-0">
                            <div class="checkout-card-title">
                                <span>3. Payment Method &amp; Notes</span>
                            </div>

                            @php
                                $selectedPayment = old('payment_method', 'cod');
                            @endphp

                            <div class="payment-methods-grid">
                                <label class="payment-option-card {{ $selectedPayment === 'cod' ? 'active' : '' }}" id="payCard_cod">
                                    <input type="radio" name="payment_method" value="cod"
                                        {{ $selectedPayment === 'cod' ? 'checked' : '' }}
                                        onchange="selectPaymentMethod('cod')">
                                    <span>Cash on Delivery</span>
                                </label>

                                <label class="payment-option-card {{ $selectedPayment === 'bkash' ? 'active' : '' }}" id="payCard_bkash">
                                    <input type="radio" name="payment_method" value="bkash"
                                        {{ $selectedPayment === 'bkash' ? 'checked' : '' }}
                                        onchange="selectPaymentMethod('bkash')">
                                    <span>bKash Payment</span>
                                </label>

                                <label class="payment-option-card {{ $selectedPayment === 'bank_transfer' ? 'active' : '' }}" id="payCard_bank_transfer">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                        {{ $selectedPayment === 'bank_transfer' ? 'checked' : '' }}
                                        onchange="selectPaymentMethod('bank_transfer')">
                                    <span>Bank Transfer</span>
                                </label>
                            </div>

                            <!-- bKash Extra Fields -->
                            <div id="bkashFieldsWrap" class="row" style="display: {{ $selectedPayment === 'bkash' ? 'flex' : 'none' }};">
                                <div class="col-md-6 checkout-form-group">
                                    <label for="bkash_number">bKash Sender Number <span class="req">*</span></label>
                                    <input type="text" id="bkash_number" name="bkash_number" class="checkout-input"
                                        placeholder="01XXXXXXXXX" value="{{ old('bkash_number') }}">
                                </div>
                                <div class="col-md-6 checkout-form-group">
                                    <label for="bkash_reference_number">bKash Transaction ID (TrxID) <span class="req">*</span></label>
                                    <input type="text" id="bkash_reference_number" name="bkash_reference_number" class="checkout-input"
                                        placeholder="e.g. 9G7A6B5C4D" value="{{ old('bkash_reference_number') }}">
                                </div>
                            </div>

                            <!-- Bank Transfer Extra Field -->
                            <div id="bankFieldsWrap" class="row" style="display: {{ $selectedPayment === 'bank_transfer' ? 'flex' : 'none' }};">
                                <div class="col-md-12 checkout-form-group">
                                    <label for="transaction_id">Bank Transaction / Reference ID</label>
                                    <input type="text" id="transaction_id" name="transaction_id" class="checkout-input"
                                        placeholder="Enter bank transfer reference number" value="{{ old('transaction_id') }}">
                                </div>
                            </div>

                            <div class="checkout-form-group mb-0">
                                <label for="order_note">Order Note (Optional)</label>
                                <textarea id="order_note" name="note" class="checkout-input"
                                    placeholder="Special instructions for delivery...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Cart Product List & Order Summary -->
                    <div class="col-lg-5 col-md-12">
                        <div class="checkout-card" style="position: sticky; top: 20px;">
                            <div class="checkout-card-title">
                                <span>Your Order Summary</span>
                                <span class="badge badge-primary" style="background-color: #0b7a3d; color: #fff; padding: 5px 10px; border-radius: 20px; font-size: 12px;">
                                    <span id="checkoutTotalItemsBadge">{{ $total_qty }}</span> Items
                                </span>
                            </div>

                            <div class="checkout-product-list" id="checkoutProductList">
                                @foreach($cart_items as $item)
                                    <div class="checkout-product-item" id="checkout_row_{{ $item['key'] }}">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="checkout-product-img">
                                        <div class="checkout-product-info">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <a href="{{ url('details/' . $item['slug']) }}" class="checkout-product-title">
                                                    {{ $item['title'] }}
                                                </a>
                                                <button type="button" class="cart-item-remove" title="Remove Item"
                                                    onclick="checkoutRemoveItem('{{ $item['key'] }}')">&times;</button>
                                            </div>

                                            @if(!empty($item['size']) || !empty($item['color']))
                                                <div class="checkout-product-meta">
                                                    @if(!empty($item['size']))
                                                        <span class="checkout-badge">Size: {{ $item['size'] }}</span>
                                                    @endif
                                                    @if(!empty($item['color']))
                                                        <span class="checkout-badge">Color: {{ $item['color'] }}</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="cart-qty-control">
                                                    <button type="button" class="cart-qty-btn"
                                                        onclick="checkoutUpdateQty('{{ $item['key'] }}', {{ (int)$item['quantity'] - 1 }})">-</button>
                                                    <span class="cart-qty-val">{{ $item['quantity'] }}</span>
                                                    <button type="button" class="cart-qty-btn"
                                                        onclick="checkoutUpdateQty('{{ $item['key'] }}', {{ (int)$item['quantity'] + 1 }})">+</button>
                                                </div>
                                                <div class="text-right">
                                                    <small class="text-muted d-block" style="font-size: 11px;">
                                                        BDT {{ number_format($item['price'], 2) }} &times; {{ $item['quantity'] }}
                                                    </small>
                                                    <strong style="color: #0b7a3d; font-size: 14px;">
                                                        BDT {{ number_format($item['line_total'], 2) }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-3">

                            <div class="checkout-summary-row">
                                <span>Subtotal</span>
                                <strong id="checkoutSubtotal">BDT {{ number_format($subtotal, 2) }}</strong>
                            </div>
                            <div class="checkout-summary-row">
                                <span>Shipping Charge</span>
                                <strong id="checkoutShipping">BDT {{ number_format($shipping, 2) }}</strong>
                            </div>
                            <div class="checkout-summary-row grand-total">
                                <span>Total Payable</span>
                                <span id="checkoutTotal" style="color: #0b7a3d;">BDT {{ number_format($total, 2) }}</span>
                            </div>

                            <button type="submit" class="checkout-submit-btn">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            @if(empty($placed_order))
                <div class="checkout-card text-center py-5">
                    <h4 class="mb-2" style="color: #17261e; font-weight: 700;">Your Shopping Cart is Empty</h4>
                    <p class="text-muted mb-4">Add some products to your cart before proceeding to checkout.</p>
                    <a href="{{ url('/') }}" class="btn bg-primary" style="color: #fff;">Browse Products</a>
                </div>
            @endif
        @endif
    </div>
</section>

<script>
    function toggleCreateAccountPassword() {
        var check = document.getElementById('createAccountCheck');
        var wrap = document.getElementById('createAccountPasswordWrap');
        var passInput = document.getElementById('account_password');
        if (!check || !wrap) return;

        if (check.checked) {
            wrap.style.display = 'block';
            if (passInput) passInput.setAttribute('required', 'required');
        } else {
            wrap.style.display = 'none';
            if (passInput) passInput.removeAttribute('required');
        }
    }

    function toggleShippingAddressFields() {
        var check = document.getElementById('sameAsBillingCheck');
        var wrap = document.getElementById('shippingAddressFieldsWrap');
        var shipAddr = document.getElementById('shipping_address');
        var shipCity = document.getElementById('shipping_city');
        if (!check || !wrap) return;

        if (check.checked) {
            wrap.style.display = 'none';
            if (shipAddr) shipAddr.removeAttribute('required');
            if (shipCity) shipCity.removeAttribute('required');
        } else {
            wrap.style.display = 'block';
            if (shipAddr) shipAddr.setAttribute('required', 'required');
            if (shipCity) shipCity.setAttribute('required', 'required');
        }
    }

    function selectPaymentMethod(method) {
        var methods = ['cod', 'bkash', 'bank_transfer'];
        methods.forEach(function (m) {
            var card = document.getElementById('payCard_' + m);
            if (card) {
                if (m === method) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }
        });

        var bkashWrap = document.getElementById('bkashFieldsWrap');
        var bankWrap = document.getElementById('bankFieldsWrap');
        if (bkashWrap) bkashWrap.style.display = (method === 'bkash') ? 'flex' : 'none';
        if (bankWrap) bankWrap.style.display = (method === 'bank_transfer') ? 'flex' : 'none';
    }

    function checkoutUpdateQty(key, qty) {
        $.post('{{ route("cart.update") }}', { key: key, quantity: qty }, function (res) {
            if (res && res.status === 'success') {
                window.location.reload();
            }
        });
    }

    function checkoutRemoveItem(key) {
        $.post('{{ route("cart.remove") }}', { key: key }, function (res) {
            if (res && res.status === 'success') {
                window.location.reload();
            }
        });
    }
</script>
@endsection