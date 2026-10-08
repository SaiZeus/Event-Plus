@extends('layouts.master')

@section('title', $event->title)

@section('content')

{{-- Font Awesome CDN for Icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* =========================================================
       EVENTEN-INSPIRED PREMIUM THEME SYSTEM
       ========================================================= */
    :root {
        --ev-navy-dark: #0b0819;
        --ev-navy-card: #130e2a;
        --ev-purple-glow: rgba(147, 51, 234, 0.15);
        --ev-pink: #ff2a70;
        --ev-pink-hover: #e01f5c;
        --ev-pink-glow: rgba(255, 42, 112, 0.35);
        --ev-lavender-bg: #f8f7ff;
        --ev-lavender-card: #ffffff;
        --ev-lavender-border: #e2d9f3;
        --ev-text-dark: #1e1335;
        --ev-text-muted: #6b5d84;
    }

    body {
        background-color: var(--ev-navy-dark);
    }

    .event-details-section {
        position: relative;
        background-color: var(--ev-navy-dark);
        color: #ffffff;
        overflow: hidden;
    }

    /* Ambient Background Glows */
    .event-details-section::before {
        content: "";
        position: absolute;
        top: -100px;
        left: -100px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, var(--ev-pink-glow) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
    }

    .event-details-section::after {
        content: "";
        position: absolute;
        top: 300px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.2) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
    }

    .event-details-section .container {
        position: relative;
        z-index: 2;
        max-width: 1140px;
    }

    /* =========================================================
       HERO / BANNER SECTION
       ========================================================= */
    .ev-hero-wrapper {
        position: relative;
        padding: 40px 0 60px;
    }

    .ev-banner-card {
        position: relative;
        width: 100%;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        background: var(--ev-navy-card);
    }

    .ev-banner-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        max-height: 480px;
        overflow: hidden;
    }

    .ev-banner-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ev-banner-card:hover .ev-banner-img-wrap img {
        transform: scale(1.03);
    }

    .ev-banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(11, 8, 25, 0.1) 0%, rgba(11, 8, 25, 0.85) 100%);
    }

    .ev-hero-header {
        margin-top: 28px;
    }

    .ev-hero-title {
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1.15;
        font-weight: 900;
        letter-spacing: -0.03em;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .ev-meta-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px 20px;
        margin-top: 16px;
        padding: 14px 22px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        backdrop-filter: blur(12px);
    }

    .ev-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
    }

    .ev-meta-item i {
        color: var(--ev-pink);
        font-size: 1rem;
    }

    .ev-badge-status {
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        font-weight: 800;
        text-transform: uppercase;
        box-shadow: 0 0 15px rgba(255, 42, 112, 0.4);
    }

    .ev-organizer-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .ev-organizer-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e0d7f3;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .ev-organizer-pill i {
        color: var(--ev-pink);
    }

    /* =========================================================
       WHITE & LAVENDER CONTENT SECTION (CURVED TOP)
       ========================================================= */
    .ev-content-wrapper {
        position: relative;
        background: var(--ev-lavender-bg);
        color: var(--ev-text-dark);
        padding: 50px 0 80px;
        border-radius: 36px 36px 0 0;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.3);
    }

    /* =========================================================
       PANELS & CARDS
       ========================================================= */
    .event-description,
    .ticket-panel,
    .attendee-panel,
    .items-panel,
    .event-map-panel {
        background: var(--ev-lavender-card);
        border: 1px solid var(--ev-lavender-border);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(25, 12, 45, 0.05);
        padding: 28px;
        margin-bottom: 24px;
    }

    .ev-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--ev-text-dark);
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 16px;
        letter-spacing: -0.01em;
    }

    .ev-section-title i {
        color: var(--ev-pink);
    }

    .event-description p {
        color: var(--ev-text-muted);
        line-height: 1.75;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    /* =========================================================
       MAP PANEL
       ========================================================= */
    .event-map-panel {
        padding: 28px;
    }

    .event-map-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .event-map-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #f0eafc;
        color: var(--ev-pink);
        font-size: 1.2rem;
    }

    .event-map-header h4 {
        margin: 0;
        color: var(--ev-text-dark);
        font-size: 1.1rem;
        font-weight: 800;
    }

    .event-map-header p {
        margin: 2px 0 0;
        color: var(--ev-text-muted);
        font-size: 0.8rem;
    }

    #event-details-map {
        width: 100%;
        height: 380px;
        overflow: hidden;
        border: 1px solid var(--ev-lavender-border);
        border-radius: 16px;
        background: #eef1f5;
        box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
    }

    .event-map-address {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
        padding: 14px 18px;
        border: 1px solid var(--ev-lavender-border);
        border-radius: 14px;
        background: #f3edfc;
        color: var(--ev-text-dark);
        font-size: 0.88rem;
        font-weight: 600;
    }

    .event-map-address i {
        color: var(--ev-pink);
    }

    /* =========================================================
       WHAT'S INCLUDED / ITEMS
       ========================================================= */
    .event-item-card {
        height: 100%;
        border: 1px solid var(--ev-lavender-border);
        border-radius: 16px;
        padding: 14px;
        text-align: center;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(25, 12, 45, 0.03);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .event-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(255, 42, 112, 0.12);
        border-color: rgba(255, 42, 112, 0.4);
    }

    .event-item-image-button {
        position: relative;
        width: 100%;
        height: 150px;
        padding: 0;
        margin: 0 0 12px;
        border: 0;
        border-radius: 12px;
        overflow: hidden;
        background: #f0eafc;
        cursor: pointer;
        display: block;
    }

    .event-item-image-button img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.4s ease;
    }

    .event-item-image-button:hover img {
        transform: scale(1.08);
    }

    .event-item-image-button::after {
        content: "\f00e";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 10px;
        bottom: 10px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--ev-pink);
        color: #fff;
        font-size: 0.75rem;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.25s ease;
        box-shadow: 0 4px 10px rgba(255, 42, 112, 0.4);
    }

    .event-item-image-button:hover::after {
        opacity: 1;
        transform: scale(1);
    }

    .event-item-placeholder {
        width: 100%;
        height: 150px;
        border-radius: 12px;
        margin-bottom: 12px;
        background: #f0eafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a855f7;
        font-size: 1.6rem;
    }

    .event-item-card h6 {
        font-size: 0.88rem;
        line-height: 1.4;
        color: var(--ev-text-dark) !important;
        font-weight: 700;
    }

    /* =========================================================
       TICKET SELECTION PANEL
       ========================================================= */
    .ticket-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        margin-bottom: 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1e1335 0%, #0b0819 100%);
        border: 1px solid rgba(255, 42, 112, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: #ffffff;
    }

    .ticket-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        margin: 0;
    }

    .ticket-header-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--ev-pink);
        color: #fff;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(255, 42, 112, 0.4);
    }

    .ticket-header-tag {
        font-size: 0.75rem;
        font-weight: 800;
        background: rgba(255, 42, 112, 0.18);
        border: 1px solid rgba(255, 42, 112, 0.4);
        color: var(--ev-pink);
        padding: 6px 14px;
        border-radius: 999px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .ticket-table-wrap {
        border: 1px solid var(--ev-lavender-border);
        border-radius: 16px;
        overflow: hidden;
        background: #ffffff;
    }

    .ticket-table {
        margin-bottom: 0 !important;
        width: 100%;
    }

    .ticket-table thead th {
        background: #1e1335 !important;
        color: #ffffff;
        border: 0;
        padding: 16px 20px;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .ticket-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .ticket-table tbody tr:hover {
        background: #f8f5fd;
    }

    .ticket-table tbody td {
        padding: 18px 20px;
        border-color: #f0eafc;
        color: var(--ev-text-dark);
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .ticket-table tbody td strong {
        color: var(--ev-text-dark);
        font-weight: 800;
    }

    .ticket-table .input-group {
        width: 135px !important;
        margin: 0 auto;
        flex-wrap: nowrap !important;
    }

    .ticket-table .btn-minus,
    .ticket-table .btn-plus {
        width: 38px !important;
        height: 38px !important;
        padding: 0;
        border-radius: 10px !important;
        border: 1px solid var(--ev-lavender-border);
        background: #ffffff;
        color: var(--ev-text-dark);
        font-size: 1rem;
        font-weight: 800;
        flex-shrink: 0;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ticket-table .btn-minus:hover:not(:disabled),
    .ticket-table .btn-plus:hover:not(:disabled) {
        border-color: var(--ev-pink);
        color: #ffffff;
        background: var(--ev-pink);
        box-shadow: 0 4px 10px rgba(255, 42, 112, 0.3);
    }

    .ticket-table .btn-plus:disabled,
    .ticket-table .btn-minus:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .ticket-table .ticket-qty {
        width: 45px !important;
        height: 38px !important;
        min-height: 38px !important;
        max-height: 38px !important;
        line-height: 38px !important;
        font-size: 1rem;
        border-color: var(--ev-lavender-border);
        background: #f8f5fd;
        font-weight: 800;
        color: var(--ev-text-dark);
        padding: 0 !important;
        text-align: center;
        flex-grow: 1;
    }

    /* =========================================================
       ATTENDEE FORM CARDS
       ========================================================= */
    .attendee-card {
        position: relative;
        margin-bottom: 22px;
        padding: 26px !important;
        border: 1px solid var(--ev-lavender-border) !important;
        border-radius: 20px !important;
        background: #ffffff;
        box-shadow: 0 6px 20px rgba(25, 12, 45, 0.04);
        transition: box-shadow 0.25s ease;
    }

    .attendee-card:hover {
        box-shadow: 0 10px 30px rgba(25, 12, 45, 0.08);
    }

    .attendee-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--ev-pink), #a855f7);
        border-radius: 20px 0 0 20px;
    }

    .attendee-card h5 {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--ev-text-dark) !important;
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 20px !important;
    }

    .attendee-card h5::before {
        content: "\f00c";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 42, 112, 0.12);
        color: var(--ev-pink);
        font-size: 0.8rem;
    }

    .attendee-card .form-label,
    .checkout-summary .form-label {
        color: var(--ev-text-dark);
        font-size: 0.82rem;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .attendee-card .form-control:not(textarea),
    .attendee-card .form-select,
    .checkout-summary .form-control:not(textarea) {
        height: 46px !important;
        min-height: 46px !important;
        max-height: 46px !important;
        padding: 8px 16px !important;
        border: 1px solid var(--ev-lavender-border);
        border-radius: 12px;
        background: #fcfaff;
        color: var(--ev-text-dark);
        font-size: 0.9rem;
        line-height: 1.5 !important;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }

    .attendee-card textarea.form-control {
        height: 85px !important;
        min-height: 85px !important;
        max-height: 130px !important;
        padding: 12px 16px !important;
        line-height: 1.5 !important;
        resize: vertical;
        border: 1px solid var(--ev-lavender-border);
        background: #fcfaff;
        color: var(--ev-text-dark);
        border-radius: 12px;
    }

    .attendee-card textarea.form-control[name*="[medical_details]"] {
        height: 75px !important;
        min-height: 75px !important;
        max-height: 110px !important;
    }

    .attendee-card .form-control:focus,
    .attendee-card .form-select:focus,
    .checkout-summary .form-control:focus {
        border-color: var(--ev-pink);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(255, 42, 112, 0.15);
        outline: none;
    }

    .attendee-card .form-select:not([multiple]),
    .checkout-summary .form-select:not([multiple]) {
        background-image:
            linear-gradient(45deg, transparent 50%, var(--ev-pink) 50%),
            linear-gradient(135deg, var(--ev-pink) 50%, transparent 50%);
        background-position:
            calc(100% - 18px) 50%,
            calc(100% - 13px) 50%;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        padding-right: 36px !important;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .attendee-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0 16px;
        padding: 10px 16px;
        border-radius: 12px;
        background: #f4eeff;
        border: 1px solid var(--ev-lavender-border);
        color: var(--ev-text-dark);
        font-size: 0.9rem;
        font-weight: 800;
    }

    .attendee-section-title i {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--ev-pink);
        color: #ffffff;
        font-size: 0.85rem;
    }

    .attendee-section {
        margin-bottom: 18px;
    }

    .required-star {
        color: var(--ev-pink);
        font-weight: 900;
    }

    /* =========================================================
       CHECKOUT SUMMARY & CTA
       ========================================================= */
    .checkout-summary {
        margin-top: 24px !important;
        padding: 24px 28px !important;
        border: 1px solid var(--ev-lavender-border) !important;
        border-radius: 20px !important;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(25, 12, 45, 0.06);
    }

    .promo-input-wrap {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid var(--ev-lavender-border);
        border-radius: 12px;
        overflow: hidden;
        height: 46px !important;
        transition: border-color 0.2s ease;
    }

    .promo-input-wrap:focus-within {
        border-color: var(--ev-pink);
        box-shadow: 0 0 0 4px rgba(255, 42, 112, 0.15);
    }

    .btn-apply-promo {
        background: var(--ev-pink) !important;
        border: 0 !important;
        color: #ffffff !important;
        font-size: 0.85rem !important;
        font-weight: 800 !important;
        padding: 0 18px !important;
        height: 46px !important;
        line-height: 46px !important;
        white-space: nowrap;
        transition: background-color 0.2s ease;
    }

    .btn-apply-promo:hover {
        background: var(--ev-pink-hover) !important;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        color: var(--ev-text-muted);
        font-size: 0.92rem;
    }

    .summary-line.total-line {
        border-top: 2px dashed var(--ev-lavender-border);
        padding-top: 14px;
        margin-top: 14px;
    }

    .payment-btn {
        min-height: 52px;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--ev-pink) 0%, #d91b5c 100%);
        box-shadow: 0 8px 25px rgba(255, 42, 112, 0.4);
        font-size: 1rem;
        font-weight: 850;
        letter-spacing: 0.02em;
        transition: all 0.25s ease;
        color: #ffffff !important;
    }

    .payment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 42, 112, 0.5);
        background: linear-gradient(135deg, #ff407f 0%, var(--ev-pink) 100%);
    }

    /* =========================================================
       MODAL IMAGE POPUP
       ========================================================= */
    .event-image-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 25px;
        background: rgba(11, 8, 25, 0.92);
        backdrop-filter: blur(8px);
    }

    .event-image-modal.active {
        display: flex;
    }

    .event-image-modal-content {
        position: relative;
        max-width: min(950px, 95vw);
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .event-image-modal img {
        display: block;
        max-width: 100%;
        max-height: 82vh;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 18px;
        box-shadow: 0 20px 70px rgba(0, 0, 0, 0.6);
        background: #fff;
    }

    .event-image-modal-title {
        margin-top: 12px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        text-align: center;
    }

    .event-image-modal-close {
        position: absolute;
        top: -16px;
        right: -16px;
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 50%;
        background: var(--ev-pink);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        z-index: 2;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .event-image-modal-close:hover {
        background: var(--ev-pink-hover);
        transform: scale(1.1);
    }

    /* Leaflet customization */
    .leaflet-container {
        font-family: inherit;
        z-index: 1;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 12px;
    }

    .leaflet-popup-content {
        margin: 12px 14px;
        color: var(--ev-text-dark);
        font-size: 0.85rem;
        font-weight: 700;
    }

    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .ev-hero-wrapper {
            padding: 20px 0 40px;
        }

        .ev-banner-img-wrap {
            aspect-ratio: 16 / 9;
            max-height: 230px;
        }

        .ev-meta-bar {
            padding: 12px 16px;
            gap: 10px 14px;
        }

        .ev-content-wrapper {
            padding: 30px 0 60px;
            border-radius: 24px 24px 0 0;
        }

        .event-description,
        .ticket-panel,
        .attendee-panel,
        .items-panel,
        .event-map-panel {
            padding: 18px;
            border-radius: 16px;
        }

        #event-details-map {
            height: 280px;
        }

        .event-item-image-button,
        .event-item-placeholder {
            height: 125px;
        }

        .attendee-card {
            padding: 18px !important;
        }

        .ticket-header-box {
            padding: 14px 16px;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .ticket-header-tag {
            align-self: flex-start;
        }

        .ticket-table-wrap {
            border: 0;
            background: transparent;
            overflow-x: visible;
        }

        .ticket-table, 
        .ticket-table thead, 
        .ticket-table tbody, 
        .ticket-table tr, 
        .ticket-table th, 
        .ticket-table td {
            display: block;
            width: 100% !important;
        }

        .ticket-table thead {
            display: none;
        }

        .ticket-table tbody tr {
            background: #ffffff;
            border: 1px solid var(--ev-lavender-border);
            border-radius: 14px;
            margin-bottom: 14px;
            padding: 16px;
            box-shadow: 0 4px 15px rgba(25, 12, 45, 0.04);
        }

        .ticket-table tbody td {
            padding: 8px 0 !important;
            border: none !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            font-size: 0.9rem !important;
        }

        .ticket-table tbody td:first-child {
            font-size: 1.05rem !important;
            font-weight: 800;
            color: var(--ev-text-dark);
            border-bottom: 1px solid #f0eafc !important;
            padding-bottom: 10px !important;
            margin-bottom: 10px;
            justify-content: space-between;
        }

        .ticket-table tbody td:nth-child(2)::before {
            content: "Price:";
            font-weight: 700;
            color: var(--ev-text-muted);
        }

        .ticket-table tbody td:last-child {
            margin-top: 10px;
            padding-top: 12px !important;
            border-top: 1px dashed #f0eafc !important;
            justify-content: space-between;
        }

        .ticket-table tbody td:last-child::before {
            content: "Select Quantity:";
            font-weight: 700;
            color: var(--ev-text-muted);
            font-size: 0.88rem;
        }

        .ticket-table .input-group {
            width: 135px !important;
            margin: 0 !important;
        }

        .checkout-summary {
            padding: 18px !important;
        }
    }
</style>

{{-- Leaflet CSS --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    crossorigin=""
>

<section class="event-details-section">

    {{-- HERO / BANNER HEADER --}}
    <div class="ev-hero-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="ev-banner-card">
                        <div class="ev-banner-img-wrap">
                            <img
                                src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}"
                                alt="{{ $event->title }}"
                            >
                            <div class="ev-banner-overlay"></div>
                        </div>
                    </div>

                    <div class="ev-hero-header">
                        <h1 class="ev-hero-title">{{ $event->title }}</h1>

                        <div class="ev-meta-bar">
                            <div class="ev-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y • h:i A') }}</span>
                            </div>

                            <div class="ev-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location }}</span>
                            </div>

                            <span class="ev-badge-status bg-{{
                                $event->status === 'live'
                                    ? 'danger'
                                    : ($event->status === 'upcoming' ? 'success' : 'secondary')
                            }}">
                                {{ strtoupper($event->status) }}
                            </span>
                        </div>

                        @if($event->creator_name || $event->creator_phone || $event->creator_email)
                            <div class="ev-organizer-pills">
                                @if($event->creator_name)
                                    <span class="ev-organizer-pill">
                                        <i class="fas fa-user-circle"></i>
                                        Organized by: {{ $event->creator_name }}
                                    </span>
                                @endif

                                @if($event->creator_phone)
                                    <span class="ev-organizer-pill">
                                        <i class="fas fa-phone-alt"></i>
                                        Contact: {{ $event->creator_phone }}
                                    </span>
                                @endif

                                @if($event->creator_email)
                                    <span class="ev-organizer-pill">
                                        <i class="fas fa-envelope"></i>
                                        <a href="mailto:{{ $event->creator_email }}" class="text-white text-decoration-none">
                                            {{ $event->creator_email }}
                                        </a>
                                    </span>
                                @endif
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT LIGHT WRAPPER --}}
    <div class="ev-content-wrapper">
        <div class="container">

            {{-- DESCRIPTION --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="event-description">
                        <h4 class="ev-section-title">
                            <i class="fas fa-align-left"></i>
                            About This Event
                        </h4>
                        <p>{{ $event->description }}</p>
                    </div>
                </div>
            </div>

            {{-- MAP LOCATION --}}
            @if($event->latitude && $event->longitude)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="event-map-panel">
                            <div class="event-map-header">
                                <div class="event-map-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <h4>Event Location</h4>
                                    <p>Find your way to the event venue.</p>
                                </div>
                            </div>

                            <div id="event-details-map"></div>

                            <div class="event-map-address">
                                <i class="fas fa-location-arrow"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- WHAT'S INCLUDED --}}
            @if($event->items && $event->items->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="items-panel">
                            <h3 class="ev-section-title">
                                <i class="fas fa-gift"></i>
                                What's Included with Your Entry
                            </h3>

                            <div class="row g-3 mt-1">
                                @foreach($event->items as $item)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="event-item-card">
                                            @if($item->image)
                                                <button
                                                    type="button"
                                                    class="event-item-image-button"
                                                    data-image="{{ asset('storage/' . $item->image) }}"
                                                    data-title="{{ $item->title ?? 'Event Perk' }}"
                                                    aria-label="View {{ $item->title ?? 'Event Perk' }}"
                                                >
                                                    <img
                                                        src="{{ asset('storage/' . $item->image) }}"
                                                        alt="{{ $item->title ?? 'Event Perk' }}"
                                                        loading="lazy"
                                                    >
                                                </button>
                                            @else
                                                <div class="event-item-placeholder">
                                                    <i class="fas fa-box-open"></i>
                                                </div>
                                            @endif

                                            <h6 class="mb-0">
                                                {{ $item->title ?? 'Event Perk' }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- IMAGE MODAL --}}
            <div id="event-image-modal" class="event-image-modal" aria-hidden="true">
                <div class="event-image-modal-content">
                    <button type="button" class="event-image-modal-close" id="event-image-modal-close" aria-label="Close image">
                        <i class="fas fa-times"></i>
                    </button>
                    <img id="event-modal-image" src="" alt="">
                    <div id="event-modal-title" class="event-image-modal-title"></div>
                </div>
            </div>

            {{-- REGISTRATION & TICKET WORKFLOW --}}
            @if($event->status === 'live')

                @if($isEventSoldOut)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger p-4 text-center rounded-4 shadow-sm mb-0">
                                <h4 class="alert-heading font-weight-bold mb-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Event Sold Out
                                </h4>
                                <p class="mb-0 small">
                                    This event has reached its maximum overall capacity limit. Ticket registration is currently closed.
                                </p>
                            </div>
                        </div>
                    </div>
                @else

                    {{-- TICKET SELECTION TABLE --}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ticket-panel">
                                <div class="ticket-header-box">
                                    <div class="ticket-header-title">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Select Your Tickets <small class="text-white-50 ms-1" style="font-size: 0.75rem; font-weight: normal;">(Tap "+" to select quantity)</small></span>
                                    </div>
                                    <span class="ticket-header-tag">Instant Booking</span>
                                </div>

                                <div class="table-responsive ticket-table-wrap">
                                    <table class="table align-middle ticket-table">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th style="display: none;">Foreign Price</th>
                                                <th style="width: 150px;" class="text-center">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($event->ticketCategories as $category)
                                                @php
                                                    $categoryRemaining = $category->capacity !== null
                                                        ? max(0, $category->capacity - $category->tickets_sold)
                                                        : null;

                                                    $eventRemainingForCategory = $overallTicketsRemaining !== null
                                                        ? $overallTicketsRemaining
                                                        : null;

                                                    if ($categoryRemaining !== null && $eventRemainingForCategory !== null) {
                                                        $categoryMax = min($categoryRemaining, $eventRemainingForCategory);
                                                    } elseif ($categoryRemaining !== null) {
                                                        $categoryMax = $categoryRemaining;
                                                    } elseif ($eventRemainingForCategory !== null) {
                                                        $categoryMax = $eventRemainingForCategory;
                                                    } else {
                                                        $categoryMax = 999;
                                                    }

                                                    $isCategorySoldOut = $categoryMax <= 0;
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <strong>{{ $category->name }}</strong>
                                                        @if($isCategorySoldOut)
                                                            <span class="badge bg-danger ms-2" style="font-size: .65rem;">SOLD OUT</span>
                                                        @elseif($categoryRemaining !== null)
                                                            <span class="badge bg-success ms-2" style="font-size: .65rem;">{{ $categoryRemaining }} LEFT</span>
                                                        @endif
                                                    </td>

                                                    <td class="fw-bold">
                                                        {{ number_format($category->local_price) }} MMK
                                                    </td>

                                                    <td style="display: none;">
                                                        {{ $category->foreign_price ? number_format($category->foreign_price) . ' MMK' : 'N/A' }}
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <button class="btn btn-minus" type="button" data-id="{{ $category->id }}">-</button>
                                                            <input
                                                                type="number"
                                                                class="form-control text-center ticket-qty"
                                                                id="qty-{{ $category->id }}"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}"
                                                                data-local-price="{{ $category->local_price }}"
                                                                data-foreign-price="{{ $category->foreign_price ?? '' }}"
                                                                data-max="{{ $categoryMax }}"
                                                                data-event-max="{{ $overallTicketsRemaining !== null ? $overallTicketsRemaining : 999 }}"
                                                                value="0"
                                                                min="0"
                                                                readonly
                                                            >
                                                            <button
                                                                class="btn btn-plus"
                                                                type="button"
                                                                data-id="{{ $category->id }}"
                                                                @if($isCategorySoldOut) disabled @endif
                                                            >+</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CHECKOUT / ATTENDEE FORM --}}
                    <div class="row" id="checkout-section" style="display: none;">
                        <div class="col-lg-12">
                            <div class="attendee-panel">
                                <form
                                    action="{{ route('events.waiver', $event) }}"
                                    method="POST"
                                    id="checkout-form"
                                >
                                    @csrf

                                    <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">

                                    <h3 class="ev-section-title">
                                        <i class="fas fa-user-edit"></i>
                                        Participate Details
                                    </h3>

                                    <div id="attendee-forms-container"></div>

                                    <div class="card checkout-summary">
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6 text-md-end">
                                                <div class="summary-line">
                                                    <span>Subtotal:</span>
                                                    <span class="fw-bold"><span id="subtotal-amount">0</span> MMK</span>
                                                </div>

                                                <div class="summary-line text-success" id="discount-row" style="display: none;">
                                                    <span>Total Discount:</span>
                                                    <span class="fw-bold">- <span id="discount-amount">0</span> MMK</span>
                                                </div>

                                                <div class="summary-line total-line">
                                                    <span class="h6 font-weight-bold mb-0">Total Amount:</span>
                                                    <span class="h4 font-weight-bold text-dark mb-0">
                                                        <span id="grand-total">0</span> MMK
                                                    </span>
                                                </div>

                                                <button type="submit" class="btn payment-btn w-100 mt-3">
                                                    Get Tickets Now <i class="fas fa-arrow-right ms-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @endif

            @elseif($event->status === 'upcoming')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info p-4 text-center rounded-4 shadow-sm mb-0">
                            <h4 class="alert-heading font-weight-bold mb-1">
                                <i class="fas fa-clock me-1"></i>
                                Registration Opening Soon
                            </h4>
                            <p class="mb-0 small">
                                Ticket registration for this upcoming event is not open yet. Please check back once the event is live!
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-secondary p-4 text-center rounded-4 shadow-sm mb-0">
                            <h4 class="alert-heading font-weight-bold mb-1">
                                <i class="fas fa-flag-checkered me-1"></i>
                                Event Concluded
                            </h4>
                            <p class="mb-0 small">
                                This event has already ended. Ticket registration is closed.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</section>

