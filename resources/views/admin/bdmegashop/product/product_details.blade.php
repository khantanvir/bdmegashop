@extends('adminpanel')
@section('admincontent')
<style>
    .product-details-page .card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .product-details-page .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 18px 20px;
        font-weight: 600;
    }

    .product-main-image {
        width: 100%;
        height: 350px;
        object-fit: contain;
        border-radius: 10px;
        border: 1px solid #eee;
        padding: 10px;
        background: #fff;
    }

    .product-thumbnail {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
        cursor: pointer;
        padding: 2px;
        transition: 0.2s;
    }

    .product-thumbnail:hover,
    .product-thumbnail.active {
        border-color: #4361ee;
    }

    .product-info-label {
        font-size: 13px;
        color: #888;
        margin-bottom: 3px;
    }

    .product-info-value {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        word-break: break-word;
    }

    .stock-box {
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stock-box .stock-number {
        font-size: 32px;
        font-weight: 700;
    }

    .upload-image-box {
        border: 2px dashed #d9d9d9;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .upload-image-box:hover {
        border-color: #4361ee;
        background: #f8f9ff;
    }

    .image-list-item {
        position: relative;
        border: 1px solid #eee;
        border-radius: 10px;
        overflow: hidden;
        height: 150px;
    }

    .image-list-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 0;
        padding: 0;
    }

    .table-responsive {
        border-radius: 8px;
    }

    .order-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-completed {
        background: #d1fae5;
        color: #047857;
    }

    .status-pending {
        background: #fef3c7;
        color: #b45309;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #b91c1c;
    }

    @media (max-width: 991px) {
        .product-main-image {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        .product-main-image {
            height: 250px;
        }

        .product-thumbnail {
            width: 60px;
            height: 60px;
        }

        .stock-box .stock-number {
            font-size: 24px;
        }
    }
</style>
<div class="">
    <div class="secondary-nav">
        <div class="breadcrumbs-container" data-page-heading="Product Details">
            <header class="header navbar navbar-expand-sm">
                <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom"> </a>

                <div class="d-flex breadcrumb-content">
                    <div class="page-header">
                        <div class="page-title"></div>

                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('all-products') }}"> Product </a>
                                </li>

                                <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </header>
        </div>
    </div>
    <div class="container-fluid">
        <div class="widget-content widget-content-area br-8 mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">{{ $product->title ?? 'Apple iPhone 15 Pro Max' }}</h4>

                    <p class="text-muted mb-0">
                        Product Code:

                        <strong> {{ $product->product_code ?? 'PRD-10001' }} </strong>
                    </p>
                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>

                    <a href="{{ url('create-product/' . ($product->id ?? '')) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Product
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-box me-2"></i>
                            Product Basic Details
                        </span>
                        @if(($product->product_status ?? 0) == 1)
                        <span class="badge bg-warning"> Pending </span>
                        @elseif(($product->product_status ?? 0) == 2)
                        <span class="badge bg-success"> Publish </span>
                        @else
                        <span class="badge bg-secondary"> Draft </span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">
                            {{-- PRODUCT IMAGE --}}
                            <div class="col-md-5">
                                <img
                                    id="mainProductImage"
                                    src="{{ !empty($product->image) ? asset($product->image) : 'https://via.placeholder.com/600x600?text=Product+Image' }}"
                                    class="product-main-image"
                                    alt="Product Image"
                                />

                                {{-- THUMBNAILS --}}
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @if(!empty($product->image))

                                    <img
                                        src="{{ asset($product->image) }}"
                                        class="product-thumbnail active"
                                        onclick="changeProductImage(this.src)"
                                        alt="Product Thumbnail"
                                    />

                                    @endif @forelse($product->images ?? [] as $image)

                                    <img
                                        src="{{ asset($image->image) }}"
                                        class="product-thumbnail"
                                        onclick="changeProductImage(this.src)"
                                        alt="Product Thumbnail"
                                    />

                                    @empty @if(empty($product->image))

                                    <img
                                        src="https://via.placeholder.com/100"
                                        class="product-thumbnail active"
                                        onclick="changeProductImage(this.src)"
                                        alt="Product Thumbnail"
                                    />

                                    @endif @endforelse
                                </div>
                            </div>

                            {{-- PRODUCT INFORMATION --}}
                            <div class="col-md-7 mt-4 mt-md-0">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Product Name</div>

                                        <div class="product-info-value">{{ $product->title ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Product Code</div>

                                        <div class="product-info-value">{{ $product->product_code ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Category</div>

                                        <div class="product-info-value">{{ $product->category->title ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Sub Category</div>

                                        <div class="product-info-value">
                                            {{ $product->subCategory->title ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Brand</div>

                                        <div class="product-info-value">{{ $product->brand->title ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Owner</div>

                                        <div class="product-info-value">{{ $product->owner->name ?? 'Admin' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Purchase Price</div>

                                        <div class="product-info-value text-primary">
                                            BDT {{ number_format($product->purchase_price ?? 0, 2) }}
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Selling Price</div>

                                        <div class="product-info-value text-success">
                                            BDT {{ number_format($product->selling_price ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Discount</div>

                                        <div class="product-info-value text-primary">
                                            {{ $product->discount ?? 0 }}%
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Shipping Charge</div>

                                        <div class="product-info-value text-success">
                                            BDT {{ number_format($product->shipping_charge ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="product-info-label">Status</div>

                                        <div class="product-info-value">
                                            @if(($product->status ?? 0) == 0)
                                            <span class="badge bg-primary"> Active </span>
                                            @else
                                            <span class="badge bg-danger"> Inactive </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="product-info-label">Created At</div>
                                        <div class="product-info-value">
                                            {{ !empty($product->created_at) ? $product->created_at->format('d M Y') :
                                            '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="product-info-label">Last Updated</div>

                                        <div class="product-info-value">
                                            {{ !empty($product->updated_at) ? $product->updated_at->format('d M Y') :
                                            '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="mb-4">
                            <div class="product-info-label mb-2">Description</div>
                            <div class="product-info-value fw-normal">{!! $product->description ?? 'N/A' !!}</div>
                        </div>
                        <div>
                            <div class="product-info-label mb-2">Long Description</div>

                            <div class="product-info-value fw-normal">{!! $product->long_description ?? 'N/A' !!}</div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-images me-2"></i>
                        Product Images
                    </div>
                    <div class="card-body">
                        <form action="{{ url('upload-details-page-image') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? 0 }}">
                            <div class="upload-image-box">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>

                                <h6>Upload Product Images</h6>

                                <p class="text-muted mb-3">You can select multiple images at once.</p>

                                <input
                                    type="file"
                                    name="image"
                                    id="productImages"
                                    class="form-control"
                                    multiple
                                    accept="image/*"
                                />
                                <span class="text-danger" id="imageError">width: 500 px, height: 430px for better view</span>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i>
                                    Upload Images
                                </button>
                            </div>
                        </form>
                        <div class="row">
                            @forelse($product->images ?? [] as $image)

                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                <div class="image-list-item">
                                    <img src="{{ asset($image->image) }}" alt="Product Image" />

                                    <button type="button" class="btn btn-danger btn-sm remove-image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-images fa-3x mb-3"></i>

                                    <p class="mb-0">No additional product images found.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-shopping-cart me-2"></i>
                            Product Order Details
                        </span>
                        <a href="#" class="btn btn-sm btn-outline-primary"> View All Orders </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Order No</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($product->orderItems ?? [] as $key => $orderItem)

                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            <strong> #{{ $orderItem->order->order_number ?? 'N/A' }} </strong>
                                        </td>

                                        <td>{{ $orderItem->order->customer->name ?? 'N/A' }}</td>

                                        <td>{{ $orderItem->quantity ?? 0 }}</td>

                                        <td>BDT {{ number_format($orderItem->total ?? 0, 2) }}</td>

                                        <td>
                                            @php $orderStatus = strtolower( $orderItem->order->status ?? 'pending' );
                                            @endphp @if($orderStatus == 'completed')

                                            <span class="order-status status-completed"> Completed </span>

                                            @elseif($orderStatus == 'cancelled')

                                            <span class="order-status status-cancelled"> Cancelled </span>

                                            @else

                                            <span class="order-status status-pending"> Pending </span>

                                            @endif
                                        </td>

                                        <td>
                                            {{ !empty($orderItem->created_at) ? $orderItem->created_at->format('d M Y')
                                            : '-' }}
                                        </td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary"> View </a>
                                        </td>
                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No orders found for this product.
                                        </td>
                                    </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- END LEFT SIDE --}} {{-- ========================= --}} {{-- RIGHT SIDE --}} {{--
            ========================= --}}
            <div class="col-lg-4">
                {{-- ========================= --}} {{-- CURRENT STOCK --}} {{-- ========================= --}}
                @php
                    $totalStockIn = isset($product->productDetails) ? $product->productDetails->sum(fn($item) => (int) ($item->total_stock_in ?? 0)) : 0;
                    $totalStockOut = isset($product->productDetails) ? $product->productDetails->sum(fn($item) => (int) ($item->total_stock_out ?? 0)) : 0;
                    $currentStock = $totalStockIn - $totalStockOut;
                @endphp
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-warehouse me-2"></i>
                        Current Stock
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="stock-box bg-light">
                                    <small class="text-muted"> Current Stock </small>

                                    <div class="stock-number text-primary">{{ $product->stock ?? $currentStock }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stock-box bg-light">
                                    <small class="text-muted"> Sold </small>

                                    <div class="stock-number text-success">{{ $product->sold_quantity ?? $totalStockOut }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <div class="stock-box bg-light">
                                    <small class="text-muted"> Reserved </small>

                                    <div class="stock-number text-warning">{{ $product->reserved_stock ?? 0 }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stock-box bg-light">
                                    <small class="text-muted"> Total Stock </small>

                                    <div class="stock-number text-dark">{{ $product->total_stock ?? $totalStockIn }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================= --}} {{-- STOCK UPDATE FORM --}} {{-- ========================= --}}
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-plus-circle me-2"></i>
                        Update Product Stock
                    </div>

                    <div class="card-body">
                        <form action="{{ url('update-product-stock') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id ?? 0 }}">

                            <div class="mb-3">
                                <label class="form-label"> Update Type </label>

                                <select name="stock_type" class="form-select">
                                    <option value="add">Add Stock</option>

                                    <option value="remove">Remove Stock</option>

                                    <option value="adjustment">Stock Adjustment</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> Size </label>

                                <select name="size" class="form-select">
                                    <option value="">Select Size</option>
                                    @foreach($sizes ?? [] as $size)
                                        <option value="{{ $size->id }}" {{ old('size') == $size->id ? 'selected' : '' }}>{{ $size->title }}</option>
                                    @endforeach
                                </select>
                                @error('size')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> Color </label>

                                <select name="color" class="form-select">
                                    <option value="">Select Color</option>
                                    @foreach($colors ?? [] as $color)
                                        <option value="{{ $color->id }}" {{ old('color') == $color->id ? 'selected' : '' }}>{{ $color->title }}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> Quantity </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ old('quantity') }}"
                                    class="form-control"
                                    min="1"
                                    placeholder="Enter quantity"
                                    required
                                />
                                @error('quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> Note / Reason </label>

                                <textarea
                                    name="stock_note"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter stock update reason"
                                >{{ old('stock_note') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i>
                                Update Stock
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ========================= --}} {{-- STOCK HISTORY --}} {{-- ========================= --}}
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i>
                        Recent Stock Updates
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Qty</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($product->stockHistories ?? [] as $stock)

                                    <tr>
                                        <td>
                                            @if((int)($stock->total_stock_out ?? 0) > 0)
                                                <span class="badge bg-danger"> Remove </span>
                                            @else
                                                <span class="badge bg-primary"> Add </span>
                                            @endif
                                        </td>

                                        <td>{{ $stock->sizeData->title ?? $stock->size ?? '-' }}</td>

                                        <td>{{ $stock->colorData->title ?? $stock->color ?? '-' }}</td>

                                        <td>
                                            @if((int)($stock->total_stock_out ?? 0) > 0)
                                                {{ $stock->total_stock_out }}
                                            @else
                                                {{ $stock->total_stock_in ?? 0 }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ !empty($stock->created_at) ? $stock->created_at->format('d M Y') : '-' }}
                                        </td>
                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No stock update history found.
                                        </td>
                                    </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ========================= --}} {{-- PRODUCT NOTIFICATIONS & CREATOR / UPDATER --}} {{-- ========================= --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-bell me-2 text-primary"></i>
                            Product Notifications &amp; History
                        </span>
                        <span class="badge bg-primary">{{ count($notifications ?? []) }} Events</span>
                    </div>

                    <div class="card-body">
                        <!-- Creator & Updater Summary -->
                        <div class="p-3 bg-light rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Created By:</span>
                                <span class="fw-bold text-dark">
                                    <i class="fas fa-user-plus text-success me-1"></i>
                                    {{ $product->owner->name ?? 'Admin' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Created On:</span>
                                <span class="small text-muted">{{ !empty($product->created_at) ? $product->created_at->format('d M Y, h:i A') : '-' }}</span>
                            </div>
                            @if(!empty($product->updated_at) && $product->updated_at != $product->created_at)
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Last Update:</span>
                                    <span class="small text-muted">{{ $product->updated_at->format('d M Y, h:i A') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Notification List -->
                        <div style="max-height: 380px; overflow-y: auto;">
                            @forelse($notifications ?? [] as $notif)
                                <div class="p-2 border rounded mb-2 bg-white shadow-sm" style="font-size: 13px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-primary text-white" style="font-size: 11px;">{{ $notif->title }}</span>
                                        <small class="text-muted" style="font-size: 11px;">
                                            <i class="far fa-clock me-1"></i>{{ $notif->created_at ? $notif->created_at->diffForHumans() : '-' }}
                                        </small>
                                    </div>
                                    <div class="text-dark mb-1">{{ $notif->description }}</div>
                                    <div class="small text-muted d-flex align-items-center gap-1">
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <span>By: <strong>{{ $notif->creator_name ?? ($notif->creator->name ?? 'Admin') }}</strong></span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-bell-slash fa-2x mb-2 text-muted"></i>
                                    <p class="mb-0 small">No notifications logged for this product yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            {{-- END RIGHT SIDE --}}
        </div>
    </div>
</div>

<script>
    function changeProductImage(imageSrc) {
        document.getElementById("mainProductImage").src = imageSrc;

        document.querySelectorAll(".product-thumbnail").forEach(function (item) {
            item.classList.remove("active");
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".product-thumbnail").forEach(function (thumbnail) {
            thumbnail.addEventListener("click", function () {
                document.querySelectorAll(".product-thumbnail").forEach(function (item) {
                    item.classList.remove("active");
                });

                this.classList.add("active");
            });
        });
    });
</script>

@endsection
