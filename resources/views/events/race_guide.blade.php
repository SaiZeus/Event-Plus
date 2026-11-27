@extends('layouts.master')

@section('title', 'Race Guide - ' . $event->title)

@section('content')

@php
    $hasEn    = filled($event->english_race_guide);
    $hasMm    = filled($event->burmese_race_guide);
    $hasGuide = $hasEn || $hasMm;
    $hasBoth  = $hasEn && $hasMm;

    $enUrl = $hasEn ? asset('storage/' . $event->english_race_guide) : null;
    $mmUrl = $hasMm ? asset('storage/' . $event->burmese_race_guide) : null;

    $firstLang  = $hasEn ? 'en' : 'mm';
    $firstUrl   = $hasEn ? $enUrl : $mmUrl;
    $firstLabel = $hasEn ? 'English guide' : 'Burmese guide';
@endphp

<div class="rg">

    {{-- =========================================================
    HERO
    ========================================================= --}}

    <header class="rg-hero">

        <div class="container">

            <ol class="rg-steps" aria-label="Registration progress">

                <li class="is-done">
                    <span class="rg-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Tickets
                </li>

                <li class="is-done">
                    <span class="rg-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Participant details
                </li>

                <li class="is-done">
                    <span class="rg-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Waiver
                </li>

                <li class="is-current" aria-current="step">
                    <span class="rg-steps__n" aria-hidden="true">4</span>
                    {{ $hasGuide ? 'Race guide' : 'Payment' }}
                </li>

            </ol>

            @if($hasGuide)

                <h1 class="rg-title">Read the race guide</h1>

                <p class="rg-lead">
                    Everything you need to know before race day for <strong>{{ $event->title }}</strong>.
                    Read it through, then confirm to continue to payment.
                </p>

            @else

                <h1 class="rg-title">You're ready for payment</h1>

                <p class="rg-lead">
                    There's no race guide to read for <strong>{{ $event->title }}</strong>.
                    Continue whenever you're ready.
                </p>

            @endif

        </div>

    </header>


    {{-- =========================================================
    MAIN
    ========================================================= --}}

    <main class="rg-main">

        <div class="container">

            <div class="rg-card">

                @if($hasGuide)

                    {{-- =================================================
                         HEADER + LANGUAGE TABS
                    ================================================== --}}

                    <div class="rg-head">

                        <div>
                            <h2 class="rg-h">Official race guide</h2>
                            <p class="rg-sub">{{ $event->title }}</p>
                        </div>

                        @if($hasBoth)

                            <div class="rg-tabs" role="tablist" aria-label="Guide language">

                                <button
                                    type="button"
                                    class="btn-tab active"
                                    id="tab-en"
                                    role="tab"
                                    aria-selected="true"
                                    aria-controls="pdf-frame-en"
                                >
                                    <i class="fas fa-globe" aria-hidden="true"></i>
                                    <span>English</span>
                                </button>

                                <button
                                    type="button"
                                    class="btn-tab"
                                    id="tab-mm"
                                    role="tab"
                                    aria-selected="false"
                                    aria-controls="pdf-frame-mm"
                                    tabindex="-1"
                                >
                                    <i class="fas fa-language" aria-hidden="true"></i>
                                    <span>Burmese</span>
                                </button>

                            </div>

                        @else

                            <span class="rg-lang">
                                <i class="fas fa-language" aria-hidden="true"></i>
                                {{ $hasEn ? 'English' : 'Burmese' }}
                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                         TOOLBAR
                    ================================================== --}}

                    <div class="rg-toolbar">

                        <p class="rg-toolbar__now">
                            <i class="far fa-file-pdf" aria-hidden="true"></i>
                            <span id="rg-now">{{ $firstLabel }}</span>
                        </p>

                        <a
                            href="{{ $firstUrl }}"
                            id="rg-open"
                            class="rg-btn rg-btn--outline rg-btn--sm"
                            target="_blank"
                            rel="noopener"
                        >
                            <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                            <span>Open in new tab</span>
                        </a>

                    </div>


                    {{-- =================================================
                         PDF VIEWER
                    ================================================== --}}

                    <div class="rg-viewer">

                        @if($hasEn)
                            <iframe
                                id="pdf-frame-en"
                                title="English race guide"
                                data-url="{{ $enUrl }}"
                                src="{{ $enUrl }}#toolbar=0&view=FitH"
                                {{ $firstLang === 'en' ? '' : 'hidden' }}
                            ></iframe>
                        @endif

                        @if($hasMm)
                            <iframe
                                id="pdf-frame-mm"
                                title="Burmese race guide"
                                data-url="{{ $mmUrl }}"
                                src="{{ $mmUrl }}#toolbar=0&view=FitH"
                                {{ $firstLang === 'mm' ? '' : 'hidden' }}
                            ></iframe>
                        @endif

                    </div>

                    <p class="rg-viewer-note">
                        Can't see the guide, or it's hard to read on your phone? Use
                        <strong>Open in new tab</strong> above.
                    </p>

                @else

                    {{-- =================================================
                         NO GUIDE FOR THIS EVENT
                    ================================================== --}}

                    <div class="rg-empty">

                        <span class="rg-empty__icon" aria-hidden="true">
                            <i class="fas fa-flag-checkered"></i>
                        </span>

                        <div>
                            <h2 class="rg-h">No race guide for this event</h2>
                            <p class="rg-sub">
                                The organiser hasn't added one, so there's nothing more to read.
                                Check your details are right, then continue to payment.
                            </p>
                        </div>

                    </div>

                @endif


                {{-- =================================================
                     FORM (POSTS DIRECTLY TO CHECKOUT)
                ================================================== --}}

                <form action="{{ route('checkout.process') }}" method="POST">

                    @csrf

                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <input type="hidden" name="promo_code" value="{{ $promoCode }}">


                    {{-- GROUP BUY: keep the chosen deal --}}