{{-- Leaflet JS --}}
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    crossorigin=""
></script>

@push('scripts')
<script>
const districtOptions = {
  "1": [
    "၀မန", "ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ"
  ],
  "2": [
    "ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"
  ],
  "3": [
    "ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"
  ],
  "4": [
    "ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"
  ],
  "5": [
    "ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"
  ],
  "6": [
    "ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"
  ],
  "7": [
    "ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"
  ],
  "8": [
    "ကထန", "ကမန", "ခမန", "ဂဂန", "ငဖန", "စတရ", "စလန", "ဆပဝ", "ဆဖန", "ဆမန", "တတက", "ထလန", "နမန", "ပခက", "ပဖြန", "ပမန", "မကန", "မတန", "မထန", "မဘန", "မမန", "မလန", "မသန", "ရစက", "ရနခ", "သရန", "အလန"
  ],
  "9": [
    "ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မရတ", "မရမ", "မနမ", "မနတ", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"
  ],
  "10": [
    "ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"
  ],
  "11": [
    "ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"
  ],
  "12": [
    "ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗတထ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"
  ],
  "13": [
    "ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မတန", "မနန", "မပန", "မပန", "မဖန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမတ", "မမန", "မမန", "မယန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"
  ],
  "14": [
    "ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"
  ]
};

const countriesList = [
    "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russian Federation", "Rwanda", "St Kitts & Nevis", "St Lucia", "Saint Vincent & the Grenadines", "Samoa", "San Marino", "Sao Tome & Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
];

