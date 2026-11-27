@extends('layouts.master')

@section('title', $event->title)

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | View state
    |--------------------------------------------------------------------------
    */
    $date     = $event->event_date ? \Carbon\Carbon::parse($event->event_date) : null;
    $hasTime  = $date && $date->format('His') !== '000000';

    $statusKey  = $event->status === 'live' ? 'live' : ($event->status === 'upcoming' ? 'upcoming' : 'ended');
    $statusText = ['live' => 'Live now', 'upcoming' => 'Upcoming', 'ended' => 'Ended'][$statusKey];

    $soldOut  = $isEventSoldOut ?? false;
    $minPrice = $event->ticketCategories->min('local_price');
    $hasMap   = (bool) ($event->latitude && $event->longitude);

    $posterUrl = $event->image
        ? asset('storage/' . $event->image)
        : asset('assets/img/about/img06.jpg');

    $phoneHref = $event->creator_phone ? preg_replace('/[^0-9+]/', '', $event->creator_phone) : null;

    $summary = \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 300);

    // GROUP BUY: active deals prepared by EventController@show.
    $groupBuys     = collect($groupBuys ?? []);
    $categoryNames = $event->ticketCategories->pluck('name', 'id');
    $formatMoney   = fn ($amount) => rtrim(rtrim(number_format((float) $amount, 2), '0'), '.');

    // "Add to calendar" (Google Calendar) for events that haven't finished
    $calendarUrl = null;
    if ($date && in_array($statusKey, ['live', 'upcoming'], true)) {
        $calendarParams = [
            'action'   => 'TEMPLATE',
            'text'     => $event->title,
            'details'  => $summary,
            'location' => $event->location,
        ];

        if ($hasTime) {
            $calendarParams['dates'] = $date->format('Ymd\THis') . '/' . $date->copy()->addHours(2)->format('Ymd\THis');
            $calendarParams['ctz']   = config('app.timezone');
        } else {
            $calendarParams['dates'] = $date->format('Ymd') . '/' . $date->copy()->addDay()->format('Ymd');
        }

        $calendarUrl = 'https://calendar.google.com/calendar/render?' . http_build_query(array_filter($calendarParams));
    }
@endphp

