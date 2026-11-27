@extends('layouts.master')

@section('title', 'Payment Confirmed - Order ' . $order->order_number)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">
@endpush

@section('content')

@php
    $attendeeTotal = $order->attendees->count();
@endphp

<div class="suc">

    {{-- =========================================================
    HERO
    ========================================================= --}}

    <header class="suc-hero">

        <div class="container">

            <ol class="suc-steps" aria-label="Registration progress">

                <li class="is-done">
                    <span class="suc-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Tickets
                </li>

                <li class="is-done">
                    <span class="suc-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Participant details
                </li>

                <li class="is-done">
                    <span class="suc-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Waiver
                </li>

                <li class="is-done">
                    <span class="suc-steps__n" aria-hidden="true"><i class="fas fa-check"></i></span>
                    Payment
                </li>

            </ol>

            <div class="suc-hero__row">

                <span class="suc-check" aria-hidden="true">
                    <svg viewBox="0 0 52 52" width="52" height="52">
                        <path d="M14 27 L23 36 L39 17" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>

                <div>

                    <h1 class="suc-title">You're all set</h1>

                    <p class="suc-lead">
                        Your payment is confirmed and your registration is complete.
                        {{ $attendeeTotal === 1 ? 'Your ticket is' : 'Your tickets are' }} ready to download.
                    </p>

                </div>

            </div>

        </div>

    </header>


    {{-- =========================================================
    MAIN
    ========================================================= --}}

    <main class="suc-main" id="receipt-capture-area">

        <div class="container">

            <div class="suc-grid">

                {{-- =================================================
                     RECEIPT TICKET
                ================================================== --}}

                <section class="suc-ticket" aria-label="Receipt">

                    <div class="suc-ticket__top">

                        <img
                            src="{{ asset('assets/img/logo/logo.jpg') }}"
                            alt="Event Plus"
                            class="suc-logo"
                        >

                        <span class="suc-paid">
                            <i class="fas fa-check" aria-hidden="true"></i>
                            Payment confirmed
                        </span>

                        <p class="suc-label">Total paid</p>

                        <p class="suc-amount">
                            {{ number_format($order->total_amount, 2) }} <small>MMK</small>
                        </p>

                        <dl class="suc-rows">

                            <div>
                                <dt>Order number</dt>
                                <dd class="suc-order">#{{ $order->order_number }}</dd>
                            </div>

                            <div>
                                <dt>Attendees</dt>
                                <dd>{{ $attendeeTotal }} {{ $attendeeTotal === 1 ? 'attendee' : 'attendees' }}</dd>
                            </div>

                            {{-- GROUP BUY: shown only for group buy orders --}}
                            @if($order->isGroupBuy())

                                <div>
                                    <dt>Group buy applied</dt>
                                    <dd>
                                        {{ $order->group_buy_name ?: $order->group_buy_quantity . '-ticket group' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt>Group discount</dt>
                                    <dd>- {{ number_format($order->group_buy_discount, 2) }} MMK</dd>
                                </div>

                            @endif

                        </dl>

                    </div>

                    <div class="suc-ticket__perf" aria-hidden="true"></div>

                    <div class="suc-ticket__stub">

                        <button type="button" id="download-receipt-btn" class="suc-btn suc-btn--primary">
                            <i class="fas fa-download" aria-hidden="true"></i>
                            <span>{{ $attendeeTotal > 1 ? 'Download all tickets (ZIP)' : 'Download ticket' }}</span>
                        </button>

                        <p class="suc-error" id="download-error" role="alert" hidden>
                            We couldn't prepare your ticket. Please try again. If it keeps failing, contact the organiser with your order number.
                        </p>

                        <a href="{{ route('home') }}" class="suc-btn suc-btn--outline">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>Return to home</span>
                        </a>

                        <p class="suc-note">
                            Each ticket has its own QR code for check-in. Keep it safe and show it when asked.
                        </p>

                    </div>

                </section>


                {{-- =================================================
                     SIDE: TICKET LIST + NEXT STEPS
                ================================================== --}}

                <aside class="suc-side">

                    <section class="suc-card" aria-labelledby="suc-tickets-title">

                        <h2 class="suc-h" id="suc-tickets-title">
                            {{ $attendeeTotal === 1 ? 'Your ticket' : 'Your tickets' }}
                        </h2>

                        <ul class="suc-people">

                            @foreach($order->attendees as $index => $attendee)

                                <li>

                                    <span class="suc-people__icon" aria-hidden="true">
                                        <i class="fas fa-ticket-alt"></i>
                                    </span>

                                    <span class="suc-people__text">
                                        <span class="suc-people__name">{{ $attendee->full_name }}</span>

                                        @if(filled($attendee->ticket_code))
                                            <span class="suc-people__code">{{ $attendee->ticket_code }}</span>
                                        @endif
                                    </span>

                                    @if($attendeeTotal > 1)
                                        <button
                                            type="button"
                                            class="suc-mini"
                                            data-ticket-index="{{ $index }}"
                                            aria-label="Download ticket for {{ $attendee->full_name }}"
                                        >
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <span>Download</span>
                                        </button>
                                    @endif

                                </li>

                            @endforeach

                        </ul>

                    </section>


                    <section class="suc-card" aria-labelledby="suc-next-title">

                        <h2 class="suc-h" id="suc-next-title">What to do next</h2>

                        <ol class="suc-how">

                            <li>
                                <span class="suc-how__n" aria-hidden="true">1</span>
                                <span>Download {{ $attendeeTotal === 1 ? 'your ticket' : 'your tickets' }} and save {{ $attendeeTotal === 1 ? 'it' : 'them' }} on your phone.</span>
                            </li>

                            <li>
                                <span class="suc-how__n" aria-hidden="true">2</span>
                                <span>Bring {{ $attendeeTotal === 1 ? 'it' : 'them' }} to the event, on your phone or printed.</span>
                            </li>

                            <li>
                                <span class="suc-how__n" aria-hidden="true">3</span>
                                <span>Show the QR code at check-in.</span>
                            </li>

                        </ol>

                    </section>

                </aside>

            </div>

        </div>

    </main>

</div>


{{-- =========================================================
     TICKETS USING SAVED TICKET DESIGN STUDIO SETTINGS
========================================================= --}}

<div class="hidden-ticket-container" id="ticket-nodes-wrapper">

    @foreach($order->attendees as $index => $attendee)

        @php
            $attendee->loadMissing('ticketCategory.event');

            $category = $attendee->ticketCategory;
            $ticketEvent = $category->event ?? $order->event;

            $design = $category->ticket_design ?? [];
            $design = is_array($design) ? $design : [];

            $elements = is_array($design['elements'] ?? null)
                ? $design['elements']
                : [];

            /*
             * Load the saved background from public storage.
             */
            $ticketBgBase64 = null;
            $background = $design['background'] ?? null;

            if (is_string($background) && $background !== '') {
                try {
                    if (preg_match('~^https?://~i', $background)) {
                        $imageData = @file_get_contents(
                            $background,
                            false,
                            stream_context_create([
                                'http' => ['timeout' => 5],
                            ])
                        );
                    } else {
                        $backgroundPath = ltrim($background, '/');

                        if (str_starts_with($backgroundPath, 'storage/')) {
                            $backgroundPath = substr(
                                $backgroundPath,
                                strlen('storage/')
                            );
                        }

                        $imageData = \Illuminate\Support\Facades\Storage::disk('public')
                            ->exists($backgroundPath)
                                ? \Illuminate\Support\Facades\Storage::disk('public')
                                    ->get($backgroundPath)
                                : null;
                    }

                    if (!empty($imageData)) {
                        $mime = 'image/jpeg';

                        if (function_exists('finfo_open')) {
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);

                            if ($finfo) {
                                $detectedMime = finfo_buffer($finfo, $imageData);
                                finfo_close($finfo);

                                if (in_array($detectedMime, [
                                    'image/jpeg',
                                    'image/png',
                                    'image/gif',
                                    'image/webp',
                                ], true)) {
                                    $mime = $detectedMime;
                                }
                            }
                        }

                        $ticketBgBase64 = 'data:' . $mime
                            . ';base64,' . base64_encode($imageData);
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning(
                        'Could not load ticket background for success page.',
                        [
                            'attendee_id' => $attendee->id,
                            'background' => $background,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            }

            /*
             * Ensure the attendee has a verification token.
             */
            if (empty($attendee->verification_token)) {
                $attendee->verification_token =
                    \Illuminate\Support\Str::random(64);

                $attendee->save();
            }

            $verificationUrl = route('ticket.verify', [
                'token' => $attendee->verification_token,
            ]);

            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=310x310&data='
                . urlencode($verificationUrl);

            $qrContext = stream_context_create([
                'http' => ['timeout' => 5],
            ]);

            $qrImageData = @file_get_contents(
                $qrApiUrl,
                false,
                $qrContext
            );

            $qrBase64 = $qrImageData
                ? 'data:image/png;base64,' . base64_encode($qrImageData)
                : null;

            /*
             * Values available to the saved design elements.
             */
            $elementValues = [
                'attendee_name' => $attendee->full_name ?? '',
                'phone' => $attendee->phone ?? '',
                'ticket_number' => $attendee->ticket_code ?? '',
                'bib_number' => $attendee->bib_number ?? '',
                'event_name' => $ticketEvent->title ?? '',
            ];
        @endphp

        <div
            class="ticket-wrapper"
            id="render-ticket-node-{{ $index }}"
        >

            @if($ticketBgBase64)
                <img
                    src="{{ $ticketBgBase64 }}"
                    class="ticket-bg"
                    alt="Ticket background"
                >
            @endif

            @foreach($elements as $element)

                @php
                    $type = $element['type'] ?? '';

                    $x = max(0, min(1600, (float) ($element['x'] ?? 0)));
                    $y = max(0, min(517, (float) ($element['y'] ?? 0)));

                    $width = max(1, min(1600, (float) ($element['width'] ?? 100)));
                    $height = max(1, min(517, (float) ($element['height'] ?? 40)));

                    $fontSize = max(6, min(120, (float) ($element['fontSize'] ?? 24)));
                    $rotation = max(-360, min(360, (float) ($element['rotation'] ?? 0)));

                    $color = $element['color'] ?? '#222222';

                    if (!is_string($color) ||
                        !preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                        $color = '#222222';
                    }

                    $elementStyle = sprintf(
                        'left:%.2fpx;top:%.2fpx;width:%.2fpx;height:%.2fpx;font-size:%.2fpx;color:%s;transform:rotate(%.2fdeg);',
                        $x,
                        $y,
                        $width,
                        $height,
                        $fontSize,
                        $color,
                        $rotation
                    );

                    /*
                     * Preserve the existing JavaScript ticket filename logic.
                     */
                    $elementClass = 'ticket-element';

                    if ($type === 'qr') {
                        $elementClass .= ' ticket-element-qr';
                    } elseif ($type === 'ticket_number') {
                        $elementClass .= ' ticket-number';
                    }
                @endphp

                <div
                    class="{{ $elementClass }}"
                    style="{{ $elementStyle }}"
                >
                    @if($type === 'qr')

                        @if($qrBase64)
                            <img
                                src="{{ $qrBase64 }}"
                                alt="Ticket verification QR code"
                            >
                        @endif

                    @elseif(array_key_exists($type, $elementValues))

                        <span class="ticket-element-text">
                            {{ $elementValues[$type] }}
                        </span>

                    @endif
                </div>

            @endforeach

        </div>

    @endforeach

</div>

@endsection


{{-- =========================================================
     STYLES
========================================================= --}}

@push('styles')

<style>

/* =========================================================
   TOKENS (shared with the rest of the booking flow)
========================================================= */

.suc {
    --suc-ink: #17122b;
    --suc-muted: #6b6580;
    --suc-pink: #db2777;
    --suc-pink-dark: #be185d;
    --suc-violet: #7c3aed;
    --suc-line: #e6e1f3;
    --suc-bg: #faf8ff;
    --suc-green: #15803d;
    --suc-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    color: var(--suc-ink);
}


/* =========================================================
   HERO
========================================================= */

.suc-hero {
    padding: clamp(1.5rem, 3.5vw, 2.5rem) 0 clamp(4rem, 8vw, 6rem);
    background:
        radial-gradient(55% 90% at 90% 30%, rgba(124, 58, 237, 0.4), transparent 70%),
        var(--suc-ink);
    color: #fff;
}

.suc-steps {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin: 0 0 2rem;
    padding: 0;
    list-style: none;
}

.suc-steps li {
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

.suc-steps__n {
    display: grid;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: var(--suc-ink);
    font-size: 0.7rem;
}

.suc-hero__row {
    display: flex;
    align-items: center;
    gap: clamp(1rem, 3vw, 1.75rem);
}

.suc-check {
    display: grid;
    flex: none;
    place-items: center;
    width: clamp(4.25rem, 10vw, 5.5rem);
    height: clamp(4.25rem, 10vw, 5.5rem);
    border-radius: 50%;
    background: var(--suc-pink);
    box-shadow: 0 0 0 10px rgba(219, 39, 119, 0.22);
}

.suc-check svg {
    width: 58%;
    height: 58%;
}

.suc-check path {
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: suc-draw 0.6s 0.35s ease-out forwards;
}

@keyframes suc-draw {
    to { stroke-dashoffset: 0; }
}

.suc-title {
    margin: 0 0 0.6rem;
    color: #fff;
    font-family: var(--suc-display);
    font-size: clamp(2.2rem, 5.5vw, 3.8rem);
    font-weight: 800;
    line-height: 1.04;
    letter-spacing: -0.025em;
}

.suc-lead {
    max-width: 52ch;
    margin: 0;
    color: rgba(255, 255, 255, 0.82);
    font-size: 1.0625rem;
    line-height: 1.65;
}


/* =========================================================
   MAIN
========================================================= */

.suc-main {
    position: relative;
    z-index: 3;
    margin-top: -2rem;
    padding: clamp(2rem, 5vw, 3rem) 0 clamp(3.5rem, 8vw, 6rem);
    border-radius: 32px 32px 0 0;
    background: var(--suc-bg);
}

.suc-grid {
    display: grid;
    grid-template-columns: minmax(0, 440px) minmax(0, 1fr);
    gap: 1.75rem;
    align-items: start;
    max-width: 960px;
    margin-inline: auto;
}

.suc-label {
    margin: 0;
    color: var(--suc-muted);
    font-size: 0.9rem;
    font-weight: 600;
}


/* =========================================================
   RECEIPT TICKET
========================================================= */

.suc-ticket {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--suc-line);
    border-radius: 24px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 18px 40px rgba(23, 18, 43, 0.08);
    animation: suc-drop 0.8s cubic-bezier(0.2, 0.9, 0.25, 1.05) both;
}

@keyframes suc-drop {
    from { opacity: 0; transform: translateY(-24px); }
    to   { opacity: 1; transform: translateY(0); }
}

.suc-ticket__top {
    padding: 1.75rem 1.75rem 1.5rem;
    text-align: center;
}

.suc-logo {
    display: block;
    width: auto;
    max-width: 200px;
    height: 48px;
    margin: 0 auto 1.25rem;
    object-fit: contain;
}

.suc-paid {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
    padding: 0.4rem 0.95rem;
    border-radius: 999px;
    background: #ecfdf3;
    color: var(--suc-green);
    font-size: 0.9rem;
    font-weight: 700;
}

.suc-paid i {
    font-size: 0.75rem;
}

.suc-amount {
    margin: 0.2rem 0 1.5rem;
    font-family: var(--suc-display);
    font-size: clamp(2.1rem, 6vw, 2.8rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.025em;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.suc-amount small {
    color: var(--suc-muted);
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0;
}

.suc-rows {
    display: grid;
    gap: 0.75rem;
    margin: 0;
    text-align: left;
}

.suc-rows > div {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.8rem 1rem;
    border-radius: 14px;
    background: var(--suc-bg);
}

.suc-rows dt {
    margin: 0;
    color: var(--suc-muted);
    font-size: 0.9rem;
    font-weight: 600;
}

.suc-rows dd {
    margin: 0;
    font-weight: 700;
    text-align: right;
    overflow-wrap: anywhere;
}

.suc-order {
    font-variant-numeric: tabular-nums;
}


/* ---------- Perforation with punched notches ---------- */

.suc-ticket__perf {
    position: relative;
    height: 0;
    margin: 0 26px;
    border-top: 2px dashed rgba(23, 18, 43, 0.2);
}

.suc-ticket__perf::before,
.suc-ticket__perf::after {
    content: "";
    position: absolute;
    top: -14px;
    width: 26px;
    height: 26px;
    border: 1px solid var(--suc-line);
    border-radius: 50%;
    background: var(--suc-bg);
}

.suc-ticket__perf::before {
    left: -39px;
    clip-path: inset(0 0 0 50%);
}

.suc-ticket__perf::after {
    right: -39px;
    clip-path: inset(0 50% 0 0);
}


/* ---------- Stub ---------- */

.suc-ticket__stub {
    display: grid;
    gap: 0.75rem;
    padding: 1.5rem 1.75rem 1.75rem;
}

.suc-note {
    margin: 0.25rem 0 0;
    color: var(--suc-muted);
    font-size: 0.875rem;
    line-height: 1.55;
    text-align: center;
}

.suc-error {
    margin: 0;
    padding: 0.8rem 1rem;
    border-radius: 14px;
    background: #fff1f2;
    color: #9f1239;
    font-size: 0.9rem;
    line-height: 1.5;
}

.suc-error[hidden] {
    display: none;
}


/* ---------- Buttons ---------- */

.suc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    width: 100%;
    min-height: 3.25rem;
    padding: 0.8rem 1.4rem;
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

.suc-btn:focus-visible,
.suc-mini:focus-visible {
    outline: 3px solid var(--suc-violet);
    outline-offset: 3px;
}

.suc-btn--primary {
    background: var(--suc-pink);
    color: #fff !important;
    box-shadow: 0 10px 24px rgba(219, 39, 119, 0.3);
}

.suc-btn--primary:hover:not(:disabled) {
    background: var(--suc-pink-dark);
    transform: translateY(-2px);
}

.suc-btn--primary:disabled {
    background: #e4dff0;
    color: #8a849c !important;
    box-shadow: none;
    cursor: progress;
}

.suc-btn--outline {
    border-color: var(--suc-ink);
    color: var(--suc-ink) !important;
}

.suc-btn--outline:hover {
    background: var(--suc-ink);
    color: #fff !important;
}


/* =========================================================
   SIDE
========================================================= */

.suc-side {
    display: grid;
    gap: 1.25rem;
}

.suc-card {
    padding: clamp(1.25rem, 3vw, 1.75rem);
    border: 1px solid var(--suc-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.suc-h {
    margin: 0 0 1.1rem;
    font-family: var(--suc-display);
    font-size: 1.3rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.015em;
}

.suc-people {
    display: grid;
    gap: 0.75rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.suc-people li {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 0.9rem;
    border: 1.5px solid var(--suc-line);
    border-radius: 16px;
}

.suc-people__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 12px;
    background: #f3edfc;
    color: var(--suc-pink);
}

.suc-people__text {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.suc-people__name {
    font-weight: 700;
    overflow-wrap: anywhere;
}

.suc-people__code {
    color: var(--suc-muted);
    font-size: 0.875rem;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.suc-mini {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    flex: none;
    min-height: 2.5rem;
    padding: 0.5rem 0.95rem;
    border: 1.5px solid var(--suc-ink);
    border-radius: 999px;
    background: transparent;
    color: var(--suc-ink);
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.suc-mini i {
    font-size: 0.8rem;
}

.suc-mini:hover:not(:disabled) {
    background: var(--suc-ink);
    color: #fff;
}

.suc-mini:disabled {
    opacity: 0.55;
    cursor: progress;
}

.suc-how {
    display: grid;
    gap: 1rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.suc-how li {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    color: #4b4563;
    line-height: 1.6;
}

.suc-how__n {
    display: grid;
    flex: none;
    place-items: center;
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 50%;
    background: var(--suc-ink);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
}


/* =========================================================
   SMALL SCREENS
========================================================= */

@media (max-width: 860px) {

    .suc-grid {
        grid-template-columns: minmax(0, 1fr);
        max-width: 480px;
    }

}

@media (max-width: 575px) {

    .suc-hero__row {
        flex-direction: column;
        align-items: flex-start;
    }

    .suc-people li {
        flex-wrap: wrap;
    }

    .suc-mini {
        width: 100%;
        justify-content: center;
    }

}

@media (max-width: 420px) {

    .suc-ticket__top,
    .suc-ticket__stub {
        padding-inline: 1.15rem;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    .suc *,
    .suc *::before,
    .suc *::after {
        animation: none !important;
        transition: none !important;
    }

    .suc-check path {
        stroke-dashoffset: 0;
    }

}


/* =========================================================
   TICKET DESIGN STUDIO RENDERING (used to build the PDFs)
========================================================= */

.hidden-ticket-container {
    position: absolute;
    left: -99999px;
    top: 0;
    pointer-events: none;
}

.ticket-wrapper {
    position: relative;
    width: 1600px;
    height: 517px;
    overflow: hidden;
    background: #fff;
}

.ticket-bg {
    position: absolute;
    inset: 0;
    width: 1600px;
    height: 517px;
    object-fit: fill;
}

.ticket-element {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 4px;
    overflow: hidden;
    text-align: center;
    white-space: nowrap;
    transform-origin: center center;
}

.ticket-element-text {
    display: block;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    line-height: 1.2;
}

.ticket-element-qr {
    padding: 0;
    background: #fff;
}

.ticket-element-qr img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: fill;
}

</style>

@endpush


{{-- =========================================================
     EXTERNAL LIBRARIES + DOWNLOAD LOGIC
========================================================= --}}

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.2/jszip.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const downloadBtn = document.getElementById('download-receipt-btn');
    if (!downloadBtn) return;

    const errorBox = document.getElementById('download-error');
    const miniButtons = document.querySelectorAll('[data-ticket-index]');

    const attendeeCount = {{ $order->attendees->count() }};

    // Only what the downloads need (names and ticket codes)
    const attendees = @json(
        $order->attendees
            ->map(fn ($a) => ['full_name' => $a->full_name, 'ticket_code' => $a->ticket_code])
            ->values()
    );

    let busy = false;


    /* =========================================================
       HELPERS
    ========================================================= */

    function setBusy(state) {
        busy = state;

        downloadBtn.disabled = state;
        downloadBtn.setAttribute('aria-busy', state ? 'true' : 'false');

        miniButtons.forEach(function (btn) {
            btn.disabled = state;
        });
    }

    function showError(show) {
        if (errorBox) errorBox.hidden = !show;
    }

    /* Renders one ticket node to a landscape PDF */
    async function buildPdf(index) {

        const ticketNode = document.getElementById('render-ticket-node-' + index);

        if (!ticketNode) {
            throw new Error('Ticket node not found: ' + index);
        }

        const canvas = await html2canvas(ticketNode, {
            scale: 2,
            useCORS: true,
            allowTaint: false,
            backgroundColor: '#ffffff'
        });

        const imgData = canvas.toDataURL('image/jpeg', 0.95);

        const { jsPDF } = window.jspdf;

        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'px',
            format: [canvas.width, canvas.height]
        });

        pdf.addImage(imgData, 'JPEG', 0, 0, canvas.width, canvas.height);

        const numberEl = ticketNode.querySelector('.ticket-number');

        const ticketNumText =
            (numberEl ? numberEl.textContent.trim() : '') ||
            (attendees[index] && attendees[index].ticket_code) ||
            ('Ticket' + (index + 1));

        const sanitizedName =
            attendees[index] && attendees[index].full_name
                ? attendees[index].full_name.replace(/[^a-zA-Z0-9]/g, '_')
                : 'Attendee_' + (index + 1);

        return {
            pdf: pdf,
            filename: 'Ticket_' + ticketNumText + '_' + sanitizedName + '.pdf'
        };
    }


    /* =========================================================
       ONE TICKET (per-attendee button)
    ========================================================= */

    miniButtons.forEach(function (btn) {

        btn.addEventListener('click', async function () {

            if (busy) return;

            const index = parseInt(btn.getAttribute('data-ticket-index'), 10);
            const originalHtml = btn.innerHTML;

            showError(false);
            setBusy(true);

            btn.innerHTML =
                '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i><span>Preparing…</span>';

            try {
                const result = await buildPdf(index);
                result.pdf.save(result.filename);
            } catch (err) {
                console.error('Download processing error:', err);
                showError(true);
            } finally {
                btn.innerHTML = originalHtml;
                setBusy(false);
            }

        });

    });


    /* =========================================================
       MAIN BUTTON: ONE PDF, OR A ZIP OF ALL TICKETS
    ========================================================= */

    downloadBtn.addEventListener('click', async function () {

        if (busy) return;

        const originalHtml = downloadBtn.innerHTML;

        showError(false);
        setBusy(true);

        try {

            if (attendeeCount === 1) {

                downloadBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>' +
                    '<span>Generating ticket PDF…</span>';

                const result = await buildPdf(0);
                result.pdf.save(result.filename);

            } else {

                const zip = new JSZip();
                const usedNames = {};

                for (let i = 0; i < attendeeCount; i++) {

                    downloadBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>' +
                        '<span>Packing PDF (' + (i + 1) + '/' + attendeeCount + ')…</span>';

                    const result = await buildPdf(i);

                    // Keep file names unique inside the ZIP
                    let filename = result.filename;

                    if (usedNames[filename]) {
                        filename = filename.replace(/\.pdf$/, '_' + (i + 1) + '.pdf');
                    }

                    usedNames[filename] = true;

                    zip.file(filename, result.pdf.output('blob'));
                }

                downloadBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i>' +
                    '<span>Compiling ZIP…</span>';

                const content = await zip.generateAsync({ type: 'blob' });

                const link = document.createElement('a');
                link.href = URL.createObjectURL(content);
                link.download = 'EventPlus_Tickets_{{ $order->order_number }}.zip';
                link.click();

                URL.revokeObjectURL(link.href);

            }

        } catch (err) {

            console.error('Download processing error:', err);
            showError(true);

        } finally {

            downloadBtn.innerHTML = originalHtml;
            setBusy(false);

        }

    });

});
</script>

@endpush