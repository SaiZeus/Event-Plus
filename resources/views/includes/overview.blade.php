@php
    $live     = $liveEvents ?? collect();
    $upcoming = $upcomingEvents ?? collect();
    $past     = $pastEvents ?? collect();

    $groups = [
        'live' => [
            'tab'        => 'Live now',
            'chip'       => 'Live now',
            'events'     => $live,
            'fallback'   => 'group/1.jpg',
            'emptyTitle' => 'Nothing is live right now',
            'emptyText'  => 'Events show up here while they are taking place.',
            'emptyTo'    => count($upcoming) ? 'upcoming' : null,
            'emptyBtn'   => 'See upcoming events',
        ],
        'upcoming' => [
            'tab'        => 'Upcoming',
            'chip'       => 'Upcoming',
            'events'     => $upcoming,
            'fallback'   => 'group/2.jpg',
            'emptyTitle' => 'No upcoming events yet',
            'emptyText'  => "New events appear here as soon as they're published.",
            'emptyTo'    => null,
            'emptyBtn'   => null,
        ],
        'past' => [
            'tab'        => 'Past',
            'chip'       => 'Ended',
            'events'     => $past,
            'fallback'   => 'group/3.jpg',
            'emptyTitle' => 'No past events yet',
            'emptyText'  => 'Finished events are kept here so you can look back at them.',
            'emptyTo'    => null,
            'emptyBtn'   => null,
        ],
    ];

    // Open on the first tab that has something in it
    $defaultTab = 'upcoming';
    foreach ($groups as $key => $group) {
        if (count($group['events'])) {
            $defaultTab = $key;
            break;
        }
    }
@endphp

