@extends('layouts.master')

@section('title', 'Event Consent & Waiver - ' . $event->title)

@section('content')

{{-- Fallback link to ensure Font Awesome 6 loads if missing from layouts.master --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* =========================================================
       PREMIUM EVENTEN-INSPIRED WAIVER PAGE
       Frontend styling only — Laravel logic unchanged
    ========================================================= */

    .event-waiver-page {
        --ew-navy: #100b2e;
        --ew-navy-2: #17113f;
        --ew-purple: #241452;
        --ew-pink: #ff2f92;
        --ew-pink-dark: #e51d7d;
        --ew-pink-soft: #fff0f8;
        --ew-lavender: #f7f3ff;
        --ew-lavender-2: #eee8ff;
        --ew-text: #17132f;
        --ew-muted: #716c82;
        --ew-border: rgba(34, 24, 76, .10);

        position: relative;
        overflow: hidden;
        min-height: 100vh;
        background:
            radial-gradient(circle at 5% 10%, rgba(255, 47, 146, .08), transparent 24%),
            radial-gradient(circle at 95% 15%, rgba(125, 79, 255, .10), transparent 26%),
            linear-gradient(180deg, #fbfaff 0%, #f4f0ff 100%);
        color: var(--ew-text);
    }

    /* =========================================================
       DECORATIVE BACKGROUND
    ========================================================= */

    .event-waiver-page::before {
        content: "";
        position: absolute;
        width: 520px;
        height: 520px;
        border-radius: 50%;
        background: rgba(255, 47, 146, .055);
        top: -250px;
        right: -180px;
        filter: blur(2px);
        pointer-events: none;
    }

    .event-waiver-page::after {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: rgba(98, 62, 220, .06);
        bottom: -220px;
        left: -180px;
        pointer-events: none;
    }

    .event-waiver-page .container {
        position: relative;
        z-index: 2;
        max-width: 1180px;
    }

    /* =========================================================
       TOP EVENT HEADER
    ========================================================= */

    .waiver-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 34px;
        min-height: 330px;
        border-radius: 0 0 42px 42px;
        background:
            radial-gradient(circle at 85% 20%, rgba(255, 47, 146, .30), transparent 28%),
            radial-gradient(circle at 20% 100%, rgba(112, 74, 255, .24), transparent 34%),
            linear-gradient(125deg, #0d0927 0%, #18103e 48%, #291054 100%);
        box-shadow: 0 25px 65px rgba(16, 11, 46, .18);
    }

    .waiver-hero::before {
        content: "";
        position: absolute;
        width: 480px;
        height: 480px;
        right: -140px;
        top: -260px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 50%;
    }

    .waiver-hero::after {
        content: "";
        position: absolute;
        width: 700px;
        height: 180px;
        left: -100px;
        bottom: -130px;
        background: rgba(255, 255, 255, .045);
        border-radius: 50%;
        transform: rotate(-8deg);
    }

    .waiver-hero-inner {
        position: relative;
        z-index: 2;
        max-width: 1180px;
        min-height: 330px;
        margin: 0 auto;
        padding: 60px 30px 75px;
        display: flex;
        align-items: center;
    }

    .waiver-hero-content {
        max-width: 850px;
    }

    .event-kicker {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 17px;
        padding: 8px 14px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50px;
        background: rgba(255, 255, 255, .07);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }

    .event-kicker i {
        color: #ff5fae;
    }

    .waiver-hero h1 {
        margin: 0 0 15px;
        color: #fff;
        font-size: clamp(2.2rem, 5vw, 4.4rem);
        line-height: .98;
        font-weight: 900;
        letter-spacing: -.045em;
    }

    .waiver-hero h1 span {
        color: #ff4da4;
    }

    .waiver-hero-description {
        max-width: 700px;
        margin: 0;
        color: rgba(255, 255, 255, .72);
        font-size: 15px;
        line-height: 1.7;
    }

    /* =========================================================
       MAIN CONTENT
    ========================================================= */

    .waiver-content {
        padding: 0 0 80px;
    }

    .waiver-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr);
        gap: 28px;
        align-items: start;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .waiver-card {
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, .96);
        border: 1px solid rgba(38, 25, 82, .08);
        border-radius: 28px !important;
        padding: 36px;
        box-shadow:
            0 25px 70px rgba(35, 20, 82, .08),
            0 3px 12px rgba(35, 20, 82, .035);
    }

    .waiver-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--ew-pink), #9c5cff);
    }

    .section-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 10px;
        color: var(--ew-pink-dark);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .section-eyebrow::before {
        content: "";
        width: 28px;
        height: 3px;
        border-radius: 5px;
        background: var(--ew-pink);
    }

    .waiver-card h2 {
        margin-bottom: 8px;
        color: var(--ew-navy);
        font-size: clamp(1.65rem, 3vw, 2.35rem);
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.035em;
    }

    .event-name {
        margin-bottom: 28px;
        color: var(--ew-muted);
        font-size: 14px;
    }

    .event-name strong {
        color: var(--ew-navy);
        font-weight: 800;
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .waiver-card .alert {
        border: 0 !important;
        border-radius: 17px !important;
        box-shadow: none;
    }

    /* =========================================================
       DOCUMENT DOWNLOADS
    ========================================================= */

    .documents-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 14px;
    }

    .documents-title h6 {
        margin: 0;
        color: var(--ew-navy);
        font-size: 14px;
        font-weight: 850;
    }

    .documents-title span {
        color: var(--ew-muted);
        font-size: 11px;
        font-weight: 700;
    }

    .document-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 30px;
    }

    .pdf-btn {
        position: relative;
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 68px;
        padding: 12px 15px;
        border: 1px solid var(--ew-border);
        border-radius: 17px;
        background: #fff;
        color: var(--ew-navy);
        text-decoration: none;
        box-shadow: 0 7px 20px rgba(35, 20, 82, .035);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease,
            background .25s ease;
    }

    .pdf-btn:hover {
        transform: translateY(-3px);
        border-color: rgba(255, 47, 146, .35);
        background: #fff7fb;
        color: var(--ew-navy);
        box-shadow: 0 14px 30px rgba(255, 47, 146, .10);
    }

    .pdf-icon {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff0f4;
        color: #ef315f;
        font-size: 17px;
    }

    .pdf-btn-content {
        min-width: 0;
    }

    .pdf-btn-title {
        display: block;
        color: var(--ew-navy);
        font-size: 12px;
        font-weight: 850;
        line-height: 1.35;
    }

    .pdf-btn-subtitle {
        display: block;
        margin-top: 2px;
        color: var(--ew-muted);
        font-size: 10px;
        font-weight: 600;
    }

    .pdf-arrow {
        margin-left: auto;
        color: #b4adbf;
        transition: transform .2s ease, color .2s ease;
    }

    .pdf-btn:hover .pdf-arrow {
        color: var(--ew-pink);
        transform: translateX(3px);
    }

    /* =========================================================
       TERMS BOX
    ========================================================= */

    .terms-section {
        margin-top: 5px;
    }

    .terms-heading {
        margin-bottom: 12px;
        color: var(--ew-navy);
        font-size: 14px;
        font-weight: 850;
    }

    .rules-box {
        position: relative;
        padding: 21px 23px;
        border: 1px solid rgba(113, 88, 183, .12);
        border-radius: 20px;
        background:
            linear-gradient(135deg, #faf8ff 0%, #f4efff 100%);
        max-height: 245px;
        overflow-y: auto;
    }

    .rules-box::before {
        content: "";
        position: absolute;
        left: 0;
        top: 20px;
        bottom: 20px;
        width: 3px;
        border-radius: 10px;
        background: linear-gradient(180deg, var(--ew-pink), #8d5cff);
    }

    .rules-box ol {
        color: #696277;
        font-size: 13px;
        line-height: 1.85;
    }

    .rules-box li {
        padding-left: 5px;
        margin-bottom: 6px;
    }

    .rules-box li:last-child {
        margin-bottom: 0;
    }

    /* =========================================================
       AGREEMENT
    ========================================================= */

    .agreement-area {
        margin-top: 26px;
        padding: 20px;
        border: 1px solid rgba(255, 47, 146, .13);
        border-radius: 21px;
        background: linear-gradient(135deg, #fff8fc 0%, #f8f3ff 100%);
    }

    .waiver-checkbox-box {
        padding: 0;
        border: 0;
        background: transparent;
    }

    .waiver-checkbox-box .form-check {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .waiver-checkbox-box .form-check-input {
        width: 21px;
        height: 21px;
        flex: 0 0 21px;
        margin-top: 1px;
        border: 2px solid #d8d0e4;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: none;
    }

    .waiver-checkbox-box .form-check-input:checked {
        background-color: var(--ew-pink);
        border-color: var(--ew-pink);
        box-shadow: 0 4px 12px rgba(255, 47, 146, .25);
    }

    .waiver-checkbox-box label {
        color: #29233b;
        font-size: 13px;
        line-height: 1.65;
        font-weight: 700;
        cursor: pointer;
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .waiver-actions {
        display: grid;
        grid-template-columns: .7fr 1.3fr;
        gap: 12px;
        margin-top: 20px;
    }

    .payment-btn,
    .btn-back-outline {
        min-height: 54px;
        border-radius: 15px !important;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: .01em;
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            background .25s ease,
            border-color .25s ease;
    }

    .payment-btn {
        border: 0;
        background:
            linear-gradient(135deg, #ff3193 0%, #e81d7f 100%);
        color: #fff !important;
        box-shadow: 0 12px 28px rgba(255, 47, 146, .27);
    }

    .payment-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        background:
            linear-gradient(135deg, #ff48a0 0%, #ed207f 100%);
        box-shadow: 0 17px 34px rgba(255, 47, 146, .34);
    }

    .payment-btn:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .payment-btn:disabled {
        background: #ded8e8 !important;
        color: #958ca3 !important;
        box-shadow: none !important;
        opacity: .75;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-back-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dcd5e8;
        background: #fff;
        color: #4e465d;
        text-decoration: none;
    }

    .btn-back-outline:hover {
        transform: translateY(-2px);
        border-color: #c7bdd8;
        background: #faf8ff;
        color: var(--ew-navy);
        box-shadow: 0 8px 20px rgba(35, 20, 82, .07);
    }

    /* =========================================================
       SIDE EVENT CARD
    ========================================================= */

    .event-side-card {
        position: sticky;
        top: 25px;
        overflow: hidden;
        border-radius: 28px;
        background: var(--ew-navy);
        color: #fff;
        box-shadow: 0 25px 60px rgba(16, 11, 46, .16);
    }

    .event-side-top {
        position: relative;
        min-height: 205px;
        padding: 26px;
        overflow: hidden;
        background:
            radial-gradient(circle at 85% 15%, rgba(255, 47, 146, .32), transparent 35%),
            radial-gradient(circle at 5% 100%, rgba(120, 81, 255, .32), transparent 42%),
            linear-gradient(145deg, #171039, #291352);
    }

    .event-side-top::before {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -110px;
        bottom: -130px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
    }

    .side-label {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 50px;
        background: rgba(255, 255, 255, .09);
        color: rgba(255, 255, 255, .8);
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .side-label i {
        color: #ff4da4;
    }

    .event-side-top h3 {
        position: relative;
        z-index: 2;
        margin: 19px 0 0;
        color: #fff;
        font-size: 25px;
        line-height: 1.15;
        font-weight: 900;
        letter-spacing: -.025em;
    }

    .event-side-body {
        padding: 23px;
        background: #fff;
        color: var(--ew-text);
    }

    .event-info-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px 0;
        border-bottom: 1px solid #eeeaf4;
    }

    .event-info-item:first-child {
        padding-top: 0;
    }

    .event-info-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .event-info-icon {
        width: 39px;
        height: 39px;
        flex: 0 0 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff0f8;
        color: var(--ew-pink);
        font-size: 14px;
    }

    .event-info-label {
        display: block;
        margin-bottom: 2px;
        color: #9a93a4;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .event-info-value {
        display: block;
        color: var(--ew-navy);
        font-size: 12px;
        font-weight: 800;
        line-height: 1.35;
    }

    .side-note {
        margin-top: 20px;
        padding: 14px;
        border-radius: 15px;
        background: #f7f4ff;
        color: #70687e;
        font-size: 11px;
        line-height: 1.6;
    }

    .side-note i {
        margin-right: 5px;
        color: var(--ew-pink);
    }

    /* =========================================================
       WAVE
    ========================================================= */

    .waiver-wave {
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 70px;
        z-index: 3;
        pointer-events: none;
    }

    .waiver-wave svg {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 991.98px) {
        .waiver-layout {
            grid-template-columns: 1fr;
        }

        .event-side-card {
            position: relative;
            top: auto;
            order: -1;
        }

        .event-side-top {
            min-height: 180px;
        }
    }

    @media (max-width: 767.98px) {
        .waiver-hero {
            margin-bottom: 22px;
            border-radius: 0 0 28px 28px;
        }

        .waiver-hero-inner {
            min-height: 290px;
            padding: 45px 20px 70px;
        }

        .waiver-hero h1 {
            font-size: 2.45rem;
        }

        .waiver-hero-description {
            font-size: 13px;
        }

        .waiver-content {
            padding-bottom: 45px;
        }

        .waiver-card {
            padding: 23px 18px;
            border-radius: 22px !important;
        }

        .document-grid {
            grid-template-columns: 1fr;
        }

        .waiver-actions {
            grid-template-columns: 1fr;
        }

        .event-side-card {
            border-radius: 22px;
        }

        .event-side-top {
            min-height: 155px;
            padding: 22px;
        }

        .event-side-top h3 {
            font-size: 21px;
        }

        .event-side-body {
            padding: 19px;
        }

        .rules-box {
            padding: 18px;
            max-height: 220px;
        }
    }

    @media (max-width: 420px) {
        .waiver-hero h1 {
            font-size: 2.1rem;
        }

        .waiver-card h2 {
            font-size: 1.55rem;
        }

        .pdf-btn {
            min-height: 62px;
        }
    }

    /* =========================================================
       SUBTLE MOTION
    ========================================================= */

    @media (prefers-reduced-motion: no-preference) {
        .waiver-card,
        .event-side-card {
            animation: waiverFadeUp .55s ease both;
        }

        .event-side-card {
            animation-delay: .08s;
        }

        @keyframes waiverFadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
</style>

{{-- =========================================================
PREMIUM EVENT HERO
========================================================= --}}

<section class="waiver-hero">

    <div class="waiver-hero-inner">

        <div class="waiver-hero-content">

            <div class="event-kicker">
                <i class="fa-solid fa-ticket"></i>
                Registration Agreement
            </div>

            <h1>
                One final step<br>
                <span>before your event.</span>
            </h1>

            <p class="waiver-hero-description">
                Please review the event terms, waiver and consent information
                before completing your registration.
            </p>

        </div>

    </div>

    <div class="waiver-wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path
                d="M0,65 C180,115 340,15 540,48 C750,82 860,105 1080,48 C1240,8 1340,25 1440,50 L1440,100 L0,100 Z"
                fill="#fbfaff">
            </path>
        </svg>
    </div>

</section>

{{-- =========================================================
MAIN CONTENT
========================================================= --}}

<section class="event-waiver-page waiver-content">

    <div class="container">

        <div class="waiver-layout">

            {{-- =================================================
                 MAIN WAIVER CONTENT
            ================================================== --}}

            <div class="waiver-card">

                {{-- ERROR MESSAGE --}}

                @if ($errors->any())
                    <div class="alert alert-danger mb-4 p-3 rounded-3">

                        <h6 class="font-weight-bold mb-2">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Please fix the following issues:
                        </h6>

                        <ul class="mb-0 ps-3 text-sm">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>
                @endif


                {{-- HEADER --}}

                <div class="section-eyebrow">
                    Participant agreement
                </div>

                <h2>
                    Terms, Waiver & Consent
                </h2>

                <p class="event-name">
                    You're registering for
                    <strong>{{ $event->title }}</strong>
                </p>


                {{-- =================================================
                     DOWNLOADABLE DOCUMENTS
                ================================================== --}}

                @if($event->english_waiver || $event->burmese_waiver || $event->english_consent || $event->burmese_consent)

                    <div class="documents-title">

                        <h6>
                            <i class="fa-regular fa-file-pdf me-2"></i>
                            Official event documents
                        </h6>

                        <span>
                            PDF documents
                        </span>

                    </div>


                    <div class="document-grid">

                        @if($event->english_waiver)

                            <a href="{{ asset('storage/' . $event->english_waiver) }}"
                               target="_blank"
                               class="pdf-btn">

                                <span class="pdf-icon">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </span>

                                <span class="pdf-btn-content">

                                    <span class="pdf-btn-title">
                                        English Waiver
                                    </span>

                                    <span class="pdf-btn-subtitle">
                                        Open PDF document
                                    </span>

                                </span>

                                <i class="fa-solid fa-arrow-right pdf-arrow"></i>

                            </a>

                        @endif


                        @if($event->burmese_waiver)

                            <a href="{{ asset('storage/' . $event->burmese_waiver) }}"
                               target="_blank"
                               class="pdf-btn">

                                <span class="pdf-icon">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </span>

                                <span class="pdf-btn-content">

                                    <span class="pdf-btn-title">
                                        Burmese Waiver
                                    </span>

                                    <span class="pdf-btn-subtitle">
                                        Open PDF document
                                    </span>

                                </span>

                                <i class="fa-solid fa-arrow-right pdf-arrow"></i>

                            </a>

                        @endif


                        @if($event->english_consent)

                            <a href="{{ asset('storage/' . $event->english_consent) }}"
                               target="_blank"
                               class="pdf-btn">

                                <span class="pdf-icon">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </span>

                                <span class="pdf-btn-content">

                                    <span class="pdf-btn-title">
                                        English Consent Form
                                    </span>

                                    <span class="pdf-btn-subtitle">
                                        Open PDF document
                                    </span>

                                </span>

                                <i class="fa-solid fa-arrow-right pdf-arrow"></i>

                            </a>

                        @endif


                        @if($event->burmese_consent)

                            <a href="{{ asset('storage/' . $event->burmese_consent) }}"
                               target="_blank"
                               class="pdf-btn">

                                <span class="pdf-icon">
                                    <i class="fa-regular fa-file-pdf"></i>
                                </span>

                                <span class="pdf-btn-content">

                                    <span class="pdf-btn-title">
                                        Burmese Consent Form
                                    </span>

                                    <span class="pdf-btn-subtitle">
                                        Open PDF document
                                    </span>

                                </span>

                                <i class="fa-solid fa-arrow-right pdf-arrow"></i>

                            </a>

                        @endif

                    </div>

                @endif


                {{-- =================================================
                     TERMS
                ================================================== --}}

                <div class="terms-section">

                    <div class="terms-heading">
                        <i class="fa-solid fa-shield-halved me-2"></i>
                        Terms & Conditions Summary
                    </div>

                    <div class="rules-box">

                        <ol class="mb-0 ps-3">

                            <li>
                                Participants must comply with event guidelines
                                and follow marshal instructions at all times.
                            </li>

                            <li>
                                Registrations are non-refundable and
                                non-transferable under any circumstances.
                            </li>

                            <li>
                                By agreeing below, you release event organizers
                                from liability regarding injury or property loss
                                during participation, and grant consent for media
                                and data processing terms where applicable.
                            </li>

                        </ol>

                    </div>

                </div>


                {{-- =================================================
                     FORM
                ================================================== --}}

                <form action="{{ route('events.waiver.accept', $event) }}"
                      method="POST">

                    @csrf

                    <input type="hidden"
                           name="event_id"
                           value="{{ $event->id }}">

                    <input type="hidden"
                           name="promo_code"
                           value="{{ $promoCode }}">


                    {{-- =================================================
                         ATTENDEE HIDDEN DATA
                         UNCHANGED
                    ================================================== --}}

                    @foreach($attendees as $index => $attendee)

                        <input type="hidden" name="attendees[{{ $index }}][ticket_category_id]" value="{{ $attendee['ticket_category_id'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][full_name]" value="{{ $attendee['full_name'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][promo_code_id]" value="{{ $attendee['promo_code_id'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][father_name]" value="{{ $attendee['father_name'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][email]" value="{{ $attendee['email'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][phone]" value="{{ $attendee['phone'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][viber]" value="{{ $attendee['viber'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][emergency_contact]" value="{{ $attendee['emergency_contact'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][nrc_passport]" value="{{ $attendee['nrc_passport'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][nationality]" value="{{ $attendee['nationality'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][country]" value="{{ $attendee['country'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][gender]" value="{{ $attendee['gender'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][date_of_birth]" value="{{ $attendee['date_of_birth'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][bib_name]" value="{{ $attendee['bib_name'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][tshirt_size]" value="{{ $attendee['tshirt_size'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][blood_type]" value="{{ $attendee['blood_type'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][has_medical_condition]" value="{{ !empty($attendee['has_medical_condition']) ? $attendee['has_medical_condition'] : 'no' }}">

                        <input type="hidden" name="attendees[{{ $index }}][medical_details]" value="{{ $attendee['medical_details'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][itra]" value="{{ !empty($attendee['itra']) ? $attendee['itra'] : 'no' }}">

                        <input type="hidden" name="attendees[{{ $index }}][itra_details]" value="{{ $attendee['itra_details'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][address]" value="{{ $attendee['address'] ?? '' }}">

                        <input type="hidden" name="attendees[{{ $index }}][experience]" value="{{ $attendee['experience'] ?? '' }}">

                    @endforeach


                    {{-- =================================================
                         AGREEMENT CHECKBOX
                    ================================================== --}}

                    <div class="agreement-area">

                        <div class="waiver-checkbox-box">

                            <div class="form-check m-0">

                                <input class="form-check-input"
                                       type="checkbox"
                                       name="agree_waiver"
                                       id="agree_waiver"
                                       required>

                                <label class="form-check-label font-weight-bold"
                                       for="agree_waiver">

                                    I have read, understood, and agree to all
                                    event rules, safety terms, waivers, and
                                    optional consent forms in English / Burmese.

                                </label>

                            </div>

                        </div>


                        {{-- =================================================
                             ACTION BUTTONS
                        ================================================== --}}

                        <div class="waiver-actions">

                            <a href="{{ route('events.show', $event) }}"
                               class="btn btn-back-outline w-100">

                                <i class="fa-solid fa-arrow-left me-2"></i>
                                Back to Event

                            </a>


                            <button type="submit"
                                    class="btn payment-btn w-100"
                                    id="btn-proceed"
                                    disabled>

                                Continue to Registration

                                <i class="fa-solid fa-arrow-right ms-2"></i>

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            {{-- =================================================
                 EVENT INFORMATION CARD
            ================================================== --}}

            <aside class="event-side-card">

                <div class="event-side-top">

                    <span class="side-label">
                        <i class="fa-solid fa-star"></i>
                        Your Event
                    </span>

                    <h3>
                        {{ $event->title }}
                    </h3>

                </div>


                <div class="event-side-body">

                    <div class="event-info-item">

                        <span class="event-info-icon">
                            <i class="fa-regular fa-calendar-days"></i>
                        </span>

                        <span>

                            <span class="event-info-label">
                                Event date
                            </span>

                            <span class="event-info-value">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </span>

                        </span>

                    </div>


                    <div class="event-info-item">

                        <span class="event-info-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>

                        <span>

                            <span class="event-info-label">
                                Location
                            </span>

                            <span class="event-info-value">
                                {{ $event->location ?? 'Event venue' }}
                            </span>

                        </span>

                    </div>


                    <div class="event-info-item">

                        <span class="event-info-icon">
                            <i class="fa-solid fa-ticket"></i>
                        </span>

                        <span>

                            <span class="event-info-label">
                                Registration
                            </span>

                            <span class="event-info-value">
                                Complete your agreement
                            </span>

                        </span>

                    </div>


                    <div class="side-note">

                        <i class="fa-solid fa-lock"></i>

                        Your registration information is securely
                        transferred to the next stage after you accept
                        the agreement.

                    </div>

                </div>

            </aside>

        </div>

    </div>

</section>

{{-- =========================================================
JAVASCRIPT
FUNCTIONALITY UNCHANGED
========================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const agreeCheckbox = document.getElementById('agree_waiver');
    const proceedBtn = document.getElementById('btn-proceed');

    if (agreeCheckbox && proceedBtn) {

        agreeCheckbox.addEventListener('change', function () {

            proceedBtn.disabled = !this.checked;

        });

    }

});
</script>

@endpush

@endsection