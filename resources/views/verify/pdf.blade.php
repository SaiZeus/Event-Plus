@php
    $event = $attendee->ticketCategory->event ?? null;

    $eventDate = null;
    try {
        if ($event && !empty($event->event_date)) {
            $eventDate = \Carbon\Carbon::parse($event->event_date)->format('M d, Y - h:i A');
        }
    } catch (\Throwable $e) {
        $eventDate = null;
    }

    $medical = trim((string) ($attendee->medical_conditions ?? ''));
    $hasMedical = $medical !== '' && !in_array(strtolower($medical), ['none', 'no', 'n/a', '-'], true);

    // Status colours (valid = green, expired = amber)
    $accent = $isExpired ? '#b45309' : '#047857';
    $dark   = $isExpired ? '#78350f' : '#065f46';
    $soft   = $isExpired ? '#fffbeb' : '#ecfdf5';
    $edge   = $isExpired ? '#fcd34d' : '#a7f3d0';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Verification - {{ $attendee->ticket_code }}</title>

    <style>
        @font-face {
            font-family: 'NotoMyanmar';
            src: url('{{ public_path("assets/fonts/NotoSansMyanmar-Regular.ttf") }}') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'NotoMyanmar';
            src: url('{{ public_path("assets/fonts/NotoSansMyanmar-Bold.ttf") }}') format('truetype');
            font-weight: bold;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body {
            font-family: 'NotoMyanmar', DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .report {
            width: 100%;
            border: 1px solid #d9dce3;
            background: #ffffff;
        }

        .accent {
            height: 6px;
            background: {{ $accent }};
        }

        /* ---------- Header ---------- */

        .header {
            padding: 14px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo-cell {
            width: 70px;
            vertical-align: middle;
        }

        .logo-cell img {
            max-width: 60px;
            max-height: 46px;
        }

        .brand-cell {
            vertical-align: middle;
        }

        .brand-name {
            margin: 0;
            color: #312e81;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .brand-tagline {
            margin: 2px 0 0;
            color: #6b7280;
            font-size: 10px;
        }

        .meta-cell {
            vertical-align: middle;
            text-align: right;
        }

        .meta-title {
            color: #312e81;
            font-size: 13px;
            font-weight: bold;
        }

        .meta-date {
            margin-top: 3px;
            color: #6b7280;
            font-size: 10px;
        }

        /* ---------- Status ---------- */

        .status {
            padding: 14px 22px;
            background: {{ $soft }};
            border-bottom: 1px solid {{ $edge }};
        }

        .status-title {
            color: {{ $accent }};
            font-size: 18px;
            font-weight: bold;
        }

        .status-description {
            margin-top: 3px;
            color: {{ $dark }};
            font-size: 11px;
        }

        .badge-cell {
            width: 120px;
            text-align: right;
            vertical-align: middle;
        }

        .badge {
            display: inline-block;
            padding: 8px 20px;
            background: {{ $accent }};
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        /* ---------- Attendee + ticket number ---------- */

        .identity {
            padding: 20px 22px;
            border-bottom: 1px dashed #c4c9d4;
        }

        .small-label {
            color: #6b7280;
            font-size: 10.5px;
            font-weight: bold;
        }

        .attendee-name {
            margin-top: 4px;
            color: #111827;
            font-size: 23px;
            font-weight: bold;
        }

        .code-cell {
            width: 215px;
            text-align: right;
            vertical-align: top;
        }

        .code-box {
            display: inline-block;
            margin-top: 4px;
            padding: 7px 14px;
            border: 2px solid {{ $accent }};
            color: {{ $accent }};
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* ---------- Event block ---------- */

        .block {
            padding: 16px 22px 6px;
        }

        .heading {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e0e7ff;
            color: #312e81;
            font-size: 13px;
            font-weight: bold;
        }

        .event-box {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .event-box td {
            width: 33%;
            padding: 10px 12px;
            vertical-align: top;
        }

        .event-box td.sep {
            border-right: 1px solid #e5e7eb;
        }

        .event-label {
            display: block;
            color: #6b7280;
            font-size: 10px;
            font-weight: bold;
        }

        .event-value {
            display: block;
            margin-top: 3px;
            color: #312e81;
            font-size: 12.5px;
            font-weight: bold;
        }

        /* ---------- Attendee details ---------- */

        .details {
            padding: 12px 22px 10px;
        }

        .columns td.col {
            width: 50%;
            vertical-align: top;
        }

        .columns td.col-left {
            padding-right: 12px;
        }

        .columns td.col-right {
            padding-left: 12px;
        }

        .group {
            margin-bottom: 14px;
        }

        .group-title {
            margin-bottom: 4px;
            color: #4338ca;
            font-size: 12px;
            font-weight: bold;
        }

        .info td {
            padding: 6px 4px;
            border-bottom: 1px solid #eef0f4;
            vertical-align: top;
        }

        .info td.label {
            width: 38%;
            color: #6b7280;
            font-size: 10.5px;
            font-weight: bold;
        }

        .info td.value {
            width: 62%;
            color: #111827;
            font-size: 11.5px;
            word-wrap: break-word;
        }

        .value-warning {
            color: #b42318;
            font-weight: bold;
        }

        /* ---------- Final statement ---------- */

        .verification {
            margin: 4px 22px 18px;
            padding: 12px 16px;
            text-align: center;
            background: {{ $soft }};
            border: 1px solid {{ $edge }};
        }

        .verification-title {
            color: {{ $accent }};
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .verification-text {
            margin-top: 3px;
            color: {{ $dark }};
            font-size: 11px;
        }

        /* ---------- Footer ---------- */

        .footer {
            padding: 10px 22px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer-brand {
            color: #312e81;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .footer-text {
            margin-top: 2px;
            color: #6b7280;
            font-size: 9.5px;
        }
    </style>
</head>

<body>

<div class="report">

    <div class="accent"></div>

    <!-- HEADER -->
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/img/logo/Eventplus.png') }}" alt="Event Plus">
                </td>

                <td class="brand-cell">
                    <p class="brand-name">EVENT PLUS</p>
                    <p class="brand-tagline">Events, experiences and memories</p>
                </td>

                <td class="meta-cell">
                    <div class="meta-title">Ticket verification report</div>
                    <div class="meta-date">Generated {{ now()->format('M d, Y - h:i A') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- STATUS -->
    <div class="status">
        <table>
            <tr>
                <td>
                    <div class="status-title">
                        {{ $isExpired ? 'Ticket expired' : 'Ticket verified successfully' }}
                    </div>

                    <div class="status-description">
                        {{ $isExpired
                            ? 'This ticket has expired because the event has passed.'
                            : 'This ticket is valid and currently active.'
                        }}
                    </div>
                </td>

                <td class="badge-cell">
                    <span class="badge">{{ $isExpired ? 'EXPIRED' : 'ACTIVE' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- ATTENDEE AND TICKET NUMBER -->
    <div class="identity">
        <table>
            <tr>
                <td>
                    <div class="small-label">Attendee</div>
                    <div class="attendee-name">{{ $attendee->full_name }}</div>
                </td>

                <td class="code-cell">
                    <div class="small-label">Ticket number</div>
                    <div class="code-box">{{ $attendee->ticket_code }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- EVENT INFORMATION -->
    <div class="block">

        <div class="heading">Event information</div>

        <table class="event-box">
            <tr>
                <td class="sep">
                    <span class="event-label">Event name</span>
                    <span class="event-value">{{ $event->title ?? 'N/A' }}</span>
                </td>

                <td class="sep">
                    <span class="event-label">Category</span>
                    <span class="event-value">{{ $attendee->ticketCategory->name ?? 'N/A' }}</span>
                </td>

                <td>
                    <span class="event-label">Event date</span>
                    <span class="event-value">{{ $eventDate ?? 'N/A' }}</span>
                </td>
            </tr>
        </table>

    </div>

    <!-- ATTENDEE INFORMATION -->
    <div class="details">

        <div class="heading">Attendee information</div>

        <table class="columns">

            <tr>
                <!-- Identity -->
                <td class="col col-left">
                    <div class="group">
                        <div class="group-title">Identity</div>
                        <table class="info">
                            <tr>
                                <td class="label">Father's name</td>
                                <td class="value">{{ $attendee->father_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Gender</td>
                                <td class="value">{{ ucfirst($attendee->gender ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="label">NRC / Passport</td>
                                <td class="value">{{ $attendee->nrc_passport ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Country</td>
                                <td class="value">{{ $attendee->country ?? 'Myanmar' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>

                <!-- Contact -->
                <td class="col col-right">
                    <div class="group">
                        <div class="group-title">Contact</div>
                        <table class="info">
                            <tr>
                                <td class="label">Phone</td>
                                <td class="value">{{ $attendee->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Email</td>
                                <td class="value">{{ $attendee->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Emergency contact</td>
                                <td class="value">
                                    {{ $attendee->emergency_contact ?? '-' }}
                                    @if(!empty($attendee->emergency_phone))
                                        ({{ $attendee->emergency_phone }})
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <tr>
                <!-- Registration -->
                <td class="col col-left">
                    <div class="group">
                        <div class="group-title">Registration</div>
                        <table class="info">
                            @if(!empty($attendee->bib_number))
                                <tr>
                                    <td class="label">BIB number</td>
                                    <td class="value">{{ $attendee->bib_number }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="label">T-shirt size</td>
                                <td class="value">{{ $attendee->tshirt_size ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Promo code</td>
                                <td class="value">{{ $attendee->promo_code ?? 'None' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>

                <!-- Health and address -->
                <td class="col col-right">
                    <div class="group">
                        <div class="group-title">Health and address</div>
                        <table class="info">
                            <tr>
                                <td class="label">Medical</td>
                                <td class="value {{ $hasMedical ? 'value-warning' : '' }}">
                                    {{ $attendee->medical_conditions ?? 'None' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Address</td>
                                <td class="value">{{ $attendee->address ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

        </table>

    </div>

    <!-- FINAL VERIFICATION -->
    <div class="verification">

        <div class="verification-title">
            {{ $isExpired ? 'TICKET EXPIRED' : 'TICKET VERIFIED' }}
        </div>

        <div class="verification-text">
            {{ $isExpired
                ? 'This ticket is no longer valid for event entry.'
                : 'This ticket is eligible for event entry, subject to event rules.'
            }}
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">

        <div class="footer-brand">EVENT PLUS</div>

        <div class="footer-text">
            Official ticket verification report | Generated by Event Plus
        </div>

    </div>

</div>

</body>
</html>