<section class="ov" id="events-section">

    <div class="container">

        {{-- =====================================================
             HEADER + SEARCH
        ====================================================== --}}

        <div class="ov__head">

            <div>
                <h2 class="ov__title">Find your next event</h2>
                <p class="ov__lead">
                    See what's on right now, what's coming up, and what you missed.
                </p>
            </div>

            <div class="ov-search">
                <label for="ov-search" class="visually-hidden">Search events</label>
                <i class="fas fa-search" aria-hidden="true"></i>
                <input
                    type="search"
                    id="ov-search"
                    placeholder="Search by name or location"
                    autocomplete="off"
                    enterkeyhint="search"
                >
            </div>

        </div>


        {{-- =====================================================
             TABS
        ====================================================== --}}

        <div class="ov-tabs" role="tablist" aria-label="Event categories">

            @foreach($groups as $key => $group)
                <button
                    type="button"
                    class="ov-tab"
                    role="tab"
                    id="ov-tab-{{ $key }}"
                    data-tab="{{ $key }}"
                    aria-controls="ov-panel-{{ $key }}"
                    aria-selected="{{ $key === $defaultTab ? 'true' : 'false' }}"
                    tabindex="{{ $key === $defaultTab ? '0' : '-1' }}"
                >
                    @if($key === 'live' && count($group['events']))
                        <span class="ov-tab__dot" aria-hidden="true"></span>
                    @endif

                    <span>{{ $group['tab'] }}</span>

                    <span class="ov-tab__count">{{ count($group['events']) }}</span>
                </button>
            @endforeach

        </div>


        {{-- =====================================================
             PANELS
        ====================================================== --}}

        @foreach($groups as $key => $group)

            <div
                class="ov-panel"
                role="tabpanel"
                id="ov-panel-{{ $key }}"
                data-panel="{{ $key }}"
                aria-labelledby="ov-tab-{{ $key }}"
                tabindex="0"
                {{ $key === $defaultTab ? '' : 'hidden' }}
            >

                @if(count($group['events']))

                    <div class="row g-4">

                        @foreach($group['events'] as $event)

                            @php
                                $date = $event->event_date
                                    ? \Carbon\Carbon::parse($event->event_date)
                                    : null;

                                $searchText = \Illuminate\Support\Str::lower(
                                    trim($event->title . ' ' . ($event->location ?? '') . ' ' . ($event->creator_name ?? ''))
                                );
                            @endphp

                            <div
                                class="ov-col col-lg-4 col-md-6"
                                data-ov-card
                                data-search="{{ $searchText }}"
                            >

                                <article class="ov-card ov-card--{{ $key }}">

                                    <div class="ov-card__shape">

                                        <div class="ov-card__media">

                                            <img
                                                src="{{ $event->image
                                                    ? asset('storage/' . $event->image)
                                                    : asset('assets/eventen/images/' . $group['fallback']) }}"
                                                alt="{{ $event->title }}"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/eventen/images/' . $group['fallback']) }}';"
                                            >

                                            <span class="ov-chip ov-chip--{{ $key }}">
                                                @if($key === 'live')
                                                    <span class="ov-chip__dot" aria-hidden="true"></span>
                                                @endif
                                                {{ $group['chip'] }}
                                            </span>

                                            @if($date)
                                                <time class="ov-date" datetime="{{ $date->format('Y-m-d') }}">
                                                    <b>{{ $date->format('d') }}</b>
                                                    <span>{{ $date->format('M') }}</span>
                                                </time>
                                            @endif

                                        </div>

                                        <div class="ov-card__content">

                                            <h3 class="ov-card__title">{{ $event->title }}</h3>

                                            <ul class="ov-card__meta">

                                                @if($date)
                                                    <li>
                                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                                        <span>
                                                            {{ $date->format('D, d M Y') }}
                                                            @if($key === 'upcoming' && $date->isFuture())
                                                                <em>({{ $date->diffForHumans() }})</em>
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endif

                                                @if(filled($event->location))
                                                    <li>
                                                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                                        <span>{{ $event->location }}</span>
                                                    </li>
                                                @endif

                                                @if($event->creator_name)
                                                    <li>
                                                        <i class="fas fa-user" aria-hidden="true"></i>
                                                        <span>Organised by {{ $event->creator_name }}</span>
                                                    </li>
                                                @endif

                                            </ul>

                                            @php
                                                $summary = \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 110);
                                            @endphp

                                            @if($summary !== '')
                                                <p class="ov-card__desc">{{ $summary }}</p>
                                            @endif

                                        </div>

                                        <div class="ov-card__foot">

                                            <a href="{{ route('events.show', $event) }}" class="ov-card__link">
                                                <span>
                                                    View event
                                                    <span class="visually-hidden">: {{ $event->title }}</span>
                                                </span>
                                                <span class="ov-card__go" aria-hidden="true">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </a>

                                        </div>

                                    </div>

                                </article>

                            </div>

                        @endforeach

                    </div>

                    <div class="ov-nomatch" hidden>
                        <h3>No matches for “<span data-ov-q></span>”</h3>
                        <p>Check the spelling, try a shorter search, or look in another tab.</p>
                        <button type="button" class="ov-btn" data-ov-clear>Clear search</button>
                    </div>

                @else

                    <div class="ov-empty">
                        <h3>{{ $group['emptyTitle'] }}</h3>
                        <p>{{ $group['emptyText'] }}</p>

                        @if($group['emptyTo'])
                            <button type="button" class="ov-btn" data-ov-tab="{{ $group['emptyTo'] }}">
                                {{ $group['emptyBtn'] }}
                            </button>
                        @endif
                    </div>

                @endif

            </div>

        @endforeach

    </div>

</section>


@push('styles')

<style>

/* =========================================================
   EVENT OVERVIEW
   Shares its look with the hero: ink + pink, ticket notches,
   Bricolage Grotesque headings (loaded by the home page).
========================================================= */

.ov {
    --ov-ink: #17122b;
    --ov-muted: #6b6580;
    --ov-pink: #db2777;
    --ov-violet: #7c3aed;
    --ov-live: #e11d48;
    --ov-line: #d9d3ea;
    --ov-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    padding: clamp(3rem, 6vw, 5rem) 0 clamp(4rem, 8vw, 6.5rem);
    color: var(--ov-ink);
}


