@extends('bdpanel')
@section('content')
@php
    $selectedCategory = !empty($categories) && request('category')
        ? $categories->firstWhere('id', (int) request('category'))
        : null;
    $selectedSubCategory = !empty($sub_categories) && request('sub_category')
        ? $sub_categories->firstWhere('id', (int) request('sub_category'))
        : null;
    $selectedBrand = !empty($brands) && request('brand')
        ? $brands->firstWhere('id', (int) request('brand'))
        : null;
    $hasActiveFilters = request()->hasAny(['search', 'category', 'sub_category', 'brand', 'min_price', 'max_price', 'in_stock', 'on_sale'])
        && collect(['search', 'category', 'sub_category', 'brand', 'min_price', 'max_price', 'in_stock', 'on_sale'])->contains(fn($k) => filled(request($k)));
@endphp

<style>
    /* ============================================================
       1. CATALOG HERO & BREADCRUMB BANNER
       ============================================================ */
    .bms-catalog-hero {
        background: linear-gradient(135deg, #070d1f 0%, #0f172a 55%, #1e1b4b 100%);
        padding: 42px 0 38px;
        position: relative;
        overflow: hidden;
        color: #ffffff;
    }

    .bms-catalog-hero::before {
        content: '';
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(51, 90, 255, 0.22) 0%, transparent 70%);
        top: -180px;
        right: -100px;
        pointer-events: none;
    }

    .bms-catalog-hero::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.14) 0%, transparent 70%);
        bottom: -140px;
        left: 10%;
        pointer-events: none;
    }

    .bms-catalog-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0 0 12px 0;
        font-size: 13px;
        color: #94a3b8;
    }

    .bms-catalog-breadcrumb li a {
        color: #cbd5e1;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .bms-catalog-breadcrumb li a:hover {
        color: #60a5fa;
    }

    .bms-catalog-breadcrumb li.active {
        color: #60a5fa;
        font-weight: 700;
    }

    .bms-catalog-hero-title {
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 6px 0;
        letter-spacing: -0.5px;
    }

    .bms-catalog-hero-sub {
        font-size: 14px;
        color: #94a3b8;
        margin: 0;
    }

    .bms-catalog-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 10px 18px;
        border-radius: 50px;
        color: #f8fafc;
        font-size: 13px;
        font-weight: 600;
    }

    .bms-catalog-stat-pill strong {
        color: #60a5fa;
        font-weight: 800;
    }

    /* Quick Sub-Category Bar */
    .bms-subcat-strip {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
    }

    .bms-subcat-scroll {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 2px;
        scrollbar-width: thin;
    }

    .bms-subcat-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .bms-subcat-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .bms-subcat-label {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        white-space: nowrap;
        margin-right: 4px;
    }

    .bms-subcat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 15px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-decoration: none !important;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .bms-subcat-pill:hover {
        border-color: #335aff;
        color: #335aff;
        background: rgba(51, 90, 255, 0.06);
        transform: translateY(-1px);
    }

    .bms-subcat-pill.active {
        background: linear-gradient(135deg, #335aff 0%, #2545d8 100%);
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 6px 14px rgba(51, 90, 255, 0.25);
    }

    .bms-subcat-pill .count {
        font-size: 11px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 20px;
        background: rgba(15, 23, 42, 0.07);
        color: inherit;
    }

    .bms-subcat-pill.active .count {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    /* ============================================================
       2. SIDEBAR FILTER PANEL
       ============================================================ */
    .bms-catalog-wrap {
        padding: 36px 0 80px;
        background: #f8fafc;
    }

    .bms-sidebar-panel {
        position: sticky;
        top: 90px;
    }

    .bms-filter-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 18px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    }

    .bms-filter-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #f1f5f9;
    }

    .bms-filter-box-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bms-filter-box-title i {
        color: #335aff;
        font-size: 14px;
    }

    /* Search Input in Sidebar */
    .bms-sidebar-search {
        position: relative;
    }

    .bms-sidebar-search input {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 10px;
        padding: 10px 42px 10px 14px;
        font-size: 13.5px;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .bms-sidebar-search input:focus {
        background: #ffffff;
        border-color: #335aff;
        box-shadow: 0 0 0 3px rgba(51, 90, 255, 0.12);
        outline: none;
    }

    .bms-sidebar-search button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 0;
        background: #335aff;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .bms-sidebar-search button:hover {
        background: #2545d8;
    }

    /* Category & Nested Sub-Category Tree List */
    .bms-filter-tree {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 340px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .bms-filter-tree::-webkit-scrollbar {
        width: 5px;
    }

    .bms-filter-tree::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }

    .bms-filter-tree > li {
        margin-bottom: 5px;
    }

    .bms-tree-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-tree-link:hover {
        background: #f1f5f9;
        color: #335aff;
    }

    .bms-tree-link.active {
        background: rgba(51, 90, 255, 0.10);
        color: #335aff;
        font-weight: 700;
    }

    .bms-tree-badge {
        font-size: 11px;
        font-weight: 700;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 8px;
        border-radius: 20px;
        min-width: 26px;
        text-align: center;
    }

    .bms-tree-link.active .bms-tree-badge {
        background: #335aff;
        color: #ffffff;
    }

    .bms-subtree-list {
        list-style: none;
        padding: 4px 0 6px 14px;
        margin: 2px 0 6px 14px;
        border-left: 2px dashed #e2e8f0;
    }

    .bms-subtree-list li {
        margin-bottom: 3px;
    }

    .bms-subtree-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-subtree-link:hover {
        color: #335aff;
        background: rgba(51, 90, 255, 0.05);
    }

    .bms-subtree-link.active {
        color: #ffffff;
        background: #335aff;
        font-weight: 700;
    }

    .bms-subtree-link.active .bms-tree-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Price Range Filter */
    .bms-price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }

    .bms-price-field label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
    }

    .bms-price-field input {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .bms-price-field input:focus {
        border-color: #335aff;
        background: #ffffff;
        outline: none;
    }

    .bms-price-range-slider {
        width: 100%;
        accent-color: #335aff;
        margin: 6px 0 12px;
        cursor: pointer;
    }

    .bms-price-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
    }

    .bms-price-preset-btn {
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 9px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .bms-price-preset-btn:hover,
    .bms-price-preset-btn.active {
        background: rgba(51, 90, 255, 0.1);
        color: #335aff;
        border-color: #335aff;
    }

    .bms-apply-filter-btn {
        width: 100%;
        background: linear-gradient(135deg, #335aff 0%, #2545d8 100%);
        color: #ffffff;
        border: 0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 6px 14px rgba(51, 90, 255, 0.2);
    }

    .bms-apply-filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(51, 90, 255, 0.3);
    }

    /* Custom Checkbox Toggles */
    .bms-check-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
        background: #f8fafc;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .bms-check-option:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .bms-check-option input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: #335aff;
        cursor: pointer;
    }

    .bms-check-label {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        cursor: pointer;
    }

    /* ============================================================
       3. TOOLBAR & ACTIVE FILTER CHIPS
       ============================================================ */
    .bms-catalog-toolbar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    }

    .bms-toolbar-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }

    .bms-result-summary {
        font-size: 14px;
        color: #475569;
    }

    .bms-result-summary strong {
        color: #0f172a;
        font-weight: 800;
    }

    .bms-toolbar-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .bms-grid-switch {
        display: inline-flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 9px;
    }

    .bms-grid-btn {
        border: 0;
        background: transparent;
        color: #64748b;
        width: 34px;
        height: 32px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .bms-grid-btn.active {
        background: #ffffff;
        color: #335aff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    .bms-sort-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .bms-sort-select:focus {
        border-color: #335aff;
        background: #ffffff;
    }

    /* Active Filter Chips Row */
    .bms-active-chips {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
    }

    .bms-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(51, 90, 255, 0.08);
        color: #335aff;
        border: 1px solid rgba(51, 90, 255, 0.2);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-chip:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    .bms-chip-clear-all {
        font-size: 12px;
        font-weight: 700;
        color: #ef4444 !important;
        text-decoration: none !important;
        margin-left: auto;
    }

    /* ============================================================
       4. MODERN PRODUCT CARDS (.bms-pcard)
       ============================================================ */
    .bms-pcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
    }

    .bms-pcard:hover {
        transform: translateY(-7px);
        border-color: #cbd5e1;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.10);
    }

    .bms-pcard-thumb {
        position: relative;
        height: 245px;
        background: #f8fafc;
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
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 50px;
        letter-spacing: 0.4px;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .bms-pbadge-stock {
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 50px;
    }

    .bms-pbadge-stock.out {
        background: rgba(239, 68, 68, 0.9);
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
        color: #1e293b !important;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .bms-pcard-act-btn:hover {
        background: #335aff;
        color: #ffffff !important;
        border-color: #335aff;
        transform: scale(1.08);
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
        color: #335aff;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .bms-pcard-cat .sub {
        color: #64748b;
        font-weight: 600;
    }

    .bms-pcard-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .bms-pcard-title:hover {
        color: #335aff;
    }

    .bms-pcard-rating {
        display: flex;
        align-items: center;
        gap: 3px;
        font-size: 11px;
        color: #f59e0b;
        margin-bottom: 10px;
    }

    .bms-pcard-rating span {
        color: #94a3b8;
        font-weight: 600;
        margin-left: 4px;
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
        color: #0f172a;
    }

    .bms-pcard-old-price {
        font-size: 12.5px;
        font-weight: 600;
        color: #94a3b8;
        text-decoration: line-through;
        margin-left: 5px;
    }

    .bms-pcard-save {
        font-size: 11px;
        font-weight: 700;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
        white-space: nowrap;
    }

    .bms-pcard-btn {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #335aff;
        background: rgba(51, 90, 255, 0.06);
        color: #335aff;
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
        background: #335aff;
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(51, 90, 255, 0.25);
    }

    .bms-pcard-btn.disabled {
        border-color: #cbd5e1;
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Empty State */
    .bms-empty-catalog {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 60px 24px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.03);
    }

    .bms-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(51, 90, 255, 0.08);
        color: #335aff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 16px;
    }
