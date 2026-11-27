@extends('layouts.master')

@section('title', 'MMQR Payment - Order #' . $order->order_number)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">
@endpush

@section('content')

@php
    $amount = number_format($order->total_amount, 2);

    $qrPayload = !empty($mmqrData['qr_code_info'])
        ? $mmqrData['qr_code_info']
        : 'MMQR_ORDER_' . $order->order_number;
@endphp

<div class="pay">

    {{-- =========================================================
    HERO
    ========================================================= --}}

    <header class="pay-hero">

        <div class="container">

            <ol class="pay-steps" aria-label="Registration progress">

                <li class="is-done">
                    <span class="pay-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Tickets
                </li>

                <li class="is-done">
                    <span class="pay-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Participant details
                </li>

                <li class="is-done">
                    <span class="pay-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Waiver
                </li>

                <li class="is-current" aria-current="step">
                    <span class="pay-steps__n" aria-hidden="true"><i class="fas fa-credit-card"></i></span>
                    Payment
                </li>

            </ol>

            <h1 class="pay-title">Scan to pay</h1>

            <p class="pay-lead">
                Scan the MMQR code with your banking app and keep this page open.
                We'll confirm your payment automatically.
            </p>

        </div>

    </header>


    {{-- =========================================================
    MAIN
    ========================================================= --}}

    <main class="pay-main">

        <div class="container">

            <div class="pay-grid">

                {{-- =================================================
                     QR TICKET
                ================================================== --}}

                <section class="pay-ticket-wrap" aria-label="MMQR payment">

                    <article class="pay-ticket" id="pay-ticket">

                        <div class="pay-ticket__top">

                            <p class="pay-label">Total to pay</p>

                            <p class="pay-amount">
                                {{ $amount }} <small>MMK</small>
                            </p>

                            <div class="pay-qr">

                                <img
                                    id="imgQR"
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrPayload) }}"
                                    alt="MMQR code for order {{ $order->order_number }}"
                                >

                                <div class="pay-qr__expired" id="pay-qr-expired" hidden>
                                    <i class="fas fa-hourglass-end" aria-hidden="true"></i>
                                    <strong>QR expired</strong>
                                </div>

                            </div>

                            <div class="pay-brand">

                                <span class="pay-brand__mmqr">MMQR</span>

                                <img
                                    src="{{ asset('assets/img/cb/logo.png') }}"
                                    alt="CB Bank"
                                >

                            </div>

                            <p class="pay-powered">Secure payment powered by CB Bank</p>

                            <p class="pay-phone-tip">
                                Paying on this phone? Take a screenshot of the code and upload it on your
                                banking app's scan screen, if it has that option.
                            </p>

                        </div>

                        <div class="pay-ticket__perf" aria-hidden="true"></div>

                        <div class="pay-ticket__stub">

                            <div class="pay-timer" id="pay-timer-block">

                                <p class="pay-label">QR expires in</p>

                                <p class="pay-timer__time" id="timer_display" role="timer">03:00</p>

                                <div class="pay-bar" aria-hidden="true">
                                    <span id="pay-bar-fill"></span>
                                </div>

                            </div>

                            <p class="pay-status" id="pay-status" role="status">
                                <span class="pay-spin" aria-hidden="true"></span>
                                <span id="pay-status-text">Waiting for payment confirmation…</span>
                            </p>

                            {{-- DONE BUTTON --}}
                            <form
                                action="{{ route('checkout.complete', $order) }}"
                                method="POST"
                                class="pay-done"
                                id="pay-done"
                            >
                                @csrf

                                <button type="submit" class="pay-btn pay-btn--primary done-payment-btn" id="pay-done-btn">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>Done, I've paid</span>
                                </button>

                                <p class="pay-done__note" id="pay-done-note">
                                    Only press this after you've completed the payment in your banking app.
                                </p>
                            </form>

                            {{-- Shown only after the QR expires --}}
                            <div class="pay-expired-actions" id="pay-expired-actions" hidden>

                                <p>This payment session has ended. Start again from the event page to get a new code.</p>

                                <a href="{{ url('/') }}#events-section" class="pay-btn pay-btn--outline">
                                    <span>Back to events</span>
                                </a>

                            </div>

                        </div>

                    </article>

                </section>


                {{-- =================================================
                     ORDER DETAILS + HOW TO PAY
                ================================================== --}}

                <aside class="pay-side">

                    <section class="pay-card" aria-labelledby="pay-order-title">

                        <h2 class="pay-h" id="pay-order-title">Order details</h2>

                        <dl class="pay-facts">

                            <div class="pay-fact">
                                <span class="pay-fact__icon" aria-hidden="true"><i class="fas fa-store"></i></span>
                                <div>
                                    <dt>Merchant</dt>
                                    <dd>Event PLUS Team</dd>
                                </div>
                            </div>

                            <div class="pay-fact">
                                <span class="pay-fact__icon" aria-hidden="true"><i class="fas fa-hashtag"></i></span>
                                <div>
                                    <dt>Order reference</dt>
                                    <dd>{{ $order->order_number }}</dd>
                                </div>
                            </div>

                            {{-- GROUP BUY: shown only for group buy orders --}}
