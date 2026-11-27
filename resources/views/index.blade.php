@extends('layouts.master')

@section('title', 'Event PLUS')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Featured event + hero state
    |--------------------------------------------------------------------------
    */
    $featuredEvent =
        ($liveEvents ?? collect())->first()
        ?? ($upcomingEvents ?? collect())->first()
        ?? ($pastEvents ?? collect())->first();

    $status = ! $featuredEvent
        ? 'none'
        : (($liveEvents ?? collect())->first()
            ? 'live'
            : (($upcomingEvents ?? collect())->first() ? 'upcoming' : 'past'));

    $statusLabels = [
        'live'     => 'Happening now',
        'upcoming' => 'Next event',
        'past'     => 'Past event',
        'none'     => 'No events yet',
    ];

    $start    = ($featuredEvent && $featuredEvent->event_date)
        ? \Carbon\Carbon::parse($featuredEvent->event_date)
        : null;
    $hasTime  = $start && $start->format('His') !== '000000';
    $location = ($featuredEvent && filled($featuredEvent->location ?? null))
        ? $featuredEvent->location
        : null;

    $description = $featuredEvent
        ? \Illuminate\Support\Str::limit(strip_tags($featuredEvent->description ?? ''), 200)
        : '';

    $posterUrl   = $featuredEvent && $featuredEvent->image
        ? asset('storage/' . $featuredEvent->image)
        : null;
    $fallbackUrl = asset('assets/eventen/images/team/4.png');

    // "Add to calendar" link (Google Calendar), only for events that haven't finished.
    $calendarUrl = null;
    if ($start && in_array($status, ['live', 'upcoming'], true)) {
        $calendarParams = [
            'action'   => 'TEMPLATE',
            'text'     => $featuredEvent->title,
            'details'  => $description,
            'location' => $location,
        ];

        if ($hasTime) {
            $calendarParams['dates'] = $start->format('Ymd\THis') . '/' . $start->copy()->addHours(2)->format('Ymd\THis');
            $calendarParams['ctz']   = config('app.timezone');
        } else {
            $calendarParams['dates'] = $start->format('Ymd') . '/' . $start->copy()->addDay()->format('Ymd');
        }

        $calendarUrl = 'https://calendar.google.com/calendar/render?' . http_build_query(array_filter($calendarParams));
    }
@endphp


{{-- =========================================================
HERO
========================================================= --}}