@if(!empty($groupBuyId))
    <input type="hidden" name="group_buy_id" value="{{ $groupBuyId }}">
@endif

                    {{-- ATTENDEE DATA (UNCHANGED) --}}
                    @foreach($attendees as $index => $attendee)

                        @foreach($attendee as $key => $value)

                            <input
                                type="hidden"
                                name="attendees[{{ $index }}][{{ $key }}]"
                                value="{{ $value }}"
                            >

                        @endforeach

                    @endforeach


                    {{-- CONFIRM + CONTINUE --}}
                    <div class="rg-confirm">

                        @if($hasGuide)

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="check-read-guide"
                                >

                                <label class="form-check-label" for="check-read-guide">
                                    I have read, understood and agree to follow all instructions in the
                                    official race guide.
                                </label>

                            </div>

                        @endif

                        <div class="rg-confirm__actions">

                            <button
                                type="submit"
                                class="rg-btn rg-btn--primary accept-btn"
                                id="btn-proceed"
                                @if($hasGuide) aria-describedby="rg-hint" disabled @endif
                            >
                                <span>Proceed to payment</span>
                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            </button>

                        </div>

                        @if($hasGuide)
                            <p class="rg-hint" id="rg-hint">Tick the box above to continue.</p>
                        @endif

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

@endsection


{{-- =========================================================
STYLES
========================================================= --}}

