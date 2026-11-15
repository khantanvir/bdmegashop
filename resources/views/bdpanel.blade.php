<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (!empty($page_title)) ? $page_title : 'BD Mega Shop | Online Shopping in Bangladesh' }}</title>
    <link rel="stylesheet" href="{{ asset('bdmegashop/dist/main.css') }}">
    <style>
        :root {
            --bms-primary: #0b7a3d;
            --bms-primary-dark: #035c2d;
            --bms-accent: #d6131b;
            --bms-dark: #062417;
            --bms-slate: #48564f;
            --bms-light: #f7faf8;
            --bms-border: #e2e9e4;
            --bms-forest: #024d25;
            --bms-leaf: #16a330;
            --bms-red: #cf0b0d;
            --bms-red-dark: #a80d14;
            --bms-mint: #edf7f1;
            --bms-grad-primary: linear-gradient(135deg, #0b7a3d 0%, #024d25 100%);
            --bms-grad-red: linear-gradient(135deg, #e0242b 0%, #a80d14 100%);
            --bms-brand-stripe: linear-gradient(90deg, #cf0b0d 0%, #cf0b0d 33%, #024d25 33%, #024d25 66%, #16a330 66%, #16a330 100%);
            --bms-shadow-sm: 0 2px 10px rgba(6, 36, 23, 0.05);
            --bms-shadow-md: 0 14px 34px rgba(6, 36, 23, 0.12);
        }

        body {
            background-color: #f7faf8;
            color: #17261e;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #062417;
            letter-spacing: -0.2px;
        }

        a { transition: color 0.2s ease; }
        a:hover { color: var(--bms-primary); }

        ::selection {
            background: rgba(22, 163, 48, 0.22);
            color: #062417;
        }

        :focus-visible {
            outline: 2px solid var(--bms-leaf);
            outline-offset: 2px;
        }

        /* Brand scrollbar */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #eef3ef; }
        ::-webkit-scrollbar-thumb { background: #9fb8a8; border-radius: 10px; border: 2px solid #eef3ef; }
        ::-webkit-scrollbar-thumb:hover { background: var(--bms-primary); }

        /* Brand buttons & utilities (premium gradient finish) */
        .bg-primary,
        .btn-primary {
            background: var(--bms-grad-primary) !important;
            border-color: var(--bms-primary-dark) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(11, 122, 61, 0.22);
            transition: all 0.22s ease;
        }

        .btn.bg-primary:hover,
        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #0d8a45 0%, #035c2d 100%) !important;
            box-shadow: 0 10px 22px rgba(11, 122, 61, 0.3);
            transform: translateY(-1px);
        }

        .text-primary { color: var(--bms-primary) !important; }
        .border-primary { border-color: var(--bms-primary) !important; }

        .btn-outline-primary {
            color: var(--bms-primary) !important;
            border-color: var(--bms-primary) !important;
        }

        .btn-outline-primary:hover {
            background: var(--bms-primary) !important;
            color: #ffffff !important;
        }

        .btn-danger,
        .bg-danger {
            background: var(--bms-grad-red) !important;
            border-color: var(--bms-red-dark) !important;
        }

        .btn-outline-danger {
            color: var(--bms-red) !important;
            border-color: rgba(207, 11, 13, 0.55) !important;
        }

        .btn-outline-danger:hover {
            background: var(--bms-red) !important;
            color: #ffffff !important;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: var(--bms-primary) !important;
            box-shadow: 0 0 0 3px rgba(11, 122, 61, 0.12) !important;
        }

        .page-item.active .page-link {
            background: var(--bms-grad-primary) !important;
            border-color: var(--bms-primary) !important;
        }

        .page-link { color: var(--bms-primary); }

        @media (min-width: 992px) {
            .container,
            .container-sm,
            .container-md,
            .container-lg,
            .container-xl {
                max-width: 85% !important;
                width: 85% !important;
            }
        }

        @media (min-width: 1700px) {
            .container,
            .container-sm,
            .container-md,
            .container-lg,
            .container-xl {
                max-width: 1780px !important;
                width: 85% !important;
            }
        }

        /* Top Announcement Utility Bar */
        .bms-topbar {
            background: linear-gradient(90deg, #03150d 0%, #062417 45%, #024d25 100%);
            color: #f7faf8;
            font-size: 13px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
        }

        /* Signature logo-color stripe on top of the page */
        .bms-topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--bms-brand-stripe);
        }

        .bms-topbar a {
            color: #e2e9e4;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .bms-topbar a:hover {
            color: #ffffff;
        }

        .bms-topbar-promo {
            background: rgba(207, 11, 13, 0.22);
            border: 1px solid rgba(255, 120, 120, 0.35);
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 600;
            color: #fff;
            font-size: 12px;
            letter-spacing: 0.2px;
        }

        /* Sticky Header Upgrade */
        .header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(6, 36, 23, 0.06);
            border-bottom: 1px solid #ebf1ed;
        }

        .header .header-bottom {
            padding: 14px 0;
        }

        .main-menu li {
            position: relative;
        }

        .main-menu li a {
            font-weight: 600 !important;
            color: #17261e !important;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .main-menu li a:hover,
        .main-menu li a.active {
            color: #0b7a3d !important;
            background: rgba(11, 122, 61, 0.07);
        }

        .main-menu > li > a.active {
            box-shadow: inset 0 -2px 0 var(--bms-red);
        }

        /* Category & Sub-Category Dropdown in Navbar */
        .bms-nav-dropdown {
            position: relative;
        }

        .bms-nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 260px;
            background: #ffffff;
            border: 1px solid #e2e9e4;
            border-radius: 14px;
            box-shadow: 0 16px 38px rgba(6, 36, 23, 0.14);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.22s ease;
            z-index: 1050;
        }

        .bms-nav-dropdown:hover > .bms-nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .bms-nav-cat-item {
            position: relative;
        }

        .bms-nav-dropdown-menu a {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #2f3d36 !important;
            border-radius: 9px;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .bms-nav-dropdown-menu a:hover,
        .bms-nav-cat-item:hover > a {
            background: rgba(11, 122, 61, 0.08) !important;
            color: #0b7a3d !important;
        }

        .bms-nav-sub-menu {
            position: absolute;
            top: 0;
            left: calc(100% + 6px);
            min-width: 230px;
            background: #ffffff;
            border: 1px solid #e2e9e4;
            border-radius: 14px;
            box-shadow: 0 16px 38px rgba(6, 36, 23, 0.14);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(8px);
            transition: all 0.2s ease;
            z-index: 1060;
        }

        .bms-nav-sub-menu::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: -10px;
            width: 10px;
        }

        .bms-nav-cat-item:hover > .bms-nav-sub-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .bms-nav-sub-header {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #97a39c;
            padding: 6px 12px 4px;
            border-bottom: 1px solid #f1f5f2;
            margin-bottom: 4px;
        }

        /* Live Search Bar & Dropdown */
        .search-bar {
            position: relative;
            min-width: 280px;
        }

        .search-bar input {
            border-radius: 50px !important;
            border: 1.5px solid #e2e9e4 !important;
            background: #f7faf8 !important;
            padding: 10px 42px 10px 18px !important;
            font-size: 14px !important;
            transition: all 0.2s ease;
            width: 100%;
        }

        .search-bar input:focus {
            background: #ffffff !important;
            border-color: #0b7a3d !important;
            box-shadow: 0 0 0 3px rgba(11, 122, 61, 0.12);
            outline: none;
        }

        .search-bar .search-submit-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            padding: 4px;
            cursor: pointer;
            line-height: 1;
        }

        .live-search-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #e2e9e4;
            border-radius: 12px;
            box-shadow: 0 14px 34px rgba(6, 36, 23, 0.14);
            z-index: 1060;
            display: none;
            overflow: hidden;
        }

        .live-search-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f2;
            text-decoration: none !important;
            transition: background 0.15s ease;
        }

        .live-search-item:last-child {
            border-bottom: 0;
        }

        .live-search-item:hover {
            background: #f7faf8;
        }

        .live-search-item img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e9e4;
        }

        .live-search-item-title {
            font-size: 13px;
            font-weight: 600;
            color: #17261e;
            display: block;
            margin-bottom: 2px;
        }

        .live-search-item-price {
            font-size: 12px;
            font-weight: 700;
            color: #0b7a3d;
        }

        /* Header Action Icons */
        .menu-icon ul li a {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f7faf8;
            border: 1px solid #e2e9e4;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .menu-icon ul li a:hover {
            background: rgba(11, 122, 61, 0.08);
            border-color: #0b7a3d;
            transform: translateY(-1px);
        }

        /* Global Page Hero & Breadcrumb Bar (Matches /products page across all pages) */
        .breadcrumb-area,
        .bms-catalog-hero {
            background: linear-gradient(135deg, #03150d 0%, #062417 55%, #04391d 100%) !important;
            padding: 38px 0 34px !important;
            margin-top: 0 !important;
            position: relative;
            overflow: hidden;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .breadcrumb-area::before,
        .bms-catalog-hero::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(11, 122, 61, 0.22) 0%, transparent 70%);
            top: -180px;
            right: -100px;
            pointer-events: none;
        }

        .breadcrumb-area::after,
        .bms-catalog-hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(22, 163, 48, 0.14) 0%, transparent 70%);
            bottom: -140px;
            left: 10%;
            pointer-events: none;
        }

        .breadcrumb-area .breadcrumb {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 0 10px 0 !important;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .breadcrumb-area .breadcrumb-item,
        .breadcrumb-area .breadcrumb-item a {
            color: #cdd7d1 !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: color 0.2s ease;
        }

        .breadcrumb-area .breadcrumb-item a:hover {
            color: #5fd47a !important;
        }

        .breadcrumb-area .breadcrumb-item.active {
            color: #5fd47a !important;
            font-weight: 700 !important;
        }

        .breadcrumb-area .breadcrumb-item + .breadcrumb-item::before {
            color: #65736c !important;
            content: "›" !important;
            font-size: 15px;
            line-height: 1;
            padding: 0 6px !important;
        }

        .breadcrumb-area h5 {
            font-size: 28px !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            margin: 0 !important;
            letter-spacing: -0.4px;
        }

        /* Global Product Card Polish */
        .product-item {
            background: #ffffff;
            border: 1px solid #ebf1ed;
            border-radius: 14px !important;
            overflow: hidden;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 10px rgba(6, 36, 23, 0.03);
            margin-bottom: 24px;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 30px rgba(6, 36, 23, 0.1);
            border-color: #cdd7d1;
        }

        .product-item-image {
            position: relative;
            background: #f7faf8;
            overflow: hidden;
        }

        .product-item-image img {
            transition: transform 0.4s ease;
        }

        .product-item:hover .product-item-image img {
            transform: scale(1.05);
        }

        .product-discount-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #d81e23 0%, #b5101a 100%);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 3;
            box-shadow: 0 3px 8px rgba(216, 30, 35, 0.3);
        }

        .product-item-info {
            padding: 16px !important;
        }

        .product-item-info a {
            font-weight: 600 !important;
            color: #17261e !important;
            display: block;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-item-info a:hover {
            color: #0b7a3d !important;
        }

        .product-item-info span {
            color: #0b7a3d !important;
            font-weight: 700 !important;
            font-size: 16px !important;
        }

        .product-card-cart-btn {
            width: 100%;
            margin-top: 12px;
            padding: 9px 14px;
            border-radius: 8px;
            border: 1.5px solid #0b7a3d;
            background: rgba(11, 122, 61, 0.06);
            color: #0b7a3d;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .product-card-cart-btn:hover {
            background: var(--bms-grad-primary);
            color: #ffffff;
        }

        /* Cart Sidebar Styles */
        .cart-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(6, 36, 23, 0.55);
            backdrop-filter: blur(3px);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.28s ease;
        }

        .cart-sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -430px;
            width: 100%;
            max-width: 410px;
            height: 100vh;
            background: #ffffff;
            z-index: 9999;
            box-shadow: -10px 0 35px rgba(6, 36, 23, 0.18);
            display: flex;
            flex-direction: column;
            transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-sidebar-header {
            padding: 18px 20px;
            border-bottom: 1px solid #ebf1ed;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #062417 0%, #024d25 100%);
            color: #ffffff;
            box-shadow: inset 0 3px 0 var(--bms-red);
        }

        .cart-sidebar-header h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-sidebar-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 20px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .cart-sidebar-close:hover {
            background: #d81e23;
            border-color: #d81e23;
        }

        .cart-sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px 20px;
        }

        .cart-item-card {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f2;
            align-items: flex-start;
        }

        .cart-item-card:last-child {
            border-bottom: 0;
        }

        .cart-item-thumb {
            width: 68px;
            height: 68px;
            border-radius: 8px;
            border: 1px solid #e2e9e4;
            object-fit: cover;
            flex-shrink: 0;
            background: #f7faf8;
        }

        .cart-item-details {
            flex: 1;
            min-width: 0;
        }

        .cart-item-title {
            font-size: 14px;
            font-weight: 600;
            color: #17261e;
            margin-bottom: 4px;
            display: block;
            text-decoration: none;
            word-break: break-word;
        }

        .cart-item-title:hover {
            color: #0b7a3d;
        }

        .cart-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 6px;
        }

        .cart-item-badge {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            background: #f1f5f2;
            color: #48564f;
            font-weight: 500;
        }

        .cart-item-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 6px;
        }

        .cart-qty-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #e2e9e4;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
        }

        .cart-qty-btn {
            width: 26px;
            height: 26px;
            border: 0;
            background: #f7faf8;
            color: #17261e;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            line-height: 1;
        }

        .cart-qty-btn:hover {
            background: #e2e9e4;
        }

        .cart-qty-val {
            min-width: 28px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #17261e;
        }

        .cart-item-subtotal {
            font-size: 14px;
            font-weight: 700;
            color: #0b7a3d;
        }

        .cart-item-remove {
            border: 0;
            background: transparent;
            color: #97a39c;
            font-size: 18px;
            cursor: pointer;
            padding: 2px 4px;
            line-height: 1;
        }

        .cart-item-remove:hover {
            color: #b5101a;
        }

        .cart-sidebar-footer {
            padding: 16px 20px 20px;
            border-top: 1px solid #ebf1ed;
            background: #f7faf8;
        }

        .cart-calc-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #48564f;
            margin-bottom: 8px;
        }

        .cart-calc-row.total {
            font-size: 16px;
            font-weight: 700;
            color: #17261e;
            padding-top: 10px;
            margin-top: 8px;
            border-top: 1px dashed #cdd7d1;
            margin-bottom: 14px;
        }

        .cart-empty-state {
            text-align: center;
            padding: 48px 16px;
            color: #65736c;
        }

        /* Toast Notification */
        .bms-toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #062417;
            color: #ffffff;
            padding: 12px 22px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(6, 36, 23, 0.25);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bms-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            visibility: visible;
        }

        /* Back to Top Floating Button */
        .bms-back-to-top {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bms-grad-primary);
            color: #ffffff;
            border: 0;
            box-shadow: 0 8px 20px rgba(11, 122, 61, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 990;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }

        .bms-back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .bms-back-to-top:hover {
            background: #035c2d;
            transform: translateY(-3px);
        }

        /* Footer Redesign */
        .bms-footer-trust {
            background: #ffffff;
            border-top: 1px solid #e2e9e4;
            border-bottom: 1px solid #e2e9e4;
            padding: 28px 0;
        }

        .bms-trust-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 12px;
        }

        .bms-trust-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(11, 122, 61, 0.08);
            color: #0b7a3d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .bms-trust-text h6 {
            font-size: 15px;
            font-weight: 700;
            color: #062417;
            margin: 0 0 2px;
        }

        .bms-trust-text p {
            font-size: 13px;
            color: #65736c;
            margin: 0;
        }

        footer {
            background: radial-gradient(ellipse at 85% 0%, rgba(22, 163, 48, 0.12) 0%, transparent 55%),
                        linear-gradient(180deg, #062417 0%, #03150d 100%) !important;
            color: #cdd7d1;
            padding-top: 55px !important;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--bms-brand-stripe);
        }

        footer .main-footer-quicklinks h6 {
            position: relative;
            padding-bottom: 10px;
        }

        footer .main-footer-quicklinks h6::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 28px;
            height: 2px;
            border-radius: 2px;
            background: var(--bms-leaf);
        }

        footer .copyright-links a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #cdd7d1 !important;
            transition: all 0.2s ease;
        }

        footer .copyright-links a:hover {
            background: var(--bms-leaf);
            border-color: var(--bms-leaf);
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        footer .main-footer-info p {
            color: #97a39c !important;
        }

        footer .main-footer-quicklinks h6 {
            color: #ffffff !important;
            font-weight: 700;
            margin-bottom: 18px;
        }

        footer .main-footer-quicklinks ul.quicklink li a {
            color: #97a39c !important;
            transition: all 0.2s ease;
        }

        footer .main-footer-quicklinks ul.quicklink li a:hover {
            color: #ffffff !important;
            padding-left: 4px;
        }

        footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 22px 0 !important;
        }

        footer .copyright-text p,
        footer .copyright-text a {
            color: #97a39c !important;
        }
    </style>