@if($order->isGroupBuy())
    <div class="pay-fact">
        <span class="pay-fact__icon" aria-hidden="true"><i class="fas fa-users"></i></span>
        <div>
            <dt>Group buy</dt>
            <dd>
                {{ $order->group_buy_name ?: $order->group_buy_quantity . '-ticket group' }}
                ({{ $order->group_buy_quantity }} tickets)
            </dd>
        </div>
    </div>

    <div class="pay-fact">
        <span class="pay-fact__icon" aria-hidden="true"><i class="fas fa-tag"></i></span>
        <div>
            <dt>Group discount</dt>
            <dd>- {{ number_format($order->group_buy_discount, 2) }} MMK</dd>
        </div>
    </div>
@endif

                            <div class="pay-fact">
                                <span class="pay-fact__icon" aria-hidden="true"><i class="fas fa-receipt"></i></span>
                                <div>
                                    <dt>Amount due</dt>
                                    <dd>{{ $amount }} MMK</dd>
                                </div>
                            </div>

                        </dl>

                    </section>


                    <section class="pay-card" aria-labelledby="pay-how-title">

                        <h2 class="pay-h" id="pay-how-title">How to pay</h2>

                        <ol class="pay-how">

                            <li>
                                <span class="pay-how__n" aria-hidden="true">1</span>
                                <span>Open your banking app and choose scan or MMQR.</span>
                            </li>

                            <li>
                                <span class="pay-how__n" aria-hidden="true">2</span>
                                <span>Scan the code and check the amount shows <strong>{{ $amount }} MMK</strong> before you confirm.</span>
                            </li>

                            <li>
                                <span class="pay-how__n" aria-hidden="true">3</span>
                                <span>Stay on this page. You'll move on automatically once the payment is confirmed.</span>
                            </li>

                        </ol>

                    </section>


                    <p class="pay-notice">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <span>
                            Please don't close or refresh this page while your payment is being processed.
                        </span>
                    </p>

                </aside>

            </div>

        </div>

    </main>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   TOKENS (shared with the rest of the booking flow)
========================================================= */

.pay {
    --pay-ink: #17122b;
    --pay-muted: #6b6580;
    --pay-pink: #db2777;
    --pay-pink-dark: #be185d;
    --pay-violet: #7c3aed;
    --pay-live: #e11d48;
    --pay-line: #e6e1f3;
    --pay-bg: #faf8ff;
    --pay-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    color: var(--pay-ink);
}


/* =========================================================
   HERO
========================================================= */

.pay-hero {
    padding: clamp(1.5rem, 3.5vw, 2.5rem) 0 clamp(4rem, 8vw, 6rem);
    background:
        radial-gradient(55% 90% at 90% 30%, rgba(124, 58, 237, 0.4), transparent 70%),
        var(--pay-ink);
    color: #fff;
}

.pay-steps {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin: 0 0 1.75rem;
    padding: 0;
    list-style: none;
}

.pay-steps li {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.4rem 1rem 0.4rem 0.4rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.78);
    font-size: 0.9rem;
    font-weight: 600;
}

.pay-steps__n {
    display: grid;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.16);
    font-size: 0.75rem;
    font-weight: 700;
}

.pay-steps .is-done .pay-steps__n {
    background: rgba(255, 255, 255, 0.9);
    color: var(--pay-ink);
    font-size: 0.7rem;
}

.pay-steps .is-current {
    border-color: transparent;
    background: #fff;
    color: var(--pay-ink);
}