@push('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">

<style>

/* =========================================================
   TOKENS (shared with the home, event and waiver pages)
========================================================= */

.rg {
    --rg-ink: #17122b;
    --rg-muted: #6b6580;
    --rg-pink: #db2777;
    --rg-pink-dark: #be185d;
    --rg-violet: #7c3aed;
    --rg-line: #e6e1f3;
    --rg-bg: #faf8ff;
    --rg-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    color: var(--rg-ink);
}


/* =========================================================
   HERO
========================================================= */

.rg-hero {
    padding: clamp(1.5rem, 3.5vw, 2.5rem) 0 clamp(4rem, 8vw, 6rem);
    background:
        radial-gradient(55% 90% at 90% 30%, rgba(124, 58, 237, 0.4), transparent 70%),
        var(--rg-ink);
    color: #fff;
}

.rg-steps {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin: 0 0 1.75rem;
    padding: 0;
    list-style: none;
}

.rg-steps li {
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

.rg-steps__n {
    display: grid;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.16);
    font-size: 0.8rem;
    font-weight: 700;
}

.rg-steps .is-done .rg-steps__n {
    background: rgba(255, 255, 255, 0.9);
    color: var(--rg-ink);
    font-size: 0.7rem;
}

.rg-steps .is-current {
    border-color: transparent;
    background: #fff;
    color: var(--rg-ink);
}

.rg-steps .is-current .rg-steps__n {
    background: var(--rg-pink);
    color: #fff;
}

.rg-title {
    margin: 0 0 1rem;
    color: #fff;
    font-family: var(--rg-display);
    font-size: clamp(2rem, 4.8vw, 3.4rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.025em;
    text-wrap: balance;
}

.rg-lead {
    max-width: 56ch;
    margin: 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1.0625rem;
    line-height: 1.65;
}

.rg-lead strong {
    color: #fff;
}


/* =========================================================
   MAIN CARD
========================================================= */

.rg-main {
    position: relative;
    z-index: 3;
    margin-top: -2rem;
    padding: clamp(2rem, 5vw, 3rem) 0 clamp(3.5rem, 8vw, 6rem);
    border-radius: 32px 32px 0 0;
    background: var(--rg-bg);
}

.rg-card {
    max-width: 1000px;
    margin-inline: auto;
    padding: clamp(1.25rem, 3.5vw, 2.25rem);
    border: 1px solid var(--rg-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.rg-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.rg-h {
    margin: 0;
    font-family: var(--rg-display);
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.015em;
}

.rg-sub {
    margin: 0.35rem 0 0;
    color: var(--rg-muted);
    line-height: 1.6;
    overflow-wrap: anywhere;
}


/* ---------- Language tabs ---------- */

.rg-tabs {
    display: flex;
    gap: 0.25rem;
    padding: 0.3rem;
    border-radius: 999px;
    background: #f1edfb;
}

.rg-tabs .btn-tab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.55rem;
    min-height: 2.6rem;
    padding: 0.6rem 1.2rem;
    border: 0;
    border-radius: 999px;
    background: transparent;
    color: #4b4563;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.rg-tabs .btn-tab:hover:not(.active) {
    color: var(--rg-ink);
}

.rg-tabs .btn-tab.active {
    background: var(--rg-ink);
    color: #fff;
}

.rg-tabs .btn-tab:focus-visible {
    outline: 3px solid var(--rg-violet);
    outline-offset: 2px;
}

.rg-lang {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.55rem 1.1rem;
    border-radius: 999px;
    background: #f1edfb;
    font-weight: 600;
}

.rg-lang i {
    color: var(--rg-pink);
}


/* ---------- Toolbar + viewer ---------- */

.rg-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
    margin-bottom: 0.9rem;
}

.rg-toolbar__now {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0;
    font-weight: 700;
}

.rg-toolbar__now i {
    color: #e11d48;
    font-size: 1.15rem;
}

.rg-viewer {
    height: clamp(420px, 75vh, 780px);
    overflow: hidden;
    border: 1.5px solid var(--rg-line);
    border-radius: 18px;
    background: #eeeaf4;
}

.rg-viewer iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;
}

.rg-viewer iframe[hidden] {
    display: none;
}

.rg-viewer-note {
    margin: 0.8rem 0 0;
    color: var(--rg-muted);
    font-size: 0.9rem;
    line-height: 1.55;
}

.rg-viewer-note strong {
    color: var(--rg-ink);
}


/* ---------- Empty state (event has no guide) ---------- */

.rg-empty {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border: 1.5px dashed var(--rg-line);
    border-radius: 18px;
    background: var(--rg-bg);
}

.rg-empty__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 3rem;
    height: 3rem;
    border-radius: 14px;
    background: #f3edfc;
    color: var(--rg-pink);
    font-size: 1.2rem;
}


/* ---------- Confirm + continue ---------- */

.rg-confirm {
    margin-top: 1.5rem;
    padding: 1.25rem;
    border: 1.5px solid var(--rg-line);
    border-radius: 18px;
    background: var(--rg-bg);
}

.rg-confirm .form-check {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    padding: 0;
}

.rg-confirm .form-check-input {
    flex: none;
    width: 1.5rem;
    height: 1.5rem;
    margin: 0.1rem 0 0;
    float: none;
    border: 2px solid #b8b0cc;
    border-radius: 7px;
    background-color: #fff;
    cursor: pointer;
}

.rg-confirm .form-check-input:checked {
    border-color: var(--rg-pink);
    background-color: var(--rg-pink);
}

.rg-confirm .form-check-input:focus-visible {
    border-color: var(--rg-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.2);
    outline: none;
}

.rg-confirm label {
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.55;
    cursor: pointer;
}

.rg-confirm__actions {
    display: flex;
    justify-content: flex-end;
}

.rg-confirm .form-check + .rg-confirm__actions {
    margin-top: 1.25rem;
}

.rg-hint {
    margin: 0.75rem 0 0;
    color: var(--rg-muted);
    font-size: 0.9rem;
    text-align: right;
}