<section class="banner ep-hero position-relative pb-0" data-status="{{ $status }}">

    <div class="overlay"></div>

    <div class="container">

        <div class="ep-hero__inner position-relative text-white">

            <div class="row ep-hero__row">

                {{-- TEXT --}}
                <div class="col-lg-6">

                    <span class="ep-badge">
                        <span class="ep-badge__dot" aria-hidden="true"></span>
                        {{ $statusLabels[$status] }}
                    </span>

                    @if($featuredEvent)

                        <h1 class="ep-title">{{ $featuredEvent->title }}</h1>

                        <ul class="ep-meta">

                            @if($start)
                                <li>
                                    <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                    <span>
                                        {{ $start->format('D, d M Y') }}@if($hasTime), {{ $start->format('g:i A') }}@endif
                                    </span>
                                </li>
                            @endif

                            <li>
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                @if($location)
                                    <a
                                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}"
                                        target="_blank"
                                        rel="noopener"
                                        title="Open in Google Maps"
                                    >{{ $location }}</a>
                                @else
                                    <span>Location to be announced</span>
                                @endif
                            </li>

                        </ul>

                        @if($description !== '')
                            <p class="ep-desc">{{ $description }}</p>
                        @endif

                        <div class="ep-actions">

                            <a href="#event-overview" class="ep-btn ep-btn--primary">
                                <span>Browse events</span>
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </a>

                            @if($calendarUrl)
                                <a
                                    href="{{ $calendarUrl }}"
                                    class="ep-btn ep-btn--ghost"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                    <span>Add to calendar</span>
                                </a>
                            @endif

                        </div>

                    @else

                        <h1 class="ep-title">Your next event will show up here</h1>

                        <p class="ep-desc">
                            When an organiser publishes an event, you'll see its date, location
                            and countdown on this page.
                        </p>

                        <div class="ep-actions">
                            <a href="#event-overview" class="ep-btn ep-btn--primary">
                                <span>Browse events</span>
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </a>
                        </div>

                    @endif

                </div>

                {{-- TICKET --}}
                @if($featuredEvent)

                    <div class="col-lg-5 offset-lg-1">

                        <div class="ep-ticket-wrap">

                            <article class="ep-ticket" aria-label="{{ $featuredEvent->title }} ticket">

                                <div class="ep-ticket__media {{ $posterUrl ? '' : 'is-fallback' }}">
                                    <img
                                        src="{{ $posterUrl ?? $fallbackUrl }}"
                                        alt="{{ $featuredEvent->title }}"
                                        loading="eager"
                                        onerror="this.onerror=null;this.src='{{ $fallbackUrl }}';this.parentNode.classList.add('is-fallback');"
                                    >
                                </div>

                                <div class="ep-ticket__stub">

                                    @if($start)

                                        <p class="ep-stub-label" id="ep-label">Starts in</p>

                                        <div
                                            id="countdown"
                                            class="ep-count"
                                            role="timer"
                                            data-start="{{ $start->format('Y-m-d\TH:i:s') }}"
                                            data-status="{{ $status }}"
                                        >
                                            <div class="ep-unit">
                                                <span id="days" class="ep-digit">--</span>
                                                <small>Days</small>
                                            </div>

                                            <span class="ep-sep" aria-hidden="true">:</span>

                                            <div class="ep-unit">
                                                <span id="hours" class="ep-digit">--</span>
                                                <small>Hours</small>
                                            </div>

                                            <span class="ep-sep" aria-hidden="true">:</span>

                                            <div class="ep-unit">
                                                <span id="minutes" class="ep-digit">--</span>
                                                <small>Minutes</small>
                                            </div>

                                            <span class="ep-sep" aria-hidden="true">:</span>

                                            <div class="ep-unit">
                                                <span id="seconds" class="ep-digit">--</span>
                                                <small>Seconds</small>
                                            </div>
                                        </div>

                                        <p id="ep-done" class="ep-done" hidden></p>

                                        <span id="ep-sr" class="visually-hidden" aria-live="polite"></span>

                                    @else

                                        <p class="ep-done">Date to be announced</p>

                                    @endif

                                </div>

                            </article>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

    {{-- WAVE --}}
    <div class="wave overflow-hidden position-absolute w-100 z-0" aria-hidden="true">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1000 100"
            preserveAspectRatio="none"
            class="d-block position-relative"
        >
            <path
                class="elementor-shape-fill"
                d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z"
            ></path>
        </svg>

    </div>

</section>


{{-- =========================================================
EVENT OVERVIEW
========================================================= --}}

<div id="event-overview">

    @include('includes.overview')

</div>


{{-- =========================================================
HIDDEN SECTIONS
Everything below remains in the Blade file.
Remove the "event-plus-hidden-sections" class
to show these sections again.
========================================================= --}}

<div class="event-plus-hidden-sections" aria-hidden="true">

{{-- =========================================================
PARTNERS SECTION
========================================================= --}}

<section class="partners">

    <div class="container">

        <div class="partner-inner">

            <div class="partner-title text-center pb-6 w-lg-60 m-auto">

                <p class="mb-1 pink">OUR PARTNERS</p>

                <h2 class="mb-1">
                    SPONSORS AND
                    <span class="pink">PARTNERS</span>
                </h2>

                <p class="m-0">
                    Excepteur sint occaecat cupidatat non proident,
                    sunt in culpa qui officia deserunt mollit anim
                    id est laborum.
                </p>

            </div>

            <div class="partner-img pb-6">

                <div class="row row-cols-1 row-cols-lg-5 row-cols-md-5">

                    @foreach([1, 2, 3, 4, 5, 3, 4, 5, 1, 2] as $partner)
                        <div class="col p-0 border-end border-top border-sm-0">

                            <div class="p-2 partner-img-box text-center">

                                <img
                                    src="{{ asset('assets/eventen/images/icon/' . $partner . '.png') }}"
                                    alt="partner-img"
                                    class="opacity-75 w-lg-75 w-md-100 w-40"
                                >

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

            <div class="partner-button text-center">

                <a class="btn" href="#">
                    VIEW MORE SPONSORS
                </a>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
