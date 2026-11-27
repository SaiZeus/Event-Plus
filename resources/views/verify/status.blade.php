@extends('layouts.master')

@section('title', 'Ticket Verification Status')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

@php
    $event = $attendee->ticketCategory->event ?? null;

    $eventDate = null;
    try {
        if ($event && !empty($event->event_date)) {
            $eventDate = \Carbon\Carbon::parse($event->event_date)->format('F d, Y - h:i A');
        }
    } catch (\Throwable $e) {
        $eventDate = null;
    }

    $age = null;
    try {
        if (!empty($attendee->dob)) {
            $age = \Carbon\Carbon::parse($attendee->dob)->age;
        }
    } catch (\Throwable $e) {
        $age = null;
    }

    $medical = trim((string) ($attendee->medical_conditions ?? ''));
    $hasMedical = $medical !== '' && !in_array(strtolower($medical), ['none', 'no', 'n/a', '-'], true);

    // Turns a phone-like value into a tel: link target (or null when it is not a phone number).
    $telHref = function ($value) {
        $value = trim((string) $value);
        if ($value === '' || !preg_match('/^[\d\s+()\-]{6,}$/', $value)) {
            return null;
        }
        return 'tel:' . preg_replace('/[^\d+]/', '', $value);
    };

    $phoneHref = $telHref($attendee->phone ?? '');
    $emergencyHref = $telHref($attendee->emergency_contact ?? '');
    $emergencyPhoneHref = $telHref($attendee->emergency_phone ?? '');
@endphp

<style>
.tv {
    --tv-accent: #6d28d9;
    --tv-accent-dark: #5b21b6;
    --tv-accent-soft: #f3effd;
    --tv-ink: #1b2133;
    --tv-muted: #667085;
    --tv-line: #e6e3ef;
    --tv-bg: #f5f4fa;
    --tv-green: #0b5a3a;
    --tv-green-soft: #e4f6ec;
    --tv-amber: #7a4a00;
    --tv-amber-soft: #fff1d6;
    --tv-red: #b42318;
    --tv-red-soft: #fdeceb;
    min-height: calc(100vh - 80px);
    padding: 28px 16px 56px;
    background: var(--tv-bg);
    color: var(--tv-ink);
    font-size: 15px;
    line-height: 1.5;
}
.tv *, .tv *::before, .tv *::after { box-sizing: border-box; }
.tv-wrap { max-width: 560px; margin: 0 auto; }

/* ---------- Ticket ---------- */
.tv-ticket {
    overflow: hidden; border: 1px solid var(--tv-line); border-radius: 20px;
    background: #fff; box-shadow: 0 12px 40px rgba(27, 33, 51, .08);
}

/* ---------- Status ---------- */
.tv-status { display: flex; align-items: center; gap: 14px; padding: 20px 22px; }
.tv-status.is-valid { background: var(--tv-green-soft); color: var(--tv-green); }
.tv-status.is-expired { background: var(--tv-amber-soft); color: var(--tv-amber); }
.tv-status-icon {
    display: grid; place-items: center; flex-shrink: 0; width: 46px; height: 46px;
    border-radius: 50%; background: rgba(255, 255, 255, .75); font-size: 22px;
}
.tv-status-title { margin: 0; font-size: 21px; font-weight: 750; letter-spacing: -.2px; line-height: 1.2; }
.tv-status-sub { margin: 3px 0 0; font-size: 14px; }

/* ---------- Key facts ---------- */
.tv-body { padding: 24px 22px 22px; }
.tv-name { margin: 0; font-size: 27px; font-weight: 750; letter-spacing: -.4px; line-height: 1.2; overflow-wrap: anywhere; }
.tv-code {
    display: inline-flex; align-items: center; gap: 8px; margin: 10px 0 0; padding: 5px 12px;
    border-radius: 8px; background: var(--tv-accent-soft); color: var(--tv-accent-dark);
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: 14px; font-weight: 700;
    overflow-wrap: anywhere;
}
.tv-facts { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 16px; margin: 22px 0 0; }
.tv-fact { margin: 0; min-width: 0; }
.tv-fact.is-wide { grid-column: 1 / -1; }
.tv-fact dt { margin: 0 0 2px; color: var(--tv-muted); font-size: 13.5px; font-weight: 600; }
.tv-fact dd { margin: 0; font-size: 16.5px; font-weight: 650; overflow-wrap: anywhere; }

