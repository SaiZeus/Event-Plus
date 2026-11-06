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
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'NotoMyanmar', DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 9px;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        .report {
            width: 100%;
            border: 1px solid #e5e7eb;
            background: #ffffff;
        }

        .accent {
            height: 5px;
            background: {{ $isExpired ? '#dc2626' : '#059669' }};
        }

        /* HEADER */

        .header {
            width: 100%;
            padding: 12px 18px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            width: 65px;
            vertical-align: middle;
        }

        .logo img {
            max-width: 58px;
            max-height: 42px;
        }

        .brand {
            vertical-align: middle;
        }

        .brand h1 {
            margin: 0;
            font-size: 20px;
            color: #312e81;
            letter-spacing: 2px;
        }

        .brand p {
            margin: 1px 0 0;
            color: #9ca3af;
            font-size: 8px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .report-type {
            text-align: right;
            vertical-align: middle;
            color: #6b7280;
            font-size: 9px;
            font-weight: bold;
        }

        /* STATUS */

        .status-area {
            padding: 10px 18px;
            background: {{ $isExpired ? '#fef2f2' : '#ecfdf5' }};
            border-bottom: 1px solid #e5e7eb;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
        }

        .status-title {
            font-size: 14px;
            font-weight: bold;
            color: {{ $isExpired ? '#b91c1c' : '#047857' }};
        }

        .status-description {
            font-size: 8px;
            color: {{ $isExpired ? '#991b1b' : '#065f46' }};
            margin-top: 2px;
        }

        .status-badge {
            text-align: right;
            vertical-align: middle;
        }

        .badge {
            display: inline-block;
            padding: 6px 17px;
            background: {{ $isExpired ? '#dc2626' : '#059669' }};
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* TICKET CODE */

        .ticket-code-area {
            text-align: center;
            padding: 10px;
            border-bottom: 1px dashed #d1d5db;
        }

        .ticket-label {
            color: #9ca3af;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .ticket-code {
            font-size: 21px;
            font-weight: bold;
            letter-spacing: 3px;
            color: {{ $isExpired ? '#dc2626' : '#059669' }};
            margin-top: 2px;
        }

        /* EVENT INFORMATION */

        .section {
            padding: 10px 15px 5px;
        }

        .section-heading {
            color: #312e81;
            font-size: 10px;
            font-weight: bold;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-bottom: 2px solid #ede9fe;
            letter-spacing: .5px;
        }

        .event-table {
            width: 100%;
            border-collapse: collapse;
        }

        .event-table td {
            width: 33.33%;
            padding: 4px 7px;
            vertical-align: top;
            border-right: 1px solid #f3f4f6;
        }

        .event-table td:last-child {
            border-right: none;
        }

        .event-label {
            display: block;
            color: #9ca3af;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .event-value {
            display: block;
            color: #312e81;
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* ATTENDEE INFORMATION */

        .attendee-section {
            padding: 8px 15px 10px;
        }

        .columns {
            width: 100%;
            border-collapse: collapse;
        }

        .columns > tbody > tr > td {
            width: 50%;
            vertical-align: top;
        }

        .columns .left-column {
            padding-right: 9px;
        }

        .columns .right-column {
            padding-left: 9px;
            border-left: 1px solid #e5e7eb;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
            line-height: 1.5;
        }

        .info-table .label {
            width: 39%;
            color: #6b7280;
            font-size: 8px;
            font-weight: bold;
        }

        .info-table .value {
            width: 61%;
            color: #111827;
            font-size: 8.5px;
            text-align: right;
            word-wrap: break-word;
        }

        /* FINAL VERIFICATION */

        .verification {
            margin: 5px 15px 10px;
            padding: 9px 12px;
            text-align: center;
            background: {{ $isExpired ? '#fef2f2' : '#ecfdf5' }};
            border: 1px solid {{ $isExpired ? '#fecaca' : '#a7f3d0' }};
        }

        .verification-title {
            color: {{ $isExpired ? '#dc2626' : '#047857' }};
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .verification-text {
            color: #6b7280;
            font-size: 8px;
            margin-top: 2px;
        }

        /* FOOTER */

        .footer {
            padding: 8px 15px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer-brand {
            color: #312e81;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        .footer-text {
            color: #9ca3af;
            font-size: 7px;
            margin-top: 2px;
        }

    </style>
</head>

<body>

<div class="report">

    <div class="accent"></div>

    <!-- HEADER -->
    <div class="header">

        <table class="header-table">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/img/logo/Eventplus.png') }}">
                </td>

                <td class="brand">
                    <h1>EVENT PLUS</h1>
                    <p>Events • Experiences • Memories</p>
                </td>

                <td class="report-type">
                    TICKET<br>
                    VERIFICATION REPORT
                </td>
            </tr>
        </table>

    </div>

    <!-- STATUS -->
    <div class="status-area">

        <table class="status-table">
            <tr>
                <td>
                    <div class="status-title">
                        {{ $isExpired ? 'Ticket Expired' : 'Ticket Verified Successfully' }}
                    </div>

                    <div class="status-description">
                        {{ $isExpired
                            ? 'This ticket has expired because the event has passed.'
                            : 'This ticket is valid and currently active.'
                        }}
                    </div>
                </td>

                <td class="status-badge">
                    <span class="badge">
                        {{ $isExpired ? 'EXPIRED' : 'ACTIVE' }}
                    </span>
                </td>
            </tr>
        </table>

    </div>

    <!-- TICKET CODE -->
    <div class="ticket-code-area">

        <div class="ticket-label">TICKET IDENTIFICATION NUMBER</div>

        <div class="ticket-code">
            {{ $attendee->ticket_code }}
        </div>

    </div>

    <!-- EVENT INFORMATION -->
    <div class="section">

        <div class="section-heading">
            EVENT INFORMATION
        </div>

        <table class="event-table">
            <tr>
                <td>
                    <span class="event-label">Event Name</span>
                    <span class="event-value">
                        {{ $attendee->ticketCategory->event->title ?? 'N/A' }}
                    </span>
                </td>

                <td>
                    <span class="event-label">Category</span>
                    <span class="event-value">
                        {{ $attendee->ticketCategory->name ?? 'N/A' }}
                    </span>
                </td>

                <td>
                    <span class="event-label">Event Date</span>
                    <span class="event-value">
                        {{ isset($attendee->ticketCategory->event->event_date)
                            ? \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('M d, Y - h:i A')
                            : 'N/A'
                        }}
                    </span>
                </td>
            </tr>
        </table>

    </div>

    <!-- ATTENDEE INFORMATION -->
    <div class="attendee-section">

        <div class="section-heading">
            ATTENDEE INFORMATION
        </div>

        <table class="columns">
            <tr>

                <!-- LEFT COLUMN -->
                <td class="left-column">

                    <table class="info-table">

                        <tr>
                            <td class="label">Full Name</td>
                            <td class="value">{{ $attendee->full_name }}</td>
                        </tr>

                        <tr>
                            <td class="label">Father's Name</td>
                            <td class="value">{{ $attendee->father_name ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Phone</td>
                            <td class="value">{{ $attendee->phone ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Email</td>
                            <td class="value">{{ $attendee->email ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Emergency Contact</td>
                            <td class="value">{{ $attendee->emergency_contact ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">NRC / Passport</td>
                            <td class="value">{{ $attendee->nrc_passport ?? '-' }}</td>
                        </tr>

                    </table>

                </td>

                <!-- RIGHT COLUMN -->
                <td class="right-column">

                    <table class="info-table">

                        <tr>
                            <td class="label">Country</td>
                            <td class="value">{{ $attendee->country ?? 'Myanmar' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Gender</td>
                            <td class="value">{{ ucfirst($attendee->gender ?? '-') }}</td>
                        </tr>

                        {{-- T-SHIRT SIZE --}}
                        <tr>
                            <td class="label">T-Shirt Size</td>
                            <td class="value">{{ $attendee->tshirt_size ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Medical</td>
                            <td class="value">{{ $attendee->medical_conditions ?? 'None' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Address</td>
                            <td class="value">{{ $attendee->address ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Promo Code</td>
                            <td class="value">{{ $attendee->promo_code ?? 'None' }}</td>
                        </tr>

                    </table>

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

        <div class="footer-brand">
            EVENT PLUS
        </div>

        <div class="footer-text">
            Official Ticket Verification Report | Generated by Event Plus
        </div>

    </div>

</div>

</body>
</html>