TICKET BOOK SECTION
========================================================= --}}

<section class="ticket position-relative">

    <div class="overlay"></div>

    <div class="container">

        <div class="ticket-inner w-lg-75 mx-auto text-center position-relative text-white">

            <div class="ticket-title">

                <h5 class="text-white mb-1">
                    LET'S DO IT HURRY
                </h5>

                <h1 class="text-white mb-2">
                    HAVEN'T BOOKED YOUR SEAT YET?

                    @foreach(str_split('GETTICKET') as $letter)
                        <span class="spin pink d-inline-block">{{ $letter }}</span>
                    @endforeach
                </h1>

            </div>

            <div class="ticket-info">

                <p>Find your event and secure your place today.</p>

                <div class="ticket-button">

                    <a class="btn btn1" href="#events-section">
                        GET TICKETS NOW
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
SCHEDULES SECTION
========================================================= --}}

@php
    $scheduleDays = [
        [
            'label'    => '1ST DAY',
            'date'     => $start ? $start->format('F d, Y') : 'APRIL 23, 2024',
            'sessions' => [
                ['REGISTRATION & BREAKFAST', 'Registration and event preparation.', '08:30 - 09:30 AM', 'team/1.jpg', 'EVENT TEAM'],
                ['EVENT SESSION', 'Main event session and activities.', '09:30 - 12:00 PM', 'team/2.jpg', 'EVENT SPEAKER'],
            ],
        ],
        [
            'label'    => '2ND DAY',
            'date'     => $start ? $start->copy()->addDay()->format('F d, Y') : 'APRIL 24, 2024',
            'sessions' => [
                ['REGISTRATION & BREAKFAST', 'Registration and event preparation.', '08:30 - 09:30 AM', 'team/3.jpg', 'EVENT SPEAKER'],
                ['EXPLORATIONS OF NEW APPROACHES', 'Explore new approaches and opportunities.', '09:30 - 12:00 PM', 'team/1.jpg', 'EVENT TEAM'],
            ],
        ],
    ];

    $schedulePlace = $location ?? "William Mathew Theater\n2390 NW 2nd Ave, Miami, FL 33127";
@endphp

<section class="schedules">

    <div class="container">

        <div class="schedule-inner">

            <div class="schedule-title text-center mb-6 w-lg-60 mx-auto">

                <p class="mb-1 pink">OUR SCHEDULES</p>

                <h2 class="mb-1">
                    FOLLOW
                    <span class="pink">EVENT</span>
                    SCHEDULES
                </h2>

                <p class="m-0">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Ut elit tellus, luctus nec ullamcorper mattis,
                    pulvinar dapibus leo.
                </p>

            </div>

            <div class="schedule-list-outer">

                @foreach($scheduleDays as $day)

                    <div class="{{ $loop->first ? 'schedule-list-top mb-7' : 'schedule-title-bottom' }}">

                        <div class="row align-items-center">

                            <div class="col-lg-3 col-md-4">

                                <div class="schedule-day text-center text-sm-start py-6 position-relative ps-2 z-1">

                                    <h4 class="pink mb-2">{{ $day['label'] }}</h4>

                                    <h6 class="mb-2">{{ $day['date'] }}</h6>

                                    <p>{!! nl2br(e($schedulePlace)) !!}</p>

                                </div>

                            </div>

                            <div class="col-lg-9 col-md-8">

                                <div class="schedule-list text-center text-sm-start">

                                    @foreach($day['sessions'] as [$title, $summary, $time, $avatar, $person])

                                        <div class="schedule-list-box {{ $loop->first ? 'bg-lightgrey border border-white border-2 rounded px-6 py-4 mb-5' : 'bg-white border-lightgrey border border-2 rounded px-6 py-4' }} {{ (! $loop->first && $loop->parent->last) ? 'mb-5' : '' }}">

                                            <div class="schedule-box-title">
                                                <h5 class="mb-2">
                                                    <a href="#" class="black">{{ $title }}</a>
                                                </h5>
                                            </div>

                                            <div class="schedule-box-info">

                                                <p class="mb-2">
                                                    <small>{{ $summary }}</small>
                                                </p>

                                                <ul class="mb-2 p-0">

                                                    <li class="d-inline">
                                                        <i class="fa fa-clock-o pink me-2"></i>
                                                        <small>{{ $time }}</small>
                                                    </li>

                                                    <li class="d-inline">
                                                        <i class="fa fa-thumb-tack pink mx-2"></i>
                                                        <small>Event Hall</small>
                                                    </li>

                                                </ul>

                                            </div>

                                            <div class="schedule-box-bio d-md-flex">

                                                <div class="schedule-bio-image">
                                                    <img
                                                        src="{{ asset('assets/eventen/images/' . $avatar) }}"
                                                        alt="team-image"
                                                        class="me-2 rounded-circle"
                                                    >
                                                </div>

                                                <div class="schedule-bio-info">

                                                    <p class="mt-1 mb-0">
                                                        <a href="#" class="pink">{{ $person }}</a>
                                                    </p>

                                                    <small>Host & Speaker</small>

                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                    @if($loop->last)
                                        <div class="partner-button mt-6">
                                            <a class="btn" href="#events-section">
                                                VIEW MORE DETAILS
                                            </a>
                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