const enabledFields = {!! \Illuminate\Support\Js::from($event->enabled_fields ?? [
    'viber',
    'father_name',
    'blood_type',
    'tshirt_size',
    'has_medical_condition',
    'itra',
    'experience',
    'address'
]) !!};

const eventTshirtSizes = {!! \Illuminate\Support\Js::from($event->tshirt_sizes ?? ['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL']) !!};

const enableBibNumber = {!! \Illuminate\Support\Js::from((bool) ($event->enable_bib_number ?? true)) !!};

/* MAP INITIALIZATION */
document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('event-details-map');
    if (
        mapElement &&
        typeof L !== 'undefined' &&
        @json($event->latitude) !== null &&
        @json($event->longitude) !== null
    ) {
        const eventLatitude = parseFloat(@json($event->latitude));
        const eventLongitude = parseFloat(@json($event->longitude));

        if (Number.isFinite(eventLatitude) && Number.isFinite(eventLongitude)) {
            const eventMap = L.map(mapElement).setView([eventLatitude, eventLongitude], 16);

            L.tileLayer(
                'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
                }
            ).addTo(eventMap);

            const eventMarker = L.marker([eventLatitude, eventLongitude]).addTo(eventMap);

            eventMarker.bindPopup(`
                <strong>{{ addslashes($event->title) }}</strong><br>
                {{ addslashes($event->location) }}
            `);

            setTimeout(function () {
                eventMap.invalidateSize();
            }, 300);
        }
    }
});