.rg-hint[hidden] {
    display: none;
}


/* ---------- Buttons ---------- */

.rg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    min-height: 3.25rem;
    padding: 0.8rem 1.7rem;
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
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        color 0.2s ease;
}

.rg-btn:focus-visible {
    outline: 3px solid var(--rg-violet);
    outline-offset: 3px;
}

.rg-btn i {
    font-size: 0.8rem;
}

.rg-btn--sm {
    min-height: 2.6rem;
    padding: 0.6rem 1.2rem;
    font-size: 0.95rem;
}

.rg-btn--primary {
    background: var(--rg-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.3);
}

.rg-btn--primary:hover:not(:disabled) {
    background: var(--rg-pink-dark);
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(219, 39, 119, 0.4);
}

.rg-btn--primary:disabled {
    background: #e4dff0;
    color: #8a849c !important;
    box-shadow: none;
    cursor: not-allowed;
}

.rg-btn--outline {
    border-color: var(--rg-ink);
    color: var(--rg-ink) !important;
}

.rg-btn--outline:hover {
    background: var(--rg-ink);
    color: #fff !important;
}


/* =========================================================
   SMALL SCREENS
========================================================= */

@media (max-width: 575px) {

    .rg-tabs {
        width: 100%;
    }

    .rg-tabs .btn-tab {
        flex: 1;
    }

    .rg-toolbar .rg-btn {
        width: 100%;
    }

    .rg-viewer {
        height: 460px;
    }

    .rg-confirm__actions .rg-btn {
        width: 100%;
    }

    .rg-hint {
        text-align: center;
    }

    .rg-empty {
        flex-direction: column;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .rg *,
    .rg *::before,
    .rg *::after {
        animation: none !important;
        transition: none !important;
    }

}

</style>

@endpush


{{-- =========================================================
JAVASCRIPT
========================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const frames = {
        en: document.getElementById('pdf-frame-en'),
        mm: document.getElementById('pdf-frame-mm')
    };

    const tabs = {
        en: document.getElementById('tab-en'),
        mm: document.getElementById('tab-mm')
    };

    const labels = { en: 'English guide', mm: 'Burmese guide' };

    const openLink = document.getElementById('rg-open');
    const nowLabel = document.getElementById('rg-now');

    /* Language switch (only used when an event has both guides) */
    function switchGuide(lang) {

        ['en', 'mm'].forEach(function (key) {
            const active = key === lang;

            if (frames[key]) frames[key].hidden = !active;

            if (tabs[key]) {
                tabs[key].classList.toggle('active', active);
                tabs[key].setAttribute('aria-selected', active ? 'true' : 'false');
                tabs[key].tabIndex = active ? 0 : -1;
            }
        });

        if (frames[lang] && openLink) openLink.href = frames[lang].getAttribute('data-url');
        if (nowLabel) nowLabel.textContent = labels[lang];
    }

    window.switchGuide = switchGuide;

    ['en', 'mm'].forEach(function (key) {
        if (!tabs[key]) return;

        tabs[key].addEventListener('click', function () {
            switchGuide(key);
        });

        tabs[key].addEventListener('keydown', function (e) {
            if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;

            e.preventDefault();

            const other = key === 'en' ? 'mm' : 'en';
            switchGuide(other);
            if (tabs[other]) tabs[other].focus();
        });
    });


    /* Confirmation checkbox -> continue button */
    const checkbox = document.getElementById('check-read-guide');
    const btn = document.getElementById('btn-proceed');
    const hint = document.getElementById('rg-hint');

    if (!btn) return;

    const form = btn.closest('form');
    const label = btn.querySelector('span');
    const defaultLabel = label ? label.textContent : '';

    function toggleSubmitBtn() {
        if (!checkbox) return;

        btn.disabled = !checkbox.checked;
        if (hint) hint.hidden = checkbox.checked;
    }

    window.toggleSubmitBtn = toggleSubmitBtn;

    if (checkbox) {
        checkbox.addEventListener('change', toggleSubmitBtn);

        // Browsers can restore a ticked box after a refresh or back button
        toggleSubmitBtn();
    }

    // Stop double submits
    if (form) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            if (label) label.textContent = 'Please wait…';
        });
    }

    // Coming back via the back button: make the button usable again
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;

        if (label) label.textContent = defaultLabel;

        if (checkbox) {
            toggleSubmitBtn();
        } else {
            btn.disabled = false;
        }
    });

});
</script>

@endpush