.pay-steps .is-current .pay-steps__n {
    background: var(--pay-pink);
    color: #fff;
}

.pay-title {
    margin: 0 0 1rem;
    color: #fff;
    font-family: var(--pay-display);
    font-size: clamp(2rem, 4.8vw, 3.4rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.025em;
}

.pay-lead {
    max-width: 52ch;
    margin: 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1.0625rem;
    line-height: 1.65;
}


/* =========================================================
   MAIN
========================================================= */

.pay-main {
    position: relative;
    z-index: 3;
    margin-top: -2rem;
    padding: clamp(2rem, 5vw, 3rem) 0 clamp(3.5rem, 8vw, 6rem);
    border-radius: 32px 32px 0 0;
    background: var(--pay-bg);
}

.pay-grid {
    display: grid;
    grid-template-columns: minmax(0, 440px) minmax(0, 1fr);
    gap: 1.75rem;
    align-items: start;
    max-width: 960px;
    margin-inline: auto;
}

.pay-label {
    margin: 0;
    color: var(--pay-muted);
    font-size: 0.9rem;
    font-weight: 600;
}


/* =========================================================
   QR TICKET
========================================================= */

.pay-ticket {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--pay-line);
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 18px 40px rgba(23, 18, 43, 0.08);
}

.pay-ticket__top {
    padding: 1.75rem 1.75rem 1.5rem;
    text-align: center;
}

.pay-amount {
    margin: 0.2rem 0 1.25rem;
    font-family: var(--pay-display);
    font-size: clamp(2rem, 6vw, 2.6rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.025em;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.pay-amount small {
    color: var(--pay-muted);
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0;
}

.pay-qr {
    position: relative;
    width: min(290px, 100%);
    margin: 0 auto;
    padding: 0.9rem;
    border: 1.5px solid var(--pay-line);
    border-radius: 20px;
    background: #fff;
}

.pay-qr img {
    display: block;
    width: 100%;
    height: auto;
    aspect-ratio: 1 / 1;
    object-fit: contain;
    transition: filter 0.3s ease;
}

.pay-ticket.is-expired .pay-qr img {
    filter: blur(7px) grayscale(1);
    opacity: 0.5;
}

.pay-qr__expired {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border-radius: 20px;
    color: var(--pay-ink);
    font-size: 1.15rem;
}

.pay-qr__expired[hidden] {
    display: none;
}

.pay-qr__expired i {
    color: var(--pay-live);
    font-size: 1.8rem;
}

.pay-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.85rem;
    margin-top: 1.1rem;
}

.pay-brand__mmqr {
    padding: 0.25rem 0.7rem;
    border-radius: 8px;
    background: var(--pay-ink);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 800;
    letter-spacing: 0.12em;
}

.pay-brand img {
    width: auto;
    height: 1.9rem;
}

.pay-powered {
    margin: 0.5rem 0 0;
    color: var(--pay-muted);
    font-size: 0.85rem;
}

.pay-phone-tip {
    margin: 1rem 0 0;
    padding: 0.8rem 1rem;
    border-radius: 14px;
    background: var(--pay-bg);
    color: #4b4563;
    font-size: 0.875rem;
    line-height: 1.55;
    text-align: left;
}


/* ---------- Perforation with punched notches ---------- */

.pay-ticket__perf {
    position: relative;
    height: 0;
    margin: 0 26px;
    border-top: 2px dashed rgba(23, 18, 43, 0.2);
}

.pay-ticket__perf::before,
.pay-ticket__perf::after {
    content: "";
    position: absolute;
    top: -14px;
    width: 26px;
    height: 26px;
    border: 1px solid var(--pay-line);
    border-radius: 50%;
    background: var(--pay-bg);
}

.pay-ticket__perf::before {
    left: -39px;
    clip-path: inset(0 0 0 50%);
}

.pay-ticket__perf::after {
    right: -39px;
    clip-path: inset(0 50% 0 0);
}


/* ---------- Stub: timer, status, done ---------- */

.pay-ticket__stub {
    padding: 1.5rem 1.75rem 1.75rem;
    text-align: center;
}

.pay-timer__time {
    margin: 0.1rem 0 0.7rem;
    font-family: var(--pay-display);
    font-size: 2.6rem;
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: 0.02em;
    font-variant-numeric: tabular-nums;
}

.pay-bar {
    height: 6px;
    overflow: hidden;
    border-radius: 999px;
    background: #efeaf8;
}

.pay-bar span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: var(--pay-pink);
    transform-origin: left center;
    transition: transform 1s linear, background-color 0.3s ease;
}

