@extends('bdpanel')
@section('content')
<style>
    /* ============================================================
       1. FULL-SCREEN ANIMATED HERO BANNER
       ============================================================ */
    .bms-fullscreen-hero {
        position: relative;
        width: 100%;
        min-height: 82vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #0f172a;
        padding: 70px 0;
    }

    .bms-hero-bg-layer {
        position: absolute;
        inset: 0;
        background-image: url("{{ asset('bdmegashop/dist/images/banner.jpg') }}");
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        animation: bmsHeroKenBurns 18s ease-in-out infinite alternate;
        transform-origin: center center;
        z-index: 1;
    }

    @keyframes bmsHeroKenBurns {
        0% {
            transform: scale(1) translate(0, 0);
        }
        100% {
            transform: scale(1.12) translate(-1.5%, -1%);
        }
    }

    .bms-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            95deg,
            rgba(15, 23, 42, 0.92) 0%,
            rgba(15, 23, 42, 0.78) 48%,
            rgba(15, 23, 42, 0.35) 100%
        );
        z-index: 2;
    }

    .bms-hero-glow {
        position: absolute;
        width: 480px;
        height: 480px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(51, 90, 255, 0.35) 0%, rgba(51, 90, 255, 0) 70%);
        top: -120px;
        left: -100px;
        z-index: 2;
        pointer-events: none;
        animation: bmsPulseGlow 6s ease-in-out infinite alternate;
    }

    @keyframes bmsPulseGlow {
        0% { opacity: 0.5; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1.1); }
    }

    .bms-hero-content-wrap {
        position: relative;
        z-index: 3;
        width: 100%;
    }

    .bms-fade-up-1 {
        animation: bmsFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
    }
    .bms-fade-up-2 {
        animation: bmsFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.25s both;
    }
    .bms-fade-up-3 {
        animation: bmsFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
    }
    .bms-fade-up-4 {
        animation: bmsFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.55s both;
    }

    @keyframes bmsFadeUp {
        0% {
            opacity: 0;
            transform: translateY(28px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bms-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(51, 90, 255, 0.25);
        border: 1px solid rgba(96, 165, 250, 0.45);
        backdrop-filter: blur(8px);
        color: #93c5fd;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 50px;
        margin-bottom: 20px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .bms-hero-heading {
        font-size: 54px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 20px;
        max-width: 700px;
    }

    .bms-hero-heading span {
        background: linear-gradient(90deg, #60a5fa 0%, #38bdf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .bms-hero-subtext {
        font-size: 17px;
        color: #cbd5e1;
        line-height: 1.75;
        max-width: 600px;
        margin-bottom: 32px;
    }

    .bms-hero-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #335aff 0%, #2545d8 100%);
        color: #ffffff !important;
        font-size: 15px;
        font-weight: 700;
        padding: 15px 32px;
        border-radius: 50px;
        text-decoration: none !important;
        box-shadow: 0 10px 25px rgba(51, 90, 255, 0.4);
        transition: all 0.25s ease;
    }

    .bms-hero-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(51, 90, 255, 0.55);
    }

    .bms-hero-btn-glass {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(8px);
        color: #ffffff !important;
        font-size: 15px;
        font-weight: 600;
        padding: 15px 28px;
        border-radius: 50px;
        text-decoration: none !important;
        transition: all 0.25s ease;
    }

    .bms-hero-btn-glass:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-3px);
    }

    .bms-hero-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        margin-top: 42px;
        padding-top: 28px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        max-width: 620px;
    }

    .bms-hero-metric-item h4 {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 2px;
    }

    .bms-hero-metric-item span {
        font-size: 13px;
        color: #94a3b8;
    }

    /* Floating Glass Promo Card on Right Side of Hero */
    .bms-hero-floating-card {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(14px);
        border-radius: 20px;
        padding: 28px;
        color: #ffffff;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        animation: bmsFloatCard 5s ease-in-out infinite alternate;
        max-width: 390px;
        margin-left: auto;
    }

    @keyframes bmsFloatCard {
        0% { transform: translateY(0px); }
        100% { transform: translateY(-14px); }
    }

    .bms-hero-floating-badge {
        display: inline-block;
        background: #f97316;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    /* ============================================================
       2. SECTION HEADERS & CATEGORY CARDS
       ============================================================ */
    .bms-sec-wrap {
        padding: 65px 0;
    }

    .bms-sec-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 34px;
    }

    .bms-sec-tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #335aff;
        background: rgba(51, 90, 255, 0.08);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .bms-sec-title {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .bms-sec-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #335aff !important;
        background: rgba(51, 90, 255, 0.08);
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-sec-link:hover {
        background: #335aff;
        color: #ffffff !important;
    }

    /* ============================================================
       2B. EYE-CATCHING SHOP BY CATEGORY CARDS (.bms-cat-card)
       ============================================================ */
    .bms-cat-card {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px 22px 20px;
        height: 100%;
        text-decoration: none !important;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        z-index: 1;
    }

    .bms-cat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--cat-grad, linear-gradient(90deg, #335aff, #6366f1));
        opacity: 0.85;
        transition: height 0.3s ease;
    }

    .bms-cat-card::after {
        content: '';
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: var(--cat-soft, rgba(51, 90, 255, 0.06));
        top: -45px;
        right: -45px;
        transition: transform 0.4s ease;
        z-index: -1;
    }

    .bms-cat-card:hover {
        border-color: transparent;
        transform: translateY(-7px);
        box-shadow: 0 20px 38px rgba(15, 23, 42, 0.10);
    }

    .bms-cat-card:hover::before {
        height: 6px;
    }

    .bms-cat-card:hover::after {
        transform: scale(1.45);
    }

    .bms-cat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .bms-cat-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: var(--cat-grad, linear-gradient(135deg, #335aff 0%, #6366f1 100%));
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 10px 20px var(--cat-shadow, rgba(51, 90, 255, 0.25));
        overflow: hidden;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .bms-cat-card:hover .bms-cat-icon {
        transform: scale(1.08) rotate(-4deg);
    }

    .bms-cat-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bms-cat-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: var(--cat-color, #335aff);
        background: var(--cat-soft, rgba(51, 90, 255, 0.1));
        padding: 5px 11px;
        border-radius: 50px;
        letter-spacing: 0.3px;
    }

    .bms-cat-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px 0;
        line-height: 1.35;
        transition: color 0.2s ease;
    }

    .bms-cat-card:hover .bms-cat-title {
        color: var(--cat-color, #335aff);
    }

    .bms-cat-sub {
        font-size: 12.5px;
        color: #64748b;
        margin: 0 0 16px 0;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .bms-cat-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 13px;
        border-top: 1px dashed #e2e8f0;
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        transition: color 0.2s ease;
    }

    .bms-cat-arrow {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        transition: all 0.25s ease;
    }

    .bms-cat-card:hover .bms-cat-footer {
        color: var(--cat-color, #335aff);
    }

    .bms-cat-card:hover .bms-cat-arrow {
        background: var(--cat-grad, linear-gradient(135deg, #335aff 0%, #6366f1 100%));
        color: #ffffff;
        transform: translateX(4px);
    }

    /* ============================================================
       2C. CUSTOMER TESTIMONIALS SECTION (.bms-review-sec)
       ============================================================ */
    .bms-review-sec {
        padding: 75px 0 85px;
        background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        position: relative;
        overflow: hidden;
    }

    .bms-review-sec::before {
        content: '';
        position: absolute;
        width: 380px;
        height: 380px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(51, 90, 255, 0.08) 0%, transparent 70%);
        top: -120px;
        left: -120px;
        pointer-events: none;
    }

    .bms-review-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 10px 18px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
    }

    .bms-review-rating-pill .stars {
        color: #f59e0b;
        font-size: 13px;
        letter-spacing: 1px;
    }

    .bms-review-rating-pill .score {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
    }

    .bms-review-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 28px 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
    }

    .bms-review-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--rev-grad, linear-gradient(90deg, #335aff, #6366f1));
    }

    .bms-review-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.10);
        border-color: #cbd5e1;
    }

    .bms-review-quote-icon {
        position: absolute;
        top: 18px;
        right: 22px;
        font-size: 38px;
        color: #f1f5f9;
        pointer-events: none;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .bms-review-card:hover .bms-review-quote-icon {
        color: rgba(51, 90, 255, 0.10);
        transform: scale(1.1) rotate(-6deg);
    }

    .bms-review-stars {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #f59e0b;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .bms-review-verified {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 3px 10px;
        border-radius: 50px;
        margin-left: 8px;
    }

    .bms-review-product-tag {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 11px;
        border-radius: 8px;
        margin-bottom: 14px;
    }

    .bms-review-text {
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
        margin-bottom: 22px;
        position: relative;
        z-index: 1;
    }

    .bms-review-author {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
    }

    .bms-review-author .bms-review-avatar,
    .bms-review-avatar {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 50%;
        background: var(--rev-grad, linear-gradient(135deg, #335aff 0%, #6366f1 100%));
        color: #ffffff !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.12);
    }

    .bms-review-author h6 {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 2px 0;
    }

    .bms-review-author .bms-review-role {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        display: block;
    }

    /* ============================================================
       3. MODERN 4-PER-ROW PRODUCT CARD (.bms-pcard)
       ============================================================ */
    .bms-pcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .bms-pcard:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    .bms-pcard-thumb {
        position: relative;
        width: 100%;
        height: 265px;
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bms-pcard-thumb a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .bms-pcard-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }

    .bms-pcard:hover .bms-pcard-thumb img {
        transform: scale(1.07);
    }

    .bms-pcard-badges {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 3;
        pointer-events: none;
    }

    .bms-badge-discount {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .bms-badge-tag {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(4px);
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .bms-badge-tag.popular {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }

    .bms-pcard-actions {
        position: absolute;
        bottom: 12px;
        right: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 4;
        opacity: 0;
        transform: translateX(12px);
        transition: all 0.25s ease;
    }

    .bms-pcard:hover .bms-pcard-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .bms-pcard-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .bms-pcard-icon-btn:hover {
        background: #335aff;
        color: #ffffff !important;
        border-color: #335aff;
    }

    .bms-pcard-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .bms-pcard-category {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #335aff;
        margin-bottom: 6px;
    }

    .bms-pcard-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a !important;
        text-decoration: none !important;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 44px;
        transition: color 0.2s ease;
    }

    .bms-pcard-title:hover {
        color: #335aff !important;
    }

    .bms-pcard-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-top: auto;
        margin-bottom: 14px;
    }

    .bms-pcard-price {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .bms-pcard-old-price {
        font-size: 13px;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .bms-pcard-cart-btn {
        width: 100%;
        padding: 11px 16px;
        border-radius: 10px;
        border: 0;
        background: linear-gradient(135deg, #335aff 0%, #2545d8 100%);
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.22s ease;
        box-shadow: 0 4px 12px rgba(51, 90, 255, 0.22);
    }

    .bms-pcard-cart-btn:hover {
        background: linear-gradient(135deg, #2545d8 0%, #1d36b8 100%);
        box-shadow: 0 6px 18px rgba(51, 90, 255, 0.35);
        transform: translateY(-1px);
    }

    /* ============================================================
       4. REDESIGNED "WHY SHOP WITH BD MEGA SHOP" SECTION
       ============================================================ */
    .bms-why-section {
        padding: 75px 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .bms-why-img-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.35);
        border: 4px solid rgba(255, 255, 255, 0.12);
    }

    .bms-why-img-wrapper img {
        width: 100%;
        height: 460px;
        object-fit: cover;
        display: block;
    }

    .bms-why-floating-badge {
        position: absolute;
        bottom: 22px;
        left: 22px;
        right: 22px;
        background: rgba(15, 23, 42, 0.88);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .bms-why-floating-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #335aff;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .bms-why-feature-card {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 18px;
        height: 100%;
        transition: all 0.25s ease;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .bms-why-feature-card:hover {
        background: rgba(255, 255, 255, 0.11);
        border-color: rgba(96, 165, 250, 0.4);
        transform: translateY(-3px);
    }

    .bms-why-feature-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(51, 90, 255, 0.25);
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .bms-why-feature-card h6 {
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 4px;
    }

    .bms-why-feature-card p {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
        line-height: 1.5;
    }

    @media (max-width: 991px) {
        .bms-fullscreen-hero {
            min-height: 620px;
            padding: 50px 0;
        }
        .bms-hero-heading {
            font-size: 36px;
        }
    }
</style>

    <!-- 1. Full-Screen Animated Hero Banner Start -->
    <section class="bms-fullscreen-hero">
        <div class="bms-hero-bg-layer"></div>
        <div class="bms-hero-overlay"></div>
        <div class="bms-hero-glow"></div>

        <div class="container bms-hero-content-wrap">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="bms-fade-up-1">
                        <span class="bms-hero-pill">
                            <i class="fas fa-bolt"></i> Trusted Choice for Online Shopping in Bangladesh
                        </span>
                    </div>
                    <h1 class="bms-hero-heading bms-fade-up-2">
                        Discover Premium Quality &amp; <span>Unbeatable Deals</span> Everyday.
                    </h1>
                    <p class="bms-hero-subtext bms-fade-up-3">
                        BD Mega Shop offers 100% genuine electronics, home essentials, fashion accessories, and smart gadgets at affordable prices with fast nationwide delivery.
                    </p>
                    <div class="d-flex flex-wrap align-items-center bms-fade-up-3" style="gap: 14px;">
                        <a href="{{ url('products') }}" class="bms-hero-btn-primary">
                            Shop Now <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ url('sale-and-discount') }}" class="bms-hero-btn-glass">
                            🔥 Explore Hot Offers
                        </a>
                    </div>

                    <div class="bms-hero-metrics bms-fade-up-4">
                        <div class="bms-hero-metric-item">
                            <h4>100%</h4>
                            <span>Genuine Products</span>
                        </div>
                        <div class="bms-hero-metric-item">
                            <h4>64 Districts</h4>
                            <span>Fast Home Delivery</span>
                        </div>
                        <div class="bms-hero-metric-item">
                            <h4>Cash on Delivery</h4>
                            <span>Pay at Your Doorstep</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="bms-hero-floating-card">
                        <span class="bms-hero-floating-badge">✨ Exclusive Benefits</span>
                        <h4 style="font-weight: 800; color: #fff; margin-bottom: 12px;">Shop With Complete Confidence</h4>
                        <p style="font-size: 14px; color: #e2e8f0; line-height: 1.6; margin-bottom: 18px;">
                            Every order is quality-checked before dispatch and backed by our dedicated customer support team.
                        </p>
                        <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                            <i class="fas fa-check-circle" style="color: #4ade80; font-size: 18px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">Fast 3-Day Nationwide Delivery</span>
                        </div>
                        <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                            <i class="fas fa-check-circle" style="color: #4ade80; font-size: 18px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">Easy Return &amp; Replacement Support</span>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 12px;">
                            <i class="fas fa-check-circle" style="color: #4ade80; font-size: 18px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">Cash on Delivery &amp; bKash Accepted</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Full-Screen Animated Hero Banner End -->

    <!-- 2. Shop by Category Section Start -->
    @if(!empty($categories) && $categories->count() > 0)
    @php
        $catThemes = [
            [
                'grad'   => 'linear-gradient(135deg, #335aff 0%, #6366f1 100%)',
                'soft'   => 'rgba(51, 90, 255, 0.09)',
                'shadow' => 'rgba(51, 90, 255, 0.25)',
                'color'  => '#335aff',
                'icon'   => 'fas fa-layer-group',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%)',
                'soft'   => 'rgba(139, 92, 246, 0.09)',
                'shadow' => 'rgba(139, 92, 246, 0.25)',
                'color'  => '#8b5cf6',
                'icon'   => 'fas fa-bolt',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                'soft'   => 'rgba(16, 185, 129, 0.09)',
                'shadow' => 'rgba(16, 185, 129, 0.25)',
                'color'  => '#059669',
                'icon'   => 'fas fa-gem',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #f97316 0%, #ef4444 100%)',
                'soft'   => 'rgba(249, 115, 22, 0.09)',
                'shadow' => 'rgba(249, 115, 22, 0.25)',
                'color'  => '#ea580c',
                'icon'   => 'fas fa-fire',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #06b6d4 0%, #2563eb 100%)',
                'soft'   => 'rgba(6, 182, 212, 0.09)',
                'shadow' => 'rgba(6, 182, 212, 0.25)',
                'color'  => '#0891b2',
                'icon'   => 'fas fa-headphones-alt',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)',
                'soft'   => 'rgba(236, 72, 153, 0.09)',
                'shadow' => 'rgba(236, 72, 153, 0.25)',
                'color'  => '#db2777',
                'icon'   => 'fas fa-crown',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #eab308 0%, #f97316 100%)',
                'soft'   => 'rgba(234, 179, 8, 0.11)',
                'shadow' => 'rgba(234, 179, 8, 0.25)',
                'color'  => '#d97706',
                'icon'   => 'fas fa-star',
            ],
            [
                'grad'   => 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)',
                'soft'   => 'rgba(79, 70, 229, 0.09)',
                'shadow' => 'rgba(79, 70, 229, 0.25)',
                'color'  => '#4f46e5',
                'icon'   => 'fas fa-shopping-bag',
            ],
        ];
    @endphp
    <section class="bms-sec-wrap pb-2">
        <div class="container">
            <div class="bms-sec-header">
                <div>
                    <span class="bms-sec-tag">Curated Collections</span>
                    <h2 class="bms-sec-title">Shop by Category</h2>
                </div>
                <a href="{{ url('products') }}" class="bms-sec-link">
                    Browse All Categories <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="row">
                @foreach($categories->take(8) as $index => $cat)
                @php
                    $theme = $catThemes[$index % count($catThemes)];
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <a href="{{ url('products?category=' . $cat->id) }}"
                       class="bms-cat-card"
                       style="--cat-grad: {{ $theme['grad'] }}; --cat-soft: {{ $theme['soft'] }}; --cat-shadow: {{ $theme['shadow'] }}; --cat-color: {{ $theme['color'] }};">
                        <div>
                            <div class="bms-cat-top">
                                <span class="bms-cat-icon">
                                    @if(!empty($cat->image) && file_exists(public_path('images/category/' . $cat->image)))
                                        <img src="{{ asset('images/category/' . $cat->image) }}" alt="{{ $cat->title }}">
                                    @else
                                        <i class="{{ $theme['icon'] }}"></i>
                                    @endif
                                </span>
                                <span class="bms-cat-count">
                                    <i class="fas fa-box-open"></i> {{ $cat->products_count ?? 0 }} {{ ($cat->products_count ?? 0) == 1 ? 'Item' : 'Items' }}
                                </span>
                            </div>
                            <h5 class="bms-cat-title">{{ $cat->title }}</h5>
                            <p class="bms-cat-sub">{{ !empty($cat->description) ? strip_tags($cat->description) : 'Explore top-rated products & exclusive offers' }}</p>
                        </div>
                        <div class="bms-cat-footer">
                            <span>Explore Collection</span>
                            <span class="bms-cat-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- Shop by Category Section End -->

    <!-- 3. Featured Products Section Start (4 Products Per Row) -->
    <section class="bms-sec-wrap">
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
                @forelse(($featured_products ?? collect())->take(8) as $product)
                    @php
                        $regPrice = (float) ($product->price ?? 0);
                        $sellPrice = (float) ($product->selling_price ?? $product->price ?? 0);
                        $discPct = ($regPrice > $sellPrice && $regPrice > 0)
                            ? round((($regPrice - $sellPrice) / $regPrice) * 100)
                            : (int) ($product->discount ?? 0);
                    @endphp
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="bms-pcard">
                            <div class="bms-pcard-thumb">
                                <div class="bms-pcard-badges">
                                    @if($discPct > 0)
                                        <span class="bms-badge-discount">-{{ $discPct }}% OFF</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="bms-badge-tag">Featured</span>
                                </div>

                                <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}">
                                    <img src="{{ !empty($product->image) ? asset($product->image) : asset('bdmegashop/dist/images/product/05.jpg') }}"
                                         alt="{{ $product->title ?? 'Product Image' }}">
                                </a>

                                <div class="bms-pcard-actions">
                                    <a href="javascript:void(0)" class="bms-pcard-icon-btn"
                                       onclick="toggleWishlist({{ $product->id }}, this)" title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}"
                                       class="bms-pcard-icon-btn" title="View Product Details">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="bms-pcard-body">
                                @if(!empty($product->category->title))
                                    <div class="bms-pcard-category">{{ $product->category->title }}</div>
                                @endif

                                <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}" class="bms-pcard-title">
                                    {{ $product->title ?? '' }}
                                </a>

                                <div class="bms-pcard-price-row">
                                    <span class="bms-pcard-price">BDT {{ number_format($sellPrice, 2) }}</span>
                                    @if($regPrice > $sellPrice)
                                        <span class="bms-pcard-old-price">BDT {{ number_format($regPrice, 2) }}</span>
                                    @endif
                                </div>

                                <button type="button" class="bms-pcard-cart-btn"
                                        onclick="addToCart({ product_id: {{ $product->id }}, quantity: 1 })">
                                    <i class="fas fa-shopping-bag"></i> Add to Bag
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mb-0">No Featured Products Found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Featured Products Section End -->

    <!-- 4. Redesigned "Why Shop with BD Mega Shop" Section Start -->
    <section class="bms-why-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="bms-why-img-wrapper">
                        <img src="{{ asset('bdmegashop/dist/images/feature/medicine.jpg') }}" alt="Why Shop with BD Mega Shop">
                        <div class="bms-why-floating-badge">
                            <div class="bms-why-floating-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h6 style="color: #fff; font-weight: 700; margin: 0 0 2px;">100% Quality &amp; Trust</h6>
                                <p style="color: #cbd5e1; font-size: 12px; margin: 0;">Verified products &amp; nationwide doorstep delivery</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 pl-lg-5">
                    <span class="bms-hero-pill mb-3">Why Choose Us</span>
                    <h2 style="font-size: 36px; font-weight: 800; color: #ffffff; margin-bottom: 14px;">
                        Why Shop with BD Mega Shop
                    </h2>
                    <p style="font-size: 16px; color: #cbd5e1; line-height: 1.7; margin-bottom: 28px;">
                        Enjoy smart online shopping with premium quality products, unbeatable prices, responsive support, and fast delivery service across all 64 districts of Bangladesh.
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="bms-why-feature-card">
                                <div class="bms-why-feature-icon"><i class="fas fa-shield-alt"></i></div>
                                <div>
                                    <h6>Secure Payment Method</h6>
                                    <p>Pay safely via Cash on Delivery, bKash, or Bank Transfer.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="bms-why-feature-card">
                                <div class="bms-why-feature-icon"><i class="fas fa-headset"></i></div>
                                <div>
                                    <h6>24/7 Customer Support</h6>
                                    <p>Friendly support team ready to assist with every order.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="bms-why-feature-card">
                                <div class="bms-why-feature-icon"><i class="fas fa-shipping-fast"></i></div>
                                <div>
                                    <h6>Fast &amp; Reliable Delivery</h6>
                                    <p>Quick dispatch and safe delivery right to your doorstep.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="bms-why-feature-card">
                                <div class="bms-why-feature-icon"><i class="fas fa-box-open"></i></div>
                                <div>
                                    <h6>Wide Range of Quality Products</h6>
                                    <p>Handpicked electronics, gadgets, and daily essentials.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ url('products') }}" class="bms-hero-btn-primary">
                            Get Started Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Redesigned "Why Shop with BD Mega Shop" Section End -->

    <!-- 5. Popular Products Section Start (4 Products Per Row) -->
    <section class="bms-sec-wrap">
        <div class="container">
            <div class="bms-sec-header">
                <div>
                    <span class="bms-sec-tag">Trending Now</span>
                    <h2 class="bms-sec-title">Popular Products</h2>
                </div>
                <a href="{{ url('products') }}" class="bms-sec-link">
                    Explore All Products <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="row">
                @forelse(($popular_products ?? collect())->take(8) as $product)
                    @php
                        $regPrice = (float) ($product->price ?? 0);
                        $sellPrice = (float) ($product->selling_price ?? $product->price ?? 0);
                        $discPct = ($regPrice > $sellPrice && $regPrice > 0)
                            ? round((($regPrice - $sellPrice) / $regPrice) * 100)
                            : (int) ($product->discount ?? 0);
                    @endphp
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="bms-pcard">
                            <div class="bms-pcard-thumb">
                                <div class="bms-pcard-badges">
                                    @if($discPct > 0)
                                        <span class="bms-badge-discount">-{{ $discPct }}% OFF</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="bms-badge-tag popular">🔥 Popular</span>
                                </div>

                                <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}">
                                    <img src="{{ !empty($product->image) ? asset($product->image) : asset('bdmegashop/dist/images/product/01.jpg') }}"
                                         alt="{{ $product->title ?? 'Product Image' }}">
                                </a>

                                <div class="bms-pcard-actions">
                                    <a href="javascript:void(0)" class="bms-pcard-icon-btn"
                                       onclick="toggleWishlist({{ $product->id }}, this)" title="Add to Wishlist">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}"
                                       class="bms-pcard-icon-btn" title="View Product Details">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="bms-pcard-body">
                                @if(!empty($product->category->title))
                                    <div class="bms-pcard-category">{{ $product->category->title }}</div>
                                @endif

                                <a href="{{ url('details/' . ($product->slug ?? $product->id)) }}" class="bms-pcard-title">
                                    {{ $product->title ?? '' }}
                                </a>

                                <div class="bms-pcard-price-row">
                                    <span class="bms-pcard-price">BDT {{ number_format($sellPrice, 2) }}</span>
                                    @if($regPrice > $sellPrice)
                                        <span class="bms-pcard-old-price">BDT {{ number_format($regPrice, 2) }}</span>
                                    @endif
                                </div>

                                <button type="button" class="bms-pcard-cart-btn"
                                        onclick="addToCart({ product_id: {{ $product->id }}, quantity: 1 })">
                                    <i class="fas fa-shopping-bag"></i> Add to Bag
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mb-0">No Popular Products Found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Popular Products Section End -->

    <!-- 6. Customer Reviews Section Start -->
    <section class="bms-review-sec">
        <div class="container">
            <div class="bms-sec-header">
                <div>
                    <span class="bms-sec-tag">Verified Testimonials</span>
                    <h2 class="bms-sec-title">What Our Customers Say</h2>
                </div>
                <div class="bms-review-rating-pill">
                    <span class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </span>
                    <span class="score">4.9 / 5.0 Customer Rating</span>
                </div>
            </div>

            <div class="row">
                <!-- Review 1 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="bms-review-card" style="--rev-grad: linear-gradient(135deg, #335aff 0%, #6366f1 100%);">
                        <i class="fas fa-quote-right bms-review-quote-icon"></i>
                        <div>
                            <div class="bms-review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="bms-review-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            </div>
                            <span class="bms-review-product-tag">
                                <i class="fas fa-fan text-primary mr-1"></i> Turbo Fan
                            </span>
                            <p class="bms-review-text">
                                "I had a great experience purchasing a Turbo Fan from BD Mega Shop. Excellent build quality, super fast delivery, and affordable pricing really impressed me!"
                            </p>
                        </div>
                        <div class="bms-review-author">
                            <div class="bms-review-avatar" style="--rev-grad: linear-gradient(135deg, #335aff 0%, #6366f1 100%);">TB</div>
                            <div>
                                <h6>Tisha Bushra</h6>
                                <span class="bms-review-role">Student &bull; Dhaka</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="bms-review-card" style="--rev-grad: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-quote-right bms-review-quote-icon"></i>
                        <div>
                            <div class="bms-review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="bms-review-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            </div>
                            <span class="bms-review-product-tag">
                                <i class="fas fa-gift text-success mr-1"></i> Kids &amp; Lifestyle
                            </span>
                            <p class="bms-review-text">
                                "Today I received a very beautiful product, and the kids are extremely happy and excited after getting it. Truly satisfied with the packaging and quality."
                            </p>
                        </div>
                        <div class="bms-review-author">
                            <div class="bms-review-avatar" style="--rev-grad: linear-gradient(135deg, #10b981 0%, #059669 100%);">AR</div>
                            <div>
                                <h6>Arifur Rahman</h6>
                                <span class="bms-review-role">Private Service Provider</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="bms-review-card" style="--rev-grad: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%);">
                        <i class="fas fa-quote-right bms-review-quote-icon"></i>
                        <div>
                            <div class="bms-review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="bms-review-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            </div>
                            <span class="bms-review-product-tag">
                                <i class="fas fa-wind text-info mr-1"></i> Turbo AC Fan
                            </span>
                            <p class="bms-review-text">
                                "Loved the Turbo AC Fan from BD Mega Shop! Stylish modern design, powerful cooling performance, and amazing comfort made everyone at home happy."
                            </p>
                        </div>
                        <div class="bms-review-author">
                            <div class="bms-review-avatar" style="--rev-grad: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%);">NM</div>
                            <div>
                                <h6>Nusrat Jahan Munna</h6>
                                <span class="bms-review-role">Lawyer &bull; Verified Buyer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 4 -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="bms-review-card" style="--rev-grad: linear-gradient(135deg, #f97316 0%, #ef4444 100%);">
                        <i class="fas fa-quote-right bms-review-quote-icon"></i>
                        <div>
                            <div class="bms-review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="bms-review-verified">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            </div>
                            <span class="bms-review-product-tag">
                                <i class="fas fa-headphones-alt text-warning mr-1"></i> Wireless Neckband
                            </span>
                            <p class="bms-review-text">
                                "This wireless neckband delivers crystal-clear sound, long-lasting battery backup, a comfortable fit, and an amazing daily music experience."
                            </p>
                        </div>
                        <div class="bms-review-author">
                            <div class="bms-review-avatar" style="--rev-grad: linear-gradient(135deg, #f97316 0%, #ef4444 100%);">TS</div>
                            <div>
                                <h6>Tuhin Sarkar</h6>
                                <span class="bms-review-role">Student &bull; Verified Buyer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Customer Reviews Section End -->
@endsection
