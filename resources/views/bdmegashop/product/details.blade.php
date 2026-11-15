@extends('bdpanel')
@section('content')
@php
    $regPrice = (float) ($product->price ?? 0);
    $sellPrice = (float) ($product->selling_price ?? $product->price ?? 0);
    $discPct = ($regPrice > $sellPrice && $regPrice > 0)
        ? round((($regPrice - $sellPrice) / $regPrice) * 100)
        : (int) ($product->discount ?? 0);
    $saveAmt = max(0, $regPrice - $sellPrice);

    $galleryImages = collect();
    if (!empty($product->image)) {
        $galleryImages->push(asset($product->image));
    }
    if (!empty($product->images)) {
        foreach ($product->images as $imgObj) {
            if (!empty($imgObj->image)) {
                $galleryImages->push(asset($imgObj->image));
            }
        }
    }
    $galleryImages = $galleryImages->unique()->values();
    if ($galleryImages->isEmpty()) {
        $galleryImages->push(asset('bdmegashop/dist/images/product-deatils/1-big-mage.jpg'));
    }
@endphp

<style>
    /* ============================================================
       1. TOP HERO BREADCRUMB BANNER (Matches /products page)
       ============================================================ */
    .bms-catalog-hero {
        background: linear-gradient(135deg, #03150d 0%, #062417 55%, #04391d 100%);
        padding: 38px 0 34px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
    }

    .bms-catalog-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0 0 10px 0;
        font-size: 13px;
        color: #97a39c;
    }

    .bms-catalog-breadcrumb li a {
        color: #cdd7d1;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .bms-catalog-breadcrumb li a:hover {
        color: #5fd47a;
    }

    .bms-catalog-breadcrumb li.active {
        color: #5fd47a;
        font-weight: 700;
    }

    .bms-catalog-hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 4px 0;
        letter-spacing: -0.4px;
    }

    .bms-catalog-hero-sub {
        font-size: 13.5px;
        color: #97a39c;
        margin: 0;
    }

    .bms-catalog-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 9px 18px;
        border-radius: 50px;
        color: #f7faf8;
        font-size: 13px;
        font-weight: 600;
    }

    /* ============================================================
       2. MAIN PRODUCT SHOWCASE AREA
       ============================================================ */
    .bms-detail-section {
        padding: 40px 0 65px;
        background: #f7faf8;
    }

    .bms-detail-main-card {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 22px;
        padding: 32px;
        box-shadow: 0 8px 30px rgba(6, 36, 23, 0.04);
        margin-bottom: 32px;
    }

    /* Left Gallery */
    .bms-gallery-wrap {
        position: sticky;
        top: 95px;
    }

    .bms-main-img-stage {
        position: relative;
        height: 430px;
        background: #f7faf8;
        border: 1.5px solid #e2e9e4;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .bms-main-img-stage img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 12px;
        transition: transform 0.4s ease;
    }

    .bms-main-img-stage:hover img {
        transform: scale(1.05);
    }

    .bms-stage-badges {
        position: absolute;
        top: 16px;
        left: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 3;
    }

    .bms-badge-discount {
        background: linear-gradient(135deg, #d81e23 0%, #b5101a 100%);
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(216, 30, 35, 0.3);
    }

    .bms-thumb-strip {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .bms-thumb-item {
        width: 76px;
        height: 76px;
        border-radius: 12px;
        border: 2px solid #e2e9e4;
        background: #f7faf8;
        overflow: hidden;
        cursor: pointer;
        flex-shrink: 0;
        padding: 4px;
        transition: all 0.2s ease;
    }

    .bms-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .bms-thumb-item:hover,
    .bms-thumb-item.active {
        border-color: #0b7a3d;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(11, 122, 61, 0.18);
        transform: translateY(-2px);
    }

    /* Right Product Info */
    .bms-detail-meta-top {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .bms-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 12px;
        border-radius: 50px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-meta-pill.cat {
        background: rgba(11, 122, 61, 0.10);
        color: #0b7a3d;
    }

    .bms-meta-pill.cat:hover {
        background: #0b7a3d;
        color: #ffffff;
    }

    .bms-meta-pill.subcat {
        background: #f1f5f2;
        color: #48564f;
    }

    .bms-meta-pill.subcat:hover {
        background: #e2e9e4;
        color: #062417;
    }

    .bms-meta-pill.brand {
        background: rgba(14, 107, 79, 0.10);
        color: #0a5640;
    }

    .bms-detail-title {
        font-size: 28px;
        font-weight: 800;
        color: #062417;
        line-height: 1.3;
        margin: 0 0 12px 0;
    }

    .bms-rating-stock-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding-bottom: 16px;
        margin-bottom: 18px;
        border-bottom: 1px solid #f1f5f2;
    }

    .bms-stars-box {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #f59e0b;
        font-size: 13px;
    }

    .bms-stars-box span {
        color: #48564f;
        font-weight: 700;
        margin-left: 4px;
    }

    .bms-sku-tag {
        font-size: 12.5px;
        font-weight: 600;
        color: #65736c;
        background: #f7faf8;
        border: 1px solid #e2e9e4;
        padding: 4px 11px;
        border-radius: 6px;
    }

    .stock-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 12.5px;
        font-weight: 700;
    }

    .stock-status-badge.in-stock {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .stock-status-badge.out-of-stock {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Price Box */
    .bms-detail-price-box {
        background: linear-gradient(135deg, #f7faf8 0%, #edf7f1 100%);
        border: 1.5px solid #d3ecdc;
        border-radius: 16px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .bms-detail-sell-price {
        font-size: 30px;
        font-weight: 800;
        color: #062417;
        line-height: 1;
    }

    .bms-detail-reg-price {
        font-size: 16px;
        font-weight: 600;
        color: #97a39c;
        text-decoration: line-through;
        margin-left: 10px;
    }

    .bms-detail-save-pill {
        background: #16a330;
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(22, 163, 48, 0.25);
    }

    .bms-short-desc {
        color: #48564f;
        font-size: 14.5px;
        line-height: 1.7;
        margin-bottom: 22px;
    }

    /* Selectors: Sizes, Colors, Quantity (Isolated from legacy main.css) */
    .bms-variant-section {
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-bottom: 22px;
        padding: 16px 18px;
        background: #f7faf8;
        border: 1px solid #e2e9e4;
        border-radius: 14px;
    }

    .bms-selector-group {
        margin: 0;
    }

    .bms-selector-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .bms-selector-label {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #48564f;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bms-selector-active-val {
        font-size: 12.5px;
        font-weight: 800;
        color: #0b7a3d;
        background: rgba(11, 122, 61, 0.10);
        padding: 2px 10px;
        border-radius: 50px;
        text-transform: capitalize;
    }

    .bms-size-options,
    .bms-color-options {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .bms-size-option-btn {
        min-width: 48px;
        height: 40px;
        padding: 0 16px;
        border: 1.5px solid #cdd7d1;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: #17261e;
        background: #ffffff;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        user-select: none;
    }

    .bms-size-option-btn:hover {
        border-color: #0b7a3d;
        color: #0b7a3d;
        transform: translateY(-1px);
    }

    .bms-size-option-btn.active {
        background: linear-gradient(135deg, #0b7a3d 0%, #035c2d 100%) !important;
        border-color: #0b7a3d !important;
        color: #ffffff !important;
        box-shadow: 0 6px 14px rgba(11, 122, 61, 0.25);
    }

    .bms-color-option-btn {
        width: auto !important;
        height: 42px !important;
        padding: 6px 15px 6px 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 9px !important;
        border: 1.5px solid #cdd7d1 !important;
        border-radius: 50px !important;
        background-color: #ffffff !important;
        color: #17261e !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        cursor: pointer;
        transition: all 0.22s ease;
        user-select: none;
        box-shadow: 0 2px 6px rgba(6, 36, 23, 0.03);
    }

    .bms-color-option-btn:hover {
        border-color: #0b7a3d !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(11, 122, 61, 0.12);
    }

    .bms-color-option-btn.active {
        border: 2px solid #0b7a3d !important;
        background-color: #edf7f1 !important;
        color: #0b7a3d !important;
        box-shadow: 0 6px 16px rgba(11, 122, 61, 0.18);
    }

    .bms-color-dot {
        width: 22px;
        height: 22px;
        min-width: 22px;
        border-radius: 50%;
        border: 2px solid rgba(6, 36, 23, 0.15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .bms-color-option-btn.active .bms-color-dot {
        border-color: #0b7a3d;
        transform: scale(1.06);
    }

    .bms-color-name {
        line-height: 1;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .bms-color-check {
        font-size: 11px;
        color: #0b7a3d;
        display: none;
        margin-left: 2px;
    }

    .bms-color-option-btn.active .bms-color-check {
        display: inline-block;
    }

    /* Action Row: Quantity + Add to Cart + Buy Now + Wishlist */
    .bms-purchase-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
        padding-bottom: 24px;
        border-bottom: 1px solid #f1f5f2;
    }

    .bms-qty-stepper {
        display: inline-flex;
        align-items: center;
        background: #f7faf8;
        border: 1.5px solid #cdd7d1;
        border-radius: 12px;
        height: 50px;
        padding: 0 6px;
    }

    .bms-qty-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 0;
        background: transparent;
        color: #062417;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .bms-qty-btn:hover {
        background: #e2e9e4;
        color: #0b7a3d;
    }

    .bms-qty-stepper input {
        width: 48px;
        border: 0;
        background: transparent;
        text-align: center;
        font-size: 16px;
        font-weight: 800;
        color: #062417;
        outline: none;
    }

    .bms-btn-add-cart {
        height: 50px;
        padding: 0 28px;
        border-radius: 12px;
        border: 0;
        background: linear-gradient(135deg, #0b7a3d 0%, #035c2d 100%);
        color: #ffffff !important;
        font-size: 15px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(11, 122, 61, 0.28);
        transition: all 0.25s ease;
        flex: 1;
        min-width: 190px;
    }

    .bms-btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(11, 122, 61, 0.38);
    }

    .bms-btn-buy-now {
        height: 50px;
        padding: 0 24px;
        border-radius: 12px;
        border: 1.5px solid #062417;
        background: #062417;
        color: #ffffff !important;
        font-size: 14.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none !important;
    }

    .bms-btn-buy-now:hover {
        background: #17261e;
        transform: translateY(-2px);
    }

    .btn-out-of-stock,
    .bms-btn-add-cart:disabled {
        background: #97a39c !important;
        color: #ffffff !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        transform: none !important;
    }

    .bms-btn-wishlist {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        border: 1.5px solid #e2e9e4;
        background: #ffffff;
        color: #48564f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .bms-btn-wishlist:hover {
        border-color: #d81e23;
        color: #d81e23;
        background: #fef2f2;
    }

    /* Trust & Delivery Highlights Grid */
    .bms-trust-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .bms-trust-pill {
        display: flex;
        align-items: center;
        gap: 11px;
        background: #f7faf8;
        border: 1px solid #e2e9e4;
        border-radius: 12px;
        padding: 11px 14px;
    }

    .bms-trust-pill-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(11, 122, 61, 0.10);
        color: #0b7a3d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .bms-trust-pill-text strong {
        display: block;
        font-size: 12.5px;
        font-weight: 800;
        color: #062417;
        line-height: 1.2;
    }

    .bms-trust-pill-text span {
        font-size: 11.5px;
        color: #65736c;
    }

    /* ============================================================
       3. DESCRIPTION (LEFT) + MORE PRODUCTS PANEL (RIGHT)
       ============================================================ */
    .bms-content-panel {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 20px;
        padding: 28px 30px;
        box-shadow: 0 6px 24px rgba(6, 36, 23, 0.03);
        height: 100%;
    }

    .bms-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        margin-bottom: 20px;
        border-bottom: 2px solid #f1f5f2;
        position: relative;
    }

    .bms-panel-header::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 64px;
        height: 3px;
        background: #0b7a3d;
        border-radius: 3px;
    }

    .bms-panel-title {
        font-size: 20px;
        font-weight: 800;
        color: #062417;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .bms-panel-title i {
        color: #0b7a3d;
        font-size: 18px;
    }

    .bms-desc-body {
        color: #2f3d36;
        font-size: 15px;
        line-height: 1.85;
    }

    .bms-spec-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e9e4;
    }

    .bms-spec-table th,
    .bms-spec-table td {
        padding: 11px 16px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f2;
    }

    .bms-spec-table th {
        width: 34%;
        background: #f7faf8;
        color: #48564f;
        font-weight: 700;
    }

    .bms-spec-table td {
        color: #062417;
        font-weight: 600;
    }

    /* Right Side "More Products" Panel */
    .bms-more-products-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .bms-more-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 11px;
        border-radius: 14px;
        border: 1px solid #f1f5f2;
        background: #f7faf8;
        transition: all 0.25s ease;
        text-decoration: none !important;
    }

    .bms-more-item:hover {
        background: #ffffff;
        border-color: #cdd7d1;
        transform: translateX(4px);
        box-shadow: 0 8px 20px rgba(6, 36, 23, 0.06);
    }

    .bms-more-thumb {
        position: relative;
        width: 74px;
        height: 74px;
        border-radius: 11px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e2e9e4;
        flex-shrink: 0;
    }

    .bms-more-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .bms-more-item:hover .bms-more-thumb img {
        transform: scale(1.08);
    }

    .bms-more-info {
        flex: 1;
        min-width: 0;
    }

    .bms-more-cat {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0b7a3d;
        display: block;
        margin-bottom: 2px;
    }

    .bms-more-title {
        font-size: 14px;
        font-weight: 700;
        color: #062417;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
        text-decoration: none !important;
    }

    .bms-more-title:hover {
        color: #0b7a3d;
    }

    .bms-more-price {
        font-size: 14px;
        font-weight: 800;
        color: #062417;
    }

    .bms-more-old {
        font-size: 11.5px;
        color: #97a39c;
        text-decoration: line-through;
        margin-left: 5px;
    }

    .bms-more-cart-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e2e9e4;
        background: #ffffff;
        color: #0b7a3d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .bms-more-cart-btn:hover {
        background: #0b7a3d;
        color: #ffffff;
        border-color: #0b7a3d;
    }

    /* ============================================================
       4. FEATURED PRODUCTS SECTION (4 PER ROW .bms-pcard)
       ============================================================ */
    .bms-featured-sec {
        padding: 65px 0 80px;
        background: #ffffff;
        border-top: 1px solid #e2e9e4;
    }

    .bms-sec-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 14px;
    }

    .bms-sec-tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        color: #0b7a3d;
        background: rgba(11, 122, 61, 0.08);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .bms-sec-title {
        font-size: 30px;
        font-weight: 800;
        color: #062417;
        margin: 0;
    }

    .bms-sec-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #0b7a3d !important;
        background: rgba(11, 122, 61, 0.08);
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-sec-link:hover {
        background: #0b7a3d;
        color: #ffffff !important;
    }

    .bms-pcard {
        background: #ffffff;
        border: 1px solid #e2e9e4;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 0 4px 14px rgba(6, 36, 23, 0.03);
    }

    .bms-pcard:hover {
        transform: translateY(-7px);
        border-color: #cdd7d1;
        box-shadow: 0 18px 36px rgba(6, 36, 23, 0.10);
    }

    .bms-pcard-thumb {
        position: relative;
        height: 245px;
        background: #f7faf8;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bms-pcard-thumb a.thumb-link {
        display: block;
        width: 100%;
        height: 100%;
    }

    .bms-pcard-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .bms-pcard:hover .bms-pcard-thumb img {
        transform: scale(1.07);
    }

    .bms-pcard-badges {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        z-index: 3;
    }

    .bms-pbadge-sale {
        background: linear-gradient(135deg, #d81e23 0%, #b5101a 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(216, 30, 35, 0.3);
    }

    .bms-pcard-actions {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 3;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.25s ease;
    }

    .bms-pcard:hover .bms-pcard-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .bms-pcard-act-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ffffff;
        color: #17261e !important;
        border: 1px solid #e2e9e4;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(6, 36, 23, 0.12);
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .bms-pcard-act-btn:hover {
        background: #0b7a3d;
        color: #ffffff !important;
        border-color: #0b7a3d;
    }

    .bms-pcard-body {
        padding: 16px 18px 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .bms-pcard-cat {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0b7a3d;
        margin-bottom: 5px;
    }

    .bms-pcard-title {
        font-size: 15px;
        font-weight: 700;
        color: #062417;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
        text-decoration: none !important;
    }

    .bms-pcard-title:hover {
        color: #0b7a3d;
    }

    .bms-pcard-price-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 8px;
        margin-top: auto;
        margin-bottom: 13px;
    }

    .bms-pcard-price {
        font-size: 18px;
        font-weight: 800;
        color: #062417;
    }

    .bms-pcard-old-price {
        font-size: 12.5px;
        font-weight: 600;
        color: #97a39c;
        text-decoration: line-through;
        margin-left: 5px;
    }

    .bms-pcard-btn {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #0b7a3d;
        background: rgba(11, 122, 61, 0.06);
        color: #0b7a3d;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .bms-pcard-btn:hover {
        background: #0b7a3d;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(11, 122, 61, 0.25);
    }

    @media (max-width: 767px) {
        .bms-detail-main-card {
            padding: 20px;
        }
        .bms-main-img-stage {
            height: 320px;
        }
        .bms-detail-title {
            font-size: 22px;
        }
    }
</style>

<!-- 1. Top Hero Breadcrumb Banner (Matches /products page) -->
<section class="bms-catalog-hero">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
            <div>
                <ul class="bms-catalog-breadcrumb">
                    <li><a href="{{ url('/') }}"><i class="fas fa-home mr-1"></i>Home</a></li>
                    <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                    <li><a href="{{ url('products') }}">Shop Catalog</a></li>
                    @if(!empty($product->category))
                        <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                        <li>
                            <a href="{{ url('products?category=' . $product->category->id) }}">
                                {{ $product->category->title }}
                            </a>
                        </li>
                    @endif
                    @if(!empty($product->subCategory))
                        <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                        <li>
                            <a href="{{ url('products?category=' . ($product->category_id ?? '') . '&sub_category=' . $product->subCategory->id) }}">
                                {{ $product->subCategory->title }}
                            </a>
                        </li>
                    @endif
                    <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                    <li class="active">{{ $product->title ?? 'Product Details' }}</li>
                </ul>
                <h1 class="bms-catalog-hero-title">{{ $product->title ?? 'Product Details' }}</h1>
                <p class="bms-catalog-hero-sub">
                    Genuine quality assurance, fast nationwide delivery, and easy Cash on Delivery across Bangladesh.
                </p>
            </div>
            <div>
                <div class="bms-catalog-stat-pill">
                    @if(($current_stock ?? 0) > 0)
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>Status: <strong style="color: #34d399;">In Stock ({{ $current_stock }})</strong></span>
                    @else
                        <i class="fas fa-times-circle" style="color: #f87171;"></i>
                        <span>Status: <strong style="color: #f87171;">Out of Stock</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Product Details & Description + More Products Section -->
<section class="bms-detail-section">
    <div class="container">
        <!-- Main Product Showcase Card -->
        <div class="bms-detail-main-card">
            <div class="row">
                <!-- Left Column: Product Gallery -->
                <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                    <div class="bms-gallery-wrap">
                        <div class="bms-main-img-stage">
                            <div class="bms-stage-badges">
                                @if($discPct > 0)
                                    <span class="bms-badge-discount">-{{ $discPct }}% OFF</span>
                                @endif
                            </div>
                            <img src="{{ $galleryImages->first() }}" id="bmsMainDetailImage" alt="{{ $product->title ?? 'Product Image' }}">
                        </div>

                        @if($galleryImages->count() > 1)
                        <div class="bms-thumb-strip">
                            @foreach($galleryImages as $idx => $imgUrl)
                                <div class="bms-thumb-item {{ $idx === 0 ? 'active' : '' }}"
                                     onclick="switchDetailImage('{{ $imgUrl }}', this)">
                                    <img src="{{ $imgUrl }}" alt="Thumbnail {{ $idx + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Product Information & Purchase Controls -->
                <div class="col-lg-7 col-md-6">
                    <div class="bms-detail-meta-top">
                        @if(!empty($product->category))
                            <a href="{{ url('products?category=' . $product->category->id) }}" class="bms-meta-pill cat">
                                <i class="fas fa-folder-open"></i> {{ $product->category->title }}
                            </a>
                        @endif
                        @if(!empty($product->subCategory))
                            <a href="{{ url('products?category=' . ($product->category_id ?? '') . '&sub_category=' . $product->subCategory->id) }}" class="bms-meta-pill subcat">
                                <i class="fas fa-tag"></i> {{ $product->subCategory->title }}
                            </a>
                        @endif
                        @if(!empty($product->brand->title))
                            <a href="{{ url('products?brand=' . $product->brand->id) }}" class="bms-meta-pill brand">
                                <i class="fas fa-award"></i> {{ $product->brand->title }}
                            </a>
                        @endif
                    </div>

                    <h2 class="bms-detail-title">{{ $product->title ?? '' }}</h2>

                    <div class="bms-rating-stock-bar">
                        <div class="bms-stars-box">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>4.9 Rating</span>
                        </div>

                        @if(!empty($product->product_code))
                            <span class="bms-sku-tag">SKU: <strong>{{ $product->product_code }}</strong></span>
                        @endif

                        @if(($current_stock ?? 0) > 0)
                            <span class="stock-status-badge in-stock">
                                <i class="fas fa-check-circle"></i> In Stock ({{ $current_stock }} available)
                            </span>
                        @else
                            <span class="stock-status-badge out-of-stock">
                                <i class="fas fa-times-circle"></i> Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Highlighted Price Box -->
                    <div class="bms-detail-price-box">
                        <div>
                            <span class="bms-detail-sell-price">BDT {{ number_format($sellPrice, 0) }}</span>
                            @if($regPrice > $sellPrice)
                                <span class="bms-detail-reg-price">BDT {{ number_format($regPrice, 0) }}</span>
                            @endif
                        </div>
                        @if($saveAmt > 0)
                            <span class="bms-detail-save-pill">
                                <i class="fas fa-bolt mr-1"></i> Save BDT {{ number_format($saveAmt, 0) }} ({{ $discPct }}%)
                            </span>
                        @endif
                    </div>

                    @if(!empty($product->description))
                        <div class="bms-short-desc">
                            {!! $product->description !!}
                        </div>
                    @endif

                    <!-- Size & Color Selectors -->
                    @if((!empty($available_sizes) && $available_sizes->count() > 0) || (!empty($available_colors) && $available_colors->count() > 0))
                    @php
                        $colorMap = [
                            'black'      => '#0f172a',
                            'white'      => '#ffffff',
                            'red'        => '#ef4444',
                            'blue'       => '#2563eb',
                            'navy'       => '#1e3a8a',
                            'navy blue'  => '#1e3a8a',
                            'sky blue'   => '#38bdf8',
                            'green'      => '#16a34a',
                            'yellow'     => '#eab308',
                            'orange'     => '#f97316',
                            'pink'       => '#ec4899',
                            'purple'     => '#9333ea',
                            'violet'     => '#7c3aed',
                            'gray'       => '#64748b',
                            'grey'       => '#64748b',
                            'silver'     => '#cbd5e1',
                            'gold'       => '#d97706',
                            'golden'     => '#d97706',
                            'rose gold'  => '#f43f5e',
                            'brown'      => '#78350f',
                            'maroon'     => '#881337',
                            'cyan'       => '#06b6d4',
                            'teal'       => '#0d9488',
                            'beige'      => '#f5f5dc',
                            'cream'      => '#fffdd0',
                        ];
                    @endphp
                    <div class="bms-variant-section">
                        @if(!empty($available_sizes) && $available_sizes->count() > 0)
                        @php
                            $firstSize = $available_sizes->first()->sizeData->title ?? $available_sizes->first()->size;
                        @endphp
                        <div class="bms-selector-group">
                            <div class="bms-selector-header">
                                <span class="bms-selector-label"><i class="fas fa-ruler-combined text-primary"></i> Select Size</span>
                                <span class="bms-selector-active-val" id="bmsSelectedSizeLabel">{{ $firstSize }}</span>
                            </div>
                            <div class="bms-size-options" id="bmsSizeList">
                                @foreach($available_sizes as $detail)
                                    @php
                                        $sizeTitle = $detail->sizeData->title ?? $detail->size;
                                    @endphp
                                    <button type="button"
                                            class="bms-size-option-btn {{ $loop->first ? 'active' : '' }}"
                                            data-size="{{ $sizeTitle }}"
                                            onclick="selectVariantPill(this, '#bmsSizeList .bms-size-option-btn', 'bmsSelectedSizeLabel', '{{ addslashes($sizeTitle) }}')">
                                        {{ $sizeTitle }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!empty($available_colors) && $available_colors->count() > 0)
                        @php
                            $firstColor = $available_colors->first()->colorData->title ?? $available_colors->first()->color;
                        @endphp
                        <div class="bms-selector-group">
                            <div class="bms-selector-header">
                                <span class="bms-selector-label"><i class="fas fa-palette text-primary"></i> Select Color</span>
                                <span class="bms-selector-active-val" id="bmsSelectedColorLabel">{{ $firstColor }}</span>
                            </div>
                            <div class="bms-color-options" id="bmsColorList">
                                @foreach($available_colors as $detail)
                                    @php
                                        $colorCode = $detail->colorData->code ?? null;
                                        $colorTitle = trim((string) ($detail->colorData->title ?? $detail->color));
                                        $lowerTitle = strtolower($colorTitle);
                                        $swatchBg = !empty($colorCode)
                                            ? $colorCode
                                            : ($colorMap[$lowerTitle] ?? $lowerTitle);
                                    @endphp
                                    <button type="button"
                                            class="bms-color-option-btn {{ $loop->first ? 'active' : '' }}"
                                            data-color="{{ $colorTitle }}"
                                            title="{{ $colorTitle }}"
                                            onclick="selectVariantPill(this, '#bmsColorList .bms-color-option-btn', 'bmsSelectedColorLabel', '{{ addslashes($colorTitle) }}')">
                                        <span class="bms-color-dot" style="background: {{ $swatchBg }};"></span>
                                        <span class="bms-color-name">{{ $colorTitle }}</span>
                                        <i class="fas fa-check bms-color-check"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Quantity & Add to Cart Buttons -->
                    <div class="bms-purchase-actions">
                        <div class="bms-qty-stepper">
                            <a href="javascript:void(0)" class="bms-qty-btn" id="detailQtyMinus"
                               style="{{ ($current_stock ?? 0) <= 0 ? 'pointer-events:none;opacity:0.4;' : '' }}">
                                <i class="fas fa-minus"></i>
                            </a>
                            <input type="text" id="detailQtyInput" value="1" min="1"
                                   max="{{ max(1, $current_stock ?? 1) }}" {{ ($current_stock ?? 0) <= 0 ? 'disabled' : '' }}>
                            <a href="javascript:void(0)" class="bms-qty-btn" id="detailQtyPlus"
                               style="{{ ($current_stock ?? 0) <= 0 ? 'pointer-events:none;opacity:0.4;' : '' }}">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>

                        @if(($current_stock ?? 0) > 0)
                            <button type="button" class="bms-btn-add-cart cart-bg" id="detailAddToCartBtn" onclick="handleDetailAddToCart(false)">
                                <i class="fas fa-shopping-bag"></i> Add to Cart
                            </button>
                            <button type="button" class="bms-btn-buy-now" onclick="handleDetailAddToCart(true)">
                                <i class="fas fa-bolt"></i> Buy Now
                            </button>
                        @else
                            <button type="button" class="bms-btn-add-cart btn-out-of-stock" id="detailAddToCartBtn" disabled>
                                <i class="fas fa-ban"></i> Out of Stock
                            </button>
                        @endif

                        <a href="javascript:void(0)" class="bms-btn-wishlist"
                           onclick="toggleWishlist({{ $product->id }}, this)" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </a>
                    </div>

                    <!-- Trust & Shipping Highlights -->
                    <div class="bms-trust-grid">
                        <div class="bms-trust-pill">
                            <span class="bms-trust-pill-icon"><i class="fas fa-shipping-fast"></i></span>
                            <div class="bms-trust-pill-text">
                                <strong>
                                    @if(!is_null($product->shipping_charge) && (float) $product->shipping_charge > 0)
                                        Shipping: BDT {{ $product->shipping_charge }}
                                    @else
                                        Fast Delivery
                                    @endif
                                </strong>
                                <span>2–3 Days Nationwide</span>
                            </div>
                        </div>

                        <div class="bms-trust-pill">
                            <span class="bms-trust-pill-icon" style="color: #16a330; background: rgba(22, 163, 48, 0.1);"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="bms-trust-pill-text">
                                <strong>Cash on Delivery</strong>
                                <span>Pay at your doorstep</span>
                            </div>
                        </div>

                        <div class="bms-trust-pill">
                            <span class="bms-trust-pill-icon" style="color: #0e6b4f; background: rgba(14, 107, 79, 0.1);"><i class="fas fa-shield-alt"></i></span>
                            <div class="bms-trust-pill-text">
                                <strong>100% Authentic</strong>
                                <span>Quality Checked</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Product Description (Left) + More Products Sidebar Panel (Right) -->
        <div class="row">
            <!-- Left Column: Full Product Description & Specifications -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="bms-content-panel">
                    <div class="bms-panel-header">
                        <h4 class="bms-panel-title">
                            <i class="fas fa-align-left"></i> Product Description &amp; Overview
                        </h4>
                    </div>

                    <div class="bms-desc-body">
                        @if(!empty($product->long_description))
                            {!! $product->long_description !!}
                        @elseif(!empty($product->description))
                            {!! $product->description !!}
                        @else
                            <p>Experience premium quality and reliable performance with <strong>{{ $product->title }}</strong> from BD Mega Shop.</p>
                        @endif
                    </div>

                    <!-- Product Specifications Table -->
                    <table class="bms-spec-table">
                        <tbody>
                            <tr>
                                <th>Product Name</th>
                                <td>{{ $product->title ?? 'N/A' }}</td>
                            </tr>
                            @if(!empty($product->product_code))
                            <tr>
                                <th>Product Code / SKU</th>
                                <td>{{ $product->product_code }}</td>
                            </tr>
                            @endif
                            @if(!empty($product->category->title))
                            <tr>
                                <th>Category</th>
                                <td>{{ $product->category->title }}</td>
                            </tr>
                            @endif
                            @if(!empty($product->subCategory->title))
                            <tr>
                                <th>Sub-Category</th>
                                <td>{{ $product->subCategory->title }}</td>
                            </tr>
                            @endif
                            @if(!empty($product->brand->title))
                            <tr>
                                <th>Brand</th>
                                <td>{{ $product->brand->title }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Stock Availability</th>
                                <td>
                                    @if(($current_stock ?? 0) > 0)
                                        <span class="text-success font-weight-bold">In Stock ({{ $current_stock }} units)</span>
                                    @else
                                        <span class="text-danger font-weight-bold">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: More Products Panel -->
            <div class="col-lg-4">
                <div class="bms-content-panel">
                    <div class="bms-panel-header">
                        <h4 class="bms-panel-title">
                            <i class="fas fa-layer-group"></i> More Products
                        </h4>
                        <a href="{{ url('products') }}" style="font-size: 12.5px; font-weight: 700; color: #0b7a3d; text-decoration: none;">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="bms-more-products-list">
                        @forelse($more_products ?? [] as $moreItem)
                            @php
                                $mRegPrice = (float) ($moreItem->price ?? 0);
                                $mSellPrice = (float) ($moreItem->selling_price ?? $moreItem->price ?? 0);
                            @endphp
                            <div class="bms-more-item">
                                <a href="{{ url('details/' . ($moreItem->slug ?? $moreItem->id)) }}" class="bms-more-thumb">
                                    <img src="{{ !empty($moreItem->image) ? asset($moreItem->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                         alt="{{ $moreItem->title }}">
                                </a>
                                <div class="bms-more-info">
                                    <span class="bms-more-cat">{{ $moreItem->category->title ?? 'Collection' }}</span>
                                    <a href="{{ url('details/' . ($moreItem->slug ?? $moreItem->id)) }}" class="bms-more-title">
                                        {{ $moreItem->title }}
                                    </a>
                                    <div>
                                        <span class="bms-more-price">BDT {{ number_format($mSellPrice, 0) }}</span>
                                        @if($mRegPrice > $mSellPrice)
                                            <span class="bms-more-old">BDT {{ number_format($mRegPrice, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" class="bms-more-cart-btn"
                                        onclick="addToCart({ product_id: {{ $moreItem->id }}, quantity: 1 })"
                                        title="Quick Add to Bag">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">No other products found.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <a href="{{ url('products') }}" class="bms-pcard-btn" style="text-decoration: none;">
                            Explore All Products <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Featured Products Section (4 Products Per Row) -->
@if(!empty($featured_products) && $featured_products->count() > 0)
<section class="bms-featured-sec">
    <div class="container">
        <div class="bms-sec-header">
            <div>
                <span class="bms-sec-tag">Handpicked For You</span>
                <h2 class="bms-sec-title">Featured Products</h2>
            </div>
            <a href="{{ url('products') }}" class="bms-sec-link">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row">
            @foreach($featured_products->take(8) as $feat_product)
                @php
                    $fRegPrice = (float) ($feat_product->price ?? 0);
                    $fSellPrice = (float) ($feat_product->selling_price ?? $feat_product->price ?? 0);
                    $fDiscPct = ($fRegPrice > $fSellPrice && $fRegPrice > 0)
                        ? round((($fRegPrice - $fSellPrice) / $fRegPrice) * 100)
                        : (int) ($feat_product->discount ?? 0);
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="bms-pcard">
                        <div class="bms-pcard-thumb">
                            <div class="bms-pcard-badges">
                                @if($fDiscPct > 0)
                                    <span class="bms-pbadge-sale">-{{ $fDiscPct }}% OFF</span>
                                @endif
                            </div>
                            <div class="bms-pcard-actions">
                                <a href="javascript:void(0)" class="bms-pcard-act-btn"
                                   onclick="toggleWishlist({{ $feat_product->id }}, this)" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </a>
                                <a href="{{ url('details/' . ($feat_product->slug ?? $feat_product->id)) }}"
                                   class="bms-pcard-act-btn" title="View Details">
                                    <i class="far fa-eye"></i>
                                </a>
                            </div>
                            <a href="{{ url('details/' . ($feat_product->slug ?? $feat_product->id)) }}" class="thumb-link">
                                <img src="{{ !empty($feat_product->image) ? asset($feat_product->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                     alt="{{ $feat_product->title ?? 'Product Name' }}">
                            </a>
                        </div>
                        <div class="bms-pcard-body">
                            <span class="bms-pcard-cat">{{ $feat_product->category->title ?? 'Featured' }}</span>
                            <a href="{{ url('details/' . ($feat_product->slug ?? $feat_product->id)) }}" class="bms-pcard-title">
                                {{ $feat_product->title ?? '' }}
                            </a>
                            <div class="bms-pcard-price-row">
                                <div>
                                    <span class="bms-pcard-price">BDT {{ number_format($fSellPrice, 0) }}</span>
                                    @if($fRegPrice > $fSellPrice)
                                        <span class="bms-pcard-old-price">BDT {{ number_format($fRegPrice, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="bms-pcard-btn"
                                    onclick="addToCart({ product_id: {{ $feat_product->id }}, quantity: 1 })">
                                <i class="fas fa-shopping-bag"></i> Add to Bag
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<script>
    function switchDetailImage(imgUrl, thumbEl) {
        var mainImg = document.getElementById('bmsMainDetailImage');
        if (mainImg) {
            mainImg.src = imgUrl;
        }
        document.querySelectorAll('.bms-thumb-item').forEach(function (el) {
            el.classList.remove('active');
        });
        if (thumbEl) {
            thumbEl.classList.add('active');
        }
    }

    function selectVariantPill(clickedEl, selector, labelId, labelValue) {
        document.querySelectorAll(selector).forEach(function (el) {
            el.classList.remove('active');
        });
        clickedEl.classList.add('active');
        if (labelId && labelValue !== undefined) {
            var labelEl = document.getElementById(labelId);
            if (labelEl) {
                labelEl.textContent = labelValue;
            }
        }
    }

    (function() {
        var maxStock = {{ (int) ($current_stock ?? 0) }};
        var qtyInput = document.getElementById('detailQtyInput');
        var minusBtn = document.getElementById('detailQtyMinus');
        var plusBtn = document.getElementById('detailQtyPlus');

        if (minusBtn && qtyInput) {
            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (maxStock <= 0) return;
                var currentVal = parseInt(qtyInput.value, 10) || 1;
                if (currentVal > 1) {
                    qtyInput.value = currentVal - 1;
                }
            });
        }

        if (plusBtn && qtyInput) {
            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (maxStock <= 0) return;
                var currentVal = parseInt(qtyInput.value, 10) || 1;
                if (currentVal < maxStock) {
                    qtyInput.value = currentVal + 1;
                }
            });
        }

        if (qtyInput) {
            qtyInput.addEventListener('change', function() {
                var val = parseInt(qtyInput.value, 10) || 1;
                if (val < 1) val = 1;
                if (maxStock > 0 && val > maxStock) val = maxStock;
                qtyInput.value = val;
            });
        }
    })();

    function handleDetailAddToCart(redirectToCheckout) {
        var maxStock = {{ (int) ($current_stock ?? 0) }};
        if (maxStock <= 0) {
            return;
        }

        var qtyInput = document.getElementById('detailQtyInput');
        var qty = qtyInput ? (parseInt(qtyInput.value, 10) || 1) : 1;
        if (qty > maxStock) {
            qty = maxStock;
        }

        var activeSizeEl = document.querySelector('#bmsSizeList .bms-size-option-btn.active');
        var selectedSize = activeSizeEl ? activeSizeEl.getAttribute('data-size') : null;

        var activeColorEl = document.querySelector('#bmsColorList .bms-color-option-btn.active');
        var selectedColor = activeColorEl ? activeColorEl.getAttribute('data-color') : null;

        addToCart({
            product_id: {{ $product->id }},
            quantity: qty,
            size: selectedSize,
            color: selectedColor
        });

        if (redirectToCheckout) {
            setTimeout(function () {
                window.location.href = "{{ url('checkout') }}";
            }, 350);
        }
    }
</script>
@endsection