/* ---------- Ticket notches ---------- */
.tv-divider { position: relative; height: 0; margin: 0; padding: 0; overflow: visible; border: 0; border-top: 2px dashed #dcd8e8; background: none; opacity: 1; }
.tv-divider::before, .tv-divider::after {
    content: ""; position: absolute; top: -13px; width: 24px; height: 24px; border-radius: 50%;
    background: var(--tv-bg); border: 1px solid var(--tv-line);
}
.tv-divider::before { left: -13px; }
.tv-divider::after { right: -13px; }

/* ---------- Detail sections ---------- */
.tv-sections { padding: 8px 14px 14px; }
.tv-sections-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 8px 8px 4px; }
.tv-sections-head p { margin: 0; color: var(--tv-muted); font-size: 14px; font-weight: 600; }
.tv-link-btn {
    padding: 6px 8px; border: 0; border-radius: 8px; background: transparent; color: var(--tv-accent);
    font: inherit; font-size: 14px; font-weight: 650; cursor: pointer;
}
.tv-link-btn:hover { background: var(--tv-accent-soft); color: var(--tv-accent-dark); }

.tv-sec { border-bottom: 1px solid var(--tv-line); }
.tv-sec:last-child { border-bottom: 0; }
.tv-sec > summary {
    display: flex; align-items: center; gap: 12px; min-height: 60px; padding: 10px 8px; list-style: none; cursor: pointer;
    border-radius: 12px;
}
.tv-sec > summary::-webkit-details-marker { display: none; }
.tv-sec > summary:hover { background: #faf9fd; }
.tv-sec-icon {
    display: grid; place-items: center; flex-shrink: 0; width: 38px; height: 38px;
    border-radius: 10px; background: var(--tv-accent-soft); color: var(--tv-accent); font-size: 15px;
}
.tv-sec-text { flex: 1; min-width: 0; }
.tv-sec-text strong { display: block; font-size: 16px; font-weight: 700; }
.tv-sec-text span { display: block; color: var(--tv-muted); font-size: 13.5px; }
.tv-sec-flag {
    flex-shrink: 0; padding: 2px 10px; border-radius: 999px; background: var(--tv-red-soft);
    color: var(--tv-red); font-size: 13px; font-weight: 700;
}
.tv-chevron { flex-shrink: 0; color: var(--tv-muted); transition: transform .2s; }
.tv-sec[open] .tv-chevron { transform: rotate(180deg); }

.tv-list { margin: 0; padding: 2px 8px 14px; }
.tv-row {
    display: grid; grid-template-columns: 140px minmax(0, 1fr); gap: 4px 16px; margin: 0;
    padding: 11px 0; border-top: 1px solid #eef0f5;
}
.tv-row:first-child { border-top: 0; }
.tv-row dt { color: var(--tv-muted); font-size: 14px; font-weight: 600; }
.tv-row dd { margin: 0; font-weight: 600; overflow-wrap: anywhere; }
.tv-row dd small { display: block; margin-top: 2px; color: var(--tv-muted); font-size: 14px; font-weight: 500; }
.tv-row a { color: var(--tv-accent); text-decoration: none; }
.tv-row a:hover { color: var(--tv-accent-dark); text-decoration: underline; }
.tv-tag {
    display: inline-flex; align-items: center; gap: 6px; margin-left: 8px; padding: 2px 10px;
    border-radius: 999px; font-size: 13px; font-weight: 700; vertical-align: middle;
}
.tv-tag-red { background: var(--tv-red-soft); color: var(--tv-red); }
.tv-empty { color: var(--tv-muted); font-weight: 500; }

/* ---------- Actions ---------- */
.tv-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 18px; }
.tv-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 50px;
    padding: 10px 20px; border-radius: 12px; font: inherit; font-size: 16px; font-weight: 700;
    text-decoration: none; cursor: pointer; transition: background-color .15s;
}
.tv-btn-primary { border: 0; background: var(--tv-accent); color: #fff; }
.tv-btn-primary:hover { background: var(--tv-accent-dark); color: #fff; }
.tv-foot { margin: 18px 0 0; color: var(--tv-muted); font-size: 14px; text-align: center; }
.tv-foot i { margin-right: 6px; color: var(--tv-accent); }

.tv a:focus-visible, .tv button:focus-visible, .tv summary:focus-visible { outline: 3px solid #b9a3f3; outline-offset: 2px; }

/* ---------- Small screens ---------- */
@media (max-width: 480px) {
    .tv { padding: 16px 12px 40px; min-height: calc(100vh - 60px); }
    .tv-status { padding: 18px; }
    .tv-body { padding: 20px 18px 20px; }
    .tv-name { font-size: 24px; }
    .tv-row { grid-template-columns: 1fr; }
    .tv-sections { padding: 6px 8px 10px; }
}

@media print {
    .tv { padding: 0; background: #fff; }
    .tv-actions, .tv-sections-head button { display: none !important; }
    .tv-ticket { box-shadow: none; }
}

@media (prefers-reduced-motion: reduce) {
    .tv * { transition: none !important; }
}
</style>

<main class="tv">
    <div class="tv-wrap">

        <article class="tv-ticket" aria-labelledby="tv-name">

            {{-- STATUS --}}
            @if($isExpired)

                <header class="tv-status is-expired" role="status">
                    <span class="tv-status-icon" aria-hidden="true"><i class="fa-solid fa-clock-rotate-left"></i></span>
                    <div>
                        <p class="tv-status-title">Ticket expired</p>
                        <p class="tv-status-sub">This event has passed and the ticket status is now expired.</p>
                    </div>
                </header>

            @else

                <header class="tv-status is-valid" role="status">
                    <span class="tv-status-icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                        <p class="tv-status-title">Valid ticket</p>
                        <p class="tv-status-sub">This ticket is valid and the event is currently active.</p>
                    </div>
                </header>

            @endif

            {{-- KEY FACTS --}}
            <div class="tv-body">

                <h1 class="tv-name" id="tv-name">{{ $attendee->full_name }}</h1>

                <p class="tv-code">
                    <i class="fa-solid fa-barcode" aria-hidden="true"></i>
                    {{ $attendee->ticket_code }}
                </p>

                <dl class="tv-facts">

                    <div class="tv-fact is-wide">
                        <dt>Event</dt>
                        <dd>{{ $event->title ?? 'N/A' }}</dd>
                    </div>

                    <div class="tv-fact">
                        <dt>Ticket category</dt>
                        <dd>{{ $attendee->ticketCategory->name ?? 'N/A' }}</dd>
                    </div>

                    @if(!empty($attendee->bib_number))
                        <div class="tv-fact">
                            <dt>BIB number</dt>
                            <dd>{{ $attendee->bib_number }}</dd>
                        </div>
                    @endif

                    <div class="tv-fact is-wide">
                        <dt>Event date</dt>
                        <dd>{{ $eventDate ?? 'N/A' }}</dd>
                    </div>

                    @if(!empty($attendee->tshirt_size))
                        <div class="tv-fact">
                            <dt>T-shirt size</dt>
                            <dd>{{ $attendee->tshirt_size }}</dd>
                        </div>
                    @endif

                </dl>

            </div>

            <hr class="tv-divider">

            {{-- DETAILS --}}
            <div class="tv-sections">

                <div class="tv-sections-head">
                    <p>Attendee details</p>
                    <button type="button" class="tv-link-btn" id="toggleAll" aria-pressed="false">Expand all</button>
                </div>

                {{-- Contact --}}
                <details class="tv-sec">
                    <summary>
                        <span class="tv-sec-icon" aria-hidden="true"><i class="fa-solid fa-phone"></i></span>
                        <span class="tv-sec-text">
                            <strong>Contact</strong>
                            <span>Phone, email and emergency contact</span>
                        </span>
                        <i class="fa-solid fa-chevron-down tv-chevron" aria-hidden="true"></i>
                    </summary>

                    <dl class="tv-list">
                        <div class="tv-row">
                            <dt>Phone</dt>
                            <dd>
                                @if(!empty($attendee->phone))
                                    @if($phoneHref)
                                        <a href="{{ $phoneHref }}">{{ $attendee->phone }}</a>
                                    @else
                                        {{ $attendee->phone }}
                                    @endif
                                @else
                                    <span class="tv-empty">-</span>
                                @endif
                            </dd>
                        </div>

                        <div class="tv-row">
                            <dt>Email</dt>
                            <dd>
                                @if(!empty($attendee->email))
                                    <a href="mailto:{{ $attendee->email }}">{{ $attendee->email }}</a>
                                @else
                                    <span class="tv-empty">-</span>
                                @endif
                            </dd>
                        </div>

                        <div class="tv-row">
                            <dt>Emergency contact</dt>
                            <dd>
                                @if(!empty($attendee->emergency_contact))
                                    @if($emergencyHref)
                                        <a href="{{ $emergencyHref }}">{{ $attendee->emergency_contact }}</a>
                                    @else
                                        {{ $attendee->emergency_contact }}
                                    @endif
                                @else
                                    <span class="tv-empty">-</span>
                                @endif

                                @if(!empty($attendee->emergency_phone))
                                    <small>
                                        @if($emergencyPhoneHref)
                                            <a href="{{ $emergencyPhoneHref }}">{{ $attendee->emergency_phone }}</a>
                                        @else
                                            {{ $attendee->emergency_phone }}
                                        @endif
                                    </small>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </details>

                {{-- Identity --}}
                <details class="tv-sec">
                    <summary>
                        <span class="tv-sec-icon" aria-hidden="true"><i class="fa-solid fa-id-card"></i></span>
                        <span class="tv-sec-text">
                            <strong>Identity</strong>
                            <span>NRC or passport, family and age</span>
                        </span>
                        <i class="fa-solid fa-chevron-down tv-chevron" aria-hidden="true"></i>
                    </summary>

                    <dl class="tv-list">
                        <div class="tv-row">
                            <dt>NRC / Passport</dt>
                            <dd>
                                {{ $attendee->nrc_passport ?? '-' }}
                                <small>{{ $attendee->country ?? 'Myanmar' }}</small>
                            </dd>
                        </div>

                        <div class="tv-row">
                            <dt>Father's name</dt>
                            <dd>{{ $attendee->father_name ?? '-' }}</dd>
                        </div>

                        <div class="tv-row">
                            <dt>Gender</dt>
                            <dd>{{ ucfirst($attendee->gender ?? '-') }}</dd>
                        </div>

                        <div class="tv-row">
                            <dt>Age</dt>
                            <dd>
                                @if($age !== null)
                                    {{ $age }} yrs
                                    <small>Date of birth: {{ $attendee->dob }}</small>
                                @else
                                    <span class="tv-empty">-</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </details>

                {{-- More details --}}
                <details class="tv-sec">
                    <summary>
                        <span class="tv-sec-icon" aria-hidden="true"><i class="fa-solid fa-heart-pulse"></i></span>
                        <span class="tv-sec-text">
                            <strong>Health and background</strong>
                            <span>Medical notes, ITRA, address and promo</span>
                        </span>
                        @if($hasMedical)
                            <span class="tv-sec-flag">Medical note</span>
                        @endif
                        <i class="fa-solid fa-chevron-down tv-chevron" aria-hidden="true"></i>
                    </summary>

                    <dl class="tv-list">
                        <div class="tv-row">
                            <dt>Medical conditions</dt>
                            <dd>
                                {{ $attendee->medical_conditions ?? 'None' }}
                                @if($hasMedical)
                                    <span class="tv-tag tv-tag-red"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Check</span>
                                @endif
                            </dd>
                        </div>

                        @if(!empty($attendee->itra_profile))
                            <div class="tv-row">
                                <dt>ITRA profile</dt>
                                <dd>{{ $attendee->itra_profile }}</dd>
                            </div>
                        @endif

                        <div class="tv-row">
                            <dt>Address</dt>
                            <dd>{{ $attendee->address ?? '-' }}</dd>
                        </div>

                        @if(!empty($attendee->running_experience))
                            <div class="tv-row">
                                <dt>Running experience</dt>
                                <dd>{{ $attendee->running_experience }}</dd>
                            </div>
                        @endif

                        <div class="tv-row">
                            <dt>Promo applied</dt>
                            <dd>{{ $attendee->promo_code ?? 'None' }}</dd>
                        </div>
                    </dl>
                </details>

            </div>

        </article>

        {{-- ACTIONS --}}
        <div class="tv-actions">
            <a href="{{ route('ticket.download_pdf', $attendee->verification_token) }}" class="tv-btn tv-btn-primary">
                <i class="fa-solid fa-download" aria-hidden="true"></i>
                Download report (PDF)
            </a>
        </div>

        <p class="tv-foot">
            <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
            Secure ticket verification
        </p>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sections = Array.prototype.slice.call(document.querySelectorAll('.tv-sec'));
    const toggle = document.getElementById('toggleAll');

    if (!sections.length || !toggle) return;

    function allOpen() {
        return sections.every(function (section) { return section.open; });
    }

    function refresh() {
        const open = allOpen();
        toggle.textContent = open ? 'Collapse all' : 'Expand all';
        toggle.setAttribute('aria-pressed', open ? 'true' : 'false');
    }

    toggle.addEventListener('click', function () {
        const open = !allOpen();
        sections.forEach(function (section) { section.open = open; });
        refresh();
    });

    sections.forEach(function (section) {
        section.addEventListener('toggle', refresh);
    });

    // Show everything when the page is printed or saved as PDF from the browser.
    window.addEventListener('beforeprint', function () {
        sections.forEach(function (section) { section.open = true; });
    });

    refresh();
});
</script>

@endsection