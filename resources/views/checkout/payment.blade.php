@extends('layouts.master')

@section('title', 'MMQR Payment - Order #' . $order->order_number)

@push('styles')
    {{-- Font Awesome CDN to guarantee icon rendering --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')

<style>
    /* =========================================================
       PREMIUM EVENT PAYMENT PAGE
    ========================================================= */

    .payment-page {
        position: relative;
        min-height: 100vh;
        padding: 70px 0 90px;
        overflow: hidden;
        background:
            radial-gradient(circle at 10% 10%, rgba(236, 72, 153, .14), transparent 30%),
            radial-gradient(circle at 90% 20%, rgba(99, 102, 241, .16), transparent 30%),
            linear-gradient(145deg, #080b24 0%, #17133d 52%, #251044 100%);
    }

    .payment-page::before,
    .payment-page::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(2px);
    }

    .payment-page::before {
        width: 380px;
        height: 380px;
        top: -180px;
        right: -100px;
        background: rgba(236, 72, 153, .12);
    }

    .payment-page::after {
        width: 300px;
        height: 300px;
        bottom: -160px;
        left: -100px;
        background: rgba(79, 70, 229, .14);
    }

    .payment-wrapper {
        position: relative;
        z-index: 2;
        max-width: 1050px;
        margin: 0 auto;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .payment-heading {
        text-align: center;
        color: #fff;
        margin-bottom: 38px;
    }

    .payment-heading .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        margin-bottom: 18px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 50px;
        background: rgba(255, 255, 255, .07);
        backdrop-filter: blur(10px);
        color: #f9a8d4;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.6px;
        text-transform: uppercase;
    }

    .payment-heading h1 {
        margin: 0;
        font-size: clamp(30px, 5vw, 52px);
        line-height: 1.08;
        font-weight: 900;
        letter-spacing: -1.5px;
    }

    .payment-heading h1 span {
        color: #f472b6;
    }

    .payment-heading p {
        max-width: 620px;
        margin: 15px auto 0;
        color: rgba(255, 255, 255, .68);
        font-size: 15px;
        line-height: 1.7;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .payment-card {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 390px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 30px;
        background: rgba(255, 255, 255, .96);
        box-shadow:
            0 35px 90px rgba(0, 0, 0, .35),
            0 10px 35px rgba(236, 72, 153, .08);
    }

    /* =========================================================
       ORDER INFORMATION
    ========================================================= */

    .order-panel {
        position: relative;
        padding: 45px 42px;
        background:
            linear-gradient(150deg, #ffffff 0%, #faf7ff 55%, #f8edff 100%);
    }

    .order-panel::after {
        content: "";
        position: absolute;
        right: -1px;
        top: 45px;
        bottom: 45px;
        width: 1px;
        background: linear-gradient(
            to bottom,
            transparent,
            #e9d5ff,
            transparent
        );
    }

    .order-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #7c3aed;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .order-label i {
        color: #ec4899;
    }

    .order-panel h2 {
        margin: 14px 0 7px;
        color: #111827;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -.8px;
    }

    .order-subtitle {
        margin-bottom: 32px;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================================================
       ORDER INFO
    ========================================================= */

    .order-info {
        display: grid;
        gap: 14px;
        margin-bottom: 32px;
    }

    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 17px 18px;
        border: 1px solid #eee7f8;
        border-radius: 16px;
        background: #fff;
    }

    .info-left {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .info-icon {
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fdf2f8;
        color: #db2777;
        font-size: 15px;
    }

    .info-text small {
        display: block;
        margin-bottom: 3px;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .info-text strong {
        display: block;
        color: #1f2937;
        font-size: 14px;
        word-break: break-word;
    }

    /* =========================================================
       AMOUNT
    ========================================================= */

    .amount-row {
        padding: 23px 20px;
        border: 0;
        background: linear-gradient(135deg, #faf5ff, #fce7f3);
    }

    .amount-row .info-icon {
        background: #fff;
        color: #7c3aed;
    }

    .amount-value {
        text-align: right;
    }

    .amount-value small {
        display: block;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
    }

    .amount-value strong {
        display: block;
        margin-top: 2px;
        color: #4c1d95;
        font-size: 25px;
        font-weight: 900;
        line-height: 1.1;
    }

    .amount-value span {
        color: #7c3aed;
        font-size: 12px;
        font-weight: 800;
    }

    /* =========================================================
       SECURITY NOTE
    ========================================================= */

    .secure-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border-radius: 15px;
        background: #f9fafb;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.6;
    }

    .secure-note i {
        margin-top: 2px;
        color: #16a34a;
        font-size: 15px;
    }

    /* =========================================================
       QR PANEL
    ========================================================= */

    .qr-panel {
        position: relative;
        padding: 42px 34px 35px;
        text-align: center;
        color: #fff;
        background:
            radial-gradient(circle at 50% 0%, rgba(236, 72, 153, .2), transparent 40%),
            linear-gradient(155deg, #111633 0%, #211642 55%, #35104a 100%);
    }

    .qr-panel::before {
        content: "";
        position: absolute;
        top: 0;
        left: 25%;
        right: 25%;
        height: 4px;
        border-radius: 0 0 10px 10px;
        background: linear-gradient(90deg, #ec4899, #f472b6, #8b5cf6);
    }

    .qr-header {
        position: relative;
        z-index: 2;
        margin-bottom: 23px;
    }

    .qr-header .qr-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 17px;
        background: linear-gradient(135deg, #ec4899, #8b5cf6);
        box-shadow: 0 12px 30px rgba(236, 72, 153, .25);
        font-size: 21px;
    }

    .qr-header h3 {
        margin: 0 0 6px;
        font-size: 23px;
        font-weight: 900;
    }

    .qr-header p {
        margin: 0;
        color: rgba(255, 255, 255, .62);
        font-size: 12px;
    }

    /* =========================================================
       QR CODE
    ========================================================= */

    .qr-code-box {
        position: relative;
        width: min(285px, 100%);
        margin: 0 auto;
        padding: 17px;
        border-radius: 25px;
        background: #fff;
        box-shadow:
            0 20px 45px rgba(0, 0, 0, .28),
            0 0 0 7px rgba(255, 255, 255, .05);
    }

    .qr-code-box::before {
        content: "";
        position: absolute;
        inset: 7px;
        border: 1px dashed #e5e7eb;
        border-radius: 17px;
        pointer-events: none;
    }

    .qr-code-box img {
        position: relative;
        z-index: 1;
        display: block;
        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
        object-fit: contain;
    }

    .mmqr-label {
        margin-top: 18px;
        color: #fff;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: 1.8px;
    }

    /* =========================================================
       CB BANK
    ========================================================= */

    .cb-brand {
        margin-top: 19px;
    }

    .cb-brand img {
        width: 72px;
        height: auto;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, .15));
    }

    .powered-text {
        margin-top: 6px;
        color: rgba(255, 255, 255, .42);
        font-size: 10px;
        letter-spacing: .5px;
    }

    /* =========================================================
       EXPIRATION
    ========================================================= */

    .expiration-box {
        margin-top: 27px;
        padding: 17px;
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 16px;
        background: rgba(255, 255, 255, .055);
    }

    .expiration-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        color: #fda4af;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .8px;
    }

    .expiration-label i {
        font-size: 12px;
    }

    #timer_display {
        display: block;
        margin-top: 5px;
        color: #fff;
        font-size: 34px;
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: 2px;
        font-variant-numeric: tabular-nums;
    }

    /* =========================================================
       PAYMENT STATUS
    ========================================================= */

    .payment-status {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-top: 17px;
        color: rgba(255, 255, 255, .62);
        font-size: 11px;
    }

    .payment-status .spinner-border {
        width: 15px;
        height: 15px;
        border-width: 2px;
        color: #f472b6 !important;
    }

    /* =========================================================
       DONE BUTTON
    ========================================================= */

    .done-payment-form {
        margin-top: 22px;
    }

    .done-payment-btn {
        width: 100%;
        min-height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #ec4899 0%, #db2777 48%, #c026d3 100%);
        color: #fff;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: .2px;
        box-shadow:
            0 12px 28px rgba(236, 72, 153, .28),
            inset 0 1px 0 rgba(255, 255, 255, .18);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            filter .25s ease;
    }

    .done-payment-btn:hover {
        color: #fff;
        transform: translateY(-2px);
        filter: brightness(1.06);
        box-shadow:
            0 17px 35px rgba(236, 72, 153, .38),
            inset 0 1px 0 rgba(255, 255, 255, .2);
    }

    .done-payment-btn:active {
        transform: translateY(0);
    }

    .done-payment-btn i {
        font-size: 14px;
    }

    .manual-note {
        margin: 9px 0 0;
        color: rgba(255, 255, 255, .38);
        font-size: 10px;
        line-height: 1.5;
    }

    /* =========================================================
       BOTTOM NOTICE
    ========================================================= */

    .payment-notice {
        max-width: 700px;
        margin: 28px auto 0;
        text-align: center;
        color: rgba(255, 255, 255, .48);
        font-size: 11px;
        line-height: 1.7;
    }

    .payment-notice i {
        color: #f472b6;
        margin-right: 4px;
    }

    /* =========================================================
       ANIMATION
    ========================================================= */

    .payment-card {
        animation: paymentCardIn .65s ease both;
    }

    .payment-heading {
        animation: headingIn .55s ease both;
    }

    @keyframes paymentCardIn {
        from {
            opacity: 0;
            transform: translateY(25px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes headingIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .payment-page {
            padding: 55px 0 70px;
        }

        .payment-card {
            grid-template-columns: 1fr;
            max-width: 620px;
            margin: 0 auto;
        }

        .order-panel::after {
            display: none;
        }

        .order-panel {
            padding: 35px 30px;
        }

        .qr-panel {
            padding: 38px 25px 42px;
        }
    }

    @media (max-width: 575.98px) {

        .payment-page {
            padding: 38px 12px 55px;
        }

        .payment-heading {
            margin-bottom: 28px;
        }

        .payment-heading .eyebrow {
            font-size: 10px;
            padding: 7px 13px;
        }

        .payment-heading h1 {
            font-size: 31px;
        }

        .payment-heading p {
            font-size: 13px;
        }

        .payment-card {
            border-radius: 22px;
        }

        .order-panel {
            padding: 30px 20px;
        }

        .order-panel h2 {
            font-size: 24px;
        }

        .info-row {
            padding: 14px;
        }

        .info-icon {
            flex-basis: 36px;
            width: 36px;
            height: 36px;
        }

        .info-text strong {
            font-size: 13px;
        }

        .amount-value strong {
            font-size: 21px;
        }

        .qr-panel {
            padding: 35px 18px 38px;
        }

        .qr-code-box {
            width: min(260px, 100%);
        }

        #timer_display {
            font-size: 31px;
        }

        .done-payment-btn {
            min-height: 50px;
        }
    }
</style>


<section class="payment-page">

    <div class="container">

        <div class="payment-wrapper">

            {{-- =====================================================
                 PAGE HEADER
            ====================================================== --}}

            <div class="payment-heading">

                <div class="eyebrow">
                    <i class="fas fa-shield-halved"></i>
                    Secure Event Payment
                </div>

                <h1>
                    Complete Your <span>Payment</span>
                </h1>

                <p>
                    Scan the MMQR code using your banking app to complete
                    your event registration. Your payment will be confirmed automatically.
                </p>

            </div>


            {{-- =====================================================
                 MAIN PAYMENT CARD
            ====================================================== --}}

            <div class="payment-card">


                {{-- =================================================
                     ORDER INFORMATION
                ================================================== --}}

                <div class="order-panel">

                    <div class="order-label">
                        <i class="fas fa-receipt"></i>
                        Payment Summary
                    </div>

                    <h2>
                        Your Registration
                    </h2>

                    <div class="order-subtitle">
                        Please confirm the payment details before scanning.
                    </div>


                    {{-- ORDER INFORMATION --}}

                    <div class="order-info">

                        {{-- MERCHANT --}}

                        <div class="info-row">

                            <div class="info-left">

                                <div class="info-icon">
                                    <i class="fas fa-store"></i>
                                </div>

                                <div class="info-text">
                                    <small>Merchant</small>
                                    <strong>Event PLUS Team</strong>
                                </div>

                            </div>

                        </div>


                        {{-- ORDER NUMBER --}}

                        <div class="info-row">

                            <div class="info-left">

                                <div class="info-icon">
                                    <i class="fas fa-hashtag"></i>
                                </div>

                                <div class="info-text">
                                    <small>Order Reference</small>
                                    <strong>{{ $order->order_number }}</strong>
                                </div>

                            </div>

                        </div>


                        {{-- AMOUNT --}}

                        <div class="info-row amount-row">

                            <div class="info-left">

                                <div class="info-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>

                                <div class="info-text">
                                    <small>Total Amount</small>
                                    <strong>Payment Due</strong>
                                </div>

                            </div>

                            <div class="amount-value">

                                <small>MMK</small>

                                <strong>
                                    {{ number_format($order->total_amount, 2) }}
                                </strong>

                                <span>MMK</span>

                            </div>

                        </div>

                    </div>


                    {{-- SECURITY NOTE --}}

                    <div class="secure-note">

                        <i class="fas fa-circle-check"></i>

                        <div>
                            Your payment status is checked automatically.
                            Please keep this page open after completing
                            your payment.
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     QR PAYMENT PANEL
                ================================================== --}}

                <div class="qr-panel">

                    <div class="qr-header">

                        <div class="qr-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>

                        <h3>
                            Scan to Pay
                        </h3>

                        <p>
                            Open your banking app and scan this MMQR
                        </p>

                    </div>


                    {{-- QR CODE --}}

                    <div class="qr-code-box">

                        @if(!empty($mmqrData['qr_code_info']))

                            <img
                                id="imgQR"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($mmqrData['qr_code_info']) }}"
                                alt="MMQR Code"
                            />

                        @else

                            <img
                                id="imgQR"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=MMQR_ORDER_{{ $order->order_number }}"
                                alt="MMQR Code"
                            />

                        @endif

                    </div>


                    <div class="mmqr-label">
                        MMQR
                    </div>


                    {{-- CB BANK LOGO --}}

                    <div class="cb-brand">

                        <img
                            src="{{ asset('assets/img/cb/logo.png') }}"
                            alt="CB Bank Logo"
                        />

                        <div class="powered-text">
                            Secure payment powered by CB Bank
                        </div>

                    </div>


                    {{-- EXPIRATION TIMER --}}

                    <div class="expiration-box">

                        <div class="expiration-label">

                            <i class="fas fa-clock"></i>

                            QR expires in 3 minutes

                        </div>

                        <span id="timer_display">
                            03:00
                        </span>

                    </div>


                    {{-- POLLING STATUS --}}

                    <div class="payment-status">

                        <div
                            class="spinner-border spinner-border-sm"
                            role="status"
                        ></div>

                        <span>
                            Waiting for payment confirmation...
                        </span>

                    </div>


                    {{-- =================================================
                         DONE / SIMULATE PAYMENT BUTTON
                    ================================================== --}}

                    <form
                        action="{{ route('checkout.complete', $order) }}"
                        method="POST"
                        class="done-payment-form"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="done-payment-btn"
                        >
                            <i class="fas fa-check-circle"></i>

                            Done — Payment Complete

                        </button>

                    </form>

                    <p class="manual-note">
                        Only press this button after you have completed
                        the payment through your banking app.
                    </p>

                </div>

            </div>


            {{-- =====================================================
                 BOTTOM NOTICE
            ====================================================== --}}

            <div class="payment-notice">

                <i class="fas fa-lock"></i>

                Please do not close or refresh this page while your payment
                is being processed. You will be redirected automatically
                once your payment is confirmed.

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     3-MINUTE TIMER & REAL-TIME STATUS POLLING
========================================================= --}}