GALLERY SECTION
========================================================= --}}

<section class="gallery" id="gallery-section">

    <div class="container">

        <div class="galler-inner">

            <div class="section-title text-center">

                <div class="row align-items-center">

                    <div class="col-lg-6 pb-2">

                        <div class="title-content text-lg-start">

                            <p class="mb-1 pink">EVENT GALLERY</p>

                            <h2 class="mb-1">
                                WAS AN AMAZING
                                <span class="pink">GALLERY</span>
                            </h2>

                            <p class="m-0">
                                Take a look at some of our memorable events.
                            </p>

                        </div>

                    </div>

                    <div class="col-lg-6">

                        <div class="speaker-button text-lg-end">

                            <a class="btn" href="#gallery-section">
                                VIEW MORE SHOTS
                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <div class="gallerybox pt-4">

                <div id="selector1" class="grid row">

                    @foreach([4, 7, 6, 7, 6, 1] as $image)
                        <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                            <img
                                src="{{ asset('assets/eventen/images/thumbnail/' . $image . '.jpg') }}"
                                class="w-100 rounded"
                                alt="Event Gallery"
                            >

                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </div>

</section>

</div>

@endsection


{{-- =========================================================
STYLES
========================================================= --}}

@push('styles')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">

<style>

/* Hidden below-the-fold sections (markup kept for future use) */
.event-plus-hidden-sections {
    display: none !important;
}

html {
    scroll-behavior: smooth;
}

#event-overview {
    scroll-margin-top: 90px;
}


/* =========================================================
   HERO
   ========================================================= */

.banner.ep-hero {
    --ep-ink: #17122b;
    --ep-paper: #fbf9ff;
    --ep-muted: #6b6580;
    --ep-pink: #db2777;
    --ep-violet: #7c3aed;
    --ep-live: #ff3b5c;
    --ep-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    position: relative;
    overflow-x: clip;
    background-color: var(--ep-ink);
}

/* Darkens the theme's banner image and puts a soft spotlight behind the ticket */
.banner.ep-hero > .overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    opacity: 1;
    pointer-events: none !important;
    background:
        radial-gradient(60% 75% at 85% 42%, rgba(124, 58, 237, 0.42), transparent 70%),
        linear-gradient(rgba(23, 18, 43, 0.92), rgba(23, 18, 43, 0.92));
}

.banner.ep-hero .container {
    position: relative;
    z-index: 10;
}

.ep-hero__inner {
    padding-top: clamp(2.5rem, 6vw, 5rem);
    padding-bottom: clamp(5.5rem, 10vw, 9rem);
}

.ep-hero__row {
    align-items: center;
    row-gap: 3rem;
}

.banner.ep-hero .wave {
    z-index: 2 !important;
    pointer-events: none !important;
}

.banner.ep-hero .wave svg {
    width: 100%;
    height: clamp(40px, 6vw, 90px);
}


/* ---------- Status badge ---------- */

.ep-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.4rem 0.95rem;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.2;
}

.ep-badge__dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 50%;
    background: #c4b5fd;
}

.ep-hero[data-status="live"] .ep-badge__dot {
    background: var(--ep-live);
    animation: ep-pulse 1.8s ease-out infinite;
}