/* IMAGE POPUP */
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('event-image-modal');
    const modalImage = document.getElementById('event-modal-image');
    const modalTitle = document.getElementById('event-modal-title');
    const closeButton = document.getElementById('event-image-modal-close');

    if (!modal || !modalImage) return;

    document.querySelectorAll('.event-item-image-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const image = this.getAttribute('data-image');
            const title = this.getAttribute('data-title') || '';

            modalImage.src = image;
            modalImage.alt = title;
            modalTitle.textContent = title;

            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeImageModal() {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        modalImage.src = '';
        document.body.style.overflow = '';
    }

    if (closeButton) {
        closeButton.addEventListener('click', closeImageModal);
    }

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeImageModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeImageModal();
    });
});

/* ATTENDEE / TICKET SYSTEM */
document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.ticket-qty');
    const container = document.getElementById('attendee-forms-container');
    const checkoutSection = document.getElementById('checkout-section');
    const checkoutForm = document.getElementById('checkout-form');
    const subtotalEl = document.getElementById('subtotal-amount');
    const discountEl = document.getElementById('discount-amount');
    const grandTotalEl = document.getElementById('grand-total');
    const discountRow = document.getElementById('discount-row');

    let appliedPromos = {};

    function getTotalSelectedTickets() {
        let total = 0;
        qtyInputs.forEach(input => {
            total += parseInt(input.value || 0);
        });
        return total;
    }

    function updateButtonStates() {
        const totalSelectedTickets = getTotalSelectedTickets();

        document.querySelectorAll('.btn-plus').forEach(button => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (!input) return;

            const categoryMax = parseInt(input.getAttribute('data-max') || 999);
            const eventMax = parseInt(input.getAttribute('data-event-max') || 999);
            const currentVal = parseInt(input.value || 0);

            const categoryFull = currentVal >= categoryMax;
            const eventFull = totalSelectedTickets >= eventMax;

            button.disabled = categoryFull || eventFull;
        });

        document.querySelectorAll('.btn-minus').forEach(button => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (!input) return;

            button.disabled = parseInt(input.value || 0) <= 0;
        });
    }

    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (!input) return;

            const categoryMax = parseInt(input.getAttribute('data-max') || 999);
            const eventMax = parseInt(input.getAttribute('data-event-max') || 999);
            const currentVal = parseInt(input.value || 0);
            const totalSelected = getTotalSelectedTickets();

            if (currentVal < categoryMax && totalSelected < eventMax) {
                input.value = currentVal + 1;
                renderForms();
                updateButtonStates();
            }
        });
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (!input) return;

            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                renderForms();
                updateButtonStates();
            }
        });
    });

    if (container) {
        container.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-apply-attendee-promo')) {
                const index = e.target.getAttribute('data-index');
                applyAttendeePromo(index);
            }
        });

        container.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('nationality-select')) {
                const index = e.target.getAttribute('data-index');
                toggleNationality(index, e.target.value);
                calculateTotals();
            }

            if (e.target && e.target.classList.contains('nrc-state-select')) {
                const index = e.target.getAttribute('data-index');
                updateDistricts(index, e.target.value);
            }

            if (e.target && e.target.classList.contains('medical-select')) {
                const index = e.target.getAttribute('data-index');
                toggleMedical(index, e.target.value);
            }

            if (e.target && e.target.classList.contains('itra-select')) {
                const index = e.target.getAttribute('data-index');
                toggleITRA(index, e.target.value);
            }
        });
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            const cards = container.querySelectorAll('.attendee-card');

            cards.forEach((card, index) => {
                const nationalitySelect = card.querySelector('.nationality-select');
                let combinedValue = '';

                if (nationalitySelect && nationalitySelect.value === 'Foreigner') {
                    const passportInput = card.querySelector(`input[name="attendees[${index}][passport_number]"]`);
                    combinedValue = passportInput ? passportInput.value : '';
                } else {
                    const state = card.querySelector(`select[name="attendees[${index}][nrc_state]"]`)?.value || '';
                    const district = card.querySelector(`select[name="attendees[${index}][nrc_district]"]`)?.value || '';
                    const naing = card.querySelector(`select[name="attendees[${index}][nrc_naing]"]`)?.value || '';
                    const number = card.querySelector(`input[name="attendees[${index}][nrc_number]"]`)?.value || '';

                    if (state && district && naing && number) {
                        combinedValue = `${state}/${district}(${naing})${number}`;
                    }
                }

                let hiddenNrcInput = card.querySelector(`input[name="attendees[${index}][nrc_passport]"]`);
                if (!hiddenNrcInput) {
                    hiddenNrcInput = document.createElement('input');
                    hiddenNrcInput.type = 'hidden';
                    hiddenNrcInput.name = `attendees[${index}][nrc_passport]`;
                    card.appendChild(hiddenNrcInput);
                }
                hiddenNrcInput.value = combinedValue;

                let hiddenPromoId = card.querySelector(`input[name="attendees[${index}][promo_code_id]"]`);
                if (appliedPromos[index] && appliedPromos[index].promo_code_id) {
                    if (!hiddenPromoId) {
                        hiddenPromoId = document.createElement('input');
                        hiddenPromoId.type = 'hidden';
                        hiddenPromoId.name = `attendees[${index}][promo_code_id]`;
                        card.appendChild(hiddenPromoId);
                    }
                    hiddenPromoId.value = appliedPromos[index].promo_code_id;
                } else if (hiddenPromoId) {
                    hiddenPromoId.remove();
                }
            });
        });
    }

    function applyAttendeePromo(index) {
        const promoInput = document.getElementById(`promo_code_${index}`);
        const msgBox = document.getElementById(`promo_msg_${index}`);
        const btn = document.querySelector(`.btn-apply-attendee-promo[data-index="${index}"]`);
        const catIdInput = document.querySelector(`input[name="attendees[${index}][ticket_category_id]"]`);

        const code = promoInput.value.trim().toUpperCase();
        const eventId = document.getElementById('event_id').value;
        const categoryId = catIdInput ? catIdInput.value : '';

        if (!code) {
            delete appliedPromos[index];
            msgBox.textContent = 'Promo code removed.';
            msgBox.className = 'mt-1 text-muted small';
            calculateTotals();
            return;
        }

        for (const idx in appliedPromos) {
            if (idx != index && appliedPromos[idx].code === code) {
                msgBox.textContent = 'This promo code has already been applied to another ticket on this page.';
                msgBox.className = 'mt-1 text-danger small';
                delete appliedPromos[index];
                calculateTotals();
                return;
            }
        }

        btn.disabled = true;
        btn.textContent = '...';

        fetch(`/api/check-promo?code=${encodeURIComponent(code)}&event_id=${eventId}&ticket_category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Apply';

            if (data.valid) {
                appliedPromos[index] = {
                    code: code,
                    ticket_category_id: categoryId,
                    promo_code_id: data.promo_code_id,
                    discount_type: data.discount_type,
                    discount_value: parseFloat(data.discount_value)
                };

                msgBox.textContent = `Applied! (${
                    data.discount_type === 'percentage'
                        ? data.discount_value + '%'
                        : new Intl.NumberFormat().format(data.discount_value) + ' MMK'
                } off)`;
                msgBox.className = 'mt-1 text-success small fw-bold';
            } else {
                delete appliedPromos[index];
                msgBox.textContent = data.message || 'Invalid promo code.';
                msgBox.className = 'mt-1 text-danger small';
            }
            calculateTotals();
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Apply';
            delete appliedPromos[index];
            msgBox.textContent = 'Failed to check promo code.';
            msgBox.className = 'mt-1 text-danger small';
            calculateTotals();
        });
    }

    window.updateDistricts = function(index, stateVal) {
        const districtSelect = document.getElementById('nrc_district_' + index);
        if (!districtSelect) return;

        districtSelect.innerHTML = '<option value="">District</option>';

        if (districtOptions[stateVal]) {
            districtOptions[stateVal].forEach(dist => {
                let opt = document.createElement("option");
                opt.value = dist;
                opt.textContent = dist;
                districtSelect.appendChild(opt);
            });
        }
    };

    window.toggleNationality = function(index, value) {
        const nrcBox = document.getElementById('nrc-box-' + index);
        const passportBox = document.getElementById('passport-box-' + index);
        if (!nrcBox || !passportBox) return;

        const nrcInputs = nrcBox.querySelectorAll('select, input');
        const passportInputs = passportBox.querySelectorAll('select, input');

        if (value === 'Foreigner') {
            nrcBox.style.display = 'none';
            passportBox.style.display = 'flex';

            nrcInputs.forEach(i => {
                i.removeAttribute('required');
                i.disabled = true;
            });

            passportInputs.forEach(i => {
                i.disabled = false;
                if (i.name.includes('[passport_number]')) {
                    i.setAttribute('required', 'required');
                }
            });
        } else {
            nrcBox.style.display = 'flex';
            passportBox.style.display = 'none';

            passportInputs.forEach(i => {
                i.removeAttribute('required');
                i.disabled = true;
            });

            nrcInputs.forEach(i => {
                i.disabled = false;
                i.setAttribute('required', 'required');
            });
        }
    };

    window.toggleMedical = function(index, value) {
        const detailsBox = document.getElementById('medical-details-box-' + index);
        const textarea = detailsBox ? detailsBox.querySelector('textarea') : null;
        if (!detailsBox || !textarea) return;

        if (value === 'yes') {
            detailsBox.style.display = 'block';
            textarea.setAttribute('required', 'required');
        } else {
            detailsBox.style.display = 'none';
            textarea.removeAttribute('required');
            textarea.value = '';
        }
    };

    window.toggleITRA = function(index, value) {
        const detailsBox = document.getElementById('itra-details-box-' + index);
        const input = detailsBox ? detailsBox.querySelector('input') : null;
        if (!detailsBox || !input) return;

        if (value === 'yes') {
            detailsBox.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            detailsBox.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        }
    };

    function calculateTotals() {
        if (!container) return;

        let subtotal = 0;
        let totalDiscount = 0;
        const cards = container.querySelectorAll('.attendee-card');

        cards.forEach((card, index) => {
            const select = card.querySelector('.nationality-select');
            const localPrice = parseFloat(select.getAttribute('data-local'));
            const foreignPriceAttr = select.getAttribute('data-foreign');
            const foreignPrice = foreignPriceAttr !== '' ? parseFloat(foreignPriceAttr) : localPrice;

            let itemPrice = select.value === 'Foreigner' ? foreignPrice : localPrice;
            subtotal += itemPrice;

            if (appliedPromos[index]) {
                const promo = appliedPromos[index];
                let itemDiscount = 0;

                if (promo.discount_type === 'percentage') {
                    itemDiscount = (itemPrice * promo.discount_value) / 100;
                } else {
                    itemDiscount = promo.discount_value;
                }

                if (itemDiscount > itemPrice) {
                    itemDiscount = itemPrice;
                }

                totalDiscount += itemDiscount;

                let hiddenPromoId = card.querySelector(`input[name="attendees[${index}][promo_code_id]"]`);
                if (!hiddenPromoId) {
                    hiddenPromoId = document.createElement('input');
                    hiddenPromoId.type = 'hidden';
                    hiddenPromoId.name = `attendees[${index}][promo_code_id]`;
                    card.appendChild(hiddenPromoId);
                }
                hiddenPromoId.value = promo.promo_code_id;
            } else {
                let hiddenPromoId = card.querySelector(`input[name="attendees[${index}][promo_code_id]"]`);
                if (hiddenPromoId) hiddenPromoId.remove();
            }
        });

        const grandTotal = Math.max(0, subtotal - totalDiscount);

        if (subtotalEl) subtotalEl.textContent = new Intl.NumberFormat().format(subtotal);
        if (grandTotalEl) grandTotalEl.textContent = new Intl.NumberFormat().format(grandTotal);

        if (discountEl && discountRow) {
            if (totalDiscount > 0) {
                discountEl.textContent = new Intl.NumberFormat().format(totalDiscount);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }
    }

    function isFieldEnabled(fieldKey) {
        return Array.isArray(enabledFields) && enabledFields.includes(fieldKey);
    }

    function renderForms() {
        if (!container) return;

        const currentCards = container.querySelectorAll('.attendee-card');
        const activeTicketsList = [];

        currentCards.forEach((card, index) => {
            const catIdInput = card.querySelector(`input[name="attendees[${index}][ticket_category_id]"]`);
            if (catIdInput) {
                activeTicketsList.push({
                    catId: catIdInput.value,
                    promo: appliedPromos[index] || null
                });
            }
        });

        container.innerHTML = '';
        appliedPromos = {};
        let formIndex = 0;

        qtyInputs.forEach(input => {
            const qty = parseInt(input.value);
            const catId = input.getAttribute('data-id');
            const catName = input.getAttribute('data-name');
            const localPrice = parseFloat(input.getAttribute('data-local-price'));
            const rawForeignPrice = input.getAttribute('data-foreign-price');

            const isForeignAvailable = rawForeignPrice !== '' && rawForeignPrice !== null && rawForeignPrice !== 'N/A';

            for (let i = 0; i < qty; i++) {
                let savedPromo = null;
                const matchIndex = activeTicketsList.findIndex(t => t.catId === catId && t.promo !== null);

                if (matchIndex !== -1) {
                    savedPromo = activeTicketsList[matchIndex].promo;
                    activeTicketsList.splice(matchIndex, 1);
                }

                if (savedPromo) {
                    appliedPromos[formIndex] = savedPromo;
                }

                const card = document.createElement('div');
                card.className = 'card attendee-card';

                let fieldsHTML = `
                    <h5 class="fw-bold">
                        Participate #${formIndex + 1} - Category: ${catName}
                    </h5>

                    <input type="hidden" name="attendees[${formIndex}][ticket_category_id]" value="${catId}">

                    <div class="row g-2">
                        <!-- 1. Contact Information -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-address-book"></i>
                                <span>1. Contact Information</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email Address <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        name="attendees[${formIndex}][email]"
                                        class="form-control"
                                        placeholder="you@example.com"
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Phone Number <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        inputmode="numeric"
                                        name="attendees[${formIndex}][phone]"
                                        class="form-control"
                                        placeholder="09xxxxxxxxx"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        required
                                    >
                                </div>

                                ${isFieldEnabled('viber') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Viber Number</label>
                                        <input
                                            type="tel"
                                            inputmode="numeric"
                                            name="attendees[${formIndex}][viber]"
                                            class="form-control"
                                            placeholder="09xxxxxxxxx"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        >
                                    </div>
                                ` : ''}

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Emergency Contact Number <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        inputmode="numeric"
                                        name="attendees[${formIndex}][emergency_contact]"
                                        class="form-control"
                                        placeholder="09xxxxxxxxx"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- 2. Required Information -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-user"></i>
                                <span>2. Required Information</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">
                                        Full Name <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="attendees[${formIndex}][full_name]"
                                        class="form-control"
                                        required
                                    >
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Nationality <span class="required-star">*</span>
                                    </label>
                                    <select
                                        name="attendees[${formIndex}][nationality]"
                                        class="form-select nationality-select"
                                        data-index="${formIndex}"
                                        data-local="${localPrice}"
                                        data-foreign="${isForeignAvailable ? rawForeignPrice : ''}"
                                        required
                                    >
                                        <option value="">Select Nationality</option>
                                        <option value="Myanmar">Local (Myanmar)</option>
                                        <option value="Foreigner" ${!isForeignAvailable ? 'disabled' : ''}>
                                            ${isForeignAvailable ? 'Foreigner' : 'Foreigner (N/A)'}
                                        </option>
                                    </select>
                                </div>

                                <!-- Promo Code -->
                                <div class="col-md-4" style="display: none;">
                                    <label class="form-label">Promo Code (Optional)</label>
                                    <div class="promo-input-wrap">
                                        <input
                                            type="text"
                                            id="promo_code_${formIndex}"
                                            class="form-control promo-input"
                                            placeholder="ENTER CODE"
                                            value="${savedPromo ? savedPromo.code : ''}"
                                        >
                                        <button
                                            class="btn btn-apply-promo btn-apply-attendee-promo"
                                            type="button"
                                            data-index="${formIndex}"
                                        >Apply</button>
                                    </div>
                                    <div id="promo_msg_${formIndex}" class="${savedPromo ? 'mt-1 text-success small fw-bold' : ''}">
                                        ${savedPromo ? `Applied! (${savedPromo.discount_type === 'percentage' ? savedPromo.discount_value + '%' : new Intl.NumberFormat().format(savedPromo.discount_value) + ' MMK'} off)` : ''}
                                    </div>
                                </div>

                                <!-- NRC -->
                                <div class="col-md-8" id="nrc-box-${formIndex}">
                                    <label class="form-label">NRC Number</label>
                                    <div class="row g-1">
                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_state]" class="form-select nrc-state-select" data-index="${formIndex}">
                                                <option value="">State</option>
                                                <option value="1">၁/</option>
                                                <option value="2">၂/</option>
                                                <option value="3">၃/</option>
                                                <option value="4">၄/</option>
                                                <option value="5">၅/</option>
                                                <option value="6">၆/</option>
                                                <option value="7">၇/</option>
                                                <option value="8">၈/</option>
                                                <option value="9">၉/</option>
                                                <option value="10">၁၀/</option>
                                                <option value="11">၁၁/</option>
                                                <option value="12">၁၂/</option>
                                                <option value="13">၁၃/</option>
                                                <option value="14">၁၄/</option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_district]" id="nrc_district_${formIndex}" class="form-select">
                                                <option value="">District</option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_naing]" class="form-select">
                                                <option value="">Type</option>
                                                <option value="နိုင်">နိုင်</option>
                                                <option value="ဧည့်">ဧည့်</option>
                                                <option value="စ">စ</option>
                                                <option value="ပြု">ပြု</option>
                                                <option value="သ">သ</option>
                                                <option value="သီ">သီ</option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <input
                                                name="attendees[${formIndex}][nrc_number]"
                                                type="text"
                                                inputmode="numeric"
                                                placeholder="123456"
                                                maxlength="6"
                                                pattern="\\d{6}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                                class="form-control"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Passport -->
                                <div class="col-md-8 row g-1" id="passport-box-${formIndex}" style="display: none;">
                                    <div class="col-md-6">
                                        <label class="form-label">Passport Number</label>
                                        <input
                                            type="text"
                                            name="attendees[${formIndex}][passport_number]"
                                            class="form-control"
                                            placeholder="Passport No."
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <select name="attendees[${formIndex}][country]" class="form-select">
                                            <option value="">Select Country</option>
                                            ${countriesList.map(c => `<option value="${c}">${c}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="required-star">*</span></label>
                                    <select name="attendees[${formIndex}][gender]" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth <span class="required-star">*</span></label>
                                    <input type="date" name="attendees[${formIndex}][date_of_birth]" class="form-control" required>
                                </div>

                                ${isFieldEnabled('father_name') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Father Name (Optional)</label>
                                        <input type="text" name="attendees[${formIndex}][father_name]" class="form-control" placeholder="Father's full name">
                                    </div>
                                ` : ''}

                                ${isFieldEnabled('address') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="attendees[${formIndex}][address]" class="form-control" placeholder="Full address...">
                                    </div>
                                ` : ''}
                            </div>
                        </div>

                        <!-- 3. Participate Information -->
                        <div class="col-12">
                            <div class="attendee-section">
                                <div class="attendee-section-title">
                                    <i class="fas fa-running"></i>
                                    <span>3. Participate Information</span>
                                </div>

                                <div class="row g-2">
                                    ${enableBibNumber ? `
                                        <div class="col-md-4">
                                            <label class="form-label">BIB Name (Max 10 chars) <span class="required-star">*</span></label>
                                            <input type="text" name="attendees[${formIndex}][bib_name]" maxlength="10" class="form-control" placeholder="Runner Name" required>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('blood_type') ? `
                                        <div class="col-md-4">
                                            <label class="form-label">Blood Type <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][blood_type]" class="form-select" required>
                                                <option value="">Select Blood Type</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('tshirt_size') ? `
                                        <div class="col-md-4">
                                            <label class="form-label">T-Shirt Size <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][tshirt_size]" class="form-select" required>
                                                <option value="">Select Size</option>
                                                ${eventTshirtSizes.map(size => `<option value="${size}">${size}</option>`).join('')}
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('experience') ? `
                                        <div class="col-md-6">
                                            <label class="form-label">Running / Event Experience <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][experience]" class="form-select" required>
                                                <option value="">Select</option>
                                                <option value="none">None</option>
                                                <option value="10KM">10KM</option>
                                                <option value="21KM">21KM</option>
                                                <option value="42KM">42KM</option>
                                                <option value="ULTRA">ULTRA</option>
                                                <option value="TRIATHLON">TRIATHLON</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('has_medical_condition') ? `
                                        <div class="col-md-3">
                                            <label class="form-label">Existing Medical Conditions? <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][has_medical_condition]" class="form-select medical-select" data-index="${formIndex}" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                        <div class="col-md-3">
                                            <label class="form-label">ITRA? <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][itra]" class="form-select itra-select" data-index="${formIndex}" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('has_medical_condition') ? `
                                        <div class="col-md-6" id="medical-details-box-${formIndex}" style="display: none;">
                                            <label class="form-label">Medical Condition Details <span class="required-star">*</span></label>
                                            <textarea name="attendees[${formIndex}][medical_details]" class="form-control" placeholder="Specify medical conditions or allergies..."></textarea>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                        <div class="col-md-6" id="itra-details-box-${formIndex}" style="display: none;">
                                            <label class="form-label">ITRA Details <span class="required-star">*</span></label>
                                            <input type="text" name="attendees[${formIndex}][itra_details]" class="form-control" placeholder="Enter ITRA details">
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                card.innerHTML = fieldsHTML;
                container.appendChild(card);

                const nationalitySelectElem = card.querySelector('.nationality-select');
                const naingSelectElem = card.querySelector(`select[name="attendees[${formIndex}][nrc_naing]"]`);

                if (nationalitySelectElem) nationalitySelectElem.value = "";
                if (naingSelectElem) naingSelectElem.value = "";

                formIndex++;
            }
        });

        if (checkoutSection) {
            if (formIndex > 0) {
                checkoutSection.style.display = 'block';
                calculateTotals();
            } else {
                checkoutSection.style.display = 'none';
            }
        }
    }

    updateButtonStates();
});
</script>
@endpush

@endsection