.pay-ticket.is-urgent .pay-timer__time {
    color: var(--pay-live);
}

.pay-ticket.is-urgent .pay-bar span {
    background: var(--pay-live);
}

.pay-status {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.65rem;
    margin: 1.25rem 0 0;
    color: #4b4563;
    font-size: 0.95rem;
    font-weight: 600;
}

.pay-spin {
    flex: none;
    width: 1.05rem;
    height: 1.05rem;
    border: 2.5px solid #f3c6dc;
    border-top-color: var(--pay-pink);
    border-radius: 50%;
    animation: pay-spin 0.9s linear infinite;
}

@keyframes pay-spin {
    to { transform: rotate(360deg); }
}

.pay-ticket.is-expired .pay-spin,
.pay-ticket.is-paid .pay-spin {
    display: none;
}

.pay-done {
    margin-top: 1.25rem;
}

.pay-done__note {
    margin: 0.7rem 0 0;
    color: var(--pay-muted);
    font-size: 0.85rem;
    line-height: 1.5;
}

.pay-ticket.is-expired .pay-timer,
.pay-ticket.is-expired .pay-done {
    display: none;
}

.pay-expired-actions {
    margin-top: 1.25rem;
}

.pay-expired-actions[hidden] {
    display: none;
}

.pay-expired-actions p {
    margin: 0 0 1rem;
    color: var(--pay-muted);
    line-height: 1.6;
}


/* ---------- Buttons ---------- */

.pay-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    width: 100%;
    min-height: 3.25rem;
    padding: 0.8rem 1.6rem;
    border: 1.5px solid transparent;
    border-radius: 999px;
    background: transparent;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none !important;
    cursor: pointer;
    transition:
        transform 0.2s ease,
        background-color 0.2s ease,
        box-shadow 0.2s ease,
        color 0.2s ease;
}

.pay-btn:focus-visible {
    outline: 3px solid var(--pay-violet);
    outline-offset: 3px;
}

.pay-btn--primary {
    background: var(--pay-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.3);
}

.pay-btn--primary:hover:not(:disabled) {
    background: var(--pay-pink-dark);
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(219, 39, 119, 0.4);
}

.pay-btn--primary:disabled {
    background: #e4dff0;
    color: #8a849c !important;
    box-shadow: none;
    cursor: wait;
}

.pay-btn--outline {
    border-color: var(--pay-ink);
    color: var(--pay-ink) !important;
}

.pay-btn--outline:hover {
    background: var(--pay-ink);
    color: #fff !important;
}


/* =========================================================
   SIDE
========================================================= */

.pay-side {
    display: grid;
    gap: 1.25rem;
}