@keyframes ep-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255, 59, 92, 0.65); }
    100% { box-shadow: 0 0 0 0.65rem rgba(255, 59, 92, 0); }
}


/* ---------- Text ---------- */

.ep-title {
    margin: 1.15rem 0 1.25rem;
    color: #fff;
    font-family: var(--ep-display);
    font-size: clamp(2.2rem, 5.2vw, 4.25rem);
    font-weight: 800;
    line-height: 1.02;
    letter-spacing: -0.025em;
    overflow-wrap: anywhere;
    text-wrap: balance;
}

.ep-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin: 0 0 1.35rem;
    padding: 0;
    list-style: none;
}

.ep-meta li {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    max-width: 100%;
    padding: 0.6rem 1rem;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    font-size: 1rem;
    line-height: 1.3;
}

.ep-meta i {
    color: #f9a8d4;
    font-size: 1.05rem;
}

.ep-meta a {
    color: #fff;
    text-decoration: underline;
    text-decoration-color: rgba(255, 255, 255, 0.4);
    text-underline-offset: 3px;
    transition: text-decoration-color 0.2s ease;
}

.ep-meta a:hover {
    color: #fff;
    text-decoration-color: #fff;
}

.ep-desc {
    max-width: 52ch;
    margin: 0 0 1.9rem;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1.0625rem;
    line-height: 1.65;
}

.ep-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}


/* ---------- Buttons ---------- */

.ep-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    min-height: 3rem;
    padding: 0.75rem 1.6rem;
    border: 1.5px solid transparent;
    border-radius: 999px;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none !important;
    cursor: pointer;
    transition:
        transform 0.2s ease,
        background-color 0.2s ease,
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.ep-btn:focus-visible,
.ep-meta a:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 3px;
}

.ep-btn--primary {
    background: var(--ep-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.35);
}

.ep-btn--primary:hover {
    background: #be185d;
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(219, 39, 119, 0.45);
}

.ep-btn--primary i {
    font-size: 0.85rem;
    transition: transform 0.25s ease;
}

.ep-btn--primary:hover i {
    transform: translateY(3px);
}

.ep-btn--ghost {
    border-color: rgba(255, 255, 255, 0.4);
    background: transparent;
    color: #fff !important;
}

.ep-btn--ghost:hover {
    border-color: #fff;
    background: rgba(255, 255, 255, 0.12);
}


/* =========================================================
   TICKET + COUNTDOWN
   ========================================================= */

.ep-ticket-wrap {
    max-width: 460px;
    margin-inline: auto;
    transform: rotate(-2deg);
    filter: drop-shadow(0 28px 36px rgba(8, 4, 24, 0.5));
    animation: ep-drop 0.9s cubic-bezier(0.2, 0.9, 0.25, 1.05) both;
}

@keyframes ep-drop {
    from { opacity: 0; transform: translateY(-30px) rotate(-7deg); }
    to   { opacity: 1; transform: translateY(0) rotate(-2deg); }
}

.ep-ticket {
    --stub: 152px;
    --notch: 14px;

    overflow: hidden;
    border-radius: 22px;
    background: var(--ep-paper);

    /* Punches the two half-circle notches at the perforation line */
    -webkit-mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--stub)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--stub)), #0000 97%, #000) right / 51% 100% no-repeat;
            mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--stub)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--stub)), #0000 97%, #000) right / 51% 100% no-repeat;
}

.ep-ticket__media {
    aspect-ratio: 16 / 11;
    background: #e9e3f8;
}

.ep-ticket__media img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ep-ticket__media.is-fallback {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    background: linear-gradient(160deg, #3b2a73, #17122b);
}

.ep-ticket__media.is-fallback img {
    width: auto;
    max-width: 80%;
    object-fit: contain;
}

.ep-ticket__stub {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    height: var(--stub);
    padding: 0.5rem 1.1rem 0;
    color: var(--ep-ink);
    text-align: center;
}

/* Dashed perforation between the notches */
.ep-ticket__stub::before {
    content: "";
    position: absolute;
    top: 0;
    left: 24px;
    right: 24px;
    border-top: 2px dashed rgba(23, 18, 43, 0.22);
}

.ep-stub-label {
    margin: 0;
    color: var(--ep-muted);
    font-size: 0.9rem;
    font-weight: 600;
}

.ep-count {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 0.45rem;
}

.ep-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 3.1rem;
}

