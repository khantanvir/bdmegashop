@extends('adminpanel')
@section('admincontent')

<style>
    .pos-page {
        margin-top: 15px;
    }
    .pos-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        padding: 18px 20px;
        margin-bottom: 20px;
    }
    .pos-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .pos-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pos-card-title i {
        color: #4361ee;
        font-size: 18px;
    }

    /* Product Cards in Grid */
    .pos-prod-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease-in-out;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .pos-prod-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.12);
        border-color: #c7d2fe;
    }
    .pos-prod-thumb-wrap {
        height: 150px;
        width: 100%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .pos-prod-thumb {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        padding: 8px;
        transition: transform 0.3s;
    }
    .pos-prod-card:hover .pos-prod-thumb {
        transform: scale(1.05);
    }
    .pos-stock-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
    }
    .pos-cat-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        background: rgba(15, 23, 42, 0.7);
        color: #ffffff;
        backdrop-filter: blur(2px);
    }
    .pos-prod-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .pos-prod-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 36px;
    }
    .pos-prod-code {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .pos-prod-pricing {
        margin-top: auto;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 4px;
        margin-bottom: 10px;
    }
    .pos-price-current {
        font-size: 15px;
        font-weight: 800;
        color: #059669;
    }
    .pos-price-old {
        font-size: 11.5px;
        color: #94a3b8;
        text-decoration: line-through;
    }
    .pos-add-btn {
        width: 100%;
        font-weight: 700;
        font-size: 12.5px;
        border-radius: 8px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    /* Cart Sidebar Styles */
    .pos-cart-list {
        max-height: 320px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .pos-cart-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .pos-cart-thumb {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .pos-cart-info {
        flex-grow: 1;
        min-width: 0;
    }
    .pos-cart-name {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }
    .pos-cart-variant {
        font-size: 11px;
        color: #4361ee;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .pos-qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        overflow: hidden;
    }
    .pos-qty-btn {
        background: #f1f5f9;
        border: 0;
        width: 24px;
        height: 24px;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .pos-qty-btn:hover {
        background: #e2e8f0;
    }
    .pos-qty-input {
        width: 34px;
        height: 24px;
        border: 0;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        outline: none;
    }
    .pos-cart-line-total {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        text-align: right;
        min-width: 75px;
    }

    /* Financial Summary */
    .pos-summary-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }
    .pos-summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 0;
        font-size: 13.5px;
        color: #475569;
    }
    .pos-summary-line.grand {
        border-top: 2px solid #cbd5e1;
        margin-top: 8px;
        padding-top: 10px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }
    .pos-summary-line.grand strong {
        color: #4361ee;
        font-size: 19px;
    }

    /* Variant Pills in Modal */
    .pos-pill-radio {
        display: inline-block;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .pos-pill-radio input[type="radio"] {
        display: none;
    }
    .pos-pill-radio label {
        padding: 5px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.15s;
        margin: 0;
        background: #ffffff;
    }
    .pos-pill-radio input[type="radio"]:checked + label {
        border-color: #4361ee;
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 800;
    }
</style>

<div>
    <!-- Breadcrumb Header -->
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Admin Sale (POS)">
            <header class="header navbar navbar-expand-sm">
                <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom"></a>
                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"></div>
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('order-list') }}">Orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Admin Sale (POS)</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </header>
        </div>
    </div>

    <!-- Main Container -->
    <div class="pos-page">
        <div class="row g-3">

            <!-- LEFT 7 COLUMNS: Product Search, Filters & Catalog Grid -->
            <div class="col-xl-7 col-lg-6">

                <!-- Filter & Search Card -->
                <div class="pos-card mb-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-search-line"></i></span>
                                <input type="text" id="posSearchInput" class="form-control border-start-0" placeholder="Search product name, code...">
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <select id="posCategoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <select id="posStockFilter" class="form-select">
                                <option value="all">All Stock</option>
                                <option value="in_stock" selected>In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="button" class="btn btn-primary w-100" onclick="triggerProductSearch()" title="Search">
                                <i class="ri-filter-3-line"></i> Filter
                            </button>
                            <button type="button" class="btn btn-light" onclick="resetProductSearch()" title="Reset Filters">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Catalog Grid Header -->
                <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                    <span class="fw-bold text-dark fs-6">
                        <i class="ri-store-2-line text-primary me-1"></i> Products Catalog
                        <small class="text-muted fw-normal ms-1">(<span id="posProductTotalCount">Loading...</span>)</small>
                    </span>
                    <div id="posCatalogSpinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm text-primary"></span>
                        <small class="text-muted ms-1">Updating...</small>
                    </div>
                </div>

                <!-- Product Grid Container -->
                <div class="row g-3" id="posProductGrid">
                    <!-- Loaded dynamically via AJAX -->
                </div>

                <!-- Catalog Pagination -->
                <div class="d-flex justify-content-center align-items-center gap-2 mt-4" id="posPaginationWrap" style="display: none !important;">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="posPrevPageBtn" onclick="changeCatalogPage(-1)">
                        <i class="ri-arrow-left-s-line"></i> Prev
                    </button>
                    <span class="small fw-bold text-muted">Page <span id="posCurrentPageText">1</span> of <span id="posLastPageText">1</span></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="posNextPageBtn" onclick="changeCatalogPage(1)">
                        Next <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>

            <!-- RIGHT 5 COLUMNS: Sale Cart & Customer Address Checkout -->
            <div class="col-xl-5 col-lg-6">

                <!-- 1. Sale Cart List Card -->
                <div class="pos-card mb-3">
                    <div class="pos-card-head">
                        <h6 class="pos-card-title">
                            <i class="ri-shopping-cart-2-line"></i> Sale Cart
                            <span class="badge bg-primary text-white ms-1" id="posCartBadge">0 items</span>
                        </h6>
                        <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2" onclick="clearSaleCart()">
                            <i class="ri-delete-bin-line me-1"></i> Clear
                        </button>
                    </div>

                    <!-- Cart Item Container -->
                    <div class="pos-cart-list" id="posCartList">
                        <div class="text-center text-muted py-5" id="posEmptyCartMsg">
                            <i class="ri-shopping-cart-line fs-1 text-muted opacity-50"></i>
                            <div class="mt-2 fw-semibold">Sale cart is empty</div>
                            <small>Click "Add to Cart" on any product to start a direct sale.</small>
                        </div>
                    </div>

                    <!-- Financial Breakdown Box -->
                    <div class="pos-summary-box mt-3">
                        <div class="pos-summary-line">
                            <span>Subtotal (<span id="posCartTotalItems">0</span> items)</span>
                            <strong>BDT <span id="posCartSubtotal">0.00</span></strong>
                        </div>

                        <!-- Delivery Charge Input -->
                        <div class="pos-summary-line align-items-center">
                            <span>Delivery Charge</span>
                            <div class="d-flex align-items-center gap-1" style="max-width: 140px;">
                                <span class="small text-muted">+ BDT</span>
                                <input type="number" step="0.01" min="0" id="posDeliveryChargeInput" class="form-control form-control-sm text-end fw-bold" value="60.00" oninput="recalcCartTotals()">
                            </div>
                        </div>

                        <!-- Quick Delivery presets -->
                        <div class="d-flex justify-content-end gap-1 mb-2">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 10px;" onclick="setDeliveryPreset(60)">Inside Dhaka 60</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 10px;" onclick="setDeliveryPreset(120)">Outside 120</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 10px;" onclick="setDeliveryPreset(0)">Free 0</button>
                        </div>

                        <!-- Discount Input -->
                        <div class="pos-summary-line align-items-center">
                            <span>Discount Amount</span>
                            <div class="d-flex align-items-center gap-1" style="max-width: 140px;">
                                <span class="small text-muted">- BDT</span>
                                <input type="number" step="0.01" min="0" id="posDiscountInput" class="form-control form-control-sm text-end fw-bold text-danger" value="0.00" oninput="recalcCartTotals()">
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="pos-summary-line grand">
                            <span>Grand Total</span>
                            <strong>BDT <span id="posCartGrandTotal">0.00</span></strong>
                        </div>
                    </div>
                </div>

                <!-- 2. Customer Address & Order Details Card -->
                <div class="pos-card mb-3">
                    <div class="pos-card-head">
                        <h6 class="pos-card-title">
                            <i class="ri-user-location-line"></i> Customer &amp; Delivery Address
                        </h6>
                        <span class="badge bg-secondary-transparent text-secondary" style="font-size: 11px;">Guest / No Account</span>
                    </div>

                    <form id="adminSaleForm">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" id="custName" name="customer_name" class="form-control form-control-sm" placeholder="e.g. Rahim Ahmed" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Customer Phone <span class="text-danger">*</span></label>
                                <input type="text" id="custPhone" name="customer_phone" class="form-control form-control-sm" placeholder="e.g. 01700000000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Customer Email <small class="text-muted">(Optional)</small></label>
                                <input type="email" id="custEmail" name="customer_email" class="form-control form-control-sm" placeholder="customer@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">City / District <span class="text-danger">*</span></label>
                                <input type="text" id="custCity" name="delivery_city" class="form-control form-control-sm" value="Dhaka" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold mb-1">Delivery Address <span class="text-danger">*</span></label>
                                <textarea id="custAddress" name="delivery_address" class="form-control form-control-sm" rows="2" placeholder="House, Road, Area, Ward/Thana..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">State / Area <small class="text-muted">(Optional)</small></label>
                                <input type="text" id="custState" name="delivery_state" class="form-control form-control-sm" placeholder="e.g. Mirpur, Dhanmondi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Zipcode <small class="text-muted">(Optional)</small></label>
                                <input type="text" id="custZipcode" name="delivery_zipcode" class="form-control form-control-sm" placeholder="e.g. 1216">
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Order & Payment Settings -->
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Payment Method <span class="text-danger">*</span></label>
                                <select id="orderPayMethod" name="payment_method" class="form-select form-select-sm">
                                    <option value="cash">Cash on Hand (POS)</option>
                                    <option value="cod" selected>Cash on Delivery (COD)</option>
                                    <option value="bkash">bKash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Payment Status <span class="text-danger">*</span></label>
                                <select id="orderPayStatus" name="payment_status" class="form-select form-select-sm">
                                    <option value="unpaid" selected>Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="partially_paid">Partially Paid</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Order Status <span class="text-danger">*</span></label>
                                <select id="orderStatus" name="order_status" class="form-select form-select-sm">
                                    <option value="confirmed" selected>Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1">Courier / Delivery Service</label>
                                <select id="orderShippingMethod" name="shipping_method" class="form-select form-select-sm">
                                    <option value="myself" selected>Self Delivery</option>
                                    @foreach($shipping_methods as $sKey => $sLabel)
                                        <option value="{{ $sKey }}">{{ $sLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold mb-1">Admin Note / Instructions <small class="text-muted">(Optional)</small></label>
                                <input type="text" id="orderAdminNote" name="admin_note" class="form-control form-control-sm" placeholder="Special delivery instructions or admin sale remarks">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" id="posSubmitBtn" class="btn btn-success btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; height: 50px;" disabled>
                                <i class="ri-check-double-line fs-5"></i>
                                <span>Complete Sale &amp; Create Order</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Product Details & Variant Selection Modal -->
<div class="modal fade" id="adminProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: 0;">
            <div class="modal-header bg-light">
                <div>
                    <h6 class="modal-title fw-bold mb-0" id="apmTitle">Product Details</h6>
                    <small class="text-muted" id="apmCode"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="apmProductId">
                <input type="hidden" id="apmPrice">
                <input type="hidden" id="apmImage">

                <div class="row g-4">
                    <!-- Left: Gallery & Main Image -->
                    <div class="col-md-5">
                        <div class="border rounded p-2 text-center bg-light mb-2" style="height: 240px; display: flex; align-items: center; justify-content: center;">
                            <img id="apmMainImg" src="" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </div>
                        <div class="d-flex flex-wrap gap-1" id="apmGalleryWrap"></div>
                    </div>

                    <!-- Right: Info, Price & Options -->
                    <div class="col-md-7">
                        <div class="d-flex align-items-baseline gap-2 mb-2">
                            <h4 class="text-success fw-bold mb-0">BDT <span id="apmPriceText">0.00</span></h4>
                            <span class="text-muted text-decoration-line-through small" id="apmOldPriceText"></span>
                            <span class="badge bg-danger ms-1" id="apmDiscountBadge" style="display: none;"></span>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-light text-dark border me-1" id="apmCategory"></span>
                            <span class="badge bg-success-transparent text-success fw-bold" id="apmStockBadge"></span>
                        </div>

                        <!-- Size Selector -->
                        <div class="mb-3" id="apmSizeSection">
                            <label class="form-label small fw-bold text-dark mb-1">Select Size:</label>
                            <div id="apmSizeWrap" class="d-flex flex-wrap"></div>
                        </div>

                        <!-- Color Selector -->
                        <div class="mb-3" id="apmColorSection">
                            <label class="form-label small fw-bold text-dark mb-1">Select Color:</label>
                            <div id="apmColorWrap" class="d-flex flex-wrap"></div>
                        </div>

                        <!-- Quantity Stepper -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark mb-1">Quantity:</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="pos-qty-stepper" style="border: 1px solid #cbd5e1; border-radius: 8px;">
                                    <button type="button" class="pos-qty-btn" style="width: 32px; height: 32px;" onclick="changeModalQty(-1)">&minus;</button>
                                    <input type="number" id="apmQty" value="1" min="1" class="pos-qty-input" style="width: 44px; height: 32px;" onchange="validateModalQty()">
                                    <button type="button" class="pos-qty-btn" style="width: 32px; height: 32px;" onclick="changeModalQty(1)">&plus;</button>
                                </div>
                                <span class="small text-muted ms-2">Subtotal: BDT <strong id="apmSubtotalPreview" class="text-dark">0.00</strong></span>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-primary btn-lg fw-bold" onclick="addModalItemToCart()">
                                <i class="ri-shopping-cart-2-line me-1"></i> Add to Sale Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sale Completed Success Modal -->
<div class="modal fade" id="saleSuccessModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" style="border-radius: 20px; border: 0;">
            <div class="mb-3">
                <span class="avatar avatar-xxl bg-success-transparent text-success rounded-circle" style="width: 76px; height: 76px; font-size: 38px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="ri-checkbox-circle-fill"></i>
                </span>
            </div>
            <h4 class="fw-bold text-dark mb-1">Sale Completed!</h4>
            <p class="text-muted small mb-3">Order has been created successfully from Admin Panel.</p>

            <div class="p-3 bg-light rounded-3 text-start mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Order Number:</span>
                    <strong class="text-primary" id="succOrderNumber">#BMS-00000</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Customer Name:</span>
                    <strong class="text-dark" id="succCustName"></strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Customer Phone:</span>
                    <strong class="text-dark" id="succCustPhone"></strong>
                </div>
                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                    <span class="fw-bold text-dark">Grand Total:</span>
                    <strong class="text-success fs-5">BDT <span id="succOrderTotal">0.00</span></strong>
                </div>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="#" id="succViewDetailsBtn" class="btn btn-primary fw-bold">
                    <i class="ri-eye-line me-1"></i> View Order Details
                </a>
                <button type="button" class="btn btn-outline-secondary fw-bold" onclick="resetSaleForNewCustomer()">
                    <i class="ri-add-line me-1"></i> Start New Sale
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var SEARCH_URL = "{{ url('admin-sale/search-products') }}";
        var QUICK_VIEW_URL = "{{ url('admin-sale/product-quick-view') }}";
        var COMPLETE_SALE_URL = "{{ url('admin-sale-complete') }}";
        var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var saleCart = [];
        var currentPage = 1;
        var lastPage = 1;
        var searchDebounceTimer = null;

        function fmt(n) {
            return parseFloat(n || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function notify(message, ok) {
            if (window.iziToast) {
                iziToast.show({
                    title: ok ? 'Success' : 'Notice',
                    message: message,
                    position: 'topRight',
                    timeout: 4500,
                    color: ok ? 'green' : 'red',
                    close: true,
                });
            } else {
                alert(message);
            }
        }

        // ---------- Catalog & Search ----------
        function loadProducts(page) {
            currentPage = page || 1;
            var spinner = document.getElementById('posCatalogSpinner');
            spinner.style.display = 'inline-block';

            var term = document.getElementById('posSearchInput').value.trim();
            var cat = document.getElementById('posCategoryFilter').value;
            var stock = document.getElementById('posStockFilter').value;

            var url = SEARCH_URL + '?page=' + currentPage + '&search=' + encodeURIComponent(term) +
                      '&category_id=' + encodeURIComponent(cat) + '&stock_status=' + encodeURIComponent(stock);

            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.json(); })
                .then(function (res) {
                    spinner.style.display = 'none';
                    if (!res.result || res.result.key !== 200) {
                        return;
                    }
                    var data = res.result;
                    renderProductsGrid(data.products || []);
                    lastPage = data.last_page || 1;
                    document.getElementById('posProductTotalCount').textContent = (data.total || 0) + ' items';

                    var pagWrap = document.getElementById('posPaginationWrap');
                    if (lastPage > 1) {
                        pagWrap.style.display = 'flex';
                        document.getElementById('posCurrentPageText').textContent = currentPage;
                        document.getElementById('posLastPageText').textContent = lastPage;
                        document.getElementById('posPrevPageBtn').disabled = (currentPage <= 1);
                        document.getElementById('posNextPageBtn').disabled = (currentPage >= lastPage);
                    } else {
                        pagWrap.style.display = 'none';
                    }
                })
                .catch(function () {
                    spinner.style.display = 'none';
                });
        }

        function renderProductsGrid(products) {
            var grid = document.getElementById('posProductGrid');
            grid.innerHTML = '';

            if (products.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-5">' +
                    '<i class="ri-inbox-line fs-1 text-muted opacity-50"></i>' +
                    '<div class="mt-2 fw-semibold">No products found matching filters</div>' +
                    '<small>Try changing your search term or category filter.</small>' +
                '</div>';
                return;
            }

            products.forEach(function (p) {
                var col = document.createElement('div');
                col.className = 'col-xl-4 col-md-6 col-sm-6';

                var isOutOfStock = (p.stock <= 0);
                var stockBadgeHtml = isOutOfStock
                    ? '<span class="pos-stock-badge bg-danger text-white">Out of Stock</span>'
                    : '<span class="pos-stock-badge bg-success text-white">Stock: ' + p.stock + '</span>';

                var oldPriceHtml = (p.original_price > p.selling_price)
                    ? '<span class="pos-price-old">BDT ' + fmt(p.original_price) + '</span>'
                    : '';

                col.innerHTML =
                    '<div class="pos-prod-card">' +
                        '<div class="pos-prod-thumb-wrap">' +
                            '<span class="pos-cat-badge">' + p.category_name + '</span>' +
                            stockBadgeHtml +
                            '<img src="' + p.image + '" alt="' + p.title + '" class="pos-prod-thumb">' +
                        '</div>' +
                        '<div class="pos-prod-body">' +
                            '<div class="pos-prod-title" title="' + p.title + '">' + p.title + '</div>' +
                            '<div class="pos-prod-code">SKU: <code>' + p.code + '</code></div>' +
                            '<div class="pos-prod-pricing">' +
                                '<span class="pos-price-current">BDT ' + fmt(p.selling_price) + '</span>' +
                                oldPriceHtml +
                            '</div>' +
                            '<button type="button" class="btn btn-outline-primary pos-add-btn" onclick="openProductQuickView(' + p.id + ')">' +
                                '<i class="ri-add-line"></i> ' + (p.has_variants ? 'Select Options' : 'Add to Cart') +
                            '</button>' +
                        '</div>' +
                    '</div>';

                grid.appendChild(col);
            });
        }

        window.triggerProductSearch = function () {
            loadProducts(1);
        };

        window.resetProductSearch = function () {
            document.getElementById('posSearchInput').value = '';
            document.getElementById('posCategoryFilter').value = '';
            document.getElementById('posStockFilter').value = 'all';
            loadProducts(1);
        };

        window.changeCatalogPage = function (delta) {
            var target = currentPage + delta;
            if (target >= 1 && target <= lastPage) {
                loadProducts(target);
            }
        };

        document.getElementById('posSearchInput').addEventListener('input', function () {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(function () {
                loadProducts(1);
            }, 350);
        });

        document.getElementById('posCategoryFilter').addEventListener('change', function () {
            loadProducts(1);
        });

        document.getElementById('posStockFilter').addEventListener('change', function () {
            loadProducts(1);
        });

        // ---------- Product Quick View Modal ----------
        window.openProductQuickView = function (productId) {
            fetch(QUICK_VIEW_URL + '/' + productId, { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.json(); })
                .then(function (res) {
                    if (!res.result || res.result.key !== 200) {
                        notify('Could not load product details', false);
                        return;
                    }
                    var p = res.result;
                    document.getElementById('apmProductId').value = p.id;
                    document.getElementById('apmPrice').value = p.selling_price;
                    document.getElementById('apmImage').value = p.image;
                    document.getElementById('apmTitle').textContent = p.title;
                    document.getElementById('apmCode').textContent = 'SKU: ' + p.code + (p.brand_name ? ' | Brand: ' + p.brand_name : '');
                    document.getElementById('apmPriceText').textContent = fmt(p.selling_price);

                    if (p.original_price > p.selling_price) {
                        document.getElementById('apmOldPriceText').textContent = 'BDT ' + fmt(p.original_price);
                        if (p.discount > 0) {
                            document.getElementById('apmDiscountBadge').style.display = 'inline-block';
                            document.getElementById('apmDiscountBadge').textContent = p.discount + '% OFF';
                        }
                    } else {
                        document.getElementById('apmOldPriceText').textContent = '';
                        document.getElementById('apmDiscountBadge').style.display = 'none';
                    }

                    document.getElementById('apmCategory').textContent = p.category_name;
                    var stockBadge = document.getElementById('apmStockBadge');
                    if (p.stock <= 0) {
                        stockBadge.className = 'badge bg-danger text-white';
                        stockBadge.textContent = 'Out of Stock (' + p.stock + ')';
                    } else {
                        stockBadge.className = 'badge bg-success-transparent text-success fw-bold';
                        stockBadge.textContent = 'In Stock: ' + p.stock;
                    }

                    document.getElementById('apmMainImg').src = p.image;
                    var gallWrap = document.getElementById('apmGalleryWrap');
                    gallWrap.innerHTML = '';
                    (p.gallery || []).forEach(function (g) {
                        var thumb = document.createElement('img');
                        thumb.src = g;
                        thumb.style = 'width: 44px; height: 44px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 1px solid #cbd5e1;';
                        thumb.onclick = function () { document.getElementById('apmMainImg').src = g; };
                        gallWrap.appendChild(thumb);
                    });

                    // Sizes
                    var sizeWrap = document.getElementById('apmSizeWrap');
                    sizeWrap.innerHTML = '';
                    if (p.sizes && p.sizes.length > 0) {
                        document.getElementById('apmSizeSection').style.display = 'block';
                        p.sizes.forEach(function (s, idx) {
                            var id = 'sz_' + s.id + '_' + Math.random().toString(36).substring(7);
                            var pill = document.createElement('div');
                            pill.className = 'pos-pill-radio';
                            pill.innerHTML = '<input type="radio" name="modal_size" id="' + id + '" value="' + s.title + '" ' + (idx === 0 ? 'checked' : '') + '>' +
                                             '<label for="' + id + '">' + s.title + '</label>';
                            sizeWrap.appendChild(pill);
                        });
                    } else {
                        document.getElementById('apmSizeSection').style.display = 'none';
                    }

                    // Colors
                    var colorWrap = document.getElementById('apmColorWrap');
                    colorWrap.innerHTML = '';
                    if (p.colors && p.colors.length > 0) {
                        document.getElementById('apmColorSection').style.display = 'block';
                        p.colors.forEach(function (c, idx) {
                            var id = 'col_' + c.id + '_' + Math.random().toString(36).substring(7);
                            var pill = document.createElement('div');
                            pill.className = 'pos-pill-radio';
                            pill.innerHTML = '<input type="radio" name="modal_color" id="' + id + '" value="' + c.title + '" ' + (idx === 0 ? 'checked' : '') + '>' +
                                             '<label for="' + id + '">' + c.title + '</label>';
                            colorWrap.appendChild(pill);
                        });
                    } else {
                        document.getElementById('apmColorSection').style.display = 'none';
                    }

                    document.getElementById('apmQty').value = 1;
                    updateModalSubtotal();

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('adminProductModal')).show();
                })
                .catch(function () {
                    notify('Failed to load product details', false);
                });
        };

        window.changeModalQty = function (delta) {
            var input = document.getElementById('apmQty');
            var cur = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, cur + delta);
            updateModalSubtotal();
        };

        window.validateModalQty = function () {
            var input = document.getElementById('apmQty');
            var cur = parseInt(input.value, 10) || 1;
            input.value = Math.max(1, cur);
            updateModalSubtotal();
        };

        function updateModalSubtotal() {
            var price = parseFloat(document.getElementById('apmPrice').value) || 0;
            var qty = parseInt(document.getElementById('apmQty').value, 10) || 1;
            document.getElementById('apmSubtotalPreview').textContent = fmt(price * qty);
        }

        window.addModalItemToCart = function () {
            var prodId = parseInt(document.getElementById('apmProductId').value, 10);
            var title = document.getElementById('apmTitle').textContent;
            var price = parseFloat(document.getElementById('apmPrice').value) || 0;
            var image = document.getElementById('apmImage').value;
            var qty = parseInt(document.getElementById('apmQty').value, 10) || 1;

            var sizeRadio = document.querySelector('input[name="modal_size"]:checked');
            var size = sizeRadio ? sizeRadio.value : '';

            var colorRadio = document.querySelector('input[name="modal_color"]:checked');
            var color = colorRadio ? colorRadio.value : '';

            addItemToCart(prodId, title, price, image, qty, size, color);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('adminProductModal')).hide();
        };

        // ---------- Cart Operations ----------
        function getCartItemKey(prodId, size, color) {
            return prodId + '_' + (size || 'none') + '_' + (color || 'none');
        }

        function addItemToCart(prodId, title, price, image, qty, size, color) {
            var key = getCartItemKey(prodId, size, color);
            var existing = saleCart.find(function (item) { return item.key === key; });

            if (existing) {
                existing.quantity += qty;
            } else {
                saleCart.push({
                    key: key,
                    product_id: prodId,
                    title: title,
                    price: price,
                    image: image,
                    quantity: qty,
                    size: size,
                    color: color,
                });
            }

            renderCart();
            notify('Added "' + title + '" to sale cart', true);
        }

        function renderCart() {
            var list = document.getElementById('posCartList');
            var emptyMsg = document.getElementById('posEmptyCartMsg');

            if (saleCart.length === 0) {
                list.innerHTML = '';
                list.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
                document.getElementById('posSubmitBtn').disabled = true;
                recalcCartTotals();
                return;
            }

            emptyMsg.style.display = 'none';
            list.innerHTML = '';

            saleCart.forEach(function (item, idx) {
                var div = document.createElement('div');
                div.className = 'pos-cart-item';

                var variantText = '';
                if (item.size || item.color) {
                    var parts = [];
                    if (item.size) parts.push('Size: ' + item.size);
                    if (item.color) parts.push('Color: ' + item.color);
                    variantText = '<div class="pos-cart-variant">' + parts.join(' | ') + '</div>';
                }

                div.innerHTML =
                    '<img src="' + item.image + '" class="pos-cart-thumb" alt="' + item.title + '">' +
                    '<div class="pos-cart-info">' +
                        '<div class="pos-cart-name" title="' + item.title + '">' + item.title + '</div>' +
                        variantText +
                        '<div class="d-flex align-items-center gap-2 mt-1">' +
                            '<span class="small text-muted">@ BDT ' + fmt(item.price) + '</span>' +
                            '<div class="pos-qty-stepper">' +
                                '<button type="button" class="pos-qty-btn" onclick="updateCartItemQty(' + idx + ', -1)">&minus;</button>' +
                                '<input type="number" class="pos-qty-input" value="' + item.quantity + '" min="1" onchange="setCartItemQty(' + idx + ', this.value)">' +
                                '<button type="button" class="pos-qty-btn" onclick="updateCartItemQty(' + idx + ', 1)">&plus;</button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="text-end">' +
                        '<div class="pos-cart-line-total">BDT ' + fmt(item.price * item.quantity) + '</div>' +
                        '<button type="button" class="btn btn-xs text-danger mt-1 p-0" onclick="removeCartItem(' + idx + ')" title="Remove item">' +
                            '<i class="ri-delete-bin-line fs-6"></i>' +
                        '</button>' +
                    '</div>';

                list.appendChild(div);
            });

            document.getElementById('posSubmitBtn').disabled = false;
            recalcCartTotals();
        }

        window.updateCartItemQty = function (idx, delta) {
            if (saleCart[idx]) {
                var newQty = saleCart[idx].quantity + delta;
                if (newQty <= 0) {
                    saleCart.splice(idx, 1);
                } else {
                    saleCart[idx].quantity = newQty;
                }
                renderCart();
            }
        };

        window.setCartItemQty = function (idx, val) {
            if (saleCart[idx]) {
                var qty = parseInt(val, 10) || 1;
                saleCart[idx].quantity = Math.max(1, qty);
                renderCart();
            }
        };

        window.removeCartItem = function (idx) {
            if (saleCart[idx]) {
                saleCart.splice(idx, 1);
                renderCart();
            }
        };

        window.clearSaleCart = function () {
            saleCart = [];
            renderCart();
        };

        window.recalcCartTotals = function () {
            var subtotal = 0;
            var totalQty = 0;
            saleCart.forEach(function (item) {
                subtotal += (item.price * item.quantity);
                totalQty += item.quantity;
            });

            var delivInput = parseFloat(document.getElementById('posDeliveryChargeInput').value) || 0;
            var discInput = parseFloat(document.getElementById('posDiscountInput').value) || 0;
            var grandTotal = Math.max(0, subtotal - discInput + delivInput);

            document.getElementById('posCartBadge').textContent = totalQty + (totalQty === 1 ? ' item' : ' items');
            document.getElementById('posCartTotalItems').textContent = totalQty;
            document.getElementById('posCartSubtotal').textContent = fmt(subtotal);
            document.getElementById('posCartGrandTotal').textContent = fmt(grandTotal);
        };

        window.setDeliveryPreset = function (amount) {
            document.getElementById('posDeliveryChargeInput').value = parseFloat(amount).toFixed(2);
            recalcCartTotals();
        };

        // ---------- Complete Sale Form Submission ----------
        document.getElementById('adminSaleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (saleCart.length === 0) {
                notify('Please add at least one product to the sale cart before completing sale.', false);
                return;
            }

            var submitBtn = document.getElementById('posSubmitBtn');
            var originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing Sale...';

            var payload = {
                customer_name: document.getElementById('custName').value.trim(),
                customer_phone: document.getElementById('custPhone').value.trim(),
                customer_email: document.getElementById('custEmail').value.trim(),
                delivery_city: document.getElementById('custCity').value.trim(),
                delivery_address: document.getElementById('custAddress').value.trim(),
                delivery_state: document.getElementById('custState').value.trim(),
                delivery_zipcode: document.getElementById('custZipcode').value.trim(),
                payment_method: document.getElementById('orderPayMethod').value,
                payment_status: document.getElementById('orderPayStatus').value,
                order_status: document.getElementById('orderStatus').value,
                shipping_method: document.getElementById('orderShippingMethod').value,
                admin_note: document.getElementById('orderAdminNote').value.trim(),
                delivery_charge: parseFloat(document.getElementById('posDeliveryChargeInput').value) || 0,
                discount_amount: parseFloat(document.getElementById('posDiscountInput').value) || 0,
                cart_items: saleCart,
            };

            fetch(COMPLETE_SALE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify(payload),
            })
            .then(function (res) { return res.json().then(function (json) { return { ok: res.ok, json: json }; }); })
            .then(function (r) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;

                if (!r.ok) {
                    var msg = r.json.val || r.json.message || 'Validation error occurred';
                    if (r.json.errors) {
                        msg = Object.values(r.json.errors).map(function (e) { return e[0]; }).join('<br>');
                    }
                    throw new Error(msg);
                }

                if (!r.json.result || r.json.result.key !== 200) {
                    throw new Error((r.json.result && r.json.result.val) || 'Failed to complete sale');
                }

                var res = r.json.result;
                document.getElementById('succOrderNumber').textContent = '#' + res.order_number;
                document.getElementById('succCustName').textContent = payload.customer_name;
                document.getElementById('succCustPhone').textContent = payload.customer_phone;
                document.getElementById('succOrderTotal').textContent = res.total;
                document.getElementById('succViewDetailsBtn').href = res.order_details_url;

                bootstrap.Modal.getOrCreateInstance(document.getElementById('saleSuccessModal')).show();
                notify(res.val, true);
            })
            .catch(function (err) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                notify(err.message, false);
            });
        });

        window.resetSaleForNewCustomer = function () {
            saleCart = [];
            renderCart();
            document.getElementById('adminSaleForm').reset();
            document.getElementById('custCity').value = 'Dhaka';
            document.getElementById('posDeliveryChargeInput').value = '60.00';
            document.getElementById('posDiscountInput').value = '0.00';
            bootstrap.Modal.getOrCreateInstance(document.getElementById('saleSuccessModal')).hide();
            loadProducts(1);
        };

        // Initial catalog load
        loadProducts(1);
    })();
</script>

@endsection

