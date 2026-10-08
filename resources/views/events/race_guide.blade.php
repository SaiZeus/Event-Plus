@extends('layouts.master')

@section('title', 'Race Guide - ' . $event->title)

@section('content')

<!-- Font Awesome CDN for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<style>
    /* =========================================================
       PREMIUM EVENT RACE GUIDE
       Event-focused frontend only
       Backend / routes / forms / JS preserved
    ========================================================= */

    .race-guide-page {
        --rg-navy: #0e0928;
        --rg-navy-2: #171039;
        --rg-purple: #291452;
        --rg-pink: #ff2f92;
        --rg-pink-dark: #e51d7d;
        --rg-pink-soft: #fff0f8;
        --rg-lavender: #f6f2ff;
        --rg-lavender-2: #eee8ff;
        --rg-text: #19142e;
        --rg-muted: #756f84;
        --rg-border: rgba(34, 24, 76, .10);

        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background:
            radial-gradient(circle at 4% 18%, rgba(255, 47, 146, .07), transparent 24%),
            radial-gradient(circle at 96% 40%, rgba(108, 74, 255, .08), transparent 25%),
            linear-gradient(180deg, #fbfaff 0%, #f3effd 100%);
        color: var(--rg-text);
    }

    .race-guide-page::before {
        content: "";
        position: absolute;
        width: 500px;
        height: 500px;
        right: -280px;
        top: 430px;
        border-radius: 50%;
        background: rgba(255, 47, 146, .045);
        pointer-events: none;
    }

    .race-guide-page::after {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        left: -250px;
        bottom: -180px;
        border-radius: 50%;
        background: rgba(104, 75, 230, .055);
        pointer-events: none;
    }

    /* =========================================================
       HERO
    ========================================================= */

    .race-guide-hero {
        position: relative;
        overflow: hidden;
        min-height: 340px;
        border-radius: 0 0 45px 45px;
        background:
            radial-gradient(circle at 82% 18%, rgba(255, 47, 146, .30), transparent 27%),
            radial-gradient(circle at 12% 100%, rgba(110, 74, 255, .27), transparent 35%),
            linear-gradient(125deg, #0b0823 0%, #171039 50%, #2a1251 100%);
        box-shadow: 0 25px 70px rgba(15, 9, 40, .18);
    }

    .race-guide-hero::before {
        content: "";
        position: absolute;
        width: 470px;
        height: 470px;
        right: -150px;
        top: -270px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
    }

    .race-guide-hero::after {
        content: "";
        position: absolute;
        width: 800px;
        height: 170px;
        left: -120px;
        bottom: -125px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .045);
        transform: rotate(-7deg);
    }

    .race-guide-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 1180px;
        min-height: 340px;
        margin: 0 auto;
        padding: 58px 30px 82px;
        display: flex;
        align-items: center;
    }

    .race-guide-hero-content {
        max-width: 780px;
    }

    .hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 17px;
        padding: 8px 14px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50px;
        background: rgba(255, 255, 255, .07);
        color: rgba(255, 255, 255, .86);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .16em;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }

    .hero-kicker i {
        color: #ff4da4;
    }

    .race-guide-hero h1 {
        margin: 0 0 14px;
        color: #fff;
        font-size: clamp(2.4rem, 5vw, 4.7rem);
        line-height: .98;
        font-weight: 900;
        letter-spacing: -.05em;
    }

    .race-guide-hero h1 span {
        color: #ff429c;
    }

    .hero-description {
        max-width: 680px;
        margin: 0;
        color: rgba(255, 255, 255, .68);
        font-size: 14px;
        line-height: 1.75;
    }

    /* =========================================================
       WAVE
    ========================================================= */

    .race-guide-wave {
        position: absolute;
        z-index: 4;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 70px;
        pointer-events: none;
    }

    .race-guide-wave svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* =========================================================
       CONTENT
    ========================================================= */

    .race-guide-content {
        position: relative;
        z-index: 5;
        padding: 0 0 85px;
    }

    .race-guide-container {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* =========================================================
       GUIDE CARD
    ========================================================= */

    .race-guide-card {
        position: relative;
        margin-top: -15px;
        padding: 34px;
        border: 1px solid rgba(34, 24, 76, .08);
        border-radius: 30px;
        background: rgba(255, 255, 255, .97);
        box-shadow:
            0 28px 75px rgba(32, 20, 76, .10),
            0 5px 18px rgba(32, 20, 76, .035);
    }

    .race-guide-card::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 4px;
        border-radius: 30px 30px 0 0;
        background: linear-gradient(90deg, #ff2f92, #a05cff);
    }

    /* =========================================================
       GUIDE HEADER
    ========================================================= */

    .race-guide-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .race-guide-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .race-guide-title-icon {
        width: 54px;
        height: 54px;
        flex: 0 0 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background:
            linear-gradient(145deg, #fff0f8, #f0eaff);
        color: var(--rg-pink);
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(255, 47, 146, .08);
    }

    .guide-eyebrow {
        margin-bottom: 4px;
        color: var(--rg-pink-dark);
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .15em;
        text-transform: uppercase;
    }

    .race-guide-title h3 {
        margin: 0;
        color: var(--rg-navy);
        font-size: clamp(1.4rem, 2.5vw, 1.9rem);
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.035em;
    }

    .race-guide-title p {
        margin: 5px 0 0;
        color: var(--rg-muted) !important;
        font-size: 12px;
        font-weight: 650;
    }

    /* =========================================================
       LANGUAGE TABS
    ========================================================= */

    .guide-tabs {
        display: flex;
        align-items: center;
        padding: 5px;
        gap: 4px;
        border: 1px solid #e6e0ef;
        border-radius: 15px;
        background: #f8f6fc;
    }

    .guide-tabs .btn-tab {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 39px;
        border: 0;
        padding: 8px 15px;
        border-radius: 11px;
        background: transparent;
        color: #746d82;
        font-size: 11px;
        font-weight: 850;
        white-space: nowrap;
        transition:
            color .2s ease,
            background .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }

    .guide-tabs .btn-tab:hover:not(.active) {
        color: var(--rg-navy);
        background: #fff;
    }

    .guide-tabs .btn-tab.active {
        background: var(--rg-navy);
        color: #fff;
        box-shadow: 0 6px 16px rgba(14, 9, 40, .18);
    }

    .guide-tabs .btn-tab.active i {
        color: #ff4da4;
    }

    /* =========================================================
       PDF VIEWER
    ========================================================= */

    .guide-viewer-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 11px;
    }

    .guide-viewer-label span:first-child {
        color: var(--rg-navy);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .guide-viewer-label span:last-child {
        color: #a09aaa;
        font-size: 10px;
        font-weight: 650;
    }

    .pdf-viewer-wrap {
        position: relative;
        width: 100%;
        height: 650px;
        overflow: hidden;
        border: 1px solid #ddd6e9;
        border-radius: 22px;
        background: #eae6f1;
        box-shadow:
            inset 0 0 0 5px rgba(255, 255, 255, .65),
            0 14px 35px rgba(31, 19, 68, .07);
    }

    .pdf-viewer-wrap::before {
        content: "";
        position: absolute;
        z-index: 3;
        left: 0;
        right: 0;
        top: 0;
        height: 5px;
        background: linear-gradient(90deg, #ff2f92, #a15bff);
        pointer-events: none;
    }

    .pdf-viewer-wrap iframe {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        border: none;
        background: #eeeaf4;
    }

    /* =========================================================
       READ & CONTINUE
    ========================================================= */

    .guide-confirmation {
        position: relative;
        margin-top: 25px;
        padding: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 47, 146, .13);
        border-radius: 21px;
        background:
            linear-gradient(135deg, #fff8fc 0%, #f8f4ff 100%);
    }

    .guide-confirmation::before {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -90px;
        top: -110px;
        border-radius: 50%;
        background: rgba(255, 47, 146, .06);
    }

    .confirmation-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
    }

    .confirmation-check {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        min-width: 0;
    }

    .confirmation-icon {
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff;
        color: var(--rg-pink);
        box-shadow: 0 5px 15px rgba(35, 20, 82, .06);
    }

    .accept-checkbox-card {
        margin: 0;
        padding: 0;
        border: 0;
        background: transparent;
    }

    .accept-checkbox-card .form-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 0;
    }

    .accept-checkbox-card .form-check-input {
        width: 20px;
        height: 20px;
        flex: 0 0 20px;
        margin-top: 1px;
        border: 2px solid #d6cfdf;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: none;
    }

    .accept-checkbox-card .form-check-input:checked {
        background-color: var(--rg-pink);
        border-color: var(--rg-pink);
        box-shadow: 0 4px 12px rgba(255, 47, 146, .22);
    }

    .accept-checkbox-card label {
        color: #342e43;
        font-size: 12px;
        line-height: 1.6;
        font-weight: 750;
        cursor: pointer;
    }

    /* =========================================================
       PAYMENT CTA
    ========================================================= */

    .accept-btn {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 53px;
        min-width: 205px;
        padding: 10px 22px;
        border: 0;
        border-radius: 15px;
        background:
            linear-gradient(135deg, #ff3193 0%, #e91d7f 100%);
        color: #fff !important;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .01em;
        white-space: nowrap;
        box-shadow: 0 12px 28px rgba(255, 47, 146, .27);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            background .25s ease;
    }

    .accept-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        background:
            linear-gradient(135deg, #ff48a0 0%, #ed207f 100%);
        box-shadow: 0 17px 34px rgba(255, 47, 146, .34);
    }

    .accept-btn:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .accept-btn:disabled {
        background: #ddd7e6 !important;
        color: #958d9f !important;
        box-shadow: none !important;
        opacity: .75;
        cursor: not-allowed;
        transform: none !important;
    }

    /* =========================================================
       BOTTOM EVENT MESSAGE
    ========================================================= */

    .guide-footer-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        color: #8b8498;
        font-size: 10px;
        font-weight: 650;
        text-align: center;
    }

    .guide-footer-note i {
        color: var(--rg-pink);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .race-guide-hero {
            min-height: 300px;
            border-radius: 0 0 30px 30px;
        }

        .race-guide-hero-inner {
            min-height: 300px;
            padding: 44px 20px 70px;
        }

        .race-guide-hero h1 {
            font-size: 2.55rem;
        }

        .hero-description {
            font-size: 12px;
        }

        .race-guide-content {
            padding-bottom: 50px;
        }

        .race-guide-container {
            padding: 0 13px;
        }

        .race-guide-card {
            margin-top: -8px;
            padding: 21px 15px;
            border-radius: 22px;
        }

        .race-guide-header {
            align-items: flex-start;
            gap: 17px;
            margin-bottom: 20px;
        }

        .race-guide-title {
            width: 100%;
        }

        .race-guide-title-icon {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 13px;
            font-size: 17px;
        }

        .race-guide-title h3 {
            font-size: 1.4rem;
        }

        .guide-tabs {
            width: 100%;
        }

        .guide-tabs .btn-tab {
            flex: 1;
            padding: 8px 8px;
            font-size: 10px;
        }

        .pdf-viewer-wrap {
            height: 500px;
            border-radius: 17px;
        }

        .guide-confirmation {
            padding: 17px;
            border-radius: 18px;
        }

        .confirmation-inner {
            flex-direction: column;
            align-items: stretch;
            gap: 17px;
        }

        .accept-btn {
            width: 100%;
            min-width: 0;
        }
    }

    @media (max-width: 420px) {

        .race-guide-hero h1 {
            font-size: 2.2rem;
        }

        .pdf-viewer-wrap {
            height: 430px;
        }

        .guide-tabs .btn-tab {
            font-size: 9px;
        }
    }

    /* =========================================================
       SUBTLE ANIMATION
    ========================================================= */

    @media (prefers-reduced-motion: no-preference) {

        .race-guide-card {
            animation: guideFadeUp .55s ease both;
        }

        @keyframes guideFadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
</style>

{{-- =========================================================
EVENT HERO
========================================================= --}}

<section class="race-guide-hero">

    <div class="race-guide-hero-inner">

        <div class="race-guide-hero-content">

            <div class="hero-kicker">
                <i class="fas fa-flag-checkered"></i>
                Official Event Information
            </div>

            <h1>
                Know the race.<br>
                <span>Own the experience.</span>
            </h1>

            <p class="hero-description">
                Everything you need to know before race day.
                Review the official race guide carefully before continuing
                to the payment stage.
            </p>

        </div>

    </div>


    <div class="race-guide-wave">

        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">

            <path
                d="M0,63
                   C170,112 345,16 540,48
                   C735,80 865,108 1080,48
                   C1240,10 1345,25 1440,50
                   L1440,100
                   L0,100 Z"
                fill="#fbfaff">
            </path>

        </svg>

    </div>

</section>

{{-- =========================================================
MAIN GUIDE CONTENT
========================================================= --}}

<section class="race-guide-page race-guide-content">

    <div class="race-guide-container">

        <div class="race-guide-card">


            {{-- =================================================
                 HEADER
            ================================================== --}}

            <div class="race-guide-header">

                <div class="race-guide-title">

                    <div class="race-guide-title-icon">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div>

                        <div class="guide-eyebrow">
                            Participant briefing
                        </div>

                        <h3>
                            Official Event Race Guide
                        </h3>

                        <p>
                            {{ $event->title }}
                        </p>

                    </div>

                </div>


                {{-- LANGUAGE TABS --}}

                @if($event->english_race_guide && $event->burmese_race_guide)

                    <div class="guide-tabs">

                        <button type="button"
                                class="btn-tab active"
                                id="tab-en"
                                onclick="switchGuide('en')">

                            <i class="fas fa-globe"></i>
                            English

                        </button>


                        <button type="button"
                                class="btn-tab"
                                id="tab-mm"
                                onclick="switchGuide('mm')">

                            <i class="fas fa-language"></i>
                            Burmese

                        </button>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 PDF VIEWER
            ================================================== --}}

            <div class="guide-viewer-label">

                <span>
                    <i class="fas fa-file-alt me-1"></i>
                    Race guide document
                </span>

                <span>
                    Please read before proceeding
                </span>

            </div>


            <div class="pdf-viewer-wrap">

                @if($event->english_race_guide)

                    <iframe
                        id="pdf-frame-en"
                        src="{{ asset('storage/' . $event->english_race_guide) }}#toolbar=0&view=FitH">
                    </iframe>

                @endif


                @if($event->burmese_race_guide)

                    <iframe
                        id="pdf-frame-mm"
                        src="{{ asset('storage/' . $event->burmese_race_guide) }}#toolbar=0&view=FitH"
                        style="{{ $event->english_race_guide ? 'display: none;' : '' }}">
                    </iframe>

                @endif

            </div>


            {{-- =================================================
                 FORM
                 DIRECTLY POSTS TO CHECKOUT
            ================================================== --}}

            <form action="{{ route('checkout.process') }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="event_id"
                       value="{{ $event->id }}">

                <input type="hidden"
                       name="promo_code"
                       value="{{ $promoCode }}">


                {{-- ATTENDEE DATA UNCHANGED --}}

                @foreach($attendees as $index => $attendee)

                    @foreach($attendee as $key => $value)

                        <input type="hidden"
                               name="attendees[{{ $index }}][{{ $key }}]"
                               value="{{ $value }}">

                    @endforeach

                @endforeach


                {{-- =================================================
                     CONFIRMATION
                ================================================== --}}

                <div class="guide-confirmation">

                    <div class="confirmation-inner">

                        <div class="confirmation-check">

                            <div class="confirmation-icon">
                                <i class="fas fa-check"></i>
                            </div>

                            <div class="accept-checkbox-card">

                                <div class="form-check m-0">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="check-read-guide"
                                        onchange="toggleSubmitBtn()">

                                    <label
                                        class="form-check-label font-weight-bold"
                                        for="check-read-guide">

                                        I have read, understood, and agree
                                        to follow all instructions in the
                                        Official Race Guide.

                                    </label>

                                </div>

                            </div>

                        </div>


                        {{-- CTA --}}

                        <button type="submit"
                                class="btn accept-btn"
                                id="btn-proceed"
                                disabled>

                            Proceed to Payment

                            <i class="fas fa-arrow-right ms-2"></i>

                        </button>

                    </div>

                </div>

            </form>


            <div class="guide-footer-note">

                <i class="fas fa-shield-alt"></i>

                Please make sure you have reviewed the complete guide
                before continuing.

            </div>


        </div>

    </div>

</section>

@push('scripts')

<script>

function switchGuide(lang) {

    const frameEn = document.getElementById('pdf-frame-en');
    const frameMm = document.getElementById('pdf-frame-mm');

    const tabEn = document.getElementById('tab-en');
    const tabMm = document.getElementById('tab-mm');


    if (lang === 'en') {

        if (frameEn) {
            frameEn.style.display = 'block';
        }

        if (frameMm) {
            frameMm.style.display = 'none';
        }

        if (tabEn) {
            tabEn.classList.add('active');
        }

        if (tabMm) {
            tabMm.classList.remove('active');
        }

    } else {

        if (frameEn) {
            frameEn.style.display = 'none';
        }

        if (frameMm) {
            frameMm.style.display = 'block';
        }

        if (tabEn) {
            tabEn.classList.remove('active');
        }

        if (tabMm) {
            tabMm.classList.add('active');
        }

    }

}


function toggleSubmitBtn() {

    const checkbox = document.getElementById('check-read-guide');

    const btn = document.getElementById('btn-proceed');

    if (checkbox && btn) {

        btn.disabled = !checkbox.checked;

    }

}

</script>

@endpush

@endsection