/* ---------- Header ---------- */

.ov__head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1.5rem 2rem;
    margin-bottom: 2rem;
}

.ov__title {
    margin: 0 0 0.75rem;
    font-family: var(--ov-display);
    font-size: clamp(1.9rem, 4vw, 3rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.025em;
    text-wrap: balance;
}

.ov__lead {
    max-width: 46ch;
    margin: 0;
    color: var(--ov-muted);
    font-size: 1.0625rem;
    line-height: 1.6;
}


/* ---------- Search ---------- */

.ov-search {
    position: relative;
    width: 100%;
    max-width: 340px;
}

.ov-search i {
    position: absolute;
    top: 50%;
    left: 1.1rem;
    color: var(--ov-muted);
    pointer-events: none;
    transform: translateY(-50%);
}

.ov-search input {
    width: 100%;
    height: 3rem;
    padding: 0 1.1rem 0 2.8rem;
    border: 1.5px solid var(--ov-line);
    border-radius: 999px;
    background: #fff;
    color: var(--ov-ink);
    font-size: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ov-search input::placeholder {
    color: #756f8a;
}

.ov-search input:focus {
    outline: none;
    border-color: var(--ov-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.18);
}


/* ---------- Tabs ---------- */

.ov-tabs {
    display: flex;
    gap: 0.25rem;
    width: max-content;
    max-width: 100%;
    margin-bottom: 2rem;
    padding: 0.3rem;
    overflow-x: auto;
    border-radius: 999px;
    background: #f1edfb;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}

.ov-tabs::-webkit-scrollbar {
    display: none;
}

.ov-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.65rem 1.15rem;
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

.ov-tab:hover {
    color: var(--ov-ink);
}

.ov-tab[aria-selected="true"] {
    background: var(--ov-ink);
    color: #fff;
}

.ov-tab:focus-visible {
    outline: 3px solid var(--ov-violet);
    outline-offset: 2px;
}

.ov-tab__count {
    min-width: 1.5rem;
    padding: 0.2rem 0.45rem;
    border-radius: 999px;
    background: rgba(23, 18, 43, 0.09);
    font-size: 0.8rem;
    text-align: center;
}

.ov-tab[aria-selected="true"] .ov-tab__count {
    background: rgba(255, 255, 255, 0.2);
}

.ov-tab__dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: var(--ov-live);
}

.ov-panel:focus-visible {
    outline: 3px solid var(--ov-violet);
    outline-offset: 6px;
    border-radius: 12px;
}

.ov-panel.is-entering {
    animation: ov-in 0.35s ease-out;
}

@keyframes ov-in {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: none; }
}


/* ---------- Cards ---------- */

.ov-col[hidden],
.ov-nomatch[hidden] {
    display: none !important;
}

.ov-col {
    display: flex;
}

.ov-card {
    --foot: 62px;
    --notch: 12px;

    position: relative;
    flex: 1;
    min-width: 0;
    filter:
        drop-shadow(0 10px 22px rgba(23, 18, 43, 0.10))
        drop-shadow(0 1px 2px rgba(23, 18, 43, 0.08));
    transition: transform 0.25s ease, filter 0.25s ease;
}

.ov-card:hover {
    transform: translateY(-6px);
    filter:
        drop-shadow(0 18px 30px rgba(23, 18, 43, 0.16))
        drop-shadow(0 1px 2px rgba(23, 18, 43, 0.08));
}

/* The notches sit on the dashed line above the footer */
.ov-card__shape {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
    border-radius: 20px;
    background: #fff;

    -webkit-mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--foot)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--foot)), #0000 97%, #000) right / 51% 100% no-repeat;
            mask:
        radial-gradient(circle var(--notch) at 0 calc(100% - var(--foot)), #0000 97%, #000) left / 51% 100% no-repeat,
        radial-gradient(circle var(--notch) at 100% calc(100% - var(--foot)), #0000 97%, #000) right / 51% 100% no-repeat;
}

.ov-card__media {
    position: relative;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #e9e3f8;
}

.ov-card__media img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.ov-card:hover .ov-card__media img {
    transform: scale(1.05);
}

.ov-card--past .ov-card__media img {
    filter: saturate(0.55);
}

.ov-chip {
    position: absolute;
    top: 14px;
    left: 14px;
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.38rem 0.8rem;
    border-radius: 999px;
    background: var(--ov-violet);
    color: #fff;
    font-size: 0.8125rem;
    font-weight: 700;
    line-height: 1.2;
}

.ov-chip--live { background: var(--ov-live); }
.ov-chip--past { background: #3f3a52; }

.ov-chip__dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: #fff;
    animation: ov-pulse 1.8s ease-out infinite;
}

@keyframes ov-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
    100% { box-shadow: 0 0 0 0.55rem rgba(255, 255, 255, 0); }
}