<div class="ev">

    {{-- =========================================================
    HERO
    ========================================================= --}}

    <header class="ev-hero">

        <div class="container">

            <a href="{{ url('/') }}#events-section" class="ev-back">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                <span>All events</span>
            </a>

            <div class="row ev-hero__row">

                <div class="col-lg-7">

                    <span class="ev-badge ev-badge--{{ $statusKey }}">
                        <span class="ev-badge__dot" aria-hidden="true"></span>
                        {{ $statusText }}
                    </span>

                    <h1 class="ev-title">{{ $event->title }}</h1>

                    <ul class="ev-chips">

                        @if($date)
                            <li>
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                <span>{{ $date->format('D, d M Y') }}@if($hasTime), {{ $date->format('g:i A') }}@endif</span>
                            </li>
                        @endif

                        @if(filled($event->location))
                            <li>
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                <span>{{ $event->location }}</span>
                            </li>
                        @endif

                    </ul>

                    @if($event->creator_name)
                        <p class="ev-by">Organised by <strong>{{ $event->creator_name }}</strong></p>
                    @endif

                    <div class="ev-actions">

                        @if($statusKey === 'live' && ! $soldOut && $event->ticketCategories->isNotEmpty())
                            <a href="#tickets" class="ev-btn ev-btn--primary">
                                <span>Get tickets</span>
                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                            </a>
                        @endif

                        @if($calendarUrl)
                            <a href="{{ $calendarUrl }}" class="ev-btn ev-btn--ghost" target="_blank" rel="noopener">
                                <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                <span>Add to calendar</span>
                            </a>
                        @endif

                    </div>

                </div>

                <div class="col-lg-5">

                    <div class="ev-ticket-wrap">

                        <article class="ev-ticket" aria-label="{{ $event->title }} ticket">

                            <div class="ev-ticket__media">
                                <img src="{{ $posterUrl }}" alt="{{ $event->title }}">
                            </div>

                            <div class="ev-ticket__stub">

                                @if($statusKey === 'live' && $soldOut)

                                    <p class="ev-stub__label">Tickets</p>
                                    <p class="ev-stub__value">Sold out</p>

                                @elseif($statusKey === 'live' && $minPrice !== null)

                                    <p class="ev-stub__label">Tickets from</p>
                                    <p class="ev-stub__value">{{ number_format($minPrice) }} <small>MMK</small></p>

                                @elseif($statusKey === 'upcoming')

                                    <p class="ev-stub__label">Registration</p>
                                    <p class="ev-stub__value ev-stub__value--sm">Opens when the event goes live</p>

                                @elseif($statusKey === 'ended')

                                    <p class="ev-stub__value ev-stub__value--sm">This event has ended</p>

                                @else

                                    <p class="ev-stub__value ev-stub__value--sm">Tickets to be announced</p>

                                @endif

                            </div>

                        </article>

                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- =========================================================
    MAIN CONTENT
    ========================================================= --}}

    <main class="ev-main">

        <div class="container">

            <div class="ev-stack">

                {{-- ABOUT + FACTS --}}
                <div class="row g-4">

                    <div class="col-lg-8">

                        <section class="ev-card h-100">

                            <h2 class="ev-h">About this event</h2>

                            <div class="ev-prose">{!! nl2br(e($event->description)) !!}</div>

                        </section>

                    </div>

                    <div class="col-lg-4">

                        <aside class="ev-card ev-facts h-100" aria-label="Event details">

                            <h2 class="ev-h">Event details</h2>

                            <dl>

                                @if($date)
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></span>
                                        <div>
                                            <dt>Date</dt>
                                            <dd>{{ $date->format('l, d F Y') }}</dd>
                                        </div>
                                    </div>
                                @endif

                                @if($hasTime)
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-clock"></i></span>
                                        <div>
                                            <dt>Time</dt>
                                            <dd>{{ $date->format('g:i A') }}</dd>
                                        </div>
                                    </div>
                                @endif

                                @if(filled($event->location))
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                                        <div>
                                            <dt>Location</dt>
                                            <dd>{{ $event->location }}</dd>
                                        </div>
                                    </div>
                                @endif

                                @if($event->creator_name)
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-user-circle"></i></span>
                                        <div>
                                            <dt>Organiser</dt>
                                            <dd>{{ $event->creator_name }}</dd>
                                        </div>
                                    </div>
                                @endif

                                @if($event->creator_phone)
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-phone-alt"></i></span>
                                        <div>
                                            <dt>Phone</dt>
                                            <dd><a href="tel:{{ $phoneHref }}">{{ $event->creator_phone }}</a></dd>
                                        </div>
                                    </div>
                                @endif

                                @if($event->creator_email)
                                    <div class="ev-fact">
                                        <span class="ev-fact__icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                                        <div>
                                            <dt>Email</dt>
                                            <dd><a href="mailto:{{ $event->creator_email }}">{{ $event->creator_email }}</a></dd>
                                        </div>
                                    </div>
                                @endif

                            </dl>

                        </aside>

                    </div>

                </div>


                {{-- WHAT'S INCLUDED --}}
                @if($event->items && $event->items->count() > 0)

                    <section class="ev-card">

                        <h2 class="ev-h">What's included with your entry</h2>

                        <div class="row g-3">

                            @foreach($event->items as $item)

                                @php $itemTitle = $item->title ?? 'Event perk'; @endphp

                                <div class="col-6 col-md-4 col-lg-3">

                                    <div class="ev-item">

                                        @if($item->image)
                                            <button
                                                type="button"
                                                class="event-item-image-button"
                                                data-image="{{ asset('storage/' . $item->image) }}"
                                                data-title="{{ $itemTitle }}"
                                                aria-label="View larger image: {{ $itemTitle }}"
                                            >
                                                <img
                                                    src="{{ asset('storage/' . $item->image) }}"
                                                    alt=""
                                                    loading="lazy"
                                                >
                                            </button>
                                        @else
                                            <div class="ev-item__placeholder" aria-hidden="true">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                        @endif

                                        <p class="ev-item__title">{{ $itemTitle }}</p>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- MAP --}}
                @if($hasMap)

                    <section class="ev-card" id="location">

                        <div class="ev-map-head">

                            <h2 class="ev-h mb-0">Location</h2>

                            <a
                                class="ev-btn ev-btn--outline ev-btn--sm"
                                href="https://www.google.com/maps/dir/?api=1&destination={{ $event->latitude }},{{ $event->longitude }}"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="fas fa-location-arrow" aria-hidden="true"></i>
                                <span>Get directions</span>
                            </a>

                        </div>

                        <div id="event-details-map" role="region" aria-label="Map showing the event location"></div>

                        @if(filled($event->location))
                            <p class="ev-map-address">
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                <span>{{ $event->location }}</span>
                            </p>
                        @endif

                    </section>

                @endif


                {{-- TICKETS --}}
                <section id="tickets" class="ev-tickets" aria-label="Tickets">

                    @if($errors->any())
                        <div class="ev-callout ev-callout--error" role="alert">
                            <span class="ev-callout__icon" aria-hidden="true"><i class="fas fa-exclamation-circle"></i></span>
                            <div>
                                <h2>Check your details</h2>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($event->status === 'live')

                        @if($soldOut)

                            <div class="ev-callout ev-callout--error">
                                <span class="ev-callout__icon" aria-hidden="true"><i class="fas fa-ticket-alt"></i></span>
                                <div>
                                    <h2>This event is sold out</h2>
                                    <p>Every ticket has been taken, so registration is closed for now.</p>
                                </div>
                            </div>

                        @elseif($event->ticketCategories->isEmpty())

                            <div class="ev-callout">
                                <span class="ev-callout__icon" aria-hidden="true"><i class="fas fa-ticket-alt"></i></span>
                                <div>
                                    <h2>No tickets on sale yet</h2>
                                    <p>The organiser hasn't added ticket types for this event. Please check back soon.</p>
                                </div>
                            </div>

                        @else

                            {{-- STEP 1: CHOOSE TICKETS --}}
                            <div class="ev-card">

                                <div class="ev-steps" id="ev-steps" data-step="1">

                                    <span class="ev-step" data-s="1">
                                        <span class="ev-step__n">1</span>
                                        Choose tickets
                                    </span>

                                    <span class="ev-steps__line" aria-hidden="true"></span>

                                    <span class="ev-step" data-s="2">
                                        <span class="ev-step__n">2</span>
                                        Participant details
                                    </span>

                                </div>

                                <h2 class="ev-h">Choose your tickets</h2>

                                <div class="ev-cats">

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

                                        <div class="ev-cat {{ $isCategorySoldOut ? 'is-soldout' : '' }}">

                                            <div class="ev-cat__shape">

                                                <div class="ev-cat__main">

                                                    <h3 class="ev-cat__name">{{ $category->name }}</h3>

                                                    <p class="ev-cat__price">{{ number_format($category->local_price) }} MMK</p>

                                                    @if($isCategorySoldOut)
                                                        <span class="ev-tag ev-tag--out">Sold out</span>
                                                    @elseif($categoryRemaining !== null)
                                                        <span class="ev-tag {{ $categoryRemaining <= 10 ? 'ev-tag--low' : '' }}">
                                                            {{ $categoryRemaining <= 10 ? 'Only ' . $categoryRemaining . ' left' : $categoryRemaining . ' left' }}
                                                        </span>
                                                    @endif

                                                </div>

                                                <div class="ev-cat__stub">

                                                    <div class="ev-stepper" role="group" aria-label="Quantity for {{ $category->name }}">

                                                        <button
                                                            class="btn-minus"
                                                            type="button"
                                                            data-id="{{ $category->id }}"
                                                            aria-label="Remove one {{ $category->name }} ticket"
                                                        >
                                                            <i class="fas fa-minus" aria-hidden="true"></i>
                                                        </button>

                                                        <input
                                                            type="number"
                                                            class="ticket-qty"
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
                                                            aria-label="{{ $category->name }} quantity"
                                                        >

                                                        <button
                                                            class="btn-plus"
                                                            type="button"
                                                            data-id="{{ $category->id }}"
                                                            aria-label="Add one {{ $category->name }} ticket"
                                                            @if($isCategorySoldOut) disabled @endif
                                                        >
                                                            <i class="fas fa-plus" aria-hidden="true"></i>
                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                                {{-- GROUP BUY: optional deals --}}
                                @if($groupBuys->isNotEmpty())

                                    <div class="ev-gb" id="ev-gb">

                                        <div class="ev-gb__head">
                                            <h3>Buying as a group?</h3>
                                            <p>Pick a deal to save when you buy tickets together. Or skip this and buy single tickets above.</p>
                                        </div>

                                        <div class="ev-gb__list">

                                            @foreach($groupBuys as $deal)

                                                @php
                                                    $dealQty  = (int) $deal['quantity'];
                                                    $dealName = filled($deal['name']) ? $deal['name'] : $dealQty . '-ticket group';

                                                    if ($deal['ticket_scope'] === 'selected_categories') {
                                                        $dealScopeNames = collect($deal['allowed_ticket_category_ids'] ?? [])
                                                            ->map(fn ($id) => $categoryNames[$id] ?? null)
                                                            ->filter()
                                                            ->implode(', ');

                                                        $dealScopeText = 'Mix of: ' . $dealScopeNames;
                                                    } else {
                                                        $dealScopeText = 'All the same ticket type';
                                                    }
                                                @endphp

                                                <button
                                                    type="button"
                                                    class="ev-gb-deal"
                                                    data-deal-id="{{ $deal['id'] }}"
                                                    aria-pressed="false"
                                                >
                                                    <span class="ev-gb-deal__name">{{ $dealName }}</span>
                                                    <span class="ev-gb-deal__save">Save {{ $formatMoney($deal['discount_amount']) }} MMK</span>
                                                    <span class="ev-gb-deal__meta">Buy {{ $dealQty }} tickets. {{ $dealScopeText }}.</span>
                                                    <span class="ev-gb-deal__state">Select</span>
                                                </button>

                                            @endforeach

                                        </div>

                                        <p class="ev-gb__status" id="ev-gb-status" role="status" aria-live="polite" hidden></p>

                                        <button type="button" class="ev-gb__clear" id="ev-gb-clear" hidden>Remove group buy</button>

                                    </div>

                                @endif

                            </div>

                            {{-- STEP 2: PARTICIPANT DETAILS --}}
                            <div id="checkout-section" style="display: none;">

                                <form
                                    action="{{ route('events.waiver', $event) }}"
                                    method="POST"
                                    id="checkout-form"
                                >
                                    @csrf

                                    <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">

                                    {{-- GROUP BUY: set by the script when a deal is chosen --}}
                                    <input type="hidden" name="group_buy_id" id="group_buy_id" value="" disabled>

                                    <h2 class="ev-h ev-h--plain">Participant details</h2>

                                    <div id="attendee-forms-container"></div>

                                    <div class="checkout-summary">

                                        <div class="row align-items-center g-3">

                                            <div class="col-md-6">
                                                <p class="ev-summary-note">
                                                    Check each participant's details before you continue. The next step is the waiver.
                                                </p>
                                            </div>

                                            <div class="col-md-6">

                                                <div class="summary-line">
                                                    <span>Subtotal</span>
                                                    <span class="fw-bold"><span id="subtotal-amount">0</span> MMK</span>
                                                </div>

                                                <div class="summary-line summary-line--discount" id="discount-row" style="display: none;">
                                                    <span id="discount-label">Discount</span>
                                                    <span class="fw-bold">- <span id="discount-amount">0</span> MMK</span>
                                                </div>

                                                <div class="summary-line total-line">
                                                    <span>Total</span>
                                                    <span class="ev-total"><span id="grand-total">0</span> MMK</span>
                                                </div>

                                                <button type="submit" class="ev-btn ev-btn--primary ev-btn--block payment-btn">
                                                    <span>Continue to waiver</span>
                                                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        @endif

                    @elseif($event->status === 'upcoming')

                        <div class="ev-callout">
                            <span class="ev-callout__icon" aria-hidden="true"><i class="fas fa-clock"></i></span>
                            <div>
                                <h2>Registration isn't open yet</h2>
                                <p>Tickets go on sale when the event goes live. Check back then to book your place.</p>
                            </div>
                        </div>

                    @else

                        <div class="ev-callout">
                            <span class="ev-callout__icon" aria-hidden="true"><i class="fas fa-flag-checkered"></i></span>
                            <div>
                                <h2>This event has ended</h2>
                                <p>Registration is closed. See what's coming up next.</p>
                                <a href="{{ url('/') }}#events-section" class="ev-btn ev-btn--outline ev-btn--sm">Browse events</a>
                            </div>
                        </div>

                    @endif

                </section>

            </div>

        </div>

    </main>


    {{-- FLOATING SELECTION BAR (appears after you pick tickets) --}}
    @if($event->status === 'live' && ! $soldOut && $event->ticketCategories->isNotEmpty())
        <div class="ev-bar" id="ev-bar" aria-hidden="true">
            <div class="ev-bar__info">
                <strong id="ev-bar-count">0 tickets</strong>
                <span><span id="ev-bar-total">0</span> MMK</span>
            </div>
            <a href="#checkout-section" class="ev-btn ev-btn--primary ev-btn--sm">Add details</a>
        </div>
    @endif


    {{-- IMAGE MODAL --}}
    <div
        id="event-image-modal"
        class="event-image-modal"
        role="dialog"
        aria-modal="true"
        aria-label="Image preview"
        aria-hidden="true"
    >
        <div class="event-image-modal-content">
            <button type="button" class="event-image-modal-close" id="event-image-modal-close" aria-label="Close image">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <img id="event-modal-image" src="" alt="">
            <div id="event-modal-title" class="event-image-modal-title"></div>
        </div>
    </div>

