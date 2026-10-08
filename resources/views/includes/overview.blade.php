<section class="overview events-overview pb-0">

    <div class="container">

        <div class="inner-overview pb-10 position-relative border-dashed-bottom-2">

            {{-- =====================================================
                 SECTION TITLE
            ====================================================== --}}

            <div class="overview-title text-center pb-5">

                <p class="mb-1 pink">EVENT OVERVIEW</p>

                <h2>
                    GET THE LATEST INFO ABOUT
                    <span class="pink">OUR EVENTS</span>
                </h2>

                <p class="mt-3">
                    Explore our live, upcoming and past events.
                    Find event details, schedules, locations and registration information.
                </p>

            </div>


            {{-- =====================================================
                 LIVE EVENTS
            ====================================================== --}}

            <div class="event-overview-category mb-5">

                <div class="category-heading mb-4">

                    <p class="mb-1 pink">
                        <i class="fas fa-broadcast-tower"></i>
                        LIVE EVENTS
                    </p>

                    <h3>
                        EVENTS HAPPENING
                        <span class="pink">RIGHT NOW</span>
                    </h3>

                </div>

                <div class="row">

                    @forelse($liveEvents as $event)

                        <div class="col-lg-4 col-md-6 mb-4">

                            <div class="event-overview-card h-100">

                                <div class="event-overview-image">

                                    <img
                                        src="{{ $event->image
                                            ? asset('storage/' . $event->image)
                                            : asset('assets/eventen/images/group/1.jpg') }}"
                                        alt="{{ $event->title }}"
                                    >

                                    <span class="event-status live-status">
                                        LIVE NOW
                                    </span>

                                </div>

                                <div class="event-overview-content">

                                    <h4>
                                        GET THE LATEST INFO ABOUT
                                        <span class="pink">{{ $event->title }}</span>
                                    </h4>

                                    <div class="event-meta">

                                        <p>
                                            <i class="fas fa-map-marker-alt pink"></i>
                                            {{ $event->location }}
                                        </p>

                                        <p>
                                            <i class="fas fa-calendar-alt pink"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>

                                        @if($event->creator_name)
                                            <p>
                                                <i class="fas fa-user pink"></i>
                                                Organized by:
                                                {{ $event->creator_name }}
                                            </p>
                                        @endif

                                    </div>

                                    <p class="event-description">
                                        {{ Str::limit(strip_tags($event->description), 120) }}
                                    </p>

                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="btn mt-3"
                                    >
                                        VIEW EVENT
                                    </a>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="event-empty text-center">
                                No live events taking place right now.
                            </div>
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                 UPCOMING EVENTS
            ====================================================== --}}

            <div class="event-overview-category mb-5">

                <div class="category-heading mb-4">

                    <p class="mb-1 pink">
                        <i class="fas fa-calendar-check"></i>
                        UPCOMING EVENTS
                    </p>

                    <h3>
                        DON'T MISS OUR
                        <span class="pink">UPCOMING EVENTS</span>
                    </h3>

                </div>

                <div class="row">

                    @forelse($upcomingEvents as $event)

                        <div class="col-lg-4 col-md-6 mb-4">

                            <div class="event-overview-card h-100">

                                <div class="event-overview-image">

                                    <img
                                        src="{{ $event->image
                                            ? asset('storage/' . $event->image)
                                            : asset('assets/eventen/images/group/2.jpg') }}"
                                        alt="{{ $event->title }}"
                                    >

                                    <span class="event-status upcoming-status">
                                        UPCOMING
                                    </span>

                                </div>

                                <div class="event-overview-content">

                                    <h4>
                                        GET THE LATEST INFO ABOUT
                                        <span class="pink">{{ $event->title }}</span>
                                    </h4>

                                    <div class="event-meta">

                                        <p>
                                            <i class="fas fa-map-marker-alt pink"></i>
                                            {{ $event->location }}
                                        </p>

                                        <p>
                                            <i class="fas fa-calendar-alt pink"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>

                                        @if($event->creator_name)
                                            <p>
                                                <i class="fas fa-user pink"></i>
                                                Organized by:
                                                {{ $event->creator_name }}
                                            </p>
                                        @endif

                                    </div>

                                    <p class="event-description">
                                        {{ Str::limit(strip_tags($event->description), 120) }}
                                    </p>

                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="btn mt-3"
                                    >
                                        VIEW EVENT
                                    </a>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="event-empty text-center">
                                No upcoming events at the moment.
                            </div>
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                 PAST EVENTS
            ====================================================== --}}

            <div class="event-overview-category">

                <div class="category-heading mb-4">

                    <p class="mb-1 pink">
                        <i class="fas fa-history"></i>
                        PAST EVENTS
                    </p>

                    <h3>
                        LOOK BACK AT OUR
                        <span class="pink">PAST EVENTS</span>
                    </h3>

                </div>

                <div class="row">

                    @forelse($pastEvents as $event)

                        <div class="col-lg-4 col-md-6 mb-4">

                            <div class="event-overview-card past-event-card h-100">

                                <div class="event-overview-image">

                                    <img
                                        src="{{ $event->image
                                            ? asset('storage/' . $event->image)
                                            : asset('assets/eventen/images/group/3.jpg') }}"
                                        alt="{{ $event->title }}"
                                    >

                                    <span class="event-status past-status">
                                        PAST EVENT
                                    </span>

                                </div>

                                <div class="event-overview-content">

                                    <h4>
                                        GET THE LATEST INFO ABOUT
                                        <span class="pink">{{ $event->title }}</span>
                                    </h4>

                                    <div class="event-meta">

                                        <p>
                                            <i class="fas fa-map-marker-alt pink"></i>
                                            {{ $event->location }}
                                        </p>

                                        <p>
                                            <i class="fas fa-calendar-alt pink"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>

                                        @if($event->creator_name)
                                            <p>
                                                <i class="fas fa-user pink"></i>
                                                Organized by:
                                                {{ $event->creator_name }}
                                            </p>
                                        @endif

                                    </div>

                                    <p class="event-description">
                                        {{ Str::limit(strip_tags($event->description), 120) }}
                                    </p>

                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="btn mt-3"
                                    >
                                        VIEW EVENT
                                    </a>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="event-empty text-center">
                                No past events archived yet.
                            </div>
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</section>