</style>

<!-- 1. Catalog Hero & Breadcrumb Banner -->
<section class="bms-catalog-hero">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 16px;">
            <div>
                <ul class="bms-catalog-breadcrumb">
                    <li><a href="{{ url('/') }}"><i class="fas fa-home mr-1"></i>Home</a></li>
                    <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                    <li class="{{ !$selectedCategory && !$selectedSubCategory ? 'active' : '' }}">
                        <a href="{{ url('products') }}">Shop Catalog</a>
                    </li>
                    @if($selectedCategory)
                        <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                        <li class="{{ !$selectedSubCategory ? 'active' : '' }}">
                            <a href="{{ url('products?category=' . $selectedCategory->id) }}">{{ $selectedCategory->title }}</a>
                        </li>
                    @endif
                    @if($selectedSubCategory)
                        <li><i class="fas fa-chevron-right" style="font-size: 10px;"></i></li>
                        <li class="active">{{ $selectedSubCategory->title }}</li>
                    @endif
                </ul>
                <h1 class="bms-catalog-hero-title">
                    @if($selectedSubCategory)
                        {{ $selectedSubCategory->title }}
                    @elseif($selectedCategory)
                        {{ $selectedCategory->title }}
                    @elseif(request('search'))
                        Search: "{{ request('search') }}"
                    @else
                        Explore Our Products
                    @endif
                </h1>
                <p class="bms-catalog-hero-sub">
                    Discover 100% authentic products, competitive BDT pricing, and fast nationwide delivery.
                </p>
            </div>
            <div>
                <div class="bms-catalog-stat-pill">
                    <i class="fas fa-box-open" style="color: #60a5fa;"></i>
                    <span>Available Products: <strong>{{ $products->total() }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Sub-Category Filter Strip -->
@if(!empty($sub_categories) && $sub_categories->count() > 0)
<div class="bms-subcat-strip">
    <div class="container">
        <div class="bms-subcat-scroll">
            <span class="bms-subcat-label"><i class="fas fa-tags mr-1 text-primary"></i>Sub-Categories:</span>
            <a href="{{ url('products' . (request('category') ? '?category=' . request('category') : '')) }}"
               class="bms-subcat-pill {{ !request('sub_category') ? 'active' : '' }}">
                All Sub-Categories
            </a>
            @foreach($sub_categories as $subCat)
                <a href="{{ request()->fullUrlWithQuery(['sub_category' => $subCat->id, 'page' => null]) }}"
                   class="bms-subcat-pill {{ (string) request('sub_category') === (string) $subCat->id ? 'active' : '' }}">
                    <span>{{ $subCat->title }}</span>
                    <span class="count">{{ $subCat->products_count ?? 0 }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- 2. Main Catalog & Sidebar Filter Section -->
<section class="bms-catalog-wrap">
    <div class="container">
        <div class="row">
            <!-- LEFT SIDEBAR FILTERS -->
            <div class="col-lg-3 col-md-4">
                <div class="bms-sidebar-panel">

                    <!-- Filter Box 1: Keyword Search -->
                    <div class="bms-filter-box">
                        <div class="bms-filter-box-header">
                            <h6 class="bms-filter-box-title"><i class="fas fa-search"></i> Search Products</h6>
                            @if($hasActiveFilters)
                                <a href="{{ url('products') }}" style="font-size: 12px; font-weight: 700; color: #ef4444; text-decoration: none;">
                                    Reset All
                                </a>
                            @endif
                        </div>
                        <form action="{{ url('products') }}" method="GET" class="bms-sidebar-search">
                            @foreach(['category', 'sub_category', 'brand', 'min_price', 'max_price', 'in_stock', 'on_sale', 'sort'] as $keepKey)
                                @if(filled(request($keepKey)))
                                    <input type="hidden" name="{{ $keepKey }}" value="{{ request($keepKey) }}">
                                @endif
                            @endforeach
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or code...">
                            <button type="submit" title="Search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Filter Box 2: Categories & Nested Sub-Categories -->
                    @if(!empty($categories) && $categories->count() > 0)
                    <div class="bms-filter-box">
                        <div class="bms-filter-box-header">
                            <h6 class="bms-filter-box-title"><i class="fas fa-layer-group"></i> Categories</h6>
                        </div>
                        <ul class="bms-filter-tree">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['category' => null, 'sub_category' => null, 'page' => null]) }}"
                                   class="bms-tree-link {{ !request('category') ? 'active' : '' }}">
                                    <span>All Categories</span>
                                    <span class="bms-tree-badge">{{ $categories->sum('products_count') }}</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                @php
                                    $isCatActive = (string) request('category') === (string) $cat->id;
                                    $hasActiveSubInCat = $cat->subCategories && $cat->subCategories->contains(fn($sc) => (string) $sc->id === (string) request('sub_category'));
                                @endphp
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id, 'sub_category' => null, 'page' => null]) }}"
                                       class="bms-tree-link {{ $isCatActive || $hasActiveSubInCat ? 'active' : '' }}">
                                        <span>{{ $cat->title }}</span>
                                        <span class="bms-tree-badge">{{ $cat->products_count ?? 0 }}</span>
                                    </a>
                                    @if($cat->subCategories && $cat->subCategories->count() > 0)
                                        <ul class="bms-subtree-list">
                                            @foreach($cat->subCategories as $subCat)
                                                <li>
                                                    <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id, 'sub_category' => $subCat->id, 'page' => null]) }}"
                                                       class="bms-subtree-link {{ (string) request('sub_category') === (string) $subCat->id ? 'active' : '' }}">
                                                        <span><i class="fas fa-angle-right mr-1"></i>{{ $subCat->title }}</span>
                                                        <span class="bms-tree-badge">{{ $subCat->products_count ?? 0 }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Filter Box 3: Dedicated Sub-Categories Filter -->
                    @if(!empty($sub_categories) && $sub_categories->count() > 0)
                    <div class="bms-filter-box">
                        <div class="bms-filter-box-header">
                            <h6 class="bms-filter-box-title"><i class="fas fa-sitemap"></i> Sub-Categories</h6>
                        </div>
                        <ul class="bms-filter-tree">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['sub_category' => null, 'page' => null]) }}"
                                   class="bms-tree-link {{ !request('sub_category') ? 'active' : '' }}">
                                    <span>All Sub-Categories</span>
                                    <span class="bms-tree-badge">{{ $sub_categories->sum('products_count') }}</span>
                                </a>
                            </li>
                            @foreach($sub_categories as $subCat)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category' => $subCat->category_id, 'sub_category' => $subCat->id, 'page' => null]) }}"
                                       class="bms-tree-link {{ (string) request('sub_category') === (string) $subCat->id ? 'active' : '' }}">
                                        <span>{{ $subCat->title }}</span>
                                        <span class="bms-tree-badge">{{ $subCat->products_count ?? 0 }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Filter Box 4: Price Range & Availability Filter Form -->
                    <form action="{{ url('products') }}" method="GET" id="bmsSidebarFilterForm">
                        @foreach(['search', 'category', 'sub_category', 'brand', 'sort'] as $keepKey)
                            @if(filled(request($keepKey)))
                                <input type="hidden" name="{{ $keepKey }}" value="{{ request($keepKey) }}">
                            @endif
                        @endforeach

                        <div class="bms-filter-box">
                            <div class="bms-filter-box-header">
                                <h6 class="bms-filter-box-title"><i class="fas fa-Sliders-h"></i><i class="fas fa-coins"></i> Price Filter (BDT)</h6>
                            </div>

                            <div class="bms-price-inputs">
                                <div class="bms-price-field">
                                    <label for="bmsMinPrice">Min Price</label>
                                    <input type="number" min="0" step="1" name="min_price" id="bmsMinPrice"
                                           value="{{ request('min_price') }}" placeholder="0">
                                </div>
                                <div class="bms-price-field">
                                    <label for="bmsMaxPrice">Max Price</label>
                                    <input type="number" min="0" step="1" name="max_price" id="bmsMaxPrice"
                                           value="{{ request('max_price') }}" placeholder="{{ $max_catalog_price ?? 50000 }}">
                                </div>
                            </div>

                            <input type="range" class="bms-price-range-slider" id="bmsPriceRangeSlider"
                                   min="0" max="{{ $max_catalog_price ?? 50000 }}" step="50"
                                   value="{{ request('max_price', $max_catalog_price ?? 50000) }}"
                                   oninput="document.getElementById('bmsMaxPrice').value = this.value; document.getElementById('bmsSliderValLabel').innerText = 'Up to BDT ' + this.value;">
                            <div class="d-flex justify-content-between mb-3" style="font-size: 11.5px; font-weight: 600; color: #64748b;">
                                <span>BDT 0</span>
                                <span id="bmsSliderValLabel" style="color: #335aff; font-weight: 700;">
                                    Up to BDT {{ request('max_price', $max_catalog_price ?? 50000) }}
                                </span>
                            </div>

                            <!-- Quick Price Presets -->
                            <div class="bms-price-presets">
                                <button type="button" class="bms-price-preset-btn {{ request('min_price') == '0' && request('max_price') == '500' ? 'active' : '' }}"
                                        onclick="setQuickPrice(0, 500)">Under 500</button>
                                <button type="button" class="bms-price-preset-btn {{ request('min_price') == '500' && request('max_price') == '1500' ? 'active' : '' }}"
                                        onclick="setQuickPrice(500, 1500)">500 – 1,500</button>
                                <button type="button" class="bms-price-preset-btn {{ request('min_price') == '1500' && request('max_price') == '3000' ? 'active' : '' }}"
                                        onclick="setQuickPrice(1500, 3000)">1,500 – 3,000</button>
                                <button type="button" class="bms-price-preset-btn {{ request('min_price') == '3000' && !request('max_price') ? 'active' : '' }}"
                                        onclick="setQuickPrice(3000, '')">3,000+</button>
                            </div>

                            <button type="submit" class="bms-apply-filter-btn">
                                <i class="fas fa-filter"></i> Apply Price Filter
                            </button>
                        </div>

                        <!-- Filter Box 5: Product Status & Offers -->
                        <div class="bms-filter-box">
                            <div class="bms-filter-box-header">
                                <h6 class="bms-filter-box-title"><i class="fas fa-bolt"></i> Availability &amp; Offers</h6>
                            </div>

                            <label class="bms-check-option">
                                <span class="bms-check-label">
                                    <i class="fas fa-check-circle text-success"></i> In Stock Only
                                </span>
                                <input type="checkbox" name="in_stock" value="1"
                                       {{ request()->boolean('in_stock') ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                            </label>

                            <label class="bms-check-option mb-0">
                                <span class="bms-check-label">
                                    <i class="fas fa-fire text-danger"></i> On Sale / Discounted
                                </span>
                                <input type="checkbox" name="on_sale" value="1"
                                       {{ request()->boolean('on_sale') ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                            </label>
                        </div>
                    </form>

                    <!-- Filter Box 6: Brands -->
                    @if(!empty($brands) && $brands->count() > 0)
                    <div class="bms-filter-box">
                        <div class="bms-filter-box-header">
                            <h6 class="bms-filter-box-title"><i class="fas fa-award"></i> Brands</h6>
                        </div>
                        <ul class="bms-filter-tree">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}"
                                   class="bms-tree-link {{ !request('brand') ? 'active' : '' }}">
                                    <span>All Brands</span>
                                    <span class="bms-tree-badge">{{ $brands->sum('products_count') }}</span>
                                </a>
                            </li>
                            @foreach($brands as $brand)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['brand' => $brand->id, 'page' => null]) }}"
                                       class="bms-tree-link {{ (string) request('brand') === (string) $brand->id ? 'active' : '' }}">
                                        <span>{{ $brand->title }}</span>
                                        <span class="bms-tree-badge">{{ $brand->products_count ?? 0 }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($hasActiveFilters)
                        <a href="{{ url('products') }}" class="btn btn-outline-danger w-100 mb-4" style="border-radius: 12px; font-weight: 700; padding: 11px;">
                            <i class="fas fa-redo-alt mr-1"></i> Reset All Filters
                        </a>
                    @endif

                </div>
            </div>

            <!-- RIGHT MAIN PRODUCT SHOWCASE -->
            <div class="col-lg-9 col-md-8">
                <!-- Catalog Toolbar -->
                <div class="bms-catalog-toolbar">
                    <div class="bms-toolbar-top">
                        <div class="bms-result-summary">
                            Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $products->total() }}</strong> products
                        </div>

                        <div class="bms-toolbar-controls">
                            <div class="bms-grid-switch d-none d-xl-inline-flex" title="Grid Columns">
                                <button type="button" class="bms-grid-btn active" id="gridBtn3" onclick="setCatalogGrid(3)">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="bms-grid-btn" id="gridBtn4" onclick="setCatalogGrid(4)">
                                    <i class="fas fa-th"></i>
                                </button>
                            </div>

                            <form action="{{ url('products') }}" method="GET" class="d-flex align-items-center m-0" style="gap: 8px;">
                                @foreach(['search', 'category', 'sub_category', 'brand', 'min_price', 'max_price', 'in_stock', 'on_sale'] as $keepKey)
                                    @if(filled(request($keepKey)))
                                        <input type="hidden" name="{{ $keepKey }}" value="{{ request($keepKey) }}">
                                    @endif
                                @endforeach
                                <label class="mb-0" style="font-size: 13px; font-weight: 700; color: #475569;">Sort By:</label>
                                <select name="sort" class="bms-sort-select" onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="discount" {{ request('sort') === 'discount' ? 'selected' : '' }}>Biggest Discount</option>
                                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Active Filter Chips -->
                    @if($hasActiveFilters)
                        <div class="bms-active-chips">
                            <span style="font-size: 12px; font-weight: 700; color: #64748b;">Active Filters:</span>

                            @if(filled(request('search')))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="bms-chip">
                                    Keyword: "{{ request('search') }}" <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if($selectedCategory)
                                <a href="{{ request()->fullUrlWithQuery(['category' => null, 'sub_category' => null, 'page' => null]) }}" class="bms-chip">
                                    Category: {{ $selectedCategory->title }} <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if($selectedSubCategory)
                                <a href="{{ request()->fullUrlWithQuery(['sub_category' => null, 'page' => null]) }}" class="bms-chip">
                                    Sub-Category: {{ $selectedSubCategory->title }} <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if($selectedBrand)
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}" class="bms-chip">
                                    Brand: {{ $selectedBrand->title }} <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if(filled(request('min_price')) || filled(request('max_price')))
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null, 'page' => null]) }}" class="bms-chip">
                                    Price: BDT {{ request('min_price', 0) }} – {{ request('max_price', 'Max') }} <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if(request()->boolean('in_stock'))
                                <a href="{{ request()->fullUrlWithQuery(['in_stock' => null, 'page' => null]) }}" class="bms-chip">
                                    In Stock Only <i class="fas fa-times"></i>
                                </a>
                            @endif

                            @if(request()->boolean('on_sale'))
                                <a href="{{ request()->fullUrlWithQuery(['on_sale' => null, 'page' => null]) }}" class="bms-chip">
                                    On Sale <i class="fas fa-times"></i>
                                </a>
                            @endif

                            <a href="{{ url('products') }}" class="bms-chip-clear-all">
                                <i class="fas fa-trash-alt mr-1"></i> Clear All
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Product Grid -->
                <div class="row" id="bmsCatalogGrid">
                    @forelse($products as $product)
                        @php
                            $regPrice = (float) ($product->price ?? 0);
                            $sellPrice = (float) ($product->selling_price ?? $product->price ?? 0);
                            $discPct = ($regPrice > $sellPrice && $regPrice > 0)
                                ? round((($regPrice - $sellPrice) / $regPrice) * 100)
                                : (int) ($product->discount ?? 0);
                            $saveAmt = max(0, $regPrice - $sellPrice);

                            $stockIn = $product->productDetails ? $product->productDetails->sum(fn($d) => (int) ($d->total_stock_in ?? 0)) : 0;
                            $stockOut = $product->productDetails ? $product->productDetails->sum(fn($d) => (int) ($d->total_stock_out ?? 0)) : 0;
                            $hasStockRecords = $product->productDetails && $product->productDetails->count() > 0;
                            $availableStock = $hasStockRecords ? max(0, $stockIn - $stockOut) : 10;
                            $isOutOfStock = $hasStockRecords && $availableStock <= 0;
                        @endphp
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4 bms-catalog-col">
                            <div class="bms-pcard">
                                <div class="bms-pcard-thumb">
                                    <div class="bms-pcard-badges">
                                        @if($discPct > 0)
                                            <span class="bms-pbadge-sale">-{{ $discPct }}% OFF</span>
                                        @endif
                                        @if($isOutOfStock)
                                            <span class="bms-pbadge-stock out">Out of Stock</span>
                                        @endif
                                    </div>
                                    <div class="bms-pcard-actions">
                                        <a href="javascript:void(0)" class="bms-pcard-act-btn"
                                           onclick="toggleWishlist({{ $product->id }}, this)" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </a>
                                        <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}"
                                           class="bms-pcard-act-btn" title="View Product">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </div>
                                    <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}" class="thumb-link">
                                        <img src="{{ !empty($product->image) ? asset($product->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                             alt="{{ $product->title ?? 'Product' }}">
                                    </a>
                                </div>

                                <div class="bms-pcard-body">
                                    <div class="bms-pcard-cat">
                                        <span>{{ $product->category->title ?? 'Collection' }}</span>
                                        @if(!empty($product->subCategory->title))
                                            <span class="sub">&bull; {{ $product->subCategory->title }}</span>
                                        @endif
                                    </div>

                                    <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}" class="bms-pcard-title">
                                        {{ $product->title ?? 'Product Name' }}
                                    </a>

                                    <div class="bms-pcard-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span>(4.8)</span>
                                    </div>

                                    <div class="bms-pcard-price-row">
                                        <div>
                                            <span class="bms-pcard-price">BDT {{ number_format($sellPrice, 0) }}</span>
                                            @if($regPrice > $sellPrice)
                                                <span class="bms-pcard-old-price">BDT {{ number_format($regPrice, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($saveAmt > 0)
                                            <span class="bms-pcard-save">Save BDT {{ number_format($saveAmt, 0) }}</span>
                                        @endif
                                    </div>

                                    @if($isOutOfStock)
                                        <button type="button" class="bms-pcard-btn disabled" disabled>
                                            <i class="fas fa-ban"></i> Out of Stock
                                        </button>
                                    @else
                                        <button type="button" class="bms-pcard-btn"
                                                onclick="addToCart({ product_id: {{ $product->id }}, quantity: 1 })">
                                            <i class="fas fa-shopping-bag"></i> Add to Bag
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="bms-empty-catalog">
                                <div class="bms-empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h4 style="color: #0f172a; font-weight: 800; margin-bottom: 8px;">No Matching Products Found</h4>
                                <p class="text-muted mb-4" style="max-width: 420px; margin: 0 auto;">
                                    We couldn't find any products matching your selected filters. Try adjusting your price range or clearing some filters.
                                </p>
                                <a href="{{ url('products') }}" class="bms-apply-filter-btn" style="max-width: 220px; text-decoration: none;">
                                    <i class="fas fa-redo-alt"></i> View All Products
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
    function setQuickPrice(minVal, maxVal) {
        var minInput = document.getElementById('bmsMinPrice');
        var maxInput = document.getElementById('bmsMaxPrice');
        var form = document.getElementById('bmsSidebarFilterForm');
        if (minInput) minInput.value = minVal;
        if (maxInput) maxInput.value = maxVal;
        if (form) form.submit();
    }

    function setCatalogGrid(cols) {
        var items = document.querySelectorAll('.bms-catalog-col');
        var btn3 = document.getElementById('gridBtn3');
        var btn4 = document.getElementById('gridBtn4');
        items.forEach(function (el) {
            el.classList.remove('col-xl-3', 'col-xl-4');
            el.classList.add(cols === 4 ? 'col-xl-3' : 'col-xl-4');
        });
        if (btn3 && btn4) {
            btn3.classList.toggle('active', cols === 3);
            btn4.classList.toggle('active', cols === 4);
        }
    }
</script>
@endsection
