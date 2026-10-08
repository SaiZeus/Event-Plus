@extends('layouts.master')

@section('title', 'Payment Confirmed - Order ' . $order->order_number)

@push('styles')
    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')

<style>
    /* =========================================================
       EVENT PLUS
       PAYMENT SUCCESS / E-RECEIPT
       Premium Event Website Style
    ========================================================= */

    .eventplus-success-section {
        position: relative;
        min-height: 100vh;
        padding: 65px 15px 90px;
        overflow: hidden;

        background:
            radial-gradient(
                circle at 8% 8%,
                rgba(236, 72, 153, .20),
                transparent 27%
            ),
            radial-gradient(
                circle at 92% 12%,
                rgba(124, 58, 237, .22),
                transparent 30%
            ),
            radial-gradient(
                circle at 50% 100%,
                rgba(99, 102, 241, .16),
                transparent 38%
            ),
            linear-gradient(
                145deg,
                #090b23 0%,
                #17133b 48%,
                #27103f 100%
            );
    }

    /* =========================================================
       BACKGROUND DECORATION
    ========================================================= */

    .eventplus-glow {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(70px);
    }

    .eventplus-glow-one {
        width: 420px;
        height: 420px;
        top: -180px;
        left: -170px;
        background: rgba(236, 72, 153, .15);
    }

    .eventplus-glow-two {
        width: 360px;
        height: 360px;
        right: -150px;
        bottom: -130px;
        background: rgba(124, 58, 237, .16);
    }

    /* =========================================================
       CONFETTI
    ========================================================= */

    .eventplus-confetti {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .eventplus-confetti span {
        position: absolute;
        top: -30px;
        width: 7px;
        height: 17px;
        border-radius: 3px;
        opacity: .65;
        animation: eventPlusConfettiFall 8s linear infinite;
    }

    .eventplus-confetti span:nth-child(1) {
        left: 8%;
        background: #ec4899;
        transform: rotate(20deg);
        animation-delay: .2s;
    }

    .eventplus-confetti span:nth-child(2) {
        left: 19%;
        background: #8b5cf6;
        transform: rotate(-25deg);
        animation-delay: 2s;
    }

    .eventplus-confetti span:nth-child(3) {
        left: 32%;
        background: #f9a8d4;
        transform: rotate(15deg);
        animation-delay: 4s;
    }

    .eventplus-confetti span:nth-child(4) {
        left: 48%;
        background: #6366f1;
        transform: rotate(-15deg);
        animation-delay: 1s;
    }

    .eventplus-confetti span:nth-child(5) {
        left: 64%;
        background: #c084fc;
        transform: rotate(30deg);
        animation-delay: 3s;
    }

    .eventplus-confetti span:nth-child(6) {
        left: 77%;
        background: #f472b6;
        transform: rotate(-20deg);
        animation-delay: 5s;
    }

    .eventplus-confetti span:nth-child(7) {
        left: 90%;
        background: #a78bfa;
        transform: rotate(25deg);
        animation-delay: 2.5s;
    }

    @keyframes eventPlusConfettiFall {
        0% {
            transform: translateY(-50px) rotate(0deg);
        }

        100% {
            transform: translateY(110vh) rotate(720deg);
        }
    }

    /* =========================================================
       MAIN WRAPPER
    ========================================================= */

    .eventplus-success-wrapper {
        position: relative;
        z-index: 5;
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    /* =========================================================
       TOP BRANDING
    ========================================================= */

    .eventplus-brand {
        text-align: center;
        margin-bottom: 30px;
        color: #fff;
    }

    .eventplus-brand-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        margin-bottom: 15px;

        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50px;

        background: rgba(255, 255, 255, .06);
        backdrop-filter: blur(12px);

        color: #f9a8d4;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .eventplus-brand-badge i {
        color: #f472b6;
    }

    .eventplus-brand h1 {
        margin: 0;
        font-size: clamp(30px, 5vw, 48px);
        line-height: 1.08;
        font-weight: 900;
        letter-spacing: -1.5px;
    }

    .eventplus-brand h1 span {
        color: #f472b6;
    }

    .eventplus-brand p {
        margin: 12px auto 0;
        max-width: 570px;
        color: rgba(255, 255, 255, .62);
        font-size: 14px;
        line-height: 1.7;
    }

    /* =========================================================
       RECEIPT CARD
    ========================================================= */

    .eventplus-receipt {
        position: relative;
        overflow: hidden;

        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 30px;

        background: rgba(255, 255, 255, .98);

        box-shadow:
            0 35px 100px rgba(0, 0, 0, .38),
            0 15px 40px rgba(236, 72, 153, .08);

        animation: eventPlusCardIn .65s ease both;
    }

    @keyframes eventPlusCardIn {
        from {
            opacity: 0;
            transform: translateY(25px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================================================
       RECEIPT HEADER
    ========================================================= */

    .eventplus-receipt-header {
        position: relative;
        min-height: 105px;
        padding: 22px 32px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        background:
            linear-gradient(
                135deg,
                #ffffff 0%,
                #faf5ff 100%
            );

        border-bottom: 1px solid #eee7f8;
    }

    .eventplus-receipt-header::before {
        content: "";

        position: absolute;
        top: 0;
        left: 0;
        right: 0;

        height: 4px;

        background:
            linear-gradient(
                90deg,
                #ec4899,
                #d946ef,
                #8b5cf6,
                #6366f1
            );
    }

    .eventplus-logo {
        width: auto;
        height: 60px;
        max-width: 240px;
        object-fit: contain;
        object-position: left center;
    }

    .eventplus-receipt-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 9px 15px;

        border-radius: 50px;

        background: #fdf2f8;
        border: 1px solid #fbcfe8;

        color: #be185d;

        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .eventplus-receipt-label i {
        color: #ec4899;
    }

    /* =========================================================
       SUCCESS CONTENT
    ========================================================= */

    .eventplus-main {
        padding: 48px 58px 42px;
        text-align: center;
    }

    /* =========================================================
       SUCCESS ICON
    ========================================================= */

    .eventplus-success-icon {
        position: relative;

        width: 82px;
        height: 82px;

        margin: 0 auto 23px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #d946ef 45%,
                #7c3aed
            );

        color: #fff;

        font-size: 32px;

        box-shadow:
            0 16px 35px rgba(236, 72, 153, .28),
            0 0 0 8px rgba(236, 72, 153, .09),
            0 0 0 15px rgba(124, 58, 237, .05);

        animation: eventPlusSuccessPulse 2.5s ease-in-out infinite;
    }

    @keyframes eventPlusSuccessPulse {
        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .eventplus-success-icon::before {
        content: "✦";

        position: absolute;
        top: -17px;
        right: -24px;

        color: #f9a8d4;
        font-size: 20px;

        animation: eventPlusSparkle 1.8s ease-in-out infinite;
    }

    .eventplus-success-icon::after {
        content: "✦";

        position: absolute;
        bottom: -15px;
        left: -24px;

        color: #c4b5fd;
        font-size: 17px;

        animation: eventPlusSparkle 1.8s ease-in-out infinite .5s;
    }

    @keyframes eventPlusSparkle {
        0%,
        100% {
            opacity: .3;
            transform: scale(.7) rotate(0deg);
        }

        50% {
            opacity: 1;
            transform: scale(1.3) rotate(45deg);
        }
    }

    .eventplus-congratulations {
        margin: 0 0 9px;

        color: #17113d;

        font-size: clamp(30px, 5vw, 44px);
        line-height: 1.1;

        font-weight: 900;
        letter-spacing: -1.5px;
    }

    .eventplus-congratulations span {
        color: #db2777;
    }

    .eventplus-success-message {
        max-width: 520px;

        margin: 0 auto 32px;

        color: #6b7280;

        font-size: 14px;
        font-weight: 500;
        line-height: 1.7;
    }

    /* =========================================================
       PAYMENT STATUS BADGE
    ========================================================= */

    .eventplus-paid-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 28px;

        padding: 8px 15px;

        border-radius: 50px;

        background: #ecfdf5;
        border: 1px solid #bbf7d0;

        color: #15803d;

        font-size: 11px;
        font-weight: 800;
        letter-spacing: .5px;
    }

    .eventplus-paid-badge i {
        font-size: 12px;
    }

    /* =========================================================
       AMOUNT
    ========================================================= */

    .eventplus-amount {
        position: relative;

        margin: 0 auto 30px;
        padding: 25px 20px 23px;

        border: 1px solid #f3d5e8;
        border-radius: 19px;

        background:
            linear-gradient(
                135deg,
                #fff7fb 0%,
                #f8f3ff 100%
            );

        overflow: hidden;
    }

    .eventplus-amount::before {
        content: "";

        position: absolute;
        width: 150px;
        height: 150px;

        top: -100px;
        right: -50px;

        border-radius: 50%;

        background: rgba(236, 72, 153, .10);
    }

    .eventplus-amount-label {
        position: relative;

        margin-bottom: 5px;

        color: #9d174d;

        font-size: 11px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 1.4px;
    }

    .eventplus-amount-value {
        position: relative;

        color: #4c1d95;

        font-size: clamp(32px, 5vw, 44px);
        line-height: 1.15;

        font-weight: 900;
        letter-spacing: -1.5px;
    }

    .eventplus-amount-currency {
        color: #7c3aed;
        font-size: 15px;
        font-weight: 800;
        margin-left: 4px;
    }

    /* =========================================================
       DETAILS
    ========================================================= */

    .eventplus-details {
        overflow: hidden;

        margin: 0 auto;

        border: 1px solid #eee7f8;
        border-radius: 18px;

        background: #fff;

        text-align: left;
    }

    .eventplus-detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 25px;

        min-height: 64px;
        padding: 12px 20px;

        border-bottom: 1px solid #f4effa;
    }

    .eventplus-detail-row:last-child {
        border-bottom: none;
    }

    .eventplus-detail-label {
        display: flex;
        align-items: center;
        gap: 9px;

        color: #7c3aed;

        font-size: 13px;
        font-weight: 700;
    }

    .eventplus-detail-label i {
        width: 27px;
        height: 27px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #faf5ff;

        color: #a855f7;

        font-size: 11px;
    }

    .eventplus-detail-value {
        color: #24143f;

        font-size: 13px;
        font-weight: 800;

        text-align: right;
    }

    .eventplus-order-number {
        padding: 6px 10px;

        border-radius: 7px;

        background: #f5f3ff;

        color: #6d28d9;
    }

    .eventplus-attendee-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        color: #be185d;
    }

    .eventplus-attendee-count i {
        color: #ec4899;
    }

    /* =========================================================
       CUT LINE
    ========================================================= */

    .eventplus-cut-line {
        position: relative;

        height: 28px;

        margin: 0 30px;

        border-top: 2px dashed #e9d5ff;
    }

    .eventplus-cut-line::before,
    .eventplus-cut-line::after {
        content: "";

        position: absolute;

        top: -14px;

        width: 27px;
        height: 27px;

        background: #17133b;

        border-radius: 50%;
    }

    .eventplus-cut-line::before {
        left: -44px;
    }

    .eventplus-cut-line::after {
        right: -44px;
    }

    /* =========================================================
       FOOTER
    ========================================================= */

    .eventplus-footer {
        padding: 27px 35px 35px;

        text-align: center;

        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fbf8ff 100%
            );
    }

    .eventplus-thank-you {
        margin: 0 0 7px;

        color: #24143f;

        font-size: 20px;
        font-weight: 900;
    }

    .eventplus-thank-you span {
        color: #db2777;
    }

    .eventplus-footer-subtitle {
        margin: 0 0 25px;

        color: #8b5cf6;

        font-size: 12px;
        font-weight: 600;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .eventplus-actions {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 12px;
        flex-wrap: wrap;
    }

    .eventplus-download-btn,
    .eventplus-home-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 9px;

        min-height: 50px;

        padding: 0 24px;

        border-radius: 13px;

        font-size: 13px;
        font-weight: 800;

        text-decoration: none;

        transition:
            transform .25s ease,
            box-shadow .25s ease,
            background .25s ease;
    }

    .eventplus-download-btn {
        border: 2px solid #ec4899;

        background: #fff;

        color: #db2777 !important;

        box-shadow: 0 6px 18px rgba(236, 72, 153, .10);
    }

    .eventplus-download-btn:hover {
        color: #be185d !important;

        background: #fff7fb;

        transform: translateY(-2px);

        box-shadow: 0 11px 25px rgba(236, 72, 153, .18);
    }

    .eventplus-home-btn {
        border: none;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #d946ef,
                #7c3aed
            );

        color: #fff !important;

        box-shadow:
            0 10px 24px rgba(236, 72, 153, .25);
    }

    .eventplus-home-btn:hover {
        color: #fff !important;

        transform: translateY(-2px);

        box-shadow:
            0 15px 32px rgba(236, 72, 153, .34);
    }

    /* =========================================================
       FOOTER NOTE
    ========================================================= */

    .eventplus-footer-note {
        max-width: 520px;

        margin: 18px auto 0;

        color: #9ca3af;

        font-size: 10px;
        line-height: 1.6;
    }

    /* =========================================================
       DECORATIVE BOTTOM WAVE
    ========================================================= */

    .eventplus-wave {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        height: 90px;

        overflow: hidden;

        pointer-events: none;

        opacity: .45;
    }

    .eventplus-wave::before {
        content: "";

        position: absolute;

        width: 110%;
        height: 80px;

        left: -5%;
        bottom: -55px;

        border-radius: 50%;

        background: rgba(236, 72, 153, .12);

        transform: rotate(-2deg);
    }

    .eventplus-wave::after {
        content: "";

        position: absolute;

        width: 110%;
        height: 70px;

        left: -5%;
        bottom: -50px;

        border-radius: 50%;

        background: rgba(124, 58, 237, .13);

        transform: rotate(2deg);
    }

    /* =========================================================
       HIDDEN TICKET RENDERING
       KEEPING ORIGINAL FUNCTIONALITY
    ========================================================= */

    .hidden-ticket-container {
        position: absolute;

        left: -9999px;
        top: -9999px;

        visibility: visible;
        opacity: 0;

        pointer-events: none;
    }

    .ticket-wrapper {
        position: relative;

        width: 1600px;
        height: 517px;
    }

    .ticket-bg {
        position: absolute;

        top: 0;
        left: 0;

        width: 1600px;
        height: 517px;
    }

    .qr-box {
        position: absolute;

        top: 126px;
        left: 950px;

        width: 310px;
        height: 310px;
    }

    .qr-box img {
        display: block;

        width: 100%;
        height: 100%;
    }

    /* Ticket Number */

    .ticket-number-area {
        position: absolute;

        top: 300px;
        left: 1390px;

        width: 60px;
        height: 360px;
    }

    .ticket-number {
        position: absolute;

        top: 0;
        left: 0;

        font-size: 24px;
        font-weight: 800;

        color: #000;

        white-space: nowrap;

        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);

        -webkit-transform-origin: top left;
        transform-origin: top left;
    }

    /* Buyer Data */

    .buyer-data-area {
        position: absolute;

        top: 390px;
        left: 1480px;

        width: 60px;
        height: 500px;
    }

    .buyer-info-group {
        position: absolute;

        top: 0;
        left: 0;

        white-space: nowrap;

        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);

        -webkit-transform-origin: top left;
        transform-origin: top left;
    }

    .buyer-name {
        display: block;

        margin-bottom: 10px;

        color: #fff;

        font-size: 30px;
        font-weight: 700;

        text-transform: uppercase;
    }

    .buyer-phone {
        display: block;

        color: #fff;

        font-size: 28px;
        font-weight: 600;
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {

        .eventplus-success-section {
            padding: 35px 12px 65px;
        }

        .eventplus-brand {
            margin-bottom: 24px;
        }

        .eventplus-brand h1 {
            font-size: 31px;
        }

        .eventplus-brand p {
            font-size: 12px;
        }

        .eventplus-receipt {
            border-radius: 22px;
        }

        .eventplus-receipt-header {
            min-height: 85px;

            padding: 17px 18px;
        }

        .eventplus-logo {
            height: 48px;
            max-width: 175px;
        }

        .eventplus-receipt-label {
            padding: 7px 10px;
            font-size: 10px;
        }

        .eventplus-main {
            padding: 35px 18px 30px;
        }

        .eventplus-success-icon {
            width: 68px;
            height: 68px;

            margin-bottom: 20px;

            font-size: 27px;
        }

        .eventplus-congratulations {
            font-size: 31px;
        }

        .eventplus-success-message {
            font-size: 13px;
            margin-bottom: 25px;
        }

        .eventplus-paid-badge {
            margin-bottom: 22px;
        }

        .eventplus-amount {
            padding: 20px 15px;
            margin-bottom: 22px;
        }

        .eventplus-amount-value {
            font-size: 31px;
        }

        .eventplus-detail-row {
            min-height: 58px;

            padding: 10px 14px;

            gap: 12px;
        }

        .eventplus-detail-label {
            font-size: 11px;
        }

        .eventplus-detail-label i {
            width: 24px;
            height: 24px;
        }

        .eventplus-detail-value {
            font-size: 11px;
        }

        .eventplus-cut-line {
            margin: 0 20px;
        }

        .eventplus-footer {
            padding: 23px 18px 28px;
        }

        .eventplus-thank-you {
            font-size: 18px;
        }

        .eventplus-actions {
            flex-direction: column;
            gap: 10px;
        }

        .eventplus-download-btn,
        .eventplus-home-btn {
            width: 100%;
        }
    }
</style>


<section
    class="eventplus-success-section"
    id="receipt-capture-area"
>

    {{-- =========================================================
         BACKGROUND DECORATION
    ========================================================== --}}

    <div class="eventplus-glow eventplus-glow-one"></div>

    <div class="eventplus-glow eventplus-glow-two"></div>


    {{-- =========================================================
         CONFETTI
    ========================================================== --}}

    <div class="eventplus-confetti">

        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>

    </div>


    <div class="eventplus-success-wrapper">


        {{-- =====================================================
             EVENT PLUS HEADER
        ====================================================== --}}

        <div class="eventplus-brand">

            <div class="eventplus-brand-badge">

                <i class="fa-solid fa-circle-check"></i>

                Payment Successfully Confirmed

            </div>

            <h1>
                You're <span>All Set!</span>
            </h1>

            <p>
                Your event registration has been successfully completed.
                Your ticket is ready and can be downloaded below.
            </p>

        </div>


        {{-- =====================================================
             RECEIPT CARD
        ====================================================== --}}

        <div class="eventplus-receipt">


            {{-- =================================================
                 RECEIPT HEADER
            ================================================== --}}

            <div class="eventplus-receipt-header">

                <img
                    src="{{ asset('assets/img/logo/logo.jpg') }}"
                    alt="Event Plus"
                    class="eventplus-logo"
                >

                <div class="eventplus-receipt-label">

                    <i class="fa-solid fa-ticket"></i>

                    <span>Event Receipt</span>

                </div>

            </div>


            {{-- =================================================
                 MAIN CONTENT
            ================================================== --}}

            <div class="eventplus-main">


                {{-- SUCCESS ICON --}}

                <div class="eventplus-success-icon">

                    <i class="fa-solid fa-check"></i>

                </div>


                {{-- TITLE --}}

                <h2 class="eventplus-congratulations">

                    Payment <span>Confirmed!</span>

                </h2>


                <p class="eventplus-success-message">

                    Your payment has been successfully completed.
                    Your registration is now confirmed.

                </p>


                {{-- PAID BADGE --}}

                <div class="eventplus-paid-badge">

                    <i class="fa-solid fa-circle-check"></i>

                    PAYMENT CONFIRMED

                </div>


                {{-- =================================================
                     AMOUNT
                ================================================== --}}

                <div class="eventplus-amount">

                    <div class="eventplus-amount-label">

                        Total Amount Paid

                    </div>

                    <div class="eventplus-amount-value">

                        {{ number_format($order->total_amount, 2) }}

                        <span class="eventplus-amount-currency">
                            MMK
                        </span>

                    </div>

                </div>


                {{-- =================================================
                     DETAILS
                ================================================== --}}

                <div class="eventplus-details">


                    {{-- ORDER NUMBER --}}

                    <div class="eventplus-detail-row">

                        <span class="eventplus-detail-label">

                            <i class="fa-solid fa-receipt"></i>

                            Order Number

                        </span>

                        <span class="eventplus-detail-value eventplus-order-number">

                            #{{ $order->order_number }}

                        </span>

                    </div>


                    {{-- AMOUNT --}}

                    <div class="eventplus-detail-row">

                        <span class="eventplus-detail-label">

                            <i class="fa-solid fa-money-bill-wave"></i>

                            Amount Paid

                        </span>

                        <span class="eventplus-detail-value">

                            {{ number_format($order->total_amount, 2) }} MMK

                        </span>

                    </div>


                    {{-- ATTENDEES --}}

                    <div class="eventplus-detail-row">

                        <span class="eventplus-detail-label">

                            <i class="fa-solid fa-users"></i>

                            Number of Attendees

                        </span>

                        <span class="eventplus-detail-value eventplus-attendee-count">

                            <i class="fa-solid fa-user-group"></i>

                            {{ $order->attendees->count() }}

                            {{ $order->attendees->count() == 1 ? 'Attendee' : 'Attendees' }}

                        </span>

                    </div>


                </div>

            </div>


            {{-- =================================================
                 CUT LINE
            ================================================== --}}

            <div class="eventplus-cut-line"></div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="eventplus-footer">

                <p class="eventplus-thank-you">

                    Thank you for choosing
                    <span>Event Plus!</span>

                </p>

                <p class="eventplus-footer-subtitle">

                    Your registration has been successfully confirmed.

                </p>


                {{-- ACTIONS --}}

                <div class="eventplus-actions">

                    <button
                        type="button"
                        id="download-receipt-btn"
                        class="eventplus-download-btn"
                    >

                        <i class="fa-solid fa-download"></i>

                        <span>
                            Download Ticket
                        </span>

                    </button>


                    <a
                        href="{{ route('home') }}"
                        class="eventplus-home-btn"
                    >

                        <i class="fa-solid fa-house"></i>

                        <span>
                            Return to Home
                        </span>

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>


                <p class="eventplus-footer-note">

                    Your ticket contains a unique QR code for event verification.
                    Please keep it safe and present it when required.

                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DECORATIVE WAVE
    ========================================================== --}}

    <div class="eventplus-wave"></div>