</div>

@endsection


{{-- =========================================================
STYLES
========================================================= --}}

@push('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">

<style>

/* =========================================================
   TOKENS (shared look with the home page)
========================================================= */

.ev {
    --ev-ink: #17122b;
    --ev-muted: #6b6580;
    --ev-pink: #db2777;
    --ev-pink-dark: #be185d;
    --ev-violet: #7c3aed;
    --ev-live: #e11d48;
    --ev-line: #e6e1f3;
    --ev-field: #d9d3ea;
    --ev-bg: #faf8ff;
    --ev-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    color: var(--ev-ink);
}

html {
    scroll-behavior: smooth;
}

.ev [id] {
    scroll-margin-top: 90px;
}


/* =========================================================
   HERO
========================================================= */

.ev-hero {
    position: relative;
    padding: clamp(1.25rem, 3vw, 2rem) 0 clamp(4.5rem, 9vw, 7rem);
    overflow-x: clip;
    background:
        radial-gradient(55% 80% at 88% 40%, rgba(124, 58, 237, 0.42), transparent 70%),
        var(--ev-ink);
    color: #fff;
}

.ev-hero__row {
    align-items: center;
    row-gap: 3rem;
}

.ev-back {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    min-height: 2.75rem;
    margin-bottom: 1.25rem;
    color: rgba(255, 255, 255, 0.82);
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.ev-back:hover {
    color: #fff;
}

.ev-back i {
    font-size: 0.75rem;
}

.ev-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.4rem 0.95rem;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.08);
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.2;
}

.ev-badge__dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 50%;
    background: #c4b5fd;
}

.ev-badge--live .ev-badge__dot {
    background: #ff3b5c;
    animation: ev-pulse 1.8s ease-out infinite;
}

.ev-badge--ended .ev-badge__dot {
    background: #8b86a0;
}

@keyframes ev-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255, 59, 92, 0.65); }
    100% { box-shadow: 0 0 0 0.65rem rgba(255, 59, 92, 0); }
}

.ev-title {
    margin: 1.15rem 0 1.25rem;
    color: #fff;
    font-family: var(--ev-display);
    font-size: clamp(2.1rem, 5vw, 3.9rem);
    font-weight: 800;
    line-height: 1.04;
    letter-spacing: -0.025em;
    overflow-wrap: anywhere;
    text-wrap: balance;
}

.ev-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin: 0 0 1.1rem;
    padding: 0;
    list-style: none;
}

.ev-chips li {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    max-width: 100%;
    padding: 0.6rem 1rem;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.08);
    font-size: 1rem;
    line-height: 1.3;
}

.ev-chips i {
    color: #f9a8d4;
}

.ev-by {
    margin: 0 0 1.75rem;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1rem;
}

.ev-by strong {
    color: #fff;
}

.ev-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}


/* ---------- Buttons ---------- */

.ev-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    min-height: 3rem;
    padding: 0.75rem 1.6rem;
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

.ev-btn:focus-visible {
    outline: 3px solid var(--ev-violet);
    outline-offset: 3px;
}

.ev-hero .ev-btn:focus-visible {
    outline-color: #fff;
}

.ev-btn--sm {
    min-height: 2.6rem;
    padding: 0.6rem 1.2rem;
    font-size: 0.95rem;
}

.ev-btn--block {
    width: 100%;
}

.ev-btn--primary {
    background: var(--ev-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.3);
}

.ev-btn--primary:hover:not(:disabled) {
    background: var(--ev-pink-dark);
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(219, 39, 119, 0.4);
}

.ev-btn--primary:disabled {
    opacity: 0.7;
    cursor: wait;
}

.ev-btn--primary i {
    font-size: 0.8rem;
    transition: transform 0.25s ease;
}

.ev-btn--primary:hover:not(:disabled) i {
    transform: translateX(3px);
}

.ev-hero .ev-btn--primary:hover i {
    transform: translateY(3px);
}

.ev-btn--ghost {
    border-color: rgba(255, 255, 255, 0.4);
    color: #fff !important;
}

.ev-btn--ghost:hover {
    border-color: #fff;
    background: rgba(255, 255, 255, 0.12);
}

.ev-btn--outline {
    border-color: var(--ev-ink);
    color: var(--ev-ink) !important;
}

.ev-btn--outline:hover {
    background: var(--ev-ink);
    color: #fff !important;
}


/* ---------- Hero ticket ---------- */

.ev-ticket-wrap {
    max-width: 440px;
    margin-inline: auto;
    transform: rotate(-2deg);
    filter: drop-shadow(0 28px 36px rgba(8, 4, 24, 0.5));
    animation: ev-drop 0.9s cubic-bezier(0.2, 0.9, 0.25, 1.05) both;
}

@keyframes ev-drop {
    from { opacity: 0; transform: translateY(-30px) rotate(-7deg); }
    to   { opacity: 1; transform: translateY(0) rotate(-2deg); }
}