.ov-date {
    position: absolute;
    right: 14px;
    bottom: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 3.4rem;
    padding: 0.5rem 0.6rem;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 6px 16px rgba(8, 4, 24, 0.25);
    color: var(--ov-ink);
    line-height: 1;
    text-align: center;
}

.ov-date b {
    font-family: var(--ov-display);
    font-size: 1.5rem;
    font-weight: 800;
}

.ov-date span {
    margin-top: 0.2rem;
    color: var(--ov-pink);
    font-size: 0.8rem;
    font-weight: 700;
}

.ov-card__content {
    flex: 1;
    padding: 1.4rem 1.5rem 1.25rem;
}

.ov-card__title {
    margin: 0 0 0.9rem;
    font-family: var(--ov-display);
    font-size: 1.35rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.01em;
    overflow-wrap: anywhere;
}

.ov-card__meta {
    display: grid;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.ov-card__meta li {
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    color: #4b4563;
    font-size: 0.9375rem;
    line-height: 1.4;
}

.ov-card__meta i {
    flex: none;
    width: 1rem;
    margin-top: 0.2rem;
    color: var(--ov-pink);
    text-align: center;
}

.ov-card__meta em {
    color: var(--ov-muted);
    font-style: normal;
}

.ov-card__desc {
    display: -webkit-box;
    margin: 0.95rem 0 0;
    overflow: hidden;
    color: var(--ov-muted);
    font-size: 0.9375rem;
    line-height: 1.6;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.ov-card__foot {
    height: var(--foot);
    margin: 0 24px;
    border-top: 2px dashed rgba(23, 18, 43, 0.2);
}

/* The whole card is clickable through this link */
.ov-card__link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    color: var(--ov-ink);
    font-weight: 700;
    text-decoration: none;
}

.ov-card__link::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 20px;
}

.ov-card__link:hover {
    color: var(--ov-ink);
}

.ov-card__link:focus-visible {
    outline: none;
}

.ov-card__link:focus-visible::after {
    outline: 3px solid var(--ov-violet);
    outline-offset: -4px;
}

.ov-card__go {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 50%;
    background: var(--ov-pink);
    color: #fff;
    font-size: 0.8rem;
    transition: transform 0.2s ease;
}

.ov-card:hover .ov-card__go {
    transform: translateX(3px);
}


/* ---------- Empty + no-match states ---------- */

.ov-empty,
.ov-nomatch {
    padding: 2.75rem 1.5rem;
    border: 1.5px dashed var(--ov-line);
    border-radius: 22px;
    background: #faf8ff;
    text-align: center;
}