</section>


{{-- =========================================================
     HIDDEN TICKET RENDERING CONTAINER

     Backend / ticket generation functionality preserved.
========================================================= --}}

<div
    class="hidden-ticket-container"
    id="ticket-nodes-wrapper"
>

    @foreach($order->attendees as $index => $attendee)

        @php

            if (empty($attendee->verification_token)) {

                $attendee->verification_token =
                    \Illuminate\Support\Str::random(64);

                $attendee->save();

            }

            // Existing ticket number
            $sequentialTicketNumber =
                $attendee->ticket_code;


            // Ticket background
            $ticketBgPath =
                public_path('assets/img/ticket/ticket1.jpg');

            $ticketBgBase64 =
                (
                    file_exists($ticketBgPath)
                    &&
                    filesize($ticketBgPath) <= 2 * 1024 * 1024
                )
                ?
                'data:image/jpeg;base64,' .
                base64_encode(
                    file_get_contents($ticketBgPath)
                )
                :
                null;


            // Verification URL
            $verificationUrl =
                route(
                    'ticket.verify',
                    [
                        'token' =>
                            $attendee->verification_token
                    ]
                );


            // QR code API
            $qrApiUrl =
                'https://api.qrserver.com/v1/create-qr-code/?size=310x310&data=' .
                urlencode($verificationUrl);


            $context =
                stream_context_create([
                    'http' => [
                        'timeout' => 5
                    ]
                ]);


            $qrImageData =
                @file_get_contents(
                    $qrApiUrl,
                    false,
                    $context
                );


            $qrBase64 =
                $qrImageData
                ?
                'data:image/png;base64,' .
                base64_encode($qrImageData)
                :
                null;

        @endphp


        <div
            class="ticket-wrapper"
            id="render-ticket-node-{{ $index }}"
        >

            @if($ticketBgBase64)

                <img
                    src="{{ $ticketBgBase64 }}"
                    class="ticket-bg"
                    alt="Ticket Background"
                >

            @endif


            {{-- QR CODE --}}

            <div class="qr-box">

                <img
                    src="{{ $qrBase64 }}"
                    class="ticket-qr"
                    alt="QR Code"
                >

            </div>


            {{-- Ticket Number --}}

            <div class="ticket-number-area">

                <div class="ticket-number">

                    {{ $sequentialTicketNumber }}

                </div>

            </div>


            {{-- Name & Phone --}}

            <div class="buyer-data-area">

                <div class="buyer-info-group">

                    <span class="buyer-name">

                        {{ $attendee->full_name ?? '' }}

                    </span>

                    <span class="buyer-phone">

                        {{ $attendee->phone ?? '' }}

                    </span>

                </div>

            </div>

        </div>

    @endforeach