.pay-card {
    padding: clamp(1.25rem, 3vw, 1.75rem);
    border: 1px solid var(--pay-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.pay-h {
    margin: 0 0 1.1rem;
    font-family: var(--pay-display);
    font-size: 1.3rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.015em;
}

.pay-facts {
    display: grid;
    gap: 1rem;
    margin: 0;
}

.pay-fact {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
}

.pay-fact__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 12px;
    background: #f3edfc;
    color: var(--pay-pink);
}

.pay-fact dt {
    margin: 0;
    color: var(--pay-muted);
    font-size: 0.85rem;
    font-weight: 600;
}

.pay-fact dd {
    margin: 0.1rem 0 0;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.pay-how {
    display: grid;
    gap: 1rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.pay-how li {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    color: #4b4563;
    line-height: 1.6;
}

.pay-how li strong {
    color: var(--pay-ink);
}

.pay-how__n {
    display: grid;
    flex: none;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: var(--pay-ink);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
}

.pay-notice {
    display: flex;
    align-items: flex-start;
    gap: 0.7rem;
    margin: 0;
    padding: 0.9rem 1.1rem;
    border-radius: 16px;
    background: #fff4e0;
    color: #6b3d06;
    font-size: 0.9375rem;
    line-height: 1.55;
}

.pay-notice i {
    margin-top: 0.25rem;
}


/* =========================================================
   SMALL SCREENS
========================================================= */

@media (max-width: 860px) {

    .pay-grid {
        grid-template-columns: minmax(0, 1fr);
        max-width: 480px;
    }

}

@media (max-width: 420px) {

    .pay-ticket__top,
    .pay-ticket__stub {
        padding-inline: 1.15rem;
    }

    .pay-timer__time {
        font-size: 2.25rem;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .pay *,
    .pay *::before,
    .pay *::after {
        animation: none !important;
        transition: none !important;
    }

    .pay-spin {
        border-top-color: #f3c6dc;
    }

}

</style>

@endpush


{{-- =========================================================
    3-MINUTE TIMER & REAL-TIME STATUS POLLING
========================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const orderId = @json($order->reference);
    const successUrl = @json(route('checkout.success', $order));
    const statusUrl = '/api/check-order-status/' + encodeURIComponent(orderId);

    const DURATION = 180000;           // 3 minutes
    const URGENT_AT = 30000;           // last 30 seconds
    const endAt = Date.now() + DURATION;

    const ticket = document.getElementById('pay-ticket');
    const timerDisplay = document.getElementById('timer_display');
    const barFill = document.getElementById('pay-bar-fill');
    const statusText = document.getElementById('pay-status-text');
    const qrExpired = document.getElementById('pay-qr-expired');
    const expiredActions = document.getElementById('pay-expired-actions');

    const doneForm = document.getElementById('pay-done');
    const doneBtn = document.getElementById('pay-done-btn');
    const doneLabel = doneBtn ? doneBtn.querySelector('span') : null;
    const doneDefault = doneLabel ? doneLabel.textContent : '';

    let finished = false;
    let inFlight = false;
    let countdownId = null;
    let pollId = null;


    /* ---------- Countdown (based on the clock, so it stays accurate in background tabs) ---------- */

    function pad(n) {
        return n < 10 ? '0' + n : String(n);
    }

    function render() {
        const left = Math.max(0, endAt - Date.now());
        const total = Math.ceil(left / 1000);

        if (timerDisplay) {
            timerDisplay.textContent = pad(Math.floor(total / 60)) + ':' + pad(total % 60);
        }

        if (barFill) {
            barFill.style.transform = 'scaleX(' + (left / DURATION) + ')';
        }

        if (ticket) {
            ticket.classList.toggle('is-urgent', left > 0 && left <= URGENT_AT);
        }

        if (left <= 0) expire();
    }


    /* ---------- Status polling ---------- */

    function check() {
        if (finished || inFlight) return Promise.resolve();

        inFlight = true;

        return fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (data && data.status === 'paid') paid();
            })
            .catch(function (err) {
                console.error('Polling error:', err);
            })
            .then(function () {
                inFlight = false;
            });
    }

    function stopTimers() {
        clearInterval(countdownId);
        clearInterval(pollId);
    }

    function paid() {
        if (finished) return;

        finished = true;
        stopTimers();

        if (ticket) ticket.classList.add('is-paid');
        if (statusText) statusText.textContent = 'Payment received. Redirecting…';

        window.location.href = successUrl;
    }

    function expire() {
        if (finished) return;

        stopTimers();

        // One last check, in case the payment landed just before the timer ran out
        check().then(function () {
            if (finished) return;

            finished = true;

            if (ticket) {
                ticket.classList.remove('is-urgent');
                ticket.classList.add('is-expired');
            }

            if (timerDisplay) timerDisplay.textContent = '00:00';
            if (barFill) barFill.style.transform = 'scaleX(0)';
            if (qrExpired) qrExpired.hidden = false;
            if (expiredActions) expiredActions.hidden = false;
            if (statusText) statusText.textContent = 'This QR code has expired.';
        });
    }

    render();
    countdownId = setInterval(render, 1000);
    pollId = setInterval(check, 3000);

    // Coming back from the banking app: check straight away instead of waiting for the next poll
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') check();
    });


    /* ---------- Done button ---------- */

    if (doneForm && doneBtn) {
        doneForm.addEventListener('submit', function () {
            doneBtn.disabled = true;
            if (doneLabel) doneLabel.textContent = 'Checking…';
        });

        // Back button restores the page from cache: make the button usable again
        window.addEventListener('pageshow', function (e) {
            if (!e.persisted) return;

            doneBtn.disabled = false;
            if (doneLabel) doneLabel.textContent = doneDefault;
        });
    }

});
</script>

@endpush