.ev-ticket {
    --stub: 124px;
    --notch: 14px;

    overflow: hidden;
    border-radius: 22px;
    background: #fbf9ff;

    -webkit-mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--stub)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--stub)), #0000 97%, #000) right / 51% 100% no-repeat;
            mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--stub)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--stub)), #0000 97%, #000) right / 51% 100% no-repeat;
}

.ev-ticket__media {
    aspect-ratio: 16 / 11;
    background: #e9e3f8;
}

.ev-ticket__media img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ev-ticket__stub {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.2rem;
    height: var(--stub);
    padding: 0 1.25rem;
    color: var(--ev-ink);
    text-align: center;
}

.ev-ticket__stub::before {
    content: "";
    position: absolute;
    top: 0;
    left: 24px;
    right: 24px;
    border-top: 2px dashed rgba(23, 18, 43, 0.22);
}

.ev-stub__label {
    margin: 0;
    color: var(--ev-muted);
    font-size: 0.9rem;
    font-weight: 600;
}

.ev-stub__value {
    margin: 0;
    font-family: var(--ev-display);
    font-size: clamp(1.7rem, 4vw, 2.2rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.ev-stub__value small {
    color: var(--ev-muted);
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0;
}

.ev-stub__value--sm {
    font-size: 1.2rem;
    line-height: 1.25;
}


/* =========================================================
   MAIN
========================================================= */

.ev-main {
    position: relative;
    z-index: 3;
    margin-top: -2rem;
    padding: clamp(2rem, 5vw, 3.25rem) 0 clamp(7rem, 12vw, 9rem);
    border-radius: 32px 32px 0 0;
    background: var(--ev-bg);
}

.ev-stack {
    display: grid;
    gap: 1.5rem;
}

.ev-card {
    padding: clamp(1.25rem, 3vw, 1.9rem);
    border: 1px solid var(--ev-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.ev-h {
    margin: 0 0 1.1rem;
    font-family: var(--ev-display);
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.015em;
}

.ev-h--plain {
    margin-bottom: 1.25rem;
}

.ev-prose {
    max-width: 68ch;
    color: #4b4563;
    font-size: 1.0625rem;
    line-height: 1.75;
    overflow-wrap: anywhere;
}


/* ---------- Facts ---------- */

.ev-facts dl {
    display: grid;
    gap: 1.1rem;
    margin: 0;
}

.ev-fact {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
}

.ev-fact__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 12px;
    background: #f3edfc;
    color: var(--ev-pink);
}

.ev-fact dt {
    margin: 0;
    color: var(--ev-muted);
    font-size: 0.875rem;
    font-weight: 600;
}

.ev-fact dd {
    margin: 0.1rem 0 0;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.ev-fact a {
    color: var(--ev-ink);
    text-decoration: underline;
    text-decoration-color: rgba(23, 18, 43, 0.3);
    text-underline-offset: 3px;
}

.ev-fact a:hover {
    text-decoration-color: var(--ev-ink);
}


/* ---------- Included items ---------- */

.ev-item {
    height: 100%;
}

.event-item-image-button,
.ev-item__placeholder {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 4 / 3;
    margin: 0 0 0.7rem;
    padding: 0;
    overflow: hidden;
    border: 0;
    border-radius: 14px;
    background: #f0eafc;
}

.event-item-image-button {
    cursor: zoom-in;
}

.event-item-image-button img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.event-item-image-button:hover img {
    transform: scale(1.06);
}

.event-item-image-button::after {
    content: "\f00e";
    position: absolute;
    right: 10px;
    bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--ev-pink);
    color: #fff;
    font-family: "Font Awesome 5 Free";
    font-size: 0.75rem;
    font-weight: 900;
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.event-item-image-button:hover::after,
.event-item-image-button:focus-visible::after {
    opacity: 1;
    transform: scale(1);
}

.event-item-image-button:focus-visible {
    outline: 3px solid var(--ev-violet);
    outline-offset: 3px;
}

.ev-item__placeholder {
    display: grid;
    place-items: center;
    color: #a78bfa;
    font-size: 1.6rem;
}

.ev-item__title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1.4;
}


/* ---------- Map ---------- */

.ev-map-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
    margin-bottom: 1.1rem;
}

#event-details-map {
    width: 100%;
    height: 380px;
    overflow: hidden;
    border: 1px solid var(--ev-line);
    border-radius: 16px;
    background: #eef1f5;
}

.ev-map-address {
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    margin: 0.9rem 0 0;
    color: #4b4563;
    font-weight: 600;
}

.ev-map-address i {
    margin-top: 0.25rem;
    color: var(--ev-pink);
}

.leaflet-container {
    font-family: inherit;
    z-index: 1;
}

.ev-pin-wrap {
    background: transparent;
    border: 0;
}

.ev-pin {
    position: relative;
    display: block;
    width: 34px;
    height: 34px;
    border: 3px solid #fff;
    border-radius: 50% 50% 50% 0;
    background: var(--ev-pink);
    box-shadow: 0 6px 14px rgba(23, 18, 43, 0.35);
    transform: rotate(-45deg);
}

.ev-pin::after {
    content: "";
    position: absolute;
    inset: 9px;
    border-radius: 50%;
    background: #fff;
}


/* =========================================================
   TICKETS
========================================================= */

.ev-tickets {
    display: grid;
    gap: 1.5rem;
}

/* ---------- Steps ---------- */

.ev-steps {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.ev-step {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    color: var(--ev-muted);
    font-size: 0.95rem;
    font-weight: 600;
}

.ev-step__n {
    display: grid;
    place-items: center;
    width: 1.9rem;
    height: 1.9rem;
    border: 1.5px solid var(--ev-field);
    border-radius: 50%;
    background: #fff;
    font-size: 0.875rem;
    font-weight: 700;
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}

.ev-steps__line {
    flex: 1 1 2rem;
    max-width: 4rem;
    height: 2px;
    background: var(--ev-line);
}

.ev-steps[data-step="1"] .ev-step[data-s="1"],
.ev-steps[data-step="2"] .ev-step[data-s="2"] {
    color: var(--ev-ink);
}

.ev-steps[data-step="1"] .ev-step[data-s="1"] .ev-step__n,
.ev-steps[data-step="2"] .ev-step[data-s="2"] .ev-step__n {
    border-color: var(--ev-pink);
    background: var(--ev-pink);
    color: #fff;
}

.ev-steps[data-step="2"] .ev-step[data-s="1"] .ev-step__n {
    border-color: var(--ev-ink);
    background: var(--ev-ink);
    color: #fff;
    font-size: 0;
}

.ev-steps[data-step="2"] .ev-step[data-s="1"] .ev-step__n::after {
    content: "\f00c";
    font-family: "Font Awesome 5 Free";
    font-size: 0.7rem;
    font-weight: 900;
}


/* ---------- Ticket categories ---------- */

.ev-cats {
    display: grid;
    gap: 1rem;
}

.ev-cat {
    --stub: 176px;
    --n: 12px;

    filter:
        drop-shadow(0 1px 2px rgba(23, 18, 43, 0.10))
        drop-shadow(0 8px 16px rgba(23, 18, 43, 0.06));
}

.ev-cat__shape {
    display: grid;
    grid-template-columns: minmax(0, 1fr) var(--stub);
    overflow: hidden;
    border-radius: 18px;
    background: #fff;

    -webkit-mask:
        radial-gradient(circle var(--n) at calc(100% - var(--stub)) 0, #0000 97%, #000) top / 100% 51% no-repeat,
        radial-gradient(circle var(--n) at calc(100% - var(--stub)) 100%, #0000 97%, #000) bottom / 100% 51% no-repeat;
            mask:
        radial-gradient(circle var(--n) at calc(100% - var(--stub)) 0, #0000 97%, #000) top / 100% 51% no-repeat,
        radial-gradient(circle var(--n) at calc(100% - var(--stub)) 100%, #0000 97%, #000) bottom / 100% 51% no-repeat;
}

.ev-cat__main {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.35rem;
    padding: 1.15rem 1.4rem;
}

.ev-cat__name {
    margin: 0;
    font-family: var(--ev-display);
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1.2;
    overflow-wrap: anywhere;
}

.ev-cat__price {
    margin: 0;
    color: var(--ev-pink);
    font-size: 1.05rem;
    font-weight: 700;
}

.ev-tag {
    display: inline-block;
    margin-top: 0.15rem;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    background: #ecfdf3;
    color: #166534;
    font-size: 0.8125rem;
    font-weight: 700;
}

.ev-tag--low {
    background: #fff4e0;
    color: #92400e;
}

.ev-tag--out {
    background: #ffe4e6;
    color: #9f1239;
}

.ev-cat.is-soldout .ev-cat__name,
.ev-cat.is-soldout .ev-cat__price {
    opacity: 0.5;
}

/* GROUP BUY: a ticket type that the chosen deal doesn't allow */
.ev-cat.is-dim {
    opacity: 0.5;
}

.ev-cat__stub {
    display: flex;
    align-items: center;
    justify-content: center;
    border-left: 2px dashed rgba(23, 18, 43, 0.2);
}

.ev-stepper {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.ev-stepper button {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.5rem;
    height: 2.5rem;
    padding: 0;
    border: 1.5px solid var(--ev-field);
    border-radius: 50%;
    background: #fff;
    color: var(--ev-ink);
    font-size: 0.8rem;
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}

.ev-stepper button:hover:not(:disabled) {
    border-color: var(--ev-pink);
    background: var(--ev-pink);
    color: #fff;
}

.ev-stepper button:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

.ev-stepper button:focus-visible {
    outline: 3px solid var(--ev-violet);
    outline-offset: 2px;
}

.ev-stepper .ticket-qty {
    width: 2.6rem;
    height: 2.5rem;
    padding: 0;
    border: 0;
    background: transparent;
    color: var(--ev-ink);
    font-family: var(--ev-display);
    font-size: 1.35rem;
    font-weight: 800;
    text-align: center;
    -moz-appearance: textfield;
    appearance: textfield;
}

.ev-stepper .ticket-qty::-webkit-outer-spin-button,
.ev-stepper .ticket-qty::-webkit-inner-spin-button {
    margin: 0;
    -webkit-appearance: none;
}

.ev-stepper .ticket-qty:focus {
    outline: none;
}


/* ---------- Group buy deals ---------- */

.ev-gb {
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 2px dashed var(--ev-line);
}

.ev-gb__head h3 {
    margin: 0 0 0.25rem;
    font-family: var(--ev-display);
    font-size: 1.2rem;
    font-weight: 800;
}

.ev-gb__head p {
    margin: 0 0 1rem;
    color: var(--ev-muted);
}

.ev-gb__list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 0.9rem;
}

.ev-gb-deal {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.3rem;
    width: 100%;
    padding: 1rem 1.15rem;
    border: 1.5px solid var(--ev-field);
    border-radius: 16px;
    background: #fff;
    color: var(--ev-ink);
    text-align: left;
    cursor: pointer;
    transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
}

.ev-gb-deal:hover:not(:disabled) {
    border-color: var(--ev-pink);
}

.ev-gb-deal:focus-visible {
    outline: 3px solid var(--ev-violet);
    outline-offset: 2px;
}

.ev-gb-deal:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.ev-gb-deal.is-active {
    border-color: var(--ev-pink);
    background: #fdf2f8;
    box-shadow: 0 0 0 3px rgba(219, 39, 119, 0.15);
}

.ev-gb-deal__name {
    font-family: var(--ev-display);
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.25;
    overflow-wrap: anywhere;
}

.ev-gb-deal__save {
    color: var(--ev-pink);
    font-weight: 700;
}

.ev-gb-deal__meta {
    color: var(--ev-muted);
    font-size: 0.9rem;
    line-height: 1.45;
}

.ev-gb-deal__state {
    margin-top: 0.35rem;
    padding: 0.2rem 0.7rem;
    border-radius: 999px;
    background: #f3edfc;
    color: var(--ev-violet);
    font-size: 0.8rem;
    font-weight: 700;
}

.ev-gb-deal.is-active .ev-gb-deal__state {
    background: var(--ev-pink);
    color: #fff;
}

.ev-gb__status {
    margin: 1rem 0 0;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    background: #f3edfc;
    color: #4b4563;
    font-weight: 600;
}

.ev-gb__status.is-ready {
    background: #ecfdf3;
    color: #166534;
}

.ev-gb__clear {
    margin-top: 0.6rem;
    padding: 0;
    border: 0;
    background: none;
    color: var(--ev-muted);
    font-weight: 600;
    text-decoration: underline;
    cursor: pointer;
}

.ev-gb__clear:hover {
    color: var(--ev-ink);
}

.ev-gb__clear:focus-visible {
    outline: 3px solid var(--ev-violet);
    outline-offset: 3px;
}


/* ---------- Callouts ---------- */

.ev-callout {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border: 1.5px solid var(--ev-line);
    border-radius: 22px;
    background: #fff;
}

.ev-callout__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 3rem;
    height: 3rem;
    border-radius: 14px;
    background: #f3edfc;
    color: var(--ev-pink);
    font-size: 1.2rem;
}

.ev-callout h2 {
    margin: 0 0 0.3rem;
    font-family: var(--ev-display);
    font-size: 1.3rem;
    font-weight: 800;
}

.ev-callout p {
    margin: 0;
    color: var(--ev-muted);
}

.ev-callout p + .ev-btn {
    margin-top: 1rem;
}

.ev-callout ul {
    margin: 0;
    padding-left: 1.1rem;
    color: #7f1d33;
}

.ev-callout--error {
    border-color: #fecdd3;
    background: #fff1f2;
}

.ev-callout--error .ev-callout__icon {
    background: #ffe4e6;
    color: var(--ev-live);
}


/* =========================================================
   PARTICIPANT FORMS (markup is generated by the script below)
========================================================= */

.ev .attendee-card {
    position: relative;
    margin-bottom: 1.25rem;
    overflow: hidden;
    border: 1px solid var(--ev-line);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.attendee-card__head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem 1rem;
    padding: 1rem 1.4rem;
    background: var(--ev-ink);
    color: #fff;
}

.attendee-card__title {
    margin: 0;
    color: #fff;
    font-family: var(--ev-display);
    font-size: 1.15rem;
    font-weight: 800;
}

.attendee-card__cat {
    padding: 0.3rem 0.8rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.14);
    font-size: 0.875rem;
    font-weight: 600;
}

.attendee-card__body {
    padding: 1.4rem;
}

.ev .attendee-section {
    margin-bottom: 1.6rem;
}

.ev .attendee-section:last-child {
    margin-bottom: 0;
}

.ev .attendee-section-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin: 0 0 1rem;
    padding-bottom: 0.65rem;
    border-bottom: 1px solid var(--ev-line);
    font-family: var(--ev-display);
    font-size: 1.05rem;
    font-weight: 800;
}

.ev .attendee-section-title i {
    color: var(--ev-pink);
    font-size: 0.95rem;
}

.ev .form-label {
    margin-bottom: 0.4rem;
    color: var(--ev-ink);
    font-size: 0.9rem;
    font-weight: 600;
}

.ev .required-star {
    color: var(--ev-pink);
    font-weight: 800;
}

.ev .attendee-card .form-control:not(textarea),
.ev .attendee-card .form-select {
    height: 3rem;
    min-height: 3rem;
    padding: 0.5rem 1rem;
    border: 1.5px solid var(--ev-field);
    border-radius: 12px;
    background-color: #fff;
    color: var(--ev-ink);
    font-size: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ev .attendee-card .form-select {
    padding-right: 2.25rem;
}

.ev .attendee-card textarea.form-control {
    min-height: 5.5rem;
    padding: 0.75rem 1rem;
    border: 1.5px solid var(--ev-field);
    border-radius: 12px;
    background-color: #fff;
    color: var(--ev-ink);
    font-size: 1rem;
    resize: vertical;
}

.ev .attendee-card .form-control::placeholder,
.ev .attendee-card textarea::placeholder {
    color: #8a849c;
}

.ev .attendee-card .form-control:focus,
.ev .attendee-card .form-select:focus,
.ev .attendee-card textarea.form-control:focus {
    border-color: var(--ev-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.18);
    outline: none;
}

.ev .promo-input-wrap {
    display: flex;
    align-items: center;
    height: 3rem;
    overflow: hidden;
    border: 1.5px solid var(--ev-field);
    border-radius: 12px;
    background: #fff;
}

.ev .promo-input-wrap .form-control {
    border: 0 !important;
    box-shadow: none !important;
}

.ev .btn-apply-promo {
    height: 100%;
    padding: 0 1.1rem;
    border: 0;
    background: var(--ev-pink);
    color: #fff;
    font-size: 0.9rem;
    font-weight: 700;
    white-space: nowrap;
}


/* ---------- Summary ---------- */

.ev .checkout-summary {
    margin-top: 1.5rem;
    padding: clamp(1.25rem, 3vw, 1.75rem);
    border: 1px solid var(--ev-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.06);
}

.ev-summary-note {
    margin: 0;
    max-width: 38ch;
    color: var(--ev-muted);
    line-height: 1.6;
}

.ev .summary-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.65rem;
    color: #4b4563;
}

.ev .summary-line--discount {
    color: #166534;
}

.ev .total-line {
    margin: 0.9rem 0 1.25rem;
    padding-top: 1rem;
    border-top: 2px dashed var(--ev-line);
    color: var(--ev-ink);
    font-weight: 700;
}

.ev-total {
    font-family: var(--ev-display);
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.02em;
}


/* =========================================================
   FLOATING BAR
========================================================= */

.ev-bar {
    position: fixed;
    bottom: max(14px, env(safe-area-inset-bottom));
    left: 50%;
    z-index: 1030;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    width: min(640px, calc(100% - 24px));
    padding: 0.7rem 0.7rem 0.7rem 1.4rem;
    border-radius: 999px;
    background: var(--ev-ink);
    box-shadow: 0 18px 40px rgba(8, 4, 24, 0.4);
    color: #fff;
    transform: translate(-50%, 160%);
    visibility: hidden;
    transition: transform 0.3s ease, visibility 0s linear 0.3s;
}

.ev-bar.is-visible {
    transform: translate(-50%, 0);
    visibility: visible;
    transition: transform 0.3s ease;
}

.ev-bar__info {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}

.ev-bar__info span {
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.9rem;
}


/* =========================================================
   IMAGE MODAL
========================================================= */

.event-image-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(23, 18, 43, 0.92);
    backdrop-filter: blur(8px);
}

.event-image-modal.active {
    display: flex;
}

.event-image-modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: min(950px, 95vw);
    max-height: 92vh;
}

.event-image-modal img {
    display: block;
    width: auto;
    max-width: 100%;
    height: auto;
    max-height: 82vh;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 20px 70px rgba(0, 0, 0, 0.6);
    object-fit: contain;
}

.event-image-modal-title {
    margin-top: 0.8rem;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    text-align: center;
}

.event-image-modal-close {
    position: absolute;
    top: -16px;
    right: -16px;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 50%;
    background: var(--ev-pink);
    color: #fff;
    font-size: 1.05rem;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    transition: background-color 0.2s ease;
}

.event-image-modal-close:hover {
    background: var(--ev-pink-dark);
}

.event-image-modal-close:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 3px;
}