.ov-empty h3,
.ov-nomatch h3 {
    margin: 0 0 0.4rem;
    font-family: var(--ov-display);
    font-size: 1.35rem;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.ov-empty p,
.ov-nomatch p {
    max-width: 42ch;
    margin: 0 auto;
    color: var(--ov-muted);
}

.ov-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 3rem;
    margin-top: 1.25rem;
    padding: 0.75rem 1.5rem;
    border: 1.5px solid var(--ov-ink);
    border-radius: 999px;
    background: transparent;
    color: var(--ov-ink);
    font-size: 1rem;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.ov-btn:hover {
    background: var(--ov-ink);
    color: #fff;
}

.ov-btn:focus-visible {
    outline: 3px solid var(--ov-violet);
    outline-offset: 3px;
}


/* ---------- Small screens ---------- */

@media (max-width: 575px) {

    .ov-search {
        max-width: none;
    }

    .ov-tabs {
        width: 100%;
    }

    .ov-tab {
        flex: 1 0 auto;
        justify-content: center;
    }

}


/* ---------- Reduced motion ---------- */

@media (prefers-reduced-motion: reduce) {

    .ov *,
    .ov *::before,
    .ov *::after {
        animation: none !important;
        transition: none !important;
    }

    .ov-card:hover {
        transform: none;
    }

}

</style>

@endpush


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    var root = document.getElementById('events-section');
    if (!root) return;

    var tabs   = Array.prototype.slice.call(root.querySelectorAll('[role="tab"]'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('[role="tabpanel"]'));
    var input  = document.getElementById('ov-search');

    /* ---------- Tabs ---------- */

    function select(key, moveFocus) {

        tabs.forEach(function (tab) {
            var on = tab.getAttribute('data-tab') === key;
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
            tab.tabIndex = on ? 0 : -1;
            if (on && moveFocus) tab.focus();
        });

        panels.forEach(function (panel) {
            var on = panel.getAttribute('data-panel') === key;

            if (on && panel.hidden) {
                panel.classList.remove('is-entering');
                void panel.offsetWidth;
                panel.classList.add('is-entering');
            }

            panel.hidden = !on;
        });
    }

    panels.forEach(function (panel) {
        panel.addEventListener('animationend', function () {
            panel.classList.remove('is-entering');
        });
    });

    tabs.forEach(function (tab, index) {

        tab.addEventListener('click', function () {
            select(tab.getAttribute('data-tab'), false);
        });

        tab.addEventListener('keydown', function (e) {
            var next = null;

            if (e.key === 'ArrowRight') next = (index + 1) % tabs.length;
            if (e.key === 'ArrowLeft')  next = (index - 1 + tabs.length) % tabs.length;
            if (e.key === 'Home')       next = 0;
            if (e.key === 'End')        next = tabs.length - 1;

            if (next !== null) {
                e.preventDefault();
                select(tabs[next].getAttribute('data-tab'), true);
            }
        });
    });

    root.querySelectorAll('[data-ov-tab]').forEach(function (button) {
        button.addEventListener('click', function () {
            select(button.getAttribute('data-ov-tab'), false);
        });
    });


    /* ---------- Search ---------- */

    function applyFilter() {

        var raw = input ? input.value.trim() : '';
        var q   = raw.toLowerCase();

        panels.forEach(function (panel) {

            var cards = panel.querySelectorAll('[data-ov-card]');
            var shown = 0;

            cards.forEach(function (card) {
                var match = !q || card.getAttribute('data-search').indexOf(q) !== -1;
                card.hidden = !match;
                if (match) shown++;
            });

            var count = root.querySelector(
                '#ov-tab-' + panel.getAttribute('data-panel') + ' .ov-tab__count'
            );
            if (count) count.textContent = shown;

            var none = panel.querySelector('.ov-nomatch');
            if (none) {
                none.hidden = !(cards.length && shown === 0 && q);
                var label = none.querySelector('[data-ov-q]');
                if (label) label.textContent = raw;
            }
        });
    }

    if (input) {
        input.addEventListener('input', applyFilter);
    }

    root.querySelectorAll('[data-ov-clear]').forEach(function (button) {
        button.addEventListener('click', function () {
            input.value = '';
            applyFilter();
            input.focus();
        });
    });

});
</script>

@endpush