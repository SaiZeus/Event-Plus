@extends('layouts.master')

@section('title', 'Event Consent & Waiver - ' . $event->title)

@section('content')

@php
    $date    = $event->event_date ? \Carbon\Carbon::parse($event->event_date) : null;
    $hasTime = $date && $date->format('His') !== '000000';

    $participants = collect($attendees ?? [])->pluck('full_name')->filter()->values();

    $documents = array_filter([
        ['file' => $event->english_waiver,  'title' => 'English waiver'],
        ['file' => $event->burmese_waiver,  'title' => 'Burmese waiver'],
        ['file' => $event->english_consent, 'title' => 'English consent form'],
        ['file' => $event->burmese_consent, 'title' => 'Burmese consent form'],
    ], fn ($doc) => ! empty($doc['file']));
@endphp

<div class="ww">

    {{-- =========================================================
    HERO
    ========================================================= --}}

    <header class="ww-hero">

        <div class="container">

            <a href="{{ route('events.show', $event) }}" class="ww-back">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                <span>Back to event</span>
            </a>

            <ol class="ww-steps" aria-label="Registration progress">

                <li class="is-done">
                    <span class="ww-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Tickets
                </li>

                <li class="is-done">
                    <span class="ww-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Participant details
                </li>

                <li class="is-current" aria-current="step">
                    <span class="ww-steps__n" aria-hidden="true">3</span>
                    Waiver
                </li>

            </ol>

            <h1 class="ww-title">Review and accept the waiver</h1>

            <p class="ww-lead">
                Read the event documents and terms below, then confirm to continue
                your registration for <strong>{{ $event->title }}</strong>.
            </p>

        </div>

    </header>


    {{-- =========================================================
    MAIN
    ========================================================= --}}

    <main class="ww-main">

        <div class="container">

            <div class="ww-layout">

                {{-- =================================================
                     WAIVER CARD
                ================================================== --}}

                <div class="ww-card">

                    {{-- ERRORS --}}
                    @if ($errors->any())
                        <div class="ww-callout" role="alert">

                            <span class="ww-callout__icon" aria-hidden="true">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>

                            <div>
                                <h2>Please fix the following</h2>

                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    @endif


                    {{-- DOCUMENTS --}}
                    <section aria-labelledby="ww-docs-title">

                        <h2 class="ww-h" id="ww-docs-title">Event documents</h2>

                        @if(count($documents))

                            <p class="ww-sub">Each one opens as a PDF in a new tab.</p>

                            <div class="ww-docs">

                                @foreach($documents as $doc)
                                    <a
                                        href="{{ asset('storage/' . $doc['file']) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="ww-doc"
                                    >
                                        <span class="ww-doc__icon" aria-hidden="true">
                                            <i class="far fa-file-pdf"></i>
                                        </span>

                                        <span class="ww-doc__text">
                                            <span class="ww-doc__title">{{ $doc['title'] }}</span>
                                            <span class="ww-doc__meta">PDF, opens in a new tab</span>
                                        </span>

                                        <i class="fas fa-chevron-right ww-doc__go" aria-hidden="true"></i>
                                    </a>
                                @endforeach

                            </div>

                        @else

                            <p class="ww-sub">
                                The organiser hasn't uploaded any documents for this event.
                                The terms below apply.
                            </p>

                        @endif

                    </section>


                    {{-- TERMS --}}
                    <section aria-labelledby="ww-terms-title" class="ww-terms">

                        <h2 class="ww-h" id="ww-terms-title">Terms in short</h2>

                        <ul class="ww-rules">

                            <li>
                                <i class="fas fa-check" aria-hidden="true"></i>
                                <span>
                                    Follow the event guidelines and the marshals' instructions at all times.
                                </span>
                            </li>

                            <li>
                                <i class="fas fa-check" aria-hidden="true"></i>
                                <span>
                                    Registrations are non-refundable and non-transferable under any circumstances.
                                </span>
                            </li>

                            <li>
                                <i class="fas fa-check" aria-hidden="true"></i>
                                <span>
                                    By agreeing below, you release the event organisers from liability for injury
                                    or loss of property during participation, and you consent to media and data
                                    processing terms where they apply.
                                </span>
                            </li>

                        </ul>

                    </section>


                    {{-- =================================================
                         FORM
                    ================================================== --}}

                    <form action="{{ route('events.waiver.accept', $event) }}" method="POST">

                        @csrf

                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <input type="hidden" name="promo_code" value="{{ $promoCode }}">

                        {{-- GROUP BUY: keep the chosen deal --}}
@if(!empty($groupBuyId))
    <input type="hidden" name="group_buy_id" value="{{ $groupBuyId }}">