/* =========================================================
   SMALL SCREENS
========================================================= */

@media (max-width: 767px) {

    #event-details-map {
        height: 280px;
    }

    .ev-actions .ev-btn {
        flex: 1 1 100%;
    }

    .ev-cat {
        --stub: 150px;
    }

    .ev-cat__main {
        padding: 1rem 1.1rem;
    }

    .ev-callout {
        flex-direction: column;
    }

    .attendee-card__head,
    .attendee-card__body {
        padding-inline: 1.1rem;
    }

    .ev-bar {
        padding-left: 1.1rem;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    html {
        scroll-behavior: auto;
    }

    .ev *,
    .ev *::before,
    .ev *::after {
        animation: none !important;
        transition: none !important;
    }

}

</style>

@endpush


{{-- =========================================================
SCRIPTS
========================================================= --}}

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

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

/* Escapes text before it goes into generated HTML */
function esc(value) {
    const div = document.createElement('div');
    div.textContent = value == null ? '' : String(value);
    return div.innerHTML;
}

@if($hasMap)
/* MAP */
document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('event-details-map');
    if (!mapElement || typeof L === 'undefined') return;

    const lat = parseFloat(@json($event->latitude));
    const lng = parseFloat(@json($event->longitude));
    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

    const eventMap = L.map(mapElement, { scrollWheelZoom: false }).setView([lat, lng], 16);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(eventMap);

    const pin = L.divIcon({
        className: 'ev-pin-wrap',
        html: '<span class="ev-pin"></span>',
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -30]
    });

    const popup = document.createElement('div');
    const popupTitle = document.createElement('strong');
    popupTitle.textContent = @json($event->title);
    popup.appendChild(popupTitle);
    popup.appendChild(document.createElement('br'));
    popup.appendChild(document.createTextNode(@json($event->location ?? '')));

    L.marker([lat, lng], { icon: pin }).addTo(eventMap).bindPopup(popup);

    setTimeout(function () { eventMap.invalidateSize(); }, 300);
});
@endif