.ep-digit {
    display: inline-block;
    font-family: var(--ep-display);
    font-size: clamp(2rem, 7vw, 2.75rem);
    font-weight: 800;
    line-height: 1.05;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.02em;
}

.ep-unit small {
    margin-top: 0.15rem;
    color: var(--ep-muted);
    font-size: 0.75rem;
    font-weight: 600;
}

.ep-sep {
    color: var(--ep-pink);
    font-family: var(--ep-display);
    font-size: clamp(1.6rem, 6vw, 2.2rem);
    font-weight: 800;
    line-height: 1.2;
    opacity: 0.55;
}

.ep-digit.is-tick {
    animation: ep-roll 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) backwards;
}

@keyframes ep-roll {
    from { opacity: 0; transform: translateY(45%); }
    to   { opacity: 1; transform: translateY(0); }
}

.ep-done {
    margin: 0;
    font-family: var(--ep-display);
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.01em;
}

.ep-hero[data-status="live"] .ep-done {
    color: var(--ep-pink);
}


/* =========================================================
   SMALL SCREENS
   ========================================================= */

@media (max-width: 575px) {

    .ep-actions .ep-btn {
        flex: 1 1 100%;
    }

    .ep-ticket {
        --stub: 140px;
    }

    .ep-unit {
        min-width: 2.6rem;
    }

    .ep-count {
        gap: 0.3rem;
    }

}


/* =========================================================
   REDUCED MOTION
   ========================================================= */

@media (prefers-reduced-motion: reduce) {

    html {
        scroll-behavior: auto;
    }

    .ep-hero *,
    .ep-hero *::before,
    .ep-hero *::after {
        animation: none !important;
        transition: none !important;
    }

}

</style>

@endpush


{{-- =========================================================
COUNTDOWN
========================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    var root = document.getElementById('countdown');
    if (!root) return;

    var start  = new Date(root.getAttribute('data-start')).getTime();
    var status = root.getAttribute('data-status');

    var label = document.getElementById('ep-label');
    var done  = document.getElementById('ep-done');
    var sr    = document.getElementById('ep-sr');

    var keys = ['days', 'hours', 'minutes', 'seconds'];
    var els  = {};
    keys.forEach(function (k) { els[k] = document.getElementById(k); });

    var firstRender = true;
    var lastMinute  = null;
    var timer       = null;

    function plural(n, word) {
        return n + ' ' + word + (n === 1 ? '' : 's');
    }

    function setDigit(key, value, index) {
        var el   = els[key];
        var text = String(value).padStart(2, '0');

        if (el.textContent === text) return;

        el.textContent = text;
        el.style.animationDelay = firstRender ? (0.6 + index * 0.09) + 's' : '0s';

        // Restart the roll animation
        el.classList.remove('is-tick');
        void el.offsetWidth;
        el.classList.add('is-tick');
    }

    function showDone(text) {
        root.hidden = true;
        if (label) label.hidden = true;
        done.hidden = false;
        done.textContent = text;
        if (sr) sr.textContent = text;
        if (timer) clearInterval(timer);
    }

    function tick() {

        if (status === 'past') {
            showDone('This event has ended');
            return;
        }

        var distance = start - Date.now();

        if (isNaN(distance)) {
            showDone('Date to be announced');
            return;
        }

        if (distance <= 0) {
            showDone(status === 'live' ? 'Happening now' : 'Starting now');
            return;
        }

        var d = Math.floor(distance / 86400000);
        var h = Math.floor((distance % 86400000) / 3600000);
        var m = Math.floor((distance % 3600000) / 60000);
        var s = Math.floor((distance % 60000) / 1000);

        setDigit('days', d, 0);
        setDigit('hours', h, 1);
        setDigit('minutes', m, 2);
        setDigit('seconds', s, 3);

        firstRender = false;

        // Screen readers get one calm update per minute instead of every second
        if (sr && lastMinute !== m) {
            lastMinute = m;
            sr.textContent =
                plural(d, 'day') + ', ' + plural(h, 'hour') + ' and ' +
                plural(m, 'minute') + ' until the event starts';
        }
    }

    tick();
    timer = setInterval(tick, 1000);

});
</script>

@endpush