</head>

<body>
    @php
        $sessionCart = session('cart', []);
        $initialCartQty = collect($sessionCart)->sum('quantity');
        $sessionWishlist = (array) session('wishlist', []);
        $cookieWishlist = json_decode(request()->cookie('wishlist', '[]'), true) ?: [];
        $wishlistItems = array_values(array_unique(array_filter(array_merge($sessionWishlist, (array)$cookieWishlist))));
        $wishlistCount = count($wishlistItems);
        $navCategories = \App\Models\BdMegaShop\Category\Category::with([
            'subCategories' => function ($q) {
                $q->where('status', 0)->orderBy('id', 'desc');
            }
        ])->where('status', 0)->orderBy('id', 'desc')->take(12)->get();
        $loggedCustomer = Auth::guard('customer')->user();
    @endphp

    <!-- Top Announcement & Utility Bar -->
    <div class="bms-topbar d-none d-md-block">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="gap: 18px;">
                    <span><i class="fas fa-phone-alt mr-1" style="color: #5fd47a;"></i> Hotline: <a href="tel:+8801732930067">+880 1732-930067</a></span>
                    <span><i class="far fa-envelope mr-1" style="color: #5fd47a;"></i> <a href="mailto:info@bdmegashop.com">info@bdmegashop.com</a></span>
                </div>
                <div>
                    <span class="bms-topbar-promo">🔥 Nationwide Fast Delivery &amp; Cash on Delivery Available!</span>
                </div>
                <div class="d-flex align-items-center" style="gap: 16px;">
                    <a href="{{ url('sale-and-discount') }}">Hot Offers</a>
                    <a href="{{ url('help-and-support') }}">Help &amp; Support</a>
                    @if($loggedCustomer)
                        <a href="{{ url('customer-dashboard') }}" style="color: #a7e8b6; font-weight: 600;">
                            <i class="far fa-user mr-1"></i> {{ $loggedCustomer->name ?? 'My Account' }}
                        </a>
                    @else
                        <a href="{{ url('customer-login') }}" style="color: #a7e8b6; font-weight: 600;">
                            Sign In / Register
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Header Area Start -->
    <header class="header">
        <div class="header-bottom">
            <div class="container">
                <div class="d-none d-lg-block">
                    <nav class="menu-area d-flex align-items-center">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('bdmegashop/dist/images/logo/logo.png') }}" alt="BD Mega Shop" /></a>
                        </div>
                        <ul class="main-menu d-flex align-items-center">
                            <li><a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                            <li><a class="{{ request()->is('products*') ? 'active' : '' }}" href="{{ url('products') }}">Shop</a></li>
                            @if($navCategories->count() > 0)
                            <li class="bms-nav-dropdown">
                                <a href="{{ url('products') }}">Categories <i class="fas fa-chevron-down ml-1" style="font-size: 11px;"></i></a>
                                <div class="bms-nav-dropdown-menu">
                                    @foreach($navCategories as $navCat)
                                        <div class="bms-nav-cat-item">
                                            <a href="{{ url('products?category=' . $navCat->id) }}">
                                                <span><i class="fas fa-folder-open mr-2" style="color: #65736c; font-size: 12px;"></i>{{ $navCat->title }}</span>
                                                @if($navCat->subCategories && $navCat->subCategories->count() > 0)
                                                    <i class="fas fa-chevron-right" style="font-size: 10px; color: #0b7a3d;"></i>
                                                @else
                                                    <i class="fas fa-angle-right" style="font-size: 11px; color: #cdd7d1;"></i>
                                                @endif
                                            </a>
                                            @if($navCat->subCategories && $navCat->subCategories->count() > 0)
                                                <div class="bms-nav-sub-menu">
                                                    <div class="bms-nav-sub-header">{{ $navCat->title }}</div>
                                                    @foreach($navCat->subCategories as $subCat)
                                                        <a href="{{ url('products?category=' . $navCat->id . '&sub_category=' . $subCat->id) }}">
                                                            <span>{{ $subCat->title }}</span>
                                                            <i class="fas fa-angle-right" style="font-size: 11px; color: #97a39c;"></i>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                            @endif
                            <li><a class="{{ request()->is('sale-and-discount') ? 'active' : '' }}" href="{{ url('sale-and-discount') }}">Offers</a></li>
                            <li><a class="{{ request()->is('about') ? 'active' : '' }}" href="{{ url('about') }}">About Us</a></li>
                            <li><a class="{{ request()->is('help-and-support') ? 'active' : '' }}" href="{{ url('help-and-support') }}">Support</a></li>
                        </ul>

                        <!-- Live Search Form -->
                        <div class="search-bar">
                            <form action="{{ url('products') }}" method="GET" class="m-0">
                                <input type="text" name="search" id="bmsLiveSearchInput" autocomplete="off"
                                    value="{{ request('search') }}" placeholder="Search products...">
                                <button type="submit" class="search-submit-btn" title="Search">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20.414 20.414">
                                        <g transform="translate(1 1)">
                                            <ellipse cx="8.158" cy="8" rx="8.158" ry="8" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            <line x1="3.569" y1="3.5" transform="translate(14.431 14.5)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                        </g>
                                    </svg>
                                </button>
                            </form>
                            <div class="live-search-dropdown" id="bmsLiveSearchDropdown"></div>
                        </div>

                        <div class="menu-icon ml-auto">
                            <ul>
                                <li>
                                    <a href="{{ Auth::guard('customer')->check() ? url('customer-dashboard?tab=wishlist') : url('customer-login') }}" title="My Wishlist">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 22 20">
                                            <g transform="translate(1 1)">
                                                <path d="M20.007,4.59a5.148,5.148,0,0,0-7.444,0L11.548,5.636,10.534,4.59a5.149,5.149,0,0,0-7.444,0,5.555,5.555,0,0,0,0,7.681L4.1,13.317,11.548,21l7.444-7.681,1.014-1.047a5.553,5.553,0,0,0,0-7.681Z"
                                                    transform="translate(-1.549 -2.998)" fill="#fff" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                        <span class="heart wishlist-badge-count">{{ $wishlistCount }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="openCartSidebar()" title="Shopping Cart">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 22 22">
                                            <g transform="translate(-1524 -89)">
                                                <ellipse cx="0.909" cy="0.952" rx="0.909" ry="0.952" transform="translate(1531.364 108.095)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="0.909" cy="0.952" rx="0.909" ry="0.952" transform="translate(1541.364 108.095)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M1,1H4.636L7.073,13.752a1.84,1.84,0,0,0,1.818,1.533h8.836a1.84,1.84,0,0,0,1.818-1.533L21,5.762H5.545"
                                                    transform="translate(1524 89)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                        <span class="cart cart-badge-count">{{ $initialCartQty }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $loggedCustomer ? url('customer-dashboard') : url('customer-login') }}"
                                       title="{{ $loggedCustomer ? ($loggedCustomer->name ?? 'My Account') : 'Customer Login' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20">
                                            <g transform="translate(1 1)">
                                                <path d="M20,21V19a4,4,0,0,0-4-4H8a4,4,0,0,0-4,4v2" transform="translate(-4 -3)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <circle cx="4" cy="4" r="4" transform="translate(4)" fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <!-- Mobile Menu -->
                <aside class="d-lg-none">
                    <div id="mySidenav" class="sidenav">
                        <div class="close-mobile-menu">
                            <a href="javascript:void(0)" id="menu-close" class="closebtn" onclick="closeNav()">&times;</a>
                        </div>
                        <div class="search-bar mb-3">
                            <form action="{{ url('products') }}" method="GET">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
                                <button type="submit" class="search-submit-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <li><a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                        <li><a class="{{ request()->is('products*') ? 'active' : '' }}" href="{{ url('products') }}">Shop Products</a></li>
                        @if($navCategories->count() > 0)
                            @foreach($navCategories as $navCat)
                                <li>
                                    <a href="{{ url('products?category=' . $navCat->id) }}" style="font-weight: 600;">
                                        {{ $navCat->title }}
                                    </a>
                                    @if($navCat->subCategories && $navCat->subCategories->count() > 0)
                                        <div style="padding-left: 16px; border-left: 2px solid #e2e9e4; margin-left: 10px;">
                                            @foreach($navCat->subCategories as $subCat)
                                                <a href="{{ url('products?category=' . $navCat->id . '&sub_category=' . $subCat->id) }}"
                                                   style="display: block; font-size: 13px; padding: 4px 8px; color: #65736c;">
                                                    &bull; {{ $subCat->title }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                        <li><a class="{{ request()->is('sale-and-discount') ? 'active' : '' }}" href="{{ url('sale-and-discount') }}">Hot Offers</a></li>
                        <li><a class="{{ request()->is('about') ? 'active' : '' }}" href="{{ url('about') }}">About Us</a></li>
                        <li><a class="{{ request()->is('help-and-support') ? 'active' : '' }}" href="{{ url('help-and-support') }}">Help &amp; Support</a></li>
                        @if($loggedCustomer)
                            <li><a href="{{ url('customer-dashboard') }}">My Account</a></li>
                            <li><a href="{{ url('customer-logout') }}">Logout</a></li>
                        @else
                            <li><a href="{{ url('customer-login') }}">Customer Login</a></li>
                        @endif
                    </div>
                    <div class="mobile-nav d-flex align-items-center justify-content-between">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('bdmegashop/dist/images/logo/logo.png') }}" alt="logo" /></a>
                        </div>
                        <div class="menu-icon">
                            <ul>
                                <li>
                                    <a href="{{ Auth::guard('customer')->check() ? url('customer-dashboard?tab=wishlist') : url('customer-login') }}" title="My Wishlist">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 22 20">
                                            <g transform="translate(1 1)">
                                                <path d="M20.007,4.59a5.148,5.148,0,0,0-7.444,0L11.548,5.636,10.534,4.59a5.149,5.149,0,0,0-7.444,0,5.555,5.555,0,0,0,0,7.681L4.1,13.317,11.548,21l7.444-7.681,1.014-1.047a5.553,5.553,0,0,0,0-7.681Z"
                                                    transform="translate(-1.549 -2.998)" fill="#fff" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                        <span class="heart wishlist-badge-count">{{ $wishlistCount }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="openCartSidebar()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 22 22">
                                            <g transform="translate(-1524 -89)">
                                                <ellipse cx="0.909" cy="0.952" rx="0.909" ry="0.952" transform="translate(1531.364 108.095)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <ellipse cx="0.909" cy="0.952" rx="0.909" ry="0.952" transform="translate(1541.364 108.095)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path d="M1,1H4.636L7.073,13.752a1.84,1.84,0,0,0,1.818,1.533h8.836a1.84,1.84,0,0,0,1.818-1.533L21,5.762H5.545"
                                                    transform="translate(1524 89)" fill="none" stroke="#17261e" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                        <span class="cart cart-badge-count">{{ $initialCartQty }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ $loggedCustomer ? url('customer-dashboard') : url('customer-login') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20">
                                            <g transform="translate(1 1)">
                                                <path d="M20,21V19a4,4,0,0,0-4-4H8a4,4,0,0,0-4,4v2" transform="translate(-4 -3)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <circle cx="4" cy="4" r="4" transform="translate(4)" fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </g>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="hamburger-menu">
                            <a style="font-size:28px;cursor:pointer" onclick="openNav()">&#9776;</a>
                        </div>
                    </div>
                </aside>
                <!-- Body overlay -->
                <div class="overlay" id="overlayy"></div>
            </div>
        </div>
    </header>
    <!-- Header Area End -->

    <!-- Cart Sidebar Start -->
    <div class="cart-sidebar-overlay" id="cartSidebarOverlay" onclick="closeCartSidebar()"></div>
    <aside class="cart-sidebar" id="cartSidebar">
        <div class="cart-sidebar-header">
            <h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="#5fd47a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Shopping Bag (<span class="cart-badge-count">{{ $initialCartQty }}</span>)
            </h5>
            <button type="button" class="cart-sidebar-close" onclick="closeCartSidebar()">&times;</button>
        </div>

        <div class="cart-sidebar-body" id="cartSidebarBody">
            <!-- Populated dynamically -->
        </div>

        <div class="cart-sidebar-footer" id="cartSidebarFooter">
            <div class="cart-calc-row">
                <span>Subtotal</span>
                <strong id="cartSubtotalAmount">BDT 0.00</strong>
            </div>
            <div class="cart-calc-row">
                <span>Shipping Charge</span>
                <strong id="cartShippingAmount">BDT 0.00</strong>
            </div>
            <div class="cart-calc-row total">
                <span>Total Payable</span>
                <span id="cartTotalAmount" style="color: #0b7a3d;">BDT 0.00</span>
            </div>
            <div class="d-flex" style="gap: 10px;">
                <button type="button" class="btn btn-outline-danger w-50" onclick="clearCart()" style="padding: 11px 12px; font-size: 13px; border-radius: 8px;">
                    Clear Bag
                </button>
                <a href="{{ url('checkout') }}" class="btn bg-primary w-50 text-center" style="padding: 11px 12px; font-size: 13px; color: #fff; border-radius: 8px; font-weight: 600;">
                    Checkout Now
                </a>
            </div>
        </div>
    </aside>
    <!-- Cart Sidebar End -->

    <!-- Toast Notification -->
    <div class="bms-toast" id="bmsToast">
        <i class="fas fa-check-circle" style="color: #4ade80;"></i>
        <span id="bmsToastText">Added to bag!</span>
    </div>

    <!-- Back To Top Button -->
    <button type="button" class="bms-back-to-top" id="bmsBackToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <main>
        @yield('content')
    </main>

    <!-- Pre-Footer Trust Strip -->
    <section class="bms-footer-trust">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-trust-item">
                        <div class="bms-trust-icon"><i class="fas fa-shipping-fast"></i></div>
                        <div class="bms-trust-text">
                            <h6>Fast Delivery</h6>
                            <p>Quick shipping across Bangladesh</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-trust-item">
                        <div class="bms-trust-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="bms-trust-text">
                            <h6>100% Authentic</h6>
                            <p>Quality checked genuine items</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-trust-item">
                        <div class="bms-trust-icon"><i class="fas fa-hand-holding-usd"></i></div>
                        <div class="bms-trust-text">
                            <h6>Cash on Delivery</h6>
                            <p>Pay conveniently at your doorstep</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="bms-trust-item">
                        <div class="bms-trust-icon"><i class="fas fa-headset"></i></div>
                        <div class="bms-trust-text">
                            <h6>Dedicated Support</h6>
                            <p>Sat – Thu, 9:00 AM – 9:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row main-footer">
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="main-footer-info">
                        <img src="{{ asset('bdmegashop/dist/images/logo/logo.png') }}" alt="Logo" class="img-fluid" style="background: #fff; padding: 8px 14px; border-radius: 8px;">
                        <p class="mt-3">
                            BD Mega Shop offers quality products at affordable prices with trusted service, fast delivery, genuine items, and customer satisfaction across Bangladesh.
                        </p>
                        <div class="mt-3" style="font-size: 13px; color: #cdd7d1;">
                            <div><i class="fas fa-phone-alt mr-2" style="color: #5fd47a;"></i> +880 1732-930067</div>
                            <div class="mt-1"><i class="far fa-envelope mr-2" style="color: #5fd47a;"></i> info@bdmegashop.com</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-2 col-md-4 col-sm-6 col-12">
                    <div class="main-footer-quicklinks">
                        <h6>Company</h6>
                        <ul class="quicklink">
                            <li><a href="{{ url('about') }}">About Us</a></li>
                            <li><a href="{{ url('help-and-support') }}">Help &amp; Support</a></li>
                            <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('terms-of-service') }}">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                    <div class="main-footer-quicklinks">
                        <h6>Quick Links</h6>
                        <ul class="quicklink">
                            <li><a href="{{ url('products') }}">All Products</a></li>
                            <li><a href="{{ url('sale-and-discount') }}">Sale &amp; Discount</a></li>
                            <li><a href="{{ url('checkout') }}">Checkout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                    <div class="main-footer-quicklinks">
                        <h6>Customer Account</h6>
                        <ul class="quicklink">
                            <li><a href="javascript:void(0)" onclick="openCartSidebar()">Your Shopping Bag</a></li>
                            @if($loggedCustomer)
                                <li><a href="{{ url('customer-dashboard') }}">My Profile</a></li>
                                <li><a href="{{ url('customer-logout') }}">Log-out</a></li>
                            @else
                                <li><a href="{{ url('customer-login') }}">Customer Login</a></li>
                                <li><a href="{{ url('customer-login') }}">Create Account</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright d-flex justify-content-between align-items-center flex-wrap">
                        <div class="copyright-text order-2 order-lg-1">
                            <p>&copy; {{ date('Y') }}. Design and Developed by <a href="{{ url('/') }}">BD Mega Shop</a></p>
                        </div>
                        <div class="copyright-links order-1 order-lg-2">
                            <a href="javascript:void(0)" class="ml-0"><i class="fab fa-facebook-f"></i></a>
                            <a href="javascript:void(0)"><i class="fab fa-twitter"></i></a>
                            <a href="javascript:void(0)"><i class="fab fa-youtube"></i></a>
                            <a href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer -->

    <script src="{{ asset('bdmegashop/src/js/jquery.min.js') }}"></script>
    <script src="{{ asset('bdmegashop/src/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bdmegashop/src/scss/vendors/plugin/js/slick.min.js') }}"></script>
    <script src="{{ asset('bdmegashop/src/scss/vendors/plugin/js/jquery.exzoom.js') }}"></script>
    <script src="{{ asset('bdmegashop/src/scss/vendors/plugin/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('bdmegashop/dist/main.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var toastTimer = null;
        function showToast(msg) {
            $('#bmsToastText').text(msg || 'Done!');
            $('#bmsToast').addClass('show');
            if (toastTimer) clearTimeout(toastTimer);
            toastTimer = setTimeout(function () {
                $('#bmsToast').removeClass('show');
            }, 2500);
        }

        function openNav() {
            document.getElementById("mySidenav").style.width = "320px";
            $('#overlayy').addClass("active");
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            $('#overlayy').removeClass("active");
        }

        function openCartSidebar() {
            $('#cartSidebar').addClass('active');
            $('#cartSidebarOverlay').addClass('active');
            loadCartSidebarData();
        }

        function closeCartSidebar() {
            $('#cartSidebar').removeClass('active');
            $('#cartSidebarOverlay').removeClass('active');
        }

        function formatBDT(amount) {
            var num = parseFloat(amount) || 0;
            return 'BDT ' + num.toFixed(2);
        }

        function renderCartSidebar(data) {
            $('.cart-badge-count').text(data.total_qty || 0);
            $('#cartSubtotalAmount').text(formatBDT(data.subtotal));
            $('#cartShippingAmount').text(formatBDT(data.shipping));
            $('#cartTotalAmount').text(formatBDT(data.total));

            var body = $('#cartSidebarBody');
            if (!data.items || data.items.length === 0) {
                body.html(
                    '<div class="cart-empty-state">' +
                        '<i class="fas fa-shopping-bag mb-3" style="font-size: 38px; color: #cdd7d1;"></i>' +
                        '<h6 style="color: #2f3d36; font-weight: 700;">Your shopping bag is empty</h6>' +
                        '<p class="mb-3" style="font-size: 13px;">Explore our catalog and add your favorite products.</p>' +
                        '<a href="{{ url("products") }}" class="btn btn-sm bg-primary" style="color:#fff;">Shop Products</a>' +
                    '</div>'
                );
                $('#cartSidebarFooter').hide();
                return;
            }

            $('#cartSidebarFooter').show();
            var html = '';
            data.items.forEach(function (item) {
                var metaHtml = '';
                if (item.size) {
                    metaHtml += '<span class="cart-item-badge">Size: ' + item.size + '</span>';
                }
                if (item.color) {
                    metaHtml += '<span class="cart-item-badge">Color: ' + item.color + '</span>';
                }

                html +=
                    '<div class="cart-item-card">' +
                        '<img src="' + item.image + '" alt="' + item.title + '" class="cart-item-thumb">' +
                        '<div class="cart-item-details">' +
                            '<div class="d-flex justify-content-between align-items-start">' +
                                '<a href="{{ url("details") }}/' + item.slug + '" class="cart-item-title">' + item.title + '</a>' +
                                '<button type="button" class="cart-item-remove" title="Remove" onclick="removeCartItem(\'' + item.key + '\')">&times;</button>' +
                            '</div>' +
                            (metaHtml ? '<div class="cart-item-meta">' + metaHtml + '</div>' : '') +
                            '<div class="text-muted" style="font-size: 12px;">Unit Price: ' + formatBDT(item.price) + '</div>' +
                            '<div class="cart-item-price-row">' +
                                '<div class="cart-qty-control">' +
                                    '<button type="button" class="cart-qty-btn" onclick="updateCartItemQty(\'' + item.key + '\', ' + (parseInt(item.quantity) - 1) + ')">-</button>' +
                                    '<span class="cart-qty-val">' + item.quantity + '</span>' +
                                    '<button type="button" class="cart-qty-btn" onclick="updateCartItemQty(\'' + item.key + '\', ' + (parseInt(item.quantity) + 1) + ')">+</button>' +
                                '</div>' +
                                '<span class="cart-item-subtotal">' + formatBDT(item.line_total) + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            });
            body.html(html);
        }

        function loadCartSidebarData() {
            $.get('{{ route("cart.data") }}', function (res) {
                if (res && res.status === 'success') {
                    renderCartSidebar(res);
                }
            });
        }

        function addToCart(payload) {
            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: payload,
                success: function (res) {
                    if (res && res.status === 'success') {
                        renderCartSidebar(res);
                        showToast('Product added to bag!');
                        $('#cartSidebar').addClass('active');
                        $('#cartSidebarOverlay').addClass('active');
                    }
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to add product to cart.';
                    showToast(msg);
                }
            });
        }

        function toggleWishlist(productId, el) {
            $.post('{{ route("wishlist.toggle") }}', { product_id: productId }, function (res) {
                if (res && res.status === 'success') {
                    $('.wishlist-badge-count').text(res.count || 0);
                    showToast(res.message);
                    if (el) {
                        $(el).find('i').toggleClass('far fas').css('color', res.added ? '#d81e23' : '');
                    }
                }
            });
        }

        function updateCartItemQty(key, quantity) {
            $.post('{{ route("cart.update") }}', { key: key, quantity: quantity }, function (res) {
                if (res && res.status === 'success') {
                    renderCartSidebar(res);
                }
            });
        }

        function removeCartItem(key) {
            $.post('{{ route("cart.remove") }}', { key: key }, function (res) {
                if (res && res.status === 'success') {
                    renderCartSidebar(res);
                    showToast('Item removed from bag.');
                }
            });
        }

        function clearCart() {
            $.post('{{ route("cart.clear") }}', {}, function (res) {
                if (res && res.status === 'success') {
                    renderCartSidebar(res);
                    showToast('Shopping bag cleared.');
                }
            });
        }

        // Live Search Handler
        $(document).ready(function () {
            loadCartSidebarData();

            var searchTimer = null;
            var searchInput = $('#bmsLiveSearchInput');
            var searchDropdown = $('#bmsLiveSearchDropdown');

            searchInput.on('input', function () {
                var q = $.trim($(this).val());
                if (searchTimer) clearTimeout(searchTimer);
                if (q.length < 1) {
                    searchDropdown.hide().empty();
                    return;
                }
                searchTimer = setTimeout(function () {
                    $.get('{{ route("products.live_search") }}', { q: q }, function (res) {
                        if (!res || !res.items || res.items.length === 0) {
                            searchDropdown.html('<div class="p-3 text-center text-muted" style="font-size: 13px;">No matching products found.</div>').show();
                            return;
                        }
                        var html = '';
                        res.items.forEach(function (item) {
                            html +=
                                '<a href="' + item.url + '" class="live-search-item">' +
                                    '<img src="' + item.image + '" alt="' + item.title + '">' +
                                    '<div>' +
                                        '<span class="live-search-item-title">' + item.title + '</span>' +
                                        '<span class="live-search-item-price">' + formatBDT(item.price) + '</span>' +
                                    '</div>' +
                                '</a>';
                        });
                        html += '<a href="{{ url("products") }}?search=' + encodeURIComponent(q) + '" class="d-block text-center py-2 bg-light font-weight-bold" style="font-size: 12px; color: #0b7a3d;">View all results &rarr;</a>';
                        searchDropdown.html(html).show();
                    });
                }, 220);
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.search-bar').length) {
                    searchDropdown.hide();
                }
            });

            $(window).on('scroll', function () {
                if ($(this).scrollTop() > 300) {
                    $('#bmsBackToTop').addClass('visible');
                } else {
                    $('#bmsBackToTop').removeClass('visible');
                }
            });
        });
    </script>
</body>

</html>
