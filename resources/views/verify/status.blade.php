@extends('layouts.master')

@section('title', 'Ticket Verification Status')

@push('styles')
<!-- Font Awesome 6 CDN Fallback to ensure icons render properly -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

<style>
    /* =========================================================
       EVENT PLUS — TICKET VERIFICATION
       ========================================================= */

    .verification-page {
        min-height: calc(100vh - 80px);
        padding: 45px 15px 65px;
        background:
            radial-gradient(
                circle at 8% 8%,
                rgba(124, 58, 237, 0.10),
                transparent 30%
            ),
            radial-gradient(
                circle at 92% 90%,
                rgba(236, 72, 153, 0.09),
                transparent 30%
            ),
            linear-gradient(
                180deg,
                #f8f7fc 0%,
                #f5f7fb 100%
            );
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .verification-wrapper {
        width: 100%;
        max-width: 650px;
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .verification-header {
        text-align: center;
        margin-bottom: 24px;
        animation: epFadeDown .65s ease both;
    }

    .verification-header .small-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 50px;
        background: rgba(255, 255, 255, .88);
        border: 1px solid #e8e2f4;
        color: #6b5b84;
        font-size: .70rem;
        font-weight: 800;
        letter-spacing: .09em;
        text-transform: uppercase;
        box-shadow: 0 6px 20px rgba(76, 29, 149, .06);
    }

    .verification-header .small-label i {
        color: #7c3aed;
        font-size: .85rem;
    }

    .verification-header h2 {
        margin: 14px 0 0;
        color: #111827;
        font-size: 1.65rem;
        font-weight: 850;
        letter-spacing: -.035em;
    }

    .verification-header p {
        margin: 6px 0 0;
        color: #8b93a3;
        font-size: .82rem;
    }

    /* =========================================================
       MAIN TICKET CARD
       ========================================================= */

    .ticket-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(124, 58, 237, .08);
        border-radius: 26px;
        box-shadow:
            0 25px 70px rgba(31, 23, 51, .10),
            0 5px 18px rgba(31, 23, 51, .04);
        animation: epCardEnter .75s cubic-bezier(.22, 1, .36, 1) .08s both;
    }

    .ticket-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(
            90deg,
            #4c1d95,
            #7c3aed,
            #ec4899
        );
    }

    .ticket-card::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -110px;
        top: -110px;
        border-radius: 50%;
        background: rgba(236, 72, 153, .055);
        pointer-events: none;
    }

    /* =========================================================
       STATUS
       ========================================================= */

    .ticket-status {
        position: relative;
        z-index: 1;
        padding: 38px 25px 32px;
        text-align: center;
    }

    .status-icon {
        position: relative;
        width: 82px;
        height: 82px;
        margin: 0 auto 17px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        animation: epStatusPop .75s cubic-bezier(.22, 1, .36, 1) .35s both;
    }

    .status-icon::before {
        content: "";
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 1px solid currentColor;
        opacity: .12;
    }

    .status-icon.active {
        color: #7c3aed;
        background: linear-gradient(
            145deg,
            #f3e8ff,
            #fce7f3
        );
        box-shadow:
            0 0 0 9px rgba(124, 58, 237, .045),
            0 12px 30px rgba(124, 58, 237, .13);
    }

    .status-icon.expired {
        color: #64748b;
        background: #f1f5f9;
        box-shadow:
            0 0 0 9px rgba(100, 116, 139, .045);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: .70rem;
        font-weight: 850;
        letter-spacing: .08em;
    }

    .status-badge.active {
        color: #6d28d9;
        background: #f3e8ff;
        border: 1px solid #e9d5ff;
    }

    .status-badge.expired {
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
    }

    .ticket-name {
        margin: 17px 0 5px;
        color: #111827;
        font-size: 1.75rem;
        font-weight: 850;
        line-height: 1.2;
        letter-spacing: -.035em;
        word-break: break-word;
    }

    .verification-text {
        color: #8992a2;
        font-size: .86rem;
        margin: 0;
    }

    /* =========================================================
       DIVIDER
       ========================================================= */

    .ticket-divider {
        position: relative;
        height: 1px;
        margin: 0 25px;
        border-top: 1px dashed #dfe3ea;
    }

    .ticket-divider::before,
    .ticket-divider::after {
        content: "";
        position: absolute;
        top: -11px;
        width: 22px;
        height: 22px;
        background: #f5f7fb;
        border-radius: 50%;
    }

    .ticket-divider::before {
        left: -36px;
    }

    .ticket-divider::after {
        right: -36px;
    }

    /* =========================================================
       INFORMATION
       ========================================================= */

    .ticket-info {
        position: relative;
        z-index: 1;
        padding: 23px 25px 22px;
        max-height: 520px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #c4b5fd transparent;
    }

    .ticket-info::-webkit-scrollbar {
        width: 5px;
    }

    .ticket-info::-webkit-scrollbar-track {
        background: transparent;
    }

    .ticket-info::-webkit-scrollbar-thumb {
        background: #c4b5fd;
        border-radius: 20px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 13px 0;
        animation: epInfoEnter .5s ease both;
    }

    .info-item:nth-child(1) { animation-delay: .15s; }
    .info-item:nth-child(2) { animation-delay: .20s; }
    .info-item:nth-child(3) { animation-delay: .25s; }
    .info-item:nth-child(4) { animation-delay: .30s; }
    .info-item:nth-child(5) { animation-delay: .35s; }
    .info-item:nth-child(6) { animation-delay: .40s; }
    .info-item:nth-child(7) { animation-delay: .45s; }
    .info-item:nth-child(8) { animation-delay: .50s; }
    .info-item:nth-child(9) { animation-delay: .55s; }
    .info-item:nth-child(10) { animation-delay: .60s; }
    .info-item:nth-child(11) { animation-delay: .65s; }
    .info-item:nth-child(12) { animation-delay: .70s; }

    .info-item + .info-item {
        border-top: 1px solid #f1f3f7;
    }

    .info-icon {
        position: relative;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(
            145deg,
            #f5f3ff,
            #fdf2f8
        );
        color: #6d28d9;
        border: 1px solid #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .info-item:hover .info-icon {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 7px 16px rgba(124, 58, 237, .12);
    }

    .info-content {
        min-width: 0;
        flex: 1;
    }

    .info-label {
        display: block;
        margin-bottom: 4px;
        color: #929aaa;
        font-size: .67rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    .info-value {
        display: block;
        color: #252b36;
        font-size: .92rem;
        font-weight: 700;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .info-value.text-primary {
        color: #7c3aed !important;
    }

    .info-value .text-muted {
        color: #9aa2ae !important;
        font-weight: 600;
    }

    /* =========================================================
       MESSAGE
       ========================================================= */

    .verification-message {
        position: relative;
        z-index: 1;
        margin: 0 25px 25px;
        padding: 17px 18px;
        border-radius: 15px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: .85rem;
        line-height: 1.55;
        animation: epMessageEnter .6s ease .7s both;
    }

    .verification-message.active {
        background:
            linear-gradient(
                135deg,
                #faf5ff,
                #fdf2f8
            );
        color: #5b21b6;
        border: 1px solid #eadcff;
    }

    .verification-message.expired {
        background: #f8fafc;
        color: #5f666d;
        border: 1px solid #e2e8f0;
    }

    .verification-message strong {
        font-weight: 850;
    }

    .message-icon {
        flex: 0 0 auto;
        font-size: 1.05rem;
        margin-top: 2px;
    }

    .verification-message.active .message-icon {
        color: #7c3aed;
    }

    .verification-message.expired .message-icon {
        color: #64748b;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .verification-footer {
        text-align: center;
        margin-top: 18px;
        color: #9aa2ae;
        font-size: .70rem;
        animation: epFadeUp .7s ease .85s both;
    }

    .verification-footer i {
        margin-right: 4px;
        color: #7c3aed;
    }

    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes epFadeDown {
        from { opacity: 0; transform: translateY(-14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes epFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes epCardEnter {
        from { opacity: 0; transform: translateY(25px) scale(.985); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes epStatusPop {
        0% { opacity: 0; transform: scale(.55); }
        70% { opacity: 1; transform: scale(1.08); }
        100% { transform: scale(1); }
    }

    @keyframes epInfoEnter {
        from { opacity: 0; transform: translateX(-8px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes epMessageEnter {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 576px) {
        .verification-page {
            min-height: calc(100vh - 60px);
            padding: 27px 12px 42px;
            align-items: flex-start;
        }

        .verification-header {
            margin-bottom: 17px;
        }

        .verification-header .small-label {
            font-size: .63rem;
            padding: 7px 11px;
        }

        .verification-header h2 {
            font-size: 1.30rem;
            margin-top: 10px;
        }

        .verification-header p {
            font-size: .75rem;
        }

        .ticket-card {
            border-radius: 21px;
        }

        .ticket-status {
            padding: 30px 18px 26px;
        }

        .status-icon {
            width: 67px;
            height: 67px;
            font-size: 1.65rem;
            margin-bottom: 14px;
        }

        .status-badge {
            padding: 7px 12px;
            font-size: .64rem;
        }

        .ticket-name {
            font-size: 1.38rem;
            margin-top: 14px;
        }

        .verification-text {
            font-size: .78rem;
        }

        .ticket-divider {
            margin: 0 18px;
        }

        .ticket-info {
            padding: 19px 17px 17px;
            max-height: 600px;
        }

        .info-item {
            gap: 10px;
            padding: 11px 0;
        }

        .info-icon {
            flex-basis: 35px;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            font-size: .82rem;
        }

        .info-label {
            font-size: .61rem;
        }

        .info-value {
            font-size: .84rem;
        }

        .verification-message {
            margin: 0 17px 18px;
            padding: 14px;
            font-size: .78rem;
            border-radius: 13px;
        }

        .verification-footer {
            font-size: .65rem;
            margin-top: 13px;
        }
    }

    @media (max-width: 360px) {
        .verification-page {
            padding-left: 8px;
            padding-right: 8px;
        }

        .ticket-status {
            padding-left: 14px;
            padding-right: 14px;
        }

        .ticket-name {
            font-size: 1.25rem;
        }

        .ticket-info {
            padding-left: 13px;
            padding-right: 13px;
        }

        .verification-message {
            margin-left: 13px;
            margin-right: 13px;
        }
    }

    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .verification-header,
        .ticket-card,
        .status-icon,
        .info-item,
        .verification-message,
        .verification-footer {
            animation: none !important;
        }

        .info-icon {
            transition: none !important;
        }
    }
</style>


<div class="verification-page">

    <div class="verification-wrapper">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}
        <div class="verification-header">

            <div class="small-label">
                <i class="fa-solid fa-shield-halved"></i>
                Ticket Verification
            </div>

            <h2>
                Ticket Status
            </h2>

            <p>
                Verify the registration and attendee information
            </p>

        </div>


        {{-- =====================================================
             TICKET CARD
        ====================================================== --}}
        <div class="ticket-card">


            {{-- =================================================
                 STATUS
            ================================================== --}}
            <div class="ticket-status">

                @if($isExpired)

                    <div class="status-icon expired">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>

                    <div class="status-badge expired">
                        <i class="fa-solid fa-circle-xmark"></i>
                        STATUS: EXPIRED
                    </div>

                @else

                    <div class="status-icon active">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <div class="status-badge active">
                        <i class="fa-solid fa-circle-check"></i>
                        STATUS: ACTIVE
                    </div>

                @endif


                <h1 class="ticket-name">
                    {{ $attendee->full_name }}
                </h1>

                <p class="verification-text">
                    Ticket verification result
                </p>

            </div>


            {{-- =================================================
                 DIVIDER
            ================================================== --}}
            <div class="ticket-divider"></div>


            {{-- =================================================
                 ATTENDEE INFORMATION
            ================================================== --}}
            <div class="ticket-info">


                {{-- REG CODE / TICKET REFERENCE --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Reg Code / Ticket Ref
                        </span>

                        <span class="info-value">
                            {{ $attendee->ticket_code }}
                        </span>

                    </div>

                </div>


                {{-- EVENT NAME --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Event
                        </span>

                        <span class="info-value">
                            {{ $attendee->ticketCategory->event->title ?? 'N/A' }}
                        </span>

                    </div>

                </div>


                {{-- TICKET CATEGORY --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-ticket"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Ticket Category
                        </span>

                        <span class="info-value">
                            {{ $attendee->ticketCategory->name ?? 'N/A' }}
                        </span>

                    </div>

                </div>


                {{-- EVENT DATE --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-regular fa-calendar"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Event Date
                        </span>

                        <span class="info-value">

                            {{
                                isset($attendee->ticketCategory->event->event_date)
                                    ? \Carbon\Carbon::parse(
                                        $attendee->ticketCategory->event->event_date
                                      )->format('F d, Y - h:i A')
                                    : 'N/A'
                            }}

                        </span>

                    </div>

                </div>


                {{-- FATHER'S NAME --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-user-group"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Father's Name
                        </span>

                        <span class="info-value">
                            {{ $attendee->father_name ?? '-' }}
                        </span>

                    </div>

                </div>


                {{-- CONTACT INFORMATION --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Contact / Email
                        </span>

                        <span class="info-value">

                            {{ $attendee->phone ?? '-' }}

                            @if($attendee->email)

                                <br>

                                <small class="text-muted">
                                    {{ $attendee->email }}
                                </small>

                            @endif

                        </span>

                    </div>

                </div>


                {{-- EMERGENCY CONTACT --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Emergency Contact
                        </span>

                        <span class="info-value">

                            {{ $attendee->emergency_contact ?? '-' }}

                            @if($attendee->emergency_phone)
                                ({{ $attendee->emergency_phone }})
                            @endif

                        </span>

                    </div>

                </div>


                {{-- NRC / PASSPORT / COUNTRY --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-id-card"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            NRC / Passport / Country
                        </span>

                        <span class="info-value">

                            {{ $attendee->nrc_passport ?? '-' }}

                            <span class="text-muted">
                                ({{ $attendee->country ?? 'Myanmar' }})
                            </span>

                        </span>

                    </div>

                </div>


                {{-- DEMOGRAPHICS --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-person"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Demographics
                        </span>

                        <span class="info-value">

                            {{ ucfirst($attendee->gender ?? '-') }}

                            @if($attendee->dob)

                                &bull;

                                Age:
                                {{ \Carbon\Carbon::parse($attendee->dob)->age }}
                                yrs

                                (DOB:
                                {{ $attendee->dob }})

                            @endif

                        </span>

                    </div>

                </div>


                {{-- HEALTH & ITRA --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-heart-circle-check"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Health & ITRA
                        </span>

                        <span class="info-value">

                            Medical Cond:
                            {{ $attendee->medical_conditions ?? 'None' }}

                            @if($attendee->itra_profile)

                                <br>

                                ITRA:
                                {{ $attendee->itra_profile }}

                            @endif

                        </span>

                    </div>

                </div>


                {{-- ADDRESS & EXPERIENCE --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Address & Experience
                        </span>

                        <span class="info-value">

                            {{ $attendee->address ?? '-' }}

                            @if($attendee->running_experience)

                                <br>

                                <small class="text-muted">
                                    Exp:
                                    {{ $attendee->running_experience }}
                                </small>

                            @endif

                        </span>

                    </div>

                </div>


                {{-- PROMO APPLIED --}}
                <div class="info-item">

                    <div class="info-icon">
                        <i class="fa-solid fa-tag"></i>
                    </div>

                    <div class="info-content">

                        <span class="info-label">
                            Promo Applied
                        </span>

                        <span class="info-value">
                            {{ $attendee->promo_code ?? 'None' }}
                        </span>

                    </div>

                </div>


            </div>


            {{-- =================================================
                 VERIFICATION MESSAGE
            ================================================== --}}
            @if($isExpired)

                <div class="verification-message expired">

                    <div class="message-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>

                        <strong>
                            Ticket Expired
                        </strong>

                        <br>

                        This event has passed and the ticket status
                        is now expired.

                    </div>

                </div>

            @else

                <div class="verification-message active">

                    <div class="message-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <div>

                        <strong>
                            Ticket Valid
                        </strong>

                        <br>

                        This ticket is valid and the event is
                        currently active.

                    </div>

                </div>

            @endif


        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}
        <div class="verification-footer">

            <i class="fa-solid fa-shield-halved"></i>

            Secure ticket verification

        </div>

    </div>

</div>

@endsection