</div>


{{-- =========================================================
     EXTERNAL LIBRARIES
========================================================= --}}

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.2/jszip.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       DOWNLOAD BUTTON
    ========================================================= */

    const downloadBtn =
        document.getElementById(
            'download-receipt-btn'
        );


    const attendeeCount =
        {{ $order->attendees->count() }};


    if (downloadBtn) {


        /* =====================================================
           BUTTON TEXT
        ====================================================== */

        const spanEl =
            downloadBtn.querySelector('span');


        if (spanEl) {

            spanEl.textContent =
                attendeeCount > 1
                    ? 'Download All Tickets (ZIP)'
                    : 'Download Ticket';

        }


        /* =====================================================
           DOWNLOAD ACTION
        ====================================================== */

        downloadBtn.addEventListener(
            'click',
            async function () {


                const originalHtml =
                    downloadBtn.innerHTML;


                downloadBtn.style.pointerEvents =
                    'none';


                try {


                    const attendees =
                        @json($order->attendees);


                    const { jsPDF } =
                        window.jspdf;


                    /* =========================================
                       SINGLE TICKET
                    ========================================= */

                    if (attendeeCount === 1) {


                        downloadBtn.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i>' +
                            '<span>Generating Ticket PDF...</span>';


                        const ticketNode =
                            document.getElementById(
                                'render-ticket-node-0'
                            );


                        console.log(
                            '--- Event Plus Ticket ---',
                            ticketNode
                        );


                        if (!ticketNode) {

                            throw new Error(
                                'Ticket node not found.'
                            );

                        }


                        const canvas =
                            await html2canvas(
                                ticketNode,
                                {
                                    scale: 2,
                                    useCORS: true,
                                    allowTaint: false,
                                    backgroundColor: '#ffffff'
                                }
                            );


                        const imgData =
                            canvas.toDataURL(
                                'image/jpeg',
                                0.95
                            );


                        const pdf =
                            new jsPDF({
                                orientation: 'landscape',
                                unit: 'px',
                                format: [
                                    canvas.width,
                                    canvas.height
                                ]
                            });


                        pdf.addImage(
                            imgData,
                            'JPEG',
                            0,
                            0,
                            canvas.width,
                            canvas.height
                        );


                        const ticketNumText =
                            ticketNode
                                .querySelector(
                                    '.ticket-number'
                                )
                                .textContent
                                .trim();


                        const sanitizedName =
                            attendees[0].full_name
                            ?
                            attendees[0]
                                .full_name
                                .replace(
                                    /[^a-zA-Z0-9]/g,
                                    '_'
                                )
                            :
                            'Attendee';


                        pdf.save(
                            `Ticket_${ticketNumText}_${sanitizedName}.pdf`
                        );


                    }


                    /* =========================================
                       MULTIPLE TICKETS
                    ========================================= */

                    else {


                        const zip =
                            new JSZip();


                        for (
                            let i = 0;
                            i < attendees.length;
                            i++
                        ) {


                            downloadBtn.innerHTML =
                                `<i class="fa-solid fa-spinner fa-spin"></i>
                                 <span>Packing PDF (${i + 1}/${attendeeCount})...</span>`;


                            const ticketNode =
                                document.getElementById(
                                    'render-ticket-node-' + i
                                );


                            if (!ticketNode) {

                                console.warn(
                                    'Ticket node missing:',
                                    i
                                );

                                continue;

                            }


                            const canvas =
                                await html2canvas(
                                    ticketNode,
                                    {
                                        scale: 2,
                                        useCORS: true,
                                        allowTaint: false,
                                        backgroundColor: '#ffffff'
                                    }
                                );


                            const imgData =
                                canvas.toDataURL(
                                    'image/jpeg',
                                    0.95
                                );


                            const pdf =
                                new jsPDF({
                                    orientation: 'landscape',
                                    unit: 'px',
                                    format: [
                                        canvas.width,
                                        canvas.height
                                    ]
                                });


                            pdf.addImage(
                                imgData,
                                'JPEG',
                                0,
                                0,
                                canvas.width,
                                canvas.height
                            );


                            const pdfBlob =
                                pdf.output('blob');


                            const ticketNumText =
                                ticketNode
                                    .querySelector(
                                        '.ticket-number'
                                    )
                                    .textContent
                                    .trim();


                            const sanitizedName =
                                attendees[i].full_name
                                ?
                                attendees[i]
                                    .full_name
                                    .replace(
                                        /[^a-zA-Z0-9]/g,
                                        '_'
                                    )
                                :
                                `Attendee_${i + 1}`;


                            zip.file(
                                `Ticket_${ticketNumText}_${sanitizedName}.pdf`,
                                pdfBlob
                            );

                        }


                        /* =====================================
                           CREATE ZIP
                        ====================================== */

                        downloadBtn.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i>' +
                            '<span>Compiling ZIP...</span>';


                        const content =
                            await zip.generateAsync({
                                type: 'blob'
                            });


                        const link =
                            document.createElement('a');


                        link.href =
                            URL.createObjectURL(content);


                        link.download =
                            'EventPlus_Tickets_{{ $order->order_number }}.zip';


                        link.click();


                        URL.revokeObjectURL(
                            link.href
                        );

                    }


                }

                catch (err) {

                    console.error(
                        'Download processing error:',
                        err
                    );


                    alert(
                        'Failed to package ticket PDFs. Please try again.'
                    );

                }

                finally {

                    downloadBtn.innerHTML =
                        originalHtml;

                    downloadBtn.style.pointerEvents =
                        'auto';

                }

            }
        );

    }

});

</script>

@endpush

@endsection