@push('scripts')

<script>

document.addEventListener("DOMContentLoaded", function () {

    const orderId = "{{ $order->reference }}";

    const successUrl = "{{ route('checkout.success', $order) }}";


    /* =========================================================
       1. 3-MINUTE COUNTDOWN TIMER
    ========================================================= */

    let timeLeft = 180;

    const timerDisplay =
        document.getElementById('timer_display');


    const countdown = setInterval(function () {

        let minutes = Math.floor(timeLeft / 60);

        let seconds = timeLeft % 60;


        minutes =
            minutes < 10
                ? '0' + minutes
                : minutes;


        seconds =
            seconds < 10
                ? '0' + seconds
                : seconds;


        if (timerDisplay) {

            timerDisplay.textContent =
                `${minutes}:${seconds}`;

        }


        if (timeLeft <= 0) {

            clearInterval(countdown);


            if (timerDisplay) {

                timerDisplay.textContent = "EXPIRED";

                timerDisplay.style.color = "#f87171";

            }


            alert(
                "This MMQR payment session has expired. Please try placing your order again."
            );


            window.location.href = "/";

        }


        timeLeft--;

    }, 1000);


    /* =========================================================
       2. REAL-TIME PAYMENT STATUS POLLING
    ========================================================= */

    const statusPoll = setInterval(function () {

        fetch(`/api/check-order-status/${orderId}`)

            .then(response => response.json())

            .then(data => {

                if (data.status === 'paid') {

                    clearInterval(statusPoll);

                    clearInterval(countdown);

                    window.location.href = successUrl;

                }

            })

            .catch(err => {

                console.error(
                    "Polling error:",
                    err
                );

            });

    }, 3000);

});

</script>

@endpush

@endsection