@endif

                        {{-- ATTENDEE HIDDEN DATA (UNCHANGED) --}}
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


                        {{-- AGREEMENT --}}
                        <div class="ww-agree">

                            <div class="form-check m-0">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="agree_waiver"
                                    id="agree_waiver"
                                    required
                                >

                                <label class="form-check-label" for="agree_waiver">
                                    I have read, understood and agree to the event rules, safety terms, waivers and
                                    any consent forms, in English or Burmese.
                                </label>

                            </div>

                        </div>


                        {{-- ACTIONS --}}
                        <div class="ww-actions">

                            <a href="{{ route('events.show', $event) }}" class="ww-btn ww-btn--outline">
                                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                <span>Back to event</span>
                            </a>

                            <button
                                type="submit"
                                class="ww-btn ww-btn--primary payment-btn"
                                id="btn-proceed"
                                aria-describedby="waiver-hint"
                                disabled
                            >
                                <span>Continue to registration</span>
                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            </button>

                        </div>

                        <p class="ww-hint" id="waiver-hint">Tick the box above to continue.</p>

                    </form>

                </div>


                {{-- =================================================
                     SUMMARY TICKET
                ================================================== --}}

                <aside class="ww-ticket" aria-label="Your registration">

                    <div class="ww-ticket__top">

                        <p class="ww-ticket__label">You're registering for</p>

                        <h2 class="ww-ticket__title">{{ $event->title }}</h2>

                        <dl class="ww-facts">

                            @if($date)
                                <div class="ww-fact">
                                    <span class="ww-fact__icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></span>
                                    <div>
                                        <dt>Date</dt>
                                        <dd>{{ $date->format('D, d M Y') }}@if($hasTime), {{ $date->format('g:i A') }}@endif</dd>
                                    </div>
                                </div>
                            @endif

                            @if(filled($event->location))
                                <div class="ww-fact">
                                    <span class="ww-fact__icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                                    <div>
                                        <dt>Location</dt>
                                        <dd>{{ $event->location }}</dd>
                                    </div>
                                </div>
                            @endif

                        </dl>

                    </div>

                    <div class="ww-ticket__perf" aria-hidden="true"></div>

                    <div class="ww-ticket__bottom">

                        <p class="ww-ticket__label">
                            {{ $participants->count() === 1 ? '1 participant' : $participants->count() . ' participants' }}
                        </p>

                        @if($participants->isNotEmpty())
                            <ul class="ww-people">
                                @foreach($participants as $name)
                                    <li>
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span>{{ $name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <p class="ww-note">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <span>Your details are passed securely to the next step once you accept.</span>
                        </p>

                    </div>

                </aside>

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
   TOKENS (shared with the home and event pages)
========================================================= */

.ww {
    --ww-ink: #17122b;
    --ww-muted: #6b6580;
    --ww-pink: #db2777;
    --ww-pink-dark: #be185d;
    --ww-violet: #7c3aed;
    --ww-line: #e6e1f3;
    --ww-field: #d9d3ea;
    --ww-bg: #faf8ff;
    --ww-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    color: var(--ww-ink);
}

html {
    scroll-behavior: smooth;
}


/* =========================================================
   HERO
========================================================= */

.ww-hero {
    padding: clamp(1.25rem, 3vw, 2rem) 0 clamp(4rem, 8vw, 6rem);
    background:
        radial-gradient(55% 90% at 90% 30%, rgba(124, 58, 237, 0.4), transparent 70%),
        var(--ww-ink);
    color: #fff;
}

.ww-back {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    min-height: 2.75rem;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.ww-back:hover {
    color: #fff;
}

.ww-back i {
    font-size: 0.75rem;
}

.ww-back:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 3px;
}

.ww-steps {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin: 0 0 1.75rem;
    padding: 0;
    list-style: none;
}

.ww-steps li {
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

.ww-steps__n {
    display: grid;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.16);
    font-size: 0.8rem;
    font-weight: 700;
}

.ww-steps .is-done .ww-steps__n {
    background: rgba(255, 255, 255, 0.9);
    color: var(--ww-ink);
    font-size: 0.7rem;
}

.ww-steps .is-current {
    border-color: transparent;
    background: #fff;
    color: var(--ww-ink);
}

.ww-steps .is-current .ww-steps__n {
    background: var(--ww-pink);
    color: #fff;
}

.ww-title {
    margin: 0 0 1rem;
    color: #fff;
    font-family: var(--ww-display);
    font-size: clamp(2rem, 4.8vw, 3.4rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.025em;
    text-wrap: balance;
}

.ww-lead {
    max-width: 56ch;
    margin: 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1.0625rem;
    line-height: 1.65;
}

.ww-lead strong {
    color: #fff;
}


/* =========================================================
   MAIN
========================================================= */

.ww-main {
    position: relative;
    z-index: 3;
    margin-top: -2rem;
    padding: clamp(2rem, 5vw, 3rem) 0 clamp(3.5rem, 8vw, 6rem);
    border-radius: 32px 32px 0 0;
    background: var(--ww-bg);
}

.ww-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 350px;
    gap: 1.5rem;
    align-items: start;
}

.ww-card {
    padding: clamp(1.25rem, 3.5vw, 2.25rem);
    border: 1px solid var(--ww-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.ww-h {
    margin: 0 0 0.5rem;
    font-family: var(--ww-display);
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.015em;
}

.ww-sub {
    margin: 0 0 1.25rem;
    color: var(--ww-muted);
    line-height: 1.6;
}


/* ---------- Errors ---------- */

.ww-callout {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.75rem;
    padding: 1.25rem;
    border: 1.5px solid #fecdd3;
    border-radius: 18px;
    background: #fff1f2;
}

.ww-callout__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 12px;
    background: #ffe4e6;
    color: #e11d48;
}

.ww-callout h2 {
    margin: 0 0 0.3rem;
    font-family: var(--ww-display);
    font-size: 1.15rem;
    font-weight: 800;
}

.ww-callout ul {
    margin: 0;
    padding-left: 1.1rem;
    color: #7f1d33;
}


/* ---------- Documents ---------- */

.ww-docs {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.ww-doc {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    min-height: 4.5rem;
    padding: 0.8rem 1rem;
    border: 1.5px solid var(--ww-line);
    border-radius: 16px;
    background: #fff;
    color: var(--ww-ink);
    text-decoration: none;
    transition: border-color 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
}

.ww-doc:hover {
    border-color: var(--ww-pink);
    background: #fff7fb;
    color: var(--ww-ink);
    transform: translateY(-2px);
}

.ww-doc:focus-visible {
    outline: 3px solid var(--ww-violet);
    outline-offset: 3px;
}

.ww-doc__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.6rem;
    height: 2.6rem;
    border-radius: 12px;
    background: #fff0f4;
    color: #e11d48;
    font-size: 1.1rem;
}

.ww-doc__text {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.ww-doc__title {
    font-weight: 700;
    line-height: 1.3;
}

.ww-doc__meta {
    color: var(--ww-muted);
    font-size: 0.85rem;
}

.ww-doc__go {
    margin-left: auto;
    color: #a39cb5;
    font-size: 0.8rem;
    transition: transform 0.2s ease, color 0.2s ease;
}

.ww-doc:hover .ww-doc__go {
    color: var(--ww-pink);
    transform: translateX(3px);
}


/* ---------- Terms ---------- */

.ww-terms {
    margin-top: 2rem;
}

.ww-rules {
    display: grid;
    gap: 0.9rem;
    margin: 1rem 0 0;
    padding: 0;
    list-style: none;
}

.ww-rules li {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    color: #4b4563;
    line-height: 1.65;
}

.ww-rules i {
    display: grid;
    flex: none;
    place-items: center;
    width: 1.6rem;
    height: 1.6rem;
    margin-top: 0.1rem;
    border-radius: 50%;
    background: #f3edfc;
    color: var(--ww-pink);
    font-size: 0.65rem;
}


/* ---------- Agreement ---------- */

.ww-agree {
    margin-top: 2rem;
    padding: 1.25rem;
    border: 1.5px solid var(--ww-line);
    border-radius: 18px;
    background: var(--ww-bg);
}

.ww-agree .form-check {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    padding: 0;
}

.ww-agree .form-check-input {
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

.ww-agree .form-check-input:checked {
    border-color: var(--ww-pink);
    background-color: var(--ww-pink);
}

.ww-agree .form-check-input:focus-visible {
    border-color: var(--ww-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.2);
    outline: none;
}

.ww-agree label {
    color: var(--ww-ink);
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.55;
    cursor: pointer;
}


/* ---------- Buttons ---------- */

.ww-actions {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 0.75rem;
    margin-top: 1.25rem;
}

.ww-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
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
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        color 0.2s ease;
}

.ww-btn:focus-visible {
    outline: 3px solid var(--ww-violet);
    outline-offset: 3px;
}

.ww-btn i {
    font-size: 0.8rem;
}

.ww-btn--primary {
    background: var(--ww-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.3);
}

.ww-btn--primary:hover:not(:disabled) {
    background: var(--ww-pink-dark);
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(219, 39, 119, 0.4);
}

.ww-btn--primary:disabled {
    background: #e4dff0;
    color: #8a849c !important;
    box-shadow: none;
    cursor: not-allowed;
}

.ww-btn--outline {
    border-color: var(--ww-ink);
    color: var(--ww-ink) !important;
}

.ww-btn--outline:hover {
    background: var(--ww-ink);
    color: #fff !important;
}

.ww-hint {
    margin: 0.75rem 0 0;
    color: var(--ww-muted);
    font-size: 0.9rem;
    text-align: right;
}

.ww-hint[hidden] {
    display: none;
}


/* =========================================================
   SUMMARY TICKET
========================================================= */

.ww-ticket {
    position: sticky;
    top: 24px;
    overflow: hidden;
    border: 1px solid var(--ww-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.06);
}

.ww-ticket__top,
.ww-ticket__bottom {
    padding: 1.5rem;
}

.ww-ticket__label {
    margin: 0 0 0.4rem;
    color: var(--ww-muted);
    font-size: 0.9rem;
    font-weight: 600;
}

.ww-ticket__title {
    margin: 0 0 1.25rem;
    font-family: var(--ww-display);
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.015em;
    overflow-wrap: anywhere;
}

.ww-facts {
    display: grid;
    gap: 1rem;
    margin: 0;
}

.ww-fact {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
}

.ww-fact__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.4rem;
    height: 2.4rem;
    border-radius: 12px;
    background: #f3edfc;
    color: var(--ww-pink);
}

.ww-fact dt {
    margin: 0;
    color: var(--ww-muted);
    font-size: 0.85rem;
    font-weight: 600;
}

.ww-fact dd {
    margin: 0.1rem 0 0;
    font-weight: 600;
    overflow-wrap: anywhere;
}

/* Perforation with punched notches */
.ww-ticket__perf {
    position: relative;
    height: 0;
    margin: 0 24px;
    border-top: 2px dashed rgba(23, 18, 43, 0.2);
}

.ww-ticket__perf::before,
.ww-ticket__perf::after {
    content: "";
    position: absolute;
    top: -13px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--ww-bg);
}

.ww-ticket__perf::before {
    left: -36px;
}

.ww-ticket__perf::after {
    right: -36px;
}

.ww-people {
    display: grid;
    gap: 0.5rem;
    margin: 0.6rem 0 0;
    padding: 0;
    list-style: none;
}

.ww-people li {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.ww-people i {
    flex: none;
    width: 1rem;
    color: var(--ww-pink);
    font-size: 0.85rem;
    text-align: center;
}

.ww-note {
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    margin: 1.25rem 0 0;
    padding: 0.9rem 1rem;
    border-radius: 14px;
    background: var(--ww-bg);
    color: #4b4563;
    font-size: 0.9rem;
    line-height: 1.55;
}

.ww-note i {
    margin-top: 0.2rem;
    color: var(--ww-pink);
}


/* =========================================================
   SMALL SCREENS
========================================================= */

@media (max-width: 991px) {

    .ww-layout {
        grid-template-columns: 1fr;
    }

    .ww-ticket {
        position: static;
    }

}

@media (max-width: 575px) {

    .ww-docs {
        grid-template-columns: 1fr;
    }

    .ww-actions {
        grid-template-columns: 1fr;
    }

    .ww-actions .ww-btn--primary {
        order: -1;
    }

    .ww-hint {
        text-align: center;
    }

    .ww-callout {
        flex-direction: column;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    html {
        scroll-behavior: auto;
    }

    .ww *,
    .ww *::before,
    .ww *::after {
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

    const agreeCheckbox = document.getElementById('agree_waiver');
    const proceedBtn = document.getElementById('btn-proceed');
    const hint = document.getElementById('waiver-hint');

    if (!agreeCheckbox || !proceedBtn) return;

    const form = proceedBtn.closest('form');
    const label = proceedBtn.querySelector('span');
    const defaultLabel = label ? label.textContent : '';

    function sync() {
        proceedBtn.disabled = !agreeCheckbox.checked;
        if (hint) hint.hidden = agreeCheckbox.checked;
    }

    agreeCheckbox.addEventListener('change', sync);

    // Browsers can restore a ticked box after a refresh or back button
    sync();

    // Stop double submits
    if (form) {
        form.addEventListener('submit', function () {
            proceedBtn.disabled = true;
            if (label) label.textContent = 'Please wait…';
        });
    }

    // Coming back via the back button: make the button usable again
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;

        if (label) label.textContent = defaultLabel;
        sync();
    });

});
</script>

@endpush