<style>

/* =========================================================
   EVENT OVERVIEW
========================================================= */

.events-overview {
    padding-top: 90px;
    padding-bottom: 90px;
}

.events-overview .overview-title h2 {
    font-weight: 700;
    line-height: 1.3;
}

.event-overview-category {
    position: relative;
}

.category-heading p {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
}

.category-heading h3 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 0;
}

.event-overview-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, .08);
    transition: all .3s ease;
}

.event-overview-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, .12);
}

.event-overview-image {
    height: 240px;
    position: relative;
    overflow: hidden;
}

.event-overview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}

.event-overview-card:hover .event-overview-image img {
    transform: scale(1.06);
}

.event-status {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 7px 14px;
    border-radius: 30px;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
}

.live-status {
    background: #ef4444;
}

.upcoming-status {
    background: #a855f7;
}

.past-status {
    background: #6b21a8;
}

.event-overview-content {
    padding: 25px;
}

.event-overview-content h4 {
    font-size: 19px;
    line-height: 1.45;
    font-weight: 700;
    margin-bottom: 18px;
}

.event-meta p {
    margin-bottom: 8px;
    font-size: 14px;
    color: #666;
}

.event-meta i {
    width: 18px;
}

.event-description {
    color: #777;
    font-size: 14px;
    line-height: 1.7;
    margin-top: 15px;
}

.event-overview-content .btn {
    display: inline-block;
}

.event-empty {
    padding: 25px;
    background: #fafafa;
    border: 1px dashed #ddd;
    color: #777;
}

.past-event-card {
    opacity: .88;
}

.past-event-card:hover {
    opacity: 1;
}

</style>