/* IMAGE POPUP */
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('event-image-modal');
    const modalImage = document.getElementById('event-modal-image');
    const modalTitle = document.getElementById('event-modal-title');
    const closeButton = document.getElementById('event-image-modal-close');
    let lastFocused = null;

    if (!modal || !modalImage) return;

    document.querySelectorAll('.event-item-image-button').forEach(function (button) {
        button.addEventListener('click', function () {
            const title = this.getAttribute('data-title') || '';

            lastFocused = this;
            modalImage.src = this.getAttribute('data-image');
            modalImage.alt = title;
            modalTitle.textContent = title;

            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            if (closeButton) closeButton.focus();
        });
    });

    function closeImageModal() {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        modalImage.src = '';
        document.body.style.overflow = '';

        if (lastFocused) lastFocused.focus();
    }

    if (closeButton) closeButton.addEventListener('click', closeImageModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeImageModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeImageModal();
    });
});

/* TICKETS + PARTICIPANT FORMS */
document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.ticket-qty');
    const container = document.getElementById('attendee-forms-container');
    const checkoutSection = document.getElementById('checkout-section');
    const checkoutForm = document.getElementById('checkout-form');
    const subtotalEl = document.getElementById('subtotal-amount');
    const discountEl = document.getElementById('discount-amount');
    const grandTotalEl = document.getElementById('grand-total');
    const discountRow = document.getElementById('discount-row');
    const discountLabel = document.getElementById('discount-label');

    const stepsEl = document.getElementById('ev-steps');
    const bar = document.getElementById('ev-bar');
    const barCount = document.getElementById('ev-bar-count');
    const barTotal = document.getElementById('ev-bar-total');

    let appliedPromos = {};
    let checkoutVisible = false;

    /* ---------------------------------------------------------
       GROUP BUY
       A deal fixes the number of tickets and which ticket types
       can be mixed. The server checks everything again at checkout.
    --------------------------------------------------------- */
    const groupBuyDeals = {!! \Illuminate\Support\Js::from($groupBuys->values()->all()) !!};

    const dealButtons = document.querySelectorAll('.ev-gb-deal');
    const dealStatus = document.getElementById('ev-gb-status');
    const dealClear = document.getElementById('ev-gb-clear');
    const groupBuyInput = document.getElementById('group_buy_id');
    const submitButton = checkoutForm ? checkoutForm.querySelector('[type="submit"]') : null;
    let activeDeal = null;

    function formatMoney(value) {
        return new Intl.NumberFormat().format(value);
    }

    function dealCategoryAllowed(deal, catId) {
        return deal.ticket_scope !== 'selected_categories' ||
            deal.allowed_ticket_category_ids.indexOf(parseInt(catId, 10)) !== -1;
    }

    function dealCategoryNames(deal) {
        const names = [];

        qtyInputs.forEach(function (input) {
            if (dealCategoryAllowed(deal, input.getAttribute('data-id'))) {
                names.push(input.getAttribute('data-name'));
            }
        });

        return names;
    }

    // How many tickets could this deal still be filled with, given stock?
    function dealCapacity(deal) {
        const maxes = [];
        let eventMax = 999;

        qtyInputs.forEach(function (input) {
            if (!dealCategoryAllowed(deal, input.getAttribute('data-id'))) return;

            maxes.push(parseInt(input.getAttribute('data-max') || 0, 10));
            eventMax = Math.min(eventMax, parseInt(input.getAttribute('data-event-max') || 999, 10));
        });

        if (!maxes.length) return 0;

        const possible = deal.ticket_scope === 'same_category'
            ? Math.max.apply(null, maxes)
            : maxes.reduce(function (a, b) { return a + b; }, 0);

        return Math.min(possible, eventMax);
    }

    // Is this ticket type unavailable to the chosen deal right now?
    function dealLocksCategory(catId) {
        if (!activeDeal) return false;
        if (!dealCategoryAllowed(activeDeal, catId)) return true;

        if (activeDeal.ticket_scope === 'same_category') {
            return Array.prototype.some.call(qtyInputs, function (input) {
                return input.getAttribute('data-id') !== String(catId) && parseInt(input.value || 0, 10) > 0;
            });
        }

        return false;
    }

    function dealBlocksAdd(catId) {
        if (!activeDeal) return false;

        return getTotalSelectedTickets() >= activeDeal.quantity || dealLocksCategory(catId);
    }

    function selectionFitsDeal(deal) {
        let total = 0;
        let used = 0;
        let ok = true;

        qtyInputs.forEach(function (input) {
            const qty = parseInt(input.value || 0, 10);
            if (qty <= 0) return;

            total += qty;
            used++;

            if (!dealCategoryAllowed(deal, input.getAttribute('data-id'))) ok = false;
        });

        if (total > deal.quantity) ok = false;
        if (deal.ticket_scope === 'same_category' && used > 1) ok = false;

        return ok;
    }

    function updateDealUI() {
        dealButtons.forEach(function (button) {
            const deal = groupBuyDeals.find(function (d) {
                return String(d.id) === button.getAttribute('data-deal-id');
            });
            if (!deal) return;

            const isActive = !!activeDeal && activeDeal.id === deal.id;
            const available = dealCapacity(deal) >= deal.quantity;
            const state = button.querySelector('.ev-gb-deal__state');

            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            button.disabled = !available && !isActive;

            if (state) {
                state.textContent = isActive ? 'Selected' : (available ? 'Select' : 'Not enough tickets left');
            }
        });

        if (dealClear) dealClear.hidden = !activeDeal;

        document.querySelectorAll('.ev-cat').forEach(function (cat) {
            const input = cat.querySelector('.ticket-qty');
            if (!input) return;

            cat.classList.toggle('is-dim', dealLocksCategory(input.getAttribute('data-id')));
        });

        if (!dealStatus) return;

        if (!activeDeal) {
            dealStatus.hidden = true;
            dealStatus.textContent = '';
            dealStatus.classList.remove('is-ready');
            return;
        }

        const total = getTotalSelectedTickets();
        const need = activeDeal.quantity;
        let message;

        if (total === 0) {
            message = activeDeal.ticket_scope === 'same_category'
                ? 'Choose ' + need + ' tickets of the same type.'
                : 'Choose ' + need + ' tickets from: ' + dealCategoryNames(activeDeal).join(', ') + '.';
        } else if (total < need) {
            message = total + ' of ' + need + ' tickets chosen. Add ' + (need - total) + ' more to get the discount.';
        } else {
            message = 'Group of ' + need + ' ready. You save ' + formatMoney(activeDeal.discount_amount) + ' MMK.';
        }

        dealStatus.textContent = message;
        dealStatus.hidden = false;
        dealStatus.classList.toggle('is-ready', total === need);
    }

    // The continue button waits until the group is complete.
    function updateSubmitState() {
        if (!submitButton) return;

        submitButton.disabled = !!activeDeal && getTotalSelectedTickets() !== activeDeal.quantity;
    }

    function setDeal(deal) {
        activeDeal = deal;

        // Promo codes can't be mixed with a group buy.
        appliedPromos = {};

        if (groupBuyInput) {
            groupBuyInput.disabled = !deal;
            groupBuyInput.value = deal ? deal.id : '';
        }

        if (deal && !selectionFitsDeal(deal)) {
            qtyInputs.forEach(function (input) { input.value = 0; });
            renderForms();
        } else {
            calculateTotals();
        }

        updateButtonStates();
    }

    dealButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const deal = groupBuyDeals.find(function (d) {
                return String(d.id) === button.getAttribute('data-deal-id');
            });
            if (!deal) return;

            setDeal(activeDeal && activeDeal.id === deal.id ? null : deal);
        });
    });

    if (dealClear) {
        dealClear.addEventListener('click', function () { setDeal(null); });
    }

    function getTotalSelectedTickets() {
        let total = 0;
        qtyInputs.forEach(input => {
            total += parseInt(input.value || 0);
        });
        return total;
    }

    /* Floating bar: shows the running total until the details form is on screen */
    function updateBar() {
        if (!bar) return;

        const count = getTotalSelectedTickets();
        const show = count > 0 && !checkoutVisible;

        if (barCount) barCount.textContent = count + (count === 1 ? ' ticket' : ' tickets');
        if (barTotal && grandTotalEl) barTotal.textContent = grandTotalEl.textContent;

        bar.classList.toggle('is-visible', show);
        bar.setAttribute('aria-hidden', show ? 'false' : 'true');
    }

    if (checkoutSection && 'IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            checkoutVisible = entries[0].isIntersecting;
            updateBar();
        }).observe(checkoutSection);
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

            button.disabled = categoryFull || eventFull || dealBlocksAdd(id);
        });

        document.querySelectorAll('.btn-minus').forEach(button => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (!input) return;

            button.disabled = parseInt(input.value || 0) <= 0;
        });

        updateDealUI();
        updateSubmitState();
        updateBar();
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

            if (currentVal < categoryMax && totalSelected < eventMax && !dealBlocksAdd(id)) {
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
            // GROUP BUY: the group must be complete before moving on.
            if (activeDeal && getTotalSelectedTickets() !== activeDeal.quantity) {
                e.preventDefault();
                return;
            }

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

            /* Stop double submits */
            const submitBtn = checkoutForm.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const label = submitBtn.querySelector('span');
                if (label) label.textContent = 'Please wait…';
            }
        });

        /* Back button restores the page from cache: make the button usable again */
        window.addEventListener('pageshow', function (e) {
            if (!e.persisted) return;

            const submitBtn = checkoutForm.querySelector('[type="submit"]');
            if (submitBtn) {
                updateSubmitState();
                const label = submitBtn.querySelector('span');
                if (label) label.textContent = 'Continue to waiver';
            }
        });
    }

    function applyAttendeePromo(index) {
        const promoInput = document.getElementById(`promo_code_${index}`);
        const msgBox = document.getElementById(`promo_msg_${index}`);
        const btn = document.querySelector(`.btn-apply-attendee-promo[data-index="${index}"]`);
        const catIdInput = document.querySelector(`input[name="attendees[${index}][ticket_category_id]"]`);

        // GROUP BUY: promo codes can't be used together with a group buy.
        if (activeDeal) {
            msgBox.textContent = 'Promo codes cannot be used with a group buy.';
            msgBox.className = 'mt-1 text-danger small';
            return;
        }

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

        // GROUP BUY: the fixed deal discount applies once the group is complete.
        let groupDiscount = 0;

        if (activeDeal && cards.length === activeDeal.quantity) {
            groupDiscount = Math.min(activeDeal.discount_amount, subtotal);
        }

        const shownDiscount = totalDiscount + groupDiscount;
        const grandTotal = Math.max(0, subtotal - shownDiscount);

        if (subtotalEl) subtotalEl.textContent = new Intl.NumberFormat().format(subtotal);
        if (grandTotalEl) grandTotalEl.textContent = new Intl.NumberFormat().format(grandTotal);

        if (discountLabel) discountLabel.textContent = activeDeal ? 'Group buy discount' : 'Discount';

        if (discountEl && discountRow) {
            if (shownDiscount > 0) {
                discountEl.textContent = new Intl.NumberFormat().format(shownDiscount);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }

        updateBar();
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
                card.className = 'attendee-card';

                let fieldsHTML = `
                    <div class="attendee-card__head">
                        <h3 class="attendee-card__title">Participant ${formIndex + 1}</h3>
                        <span class="attendee-card__cat">${esc(catName)}</span>
                    </div>

                    <div class="attendee-card__body">

                    <input type="hidden" name="attendees[${formIndex}][ticket_category_id]" value="${esc(catId)}">

                    <div class="row g-2">
                        <!-- Contact -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-address-book" aria-hidden="true"></i>
                                <span>Contact</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email address <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        name="attendees[${formIndex}][email]"
                                        class="form-control"
                                        placeholder="you@example.com"
                                        autocomplete="email"
                                        required
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Phone number <span class="required-star">*</span>
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
                                        <label class="form-label">Viber number</label>
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
                                        Emergency contact number <span class="required-star">*</span>
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

                        <!-- Personal details -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span>Personal details</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">
                                        Full name <span class="required-star">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="attendees[${formIndex}][full_name]"
                                        class="form-control"
                                        autocomplete="name"
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
                                        data-foreign="${isForeignAvailable ? esc(rawForeignPrice) : ''}"
                                        required
                                    >
                                        <option value="">Select nationality</option>
                                        <option value="Myanmar">Local (Myanmar)</option>
                                        <option value="Foreigner" ${!isForeignAvailable ? 'disabled' : ''}>
                                            ${isForeignAvailable ? 'Foreigner' : 'Foreigner (not available)'}
                                        </option>
                                    </select>
                                </div>

                                <!-- Promo Code -->
                                <div class="col-md-4" style="display: none;">
                                    <label class="form-label">Promo code (optional)</label>
                                    <div class="promo-input-wrap">
                                        <input
                                            type="text"
                                            id="promo_code_${formIndex}"
                                            class="form-control promo-input"
                                            placeholder="ENTER CODE"
                                            value="${savedPromo ? esc(savedPromo.code) : ''}"
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
                                    <label class="form-label">NRC number</label>
                                    <div class="row g-2">
                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_state]" class="form-select nrc-state-select" data-index="${formIndex}" aria-label="NRC state">
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
                                            <select name="attendees[${formIndex}][nrc_district]" id="nrc_district_${formIndex}" class="form-select" aria-label="NRC district">
                                                <option value="">District</option>
                                            </select>
                                        </div>

                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_naing]" class="form-select" aria-label="NRC type">
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
                                                aria-label="NRC number, 6 digits"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Passport -->
                                <div class="col-md-8 row g-2" id="passport-box-${formIndex}" style="display: none;">
                                    <div class="col-md-6">
                                        <label class="form-label">Passport number</label>
                                        <input
                                            type="text"
                                            name="attendees[${formIndex}][passport_number]"
                                            class="form-control"
                                            placeholder="Passport no."
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <select name="attendees[${formIndex}][country]" class="form-select">
                                            <option value="">Select country</option>
                                            ${countriesList.map(c => `<option value="${esc(c)}">${esc(c)}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="required-star">*</span></label>
                                    <select name="attendees[${formIndex}][gender]" class="form-select" required>
                                        <option value="">Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date of birth <span class="required-star">*</span></label>
                                    <input type="date" name="attendees[${formIndex}][date_of_birth]" class="form-control" required>
                                </div>

                                ${isFieldEnabled('father_name') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Father's name (optional)</label>
                                        <input type="text" name="attendees[${formIndex}][father_name]" class="form-control" placeholder="Father's full name">
                                    </div>
                                ` : ''}

                                ${isFieldEnabled('address') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="attendees[${formIndex}][address]" class="form-control" placeholder="Full address" autocomplete="street-address">
                                    </div>
                                ` : ''}
                            </div>
                        </div>

                        <!-- Participation details -->
                        <div class="col-12">
                            <div class="attendee-section">
                                <div class="attendee-section-title">
                                    <i class="fas fa-running" aria-hidden="true"></i>
                                    <span>Participation details</span>
                                </div>

                                <div class="row g-3">

                                    ${isFieldEnabled('blood_type') ? `
                                        <div class="col-md-4">
                                            <label class="form-label">Blood type <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][blood_type]" class="form-select" required>
                                                <option value="">Select blood type</option>
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
                                            <label class="form-label">T-shirt size <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][tshirt_size]" class="form-select" required>
                                                <option value="">Select size</option>
                                                ${eventTshirtSizes.map(size => `<option value="${esc(size)}">${esc(size)}</option>`).join('')}
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('experience') ? `
                                        <div class="col-md-4">
                                            <label class="form-label">Running / event experience <span class="required-star">*</span></label>
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
                                        <div class="col-md-6">
                                            <label class="form-label">Existing medical conditions? <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][has_medical_condition]" class="form-select medical-select" data-index="${formIndex}" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                        <div class="col-md-6">
                                            <label class="form-label">Do you have an ITRA profile? <span class="required-star">*</span></label>
                                            <select name="attendees[${formIndex}][itra]" class="form-select itra-select" data-index="${formIndex}" required>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('has_medical_condition') ? `
                                        <div class="col-md-6" id="medical-details-box-${formIndex}" style="display: none;">
                                            <label class="form-label">Medical condition details <span class="required-star">*</span></label>
                                            <textarea name="attendees[${formIndex}][medical_details]" class="form-control" placeholder="List any medical conditions or allergies"></textarea>
                                        </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                        <div class="col-md-6" id="itra-details-box-${formIndex}" style="display: none;">
                                            <label class="form-label">ITRA details <span class="required-star">*</span></label>
                                            <input type="text" name="attendees[${formIndex}][itra_details]" class="form-control" placeholder="Enter your ITRA details">
                                        </div>
                                    ` : ''}
                                </div>
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

        if (stepsEl) stepsEl.setAttribute('data-step', formIndex > 0 ? '2' : '1');

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