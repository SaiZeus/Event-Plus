@extends('layouts.admin')

@section('title', 'Event Plus - Admin Dashboard')
@section('page-title', 'Event Overview')

@section('content')

<style>

    /* =========================================================
       EVENT PLUS DASHBOARD
    ========================================================== */

    .ep-dashboard {

        --ep-navy: #0f172a;
        --ep-navy-2: #17112f;

        --ep-purple: #6d28d9;
        --ep-violet: #7c3aed;

        --ep-pink: #ec4899;
        --ep-pink-dark: #db2777;

        --ep-indigo: #6366f1;

        --ep-green: #10b981;
        --ep-amber: #f59e0b;

        --ep-bg: #f7f8fc;
        --ep-card: #ffffff;

        --ep-border: #e8eaf0;

        --ep-text: #172033;
        --ep-muted: #7b8496;

        min-height: calc(100vh - 100px);

        margin: -1rem;
        padding: 1.7rem;

        background:

            radial-gradient(
                circle at 0% 0%,
                rgba(236, 72, 153, 0.08),
                transparent 25%
            ),

            radial-gradient(
                circle at 100% 0%,
                rgba(124, 58, 237, 0.09),
                transparent 27%
            ),

            linear-gradient(
                180deg,
                #fafaff 0%,
                #f5f7fb 100%
            );
    }


    /* =========================================================
       DASHBOARD INTRO
    ========================================================== */

    .ep-dashboard-intro {

        position: relative;

        overflow: hidden;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 25px;

        margin-bottom: 25px;

        padding: 28px 30px;

        border-radius: 24px;

        background:

            radial-gradient(
                circle at 90% 15%,
                rgba(236, 72, 153, 0.30),
                transparent 28%
            ),

            radial-gradient(
                circle at 70% 100%,
                rgba(124, 58, 237, 0.35),
                transparent 35%
            ),

            linear-gradient(
                120deg,
                #0f172a 0%,
                #21133e 55%,
                #4c1d95 100%
            );

        box-shadow:
            0 18px 45px rgba(15, 23, 42, 0.16);
    }


    .ep-dashboard-intro::before {

        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        border:
            1px solid rgba(255, 255, 255, 0.08);

        border-radius: 50%;

        right: -90px;
        top: -120px;
    }


    .ep-dashboard-intro::after {

        content: "";

        position: absolute;

        width: 160px;
        height: 160px;

        border:
            1px solid rgba(255, 255, 255, 0.06);

        border-radius: 50%;

        right: 150px;
        bottom: -110px;
    }


    .ep-intro-content {

        position: relative;

        z-index: 2;
    }


    .ep-intro-eyebrow {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        margin-bottom: 8px;

        color: #f9a8d4;

        font-size: 0.65rem;

        font-weight: 850;

        text-transform: uppercase;

        letter-spacing: 0.13em;
    }


    .ep-intro-eyebrow i {

        font-size: 0.62rem;
    }


    .ep-intro-title {

        margin: 0;

        color: #ffffff;

        font-size: clamp(1.45rem, 2.5vw, 2rem);

        line-height: 1.15;

        font-weight: 900;

        letter-spacing: -0.045em;
    }


    .ep-intro-title span {

        background:

            linear-gradient(
                90deg,
                #f9a8d4,
                #ec4899,
                #c4b5fd
            );

        -webkit-background-clip: text;

        background-clip: text;

        color: transparent;
    }


    .ep-intro-description {

        max-width: 590px;

        margin: 9px 0 0;

        color: rgba(255,255,255,0.62);

        font-size: 0.78rem;

        line-height: 1.65;
    }


    .ep-intro-icon {

        position: relative;

        z-index: 2;

        width: 78px;
        height: 78px;

        min-width: 78px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 23px;

        color: #ffffff;

        font-size: 1.75rem;

        background:

            rgba(255,255,255,0.10);

        border:

            1px solid rgba(255,255,255,0.13);

        box-shadow:

            inset 0 1px 0 rgba(255,255,255,0.10),

            0 15px 30px rgba(0,0,0,0.12);
    }


    /* =========================================================
       STAT GRID
    ========================================================== */

    .ep-stat-grid {

        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 18px;

        margin-bottom: 25px;
    }


    .ep-stat-card {

        position: relative;

        overflow: hidden;

        min-height: 148px;

        padding: 23px;

        border:
            1px solid var(--ep-border);

        border-radius: 21px;

        background:
            rgba(255,255,255,0.96);

        box-shadow:
            0 10px 32px rgba(15,23,42,0.055);

        cursor: pointer;

        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease,
            border-color 0.22s ease;
    }


    .ep-stat-card::before {

        content: "";

        position: absolute;

        width: 170px;
        height: 170px;

        right: -80px;
        top: -80px;

        border-radius: 50%;

        background:
            rgba(124,58,237,0.045);

        pointer-events: none;
    }


    .ep-stat-card::after {

        content: "";

        position: absolute;

        height: 3px;

        left: 0;
        right: 0;

        bottom: 0;

        opacity: 0;

        transition: opacity 0.2s ease;
    }


    .ep-stat-card:hover {

        transform:
            translateY(-5px);

        box-shadow:
            0 20px 45px rgba(15,23,42,0.105);
    }


    .ep-stat-card:hover::after {

        opacity: 1;
    }


    .ep-stat-revenue::after {

        background:
            linear-gradient(
                90deg,
                #10b981,
                #34d399
            );
    }


    .ep-stat-ticket::after {

        background:
            linear-gradient(
                90deg,
                #6366f1,
                #8b5cf6
            );
    }


    .ep-stat-event::after {

        background:
            linear-gradient(
                90deg,
                #f59e0b,
                #f97316
            );
    }


    .ep-stat-top {

        position: relative;

        z-index: 2;

        display: flex;

        align-items: center;

        justify-content: space-between;

        margin-bottom: 20px;
    }


    .ep-stat-icon {

        width: 51px;
        height: 51px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 15px;

        font-size: 1.15rem;
    }


    .ep-stat-icon.revenue {

        color: #059669;

        background:
            linear-gradient(
                135deg,
                #d1fae5,
                #ecfdf5
            );
    }


    .ep-stat-icon.ticket {

        color: #4f46e5;

        background:
            linear-gradient(
                135deg,
                #e0e7ff,
                #eef2ff
            );
    }


    .ep-stat-icon.event {

        color: #d97706;

        background:
            linear-gradient(
                135deg,
                #fef3c7,
                #fffbeb
            );
    }


    .ep-stat-click {

        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 5px 8px;

        border-radius: 7px;

        color: #94a3b8;

        background: #f8fafc;

        font-size: 0.57rem;

        font-weight: 750;
    }


    .ep-stat-card:hover .ep-stat-click {

        color: #7c3aed;

        background: #f5f3ff;
    }


    .ep-stat-label {

        position: relative;

        z-index: 2;

        margin: 0 0 4px;

        color: var(--ep-muted);

        font-size: 0.65rem;

        font-weight: 850;

        text-transform: uppercase;

        letter-spacing: 0.08em;
    }


    .ep-stat-value {

        position: relative;

        z-index: 2;

        margin: 0;

        color: var(--ep-text);

        font-size: 1.72rem;

        line-height: 1.1;

        font-weight: 900;

        letter-spacing: -0.045em;
    }


    .ep-stat-value small {

        color: #94a3b8;

        font-size: 0.68rem;

        font-weight: 750;

        letter-spacing: 0;
    }


    /* =========================================================
       SECTION CARD
    ========================================================== */

    .ep-section {

        overflow: hidden;

        margin-bottom: 25px;

        border:
            1px solid var(--ep-border);

        border-radius: 22px;

        background:
            #ffffff;

        box-shadow:
            0 12px 38px rgba(15,23,42,0.055);
    }


    .ep-section-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 21px 24px;

        border-bottom:
            1px solid var(--ep-border);

        background:
            linear-gradient(
                135deg,
                #ffffff,
                #fbfbff
            );
    }


    .ep-section-heading {

        display: flex;

        align-items: center;

        gap: 12px;
    }


    .ep-section-icon {

        width: 40px;
        height: 40px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 12px;

        color: #7c3aed;

        background:
            linear-gradient(
                135deg,
                #fce7f3,
                #ede9fe
            );
    }


    .ep-section-title {

        margin: 0;

        color: var(--ep-text);

        font-size: 0.98rem;

        font-weight: 900;

        letter-spacing: -0.02em;
    }


    .ep-section-subtitle {

        margin: 3px 0 0;

        color: #9aa3b2;

        font-size: 0.64rem;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .ep-table-wrap {

        overflow-x: auto;
    }


    .ep-table {

        width: 100%;

        border-collapse: collapse;
    }


    .ep-table thead {

        background:
            #fafbfe;
    }


    .ep-table th {

        padding:
            13px 19px;

        color: #8791a1;

        font-size: 0.62rem;

        font-weight: 850;

        letter-spacing: 0.075em;

        text-transform: uppercase;

        white-space: nowrap;

        border-bottom:
            1px solid var(--ep-border);
    }


    .ep-table td {

        padding:
            15px 19px;

        color: #596579;

        font-size: 0.79rem;

        vertical-align: middle;

        border-bottom:
            1px solid #edf0f4;
    }


    .ep-table tbody tr {

        transition:
            background 0.18s ease;
    }


    .ep-table tbody tr:hover {

        background:
            linear-gradient(
                90deg,
                #ffffff,
                #fafaff
            );
    }


    .ep-runner-cell {

        display: flex;

        align-items: center;

        gap: 10px;
    }


    .ep-runner-avatar {

        width: 34px;
        height: 34px;

        min-width: 34px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 10px;

        color: #ffffff;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #7c3aed
            );

        font-size: 0.68rem;

        font-weight: 900;
    }


    .ep-runner-name {

        color: #1e293b;

        font-weight: 800;
    }


    .ep-user-code {

        display: inline-flex;

        align-items: center;

        padding:
            3px 7px;

        margin-bottom: 2px;

        border:
            1px solid #ddd6fe;

        border-radius: 6px;

        background:
            #f5f3ff;

        color:
            #6d28d9;

        font-family: monospace;

        font-size: 0.61rem;

        font-weight: 850;
    }


    .ep-ticket-count {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 34px;

        height: 29px;

        padding: 0 9px;

        border-radius: 8px;

        background:
            #eef2ff;

        color:
            #4f46e5;

        font-weight: 900;
    }


    .ep-view-button {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding:
            7px 12px;

        border:
            1px solid #ddd6fe;

        border-radius: 9px;

        background:
            #faf5ff;

        color:
            #6d28d9;

        font-size: 0.67rem;

        font-weight: 850;

        transition:
            all 0.18s ease;
    }


    .ep-view-button:hover {

        color: #ffffff;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #7c3aed
            );

        border-color:
            transparent;

        box-shadow:
            0 6px 15px rgba(124,58,237,0.22);
    }


    /* =========================================================
       EMPTY
    ========================================================== */

    .ep-empty {

        padding:
            55px 20px !important;

        text-align: center;

        color: #94a3b8 !important;
    }


    .ep-empty-icon {

        width: 54px;
        height: 54px;

        margin:
            0 auto 12px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 17px;

        background:
            #f1f5f9;

        color:
            #a8b1bf;

        font-size: 1.1rem;
    }


    /* =========================================================
       PAGINATION
    ========================================================== */

    .ep-pagination {

        padding: 14px 18px;

        border-top:
            1px solid #edf0f4;

        background:
            #fafbfc;
    }


    /* =========================================================
       MODAL
    ========================================================== */

    .ep-modal {

        background:
            rgba(15,23,42,0.68) !important;

        backdrop-filter:
            blur(7px);
    }


    .ep-modal-panel {

        overflow: hidden;

        max-height:
            calc(100vh - 40px);

        border:
            1px solid rgba(255,255,255,0.55);

        border-radius:
            22px !important;

        background:
            #ffffff;

        box-shadow:
            0 30px 90px rgba(0,0,0,0.25) !important;

        animation:
            epModalOpen 0.22s ease-out;
    }


    @keyframes epModalOpen {

        from {

            opacity: 0;

            transform:
                translateY(12px)
                scale(.985);
        }

        to {

            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }
    }


    .ep-modal-header {

        padding:
            20px 24px !important;

        border-bottom:
            1px solid var(--ep-border);

        background:
            linear-gradient(
                135deg,
                #ffffff,
                #faf8ff
            );
    }


    .ep-modal-title {

        display: flex;

        align-items: center;

        gap: 10px;

        margin: 0;

        color: #172033;

        font-size: 0.94rem !important;

        font-weight: 900 !important;
    }


    .ep-modal-title i {

        color:
            #ec4899;
    }


    .ep-modal-close {

        width: 35px;
        height: 35px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border: 0;

        border-radius: 9px;

        background:
            transparent;

        color:
            #94a3b8;

        font-size:
            1.15rem;

        transition:
            all .18s ease;
    }


    .ep-modal-close:hover {

        background:
            #fff1f2;

        color:
            #e11d48;
    }


    .ep-modal-body {

        max-height:
            55vh;

        overflow:
            auto;

        padding:
            0 24px 20px;
    }


    .ep-modal-footer {

        padding:
            17px 24px !important;

        border-top:
            1px solid var(--ep-border);

        background:
            #fafbfc;
    }


    .ep-close-button {

        padding:
            8px 16px;

        border:
            0;

        border-radius:
            9px;

        background:
            #edf0f4;

        color:
            #475569;

        font-size:
            0.72rem;

        font-weight:
            850;

        transition:
            all .18s ease;
    }


    .ep-close-button:hover {

        background:
            #e2e8f0;

        color:
            #1e293b;
    }


    /* =========================================================
       STATUS SECTIONS
    ========================================================== */

    .ep-status-section {

        margin-top:
            21px;

        overflow:
            hidden;

        border:
            1px solid var(--ep-border);

        border-radius:
            15px;

        background:
            #ffffff;
    }


    .ep-status-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding:
            14px 16px;

        border-bottom:
            1px solid var(--ep-border);
    }


    .ep-status-header.live {

        background:
            linear-gradient(
                135deg,
                #f0fdf4,
                #f7fff9
            );
    }


    .ep-status-header.past {

        background:
            linear-gradient(
                135deg,
                #f8fafc,
                #fafbfc
            );
    }


    .ep-status-name {

        display: flex;

        align-items: center;

        gap: 9px;

        color:
            #263244;

        font-size:
            0.72rem;

        font-weight:
            900;

        text-transform:
            uppercase;

        letter-spacing:
            .045em;
    }


    .ep-status-dot {

        width:
            8px;

        height:
            8px;

        border-radius:
            50%;
    }


    .ep-status-dot.live {

        background:
            #22c55e;

        box-shadow:
            0 0 0 4px rgba(34,197,94,.11);
    }


    .ep-status-dot.past {

        background:
            #64748b;

        box-shadow:
            0 0 0 4px rgba(100,116,139,.09);
    }


    .ep-status-total {

        padding:
            5px 9px;

        border-radius:
            7px;

        font-size:
            0.64rem;

        font-weight:
            900;
    }


    .ep-status-total.live {

        background:
            #dcfce7;

        color:
            #15803d;
    }


    .ep-status-total.past {

        background:
            #e2e8f0;

        color:
            #475569;
    }


    .ep-modal-table {

        width:
            100%;

        border-collapse:
            collapse;
    }


    .ep-modal-table th {

        padding:
            11px 12px;

        background:
            #f8f9fc;

        color:
            #7a8495;

        border-bottom:
            1px solid var(--ep-border);

        font-size:
            0.60rem;

        font-weight:
            900;

        letter-spacing:
            .065em;

        text-transform:
            uppercase;

        white-space:
            nowrap;
    }


    .ep-modal-table td {

        padding:
            12px;

        color:
            #596579;

        border-bottom:
            1px solid #edf0f4;

        font-size:
            0.76rem;

        vertical-align:
            middle;
    }


    .ep-modal-table tbody tr:hover {

        background:
            #fafbfe;
    }


    .ep-revenue {

        color:
            #059669 !important;

        font-weight:
            900 !important;
    }


    .ep-past-revenue {

        color:
            #475569 !important;

        font-weight:
            900 !important;
    }


    .ep-small-ticket {

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        min-width:
            33px;

        height:
            27px;

        padding:
            0 8px;

        border-radius:
            7px;

        background:
            #eef2ff;

        color:
            #4f46e5;

        font-weight:
            900;
    }


    .ep-small-ticket.past {

        background:
            #f1f5f9;

        color:
            #475569;
    }


    .ep-no-data {

        padding:
            24px 15px !important;

        text-align:
            center;

        color:
            #98a2b3 !important;

        font-size:
            .69rem !important;
    }


    .ep-no-data i {

        margin-right:
            5px;

        color:
            #c1c8d2;
    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 900px) {

        .ep-stat-grid {

            grid-template-columns:
                1fr;
        }

        .ep-dashboard-intro {

            padding:
                24px;
        }
    }


    @media (max-width: 767px) {

        .ep-dashboard {

            margin:
                -.75rem;

            padding:
                .75rem;
        }


        .ep-dashboard-intro {

            min-height:
                170px;
        }


        .ep-intro-icon {

            display:
                none;
        }


        .ep-stat-card {

            min-height:
                125px;

            padding:
                20px;
        }


        .ep-stat-value {

            font-size:
                1.5rem;
        }


        .ep-section-header {

            padding:
                18px;
        }


        .ep-table th,
        .ep-table td {

            padding:
                12px 14px;
        }


        .ep-modal-panel {

            max-height:
                calc(100vh - 24px);

            border-radius:
                18px !important;
        }


        .ep-modal-header,
        .ep-modal-footer {

            padding-left:
                18px !important;

            padding-right:
                18px !important;
        }


        .ep-modal-body {

            padding-left:
                18px;

            padding-right:
                18px;
        }
    }

</style>

<div class="ep-dashboard">


{{-- =========================================================
     EVENT PLUS DASHBOARD HERO
========================================================== --}}

<div class="ep-dashboard-intro">

    <div class="ep-intro-content">

        <div class="ep-intro-eyebrow">

            <i class="fa-solid fa-sparkles"></i>

            EVENT PLUS ADMIN

        </div>


        <h2 class="ep-intro-title">

            Everything happening,

            <span>at a glance.</span>

        </h2>


        <p class="ep-intro-description">

            Monitor event registrations, ticket sales, revenue and
            runner activity from your Event Plus control center.

        </p>

    </div>


    <div class="ep-intro-icon">

        <i class="fa-solid fa-calendar-check"></i>

    </div>

</div>


{{-- =========================================================
     STATISTICS
========================================================== --}}

<div class="ep-stat-grid">


    {{-- TOTAL REVENUE --}}

    <div
        onclick="openModal('revenueModal')"
        class="ep-stat-card ep-stat-revenue"
    >

        <div class="ep-stat-top">

            <div class="ep-stat-icon revenue">

                <i class="fa-solid fa-sack-dollar"></i>

            </div>


            <span class="ep-stat-click">

                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                Details

            </span>

        </div>


        <p class="ep-stat-label">
            Total Revenue
        </p>


        <h3 class="ep-stat-value">

            {{ number_format($totalRevenue) }}

            <small>MMK</small>

        </h3>

    </div>


    {{-- TOTAL TICKETS --}}

    <div
        onclick="openModal('ticketsModal')"
        class="ep-stat-card ep-stat-ticket"
    >

        <div class="ep-stat-top">

            <div class="ep-stat-icon ticket">

                <i class="fa-solid fa-ticket"></i>

            </div>


            <span class="ep-stat-click">

                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                Details

            </span>

        </div>


        <p class="ep-stat-label">
            Tickets Sold
        </p>


        <h3 class="ep-stat-value">

            {{ $totalTicketsSold }}

        </h3>

    </div>


    {{-- ACTIVE EVENTS --}}

    <div
        class="ep-stat-card ep-stat-event"
    >

        <div class="ep-stat-top">

            <div class="ep-stat-icon event">

                <i class="fa-solid fa-calendar-days"></i>

            </div>


            <span class="ep-stat-click">

                <i class="fa-solid fa-circle-check"></i>

                Running

            </span>

        </div>


        <p class="ep-stat-label">
            Active Events
        </p>


        <h3 class="ep-stat-value">

            {{ $activeEventsCount }}

        </h3>

    </div>

</div>


{{-- =========================================================
     RUNNER PURCHASE DIRECTORY
========================================================== --}}

<div class="ep-section">

    <div class="ep-section-header">

        <div class="ep-section-heading">

            <div class="ep-section-icon">

                <i class="fa-solid fa-users"></i>

            </div>


            <div>

                <h2 class="ep-section-title">

                    Runner Purchase Directory

                </h2>


                <p class="ep-section-subtitle">

                    Registration history and purchased events

                </p>

            </div>

        </div>

    </div>


    <div class="ep-table-wrap">

        <table class="ep-table text-left">

            <thead>

                <tr>

                    <th>
                        Runner
                    </th>

                    <th>
                        Email Address
                    </th>

                    <th>
                        Phone
                    </th>

                    <th>
                        Tickets
                    </th>

                    <th class="text-right">
                        Purchased Events
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($loyaltyRunners as $index => $runner)

                    @php

                        $globalIndex =
                            ($loyaltyRunners->currentPage() - 1)
                            *
                            $loyaltyRunners->perPage()
                            +
                            $index;

                        $runnerName =
                            $runner->full_name ?? 'Runner';

                        $runnerInitial =
                            strtoupper(
                                substr(
                                    $runnerName,
                                    0,
                                    1
                                )
                            );

                    @endphp


                    <tr>

                        <td>

                            <div class="ep-runner-cell">

                                <div class="ep-runner-avatar">

                                    {{ $runnerInitial }}

                                </div>


                                <div>

                                    <div class="ep-user-code">

                                        {{
                                            $runner->user_code
                                            ??
                                            'EP-' .
                                            str_pad(
                                                $globalIndex + 1,
                                                4,
                                                '0',
                                                STR_PAD_LEFT
                                            )
                                        }}

                                    </div>


                                    <div class="ep-runner-name">

                                        {{ $runnerName }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        <td>

                            {{ $runner->email }}

                        </td>


                        <td>

                            {{ $runner->phone }}

                        </td>


                        <td>

                            <span class="ep-ticket-count">

                                {{ $runner->ticket_count }}

                            </span>

                        </td>


                        <td class="text-right">

                            <button
                                type="button"
                                onclick="openModal('runnerEventsModal-{{ $globalIndex }}')"
                                class="ep-view-button"
                            >

                                <i class="fa-solid fa-calendar-check"></i>

                                View Events

                                <span>
                                    ({{ count($runner->purchased_events) }})
                                </span>

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="ep-empty"
                        >

                            <div class="ep-empty-icon">

                                <i class="fa-solid fa-users-slash"></i>

                            </div>


                            No paid orders registered yet.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}

    @if($loyaltyRunners->hasPages())

        <div class="ep-pagination">

            {{ $loyaltyRunners->links() }}

        </div>

    @endif

</div>


{{-- =========================================================
     RUNNER EVENTS MODALS
========================================================== --}}

@foreach($loyaltyRunners as $index => $runner)

    @php

        $globalIndex =
            ($loyaltyRunners->currentPage() - 1)
            *
            $loyaltyRunners->perPage()
            +
            $index;

    @endphp


    <div
        id="runnerEventsModal-{{ $globalIndex }}"
        class="ep-modal fixed inset-0 hidden items-center justify-center z-50 p-4"
    >

        <div class="ep-modal-panel max-w-2xl w-full">


            <div class="ep-modal-header flex justify-between items-center">

                <h3 class="ep-modal-title">

                    <i class="fa-solid fa-user-tag"></i>

                    Events Joined by
                    {{ $runner->full_name }}

                    <span class="text-slate-400">
                        (
                        {{
                            $runner->user_code
                            ??
                            'EP-' .
                            str_pad(
                                $globalIndex + 1,
                                4,
                                '0',
                                STR_PAD_LEFT
                            )
                        }}
                        )
                    </span>

                </h3>


                <button
                    onclick="closeModal('runnerEventsModal-{{ $globalIndex }}')"
                    class="ep-modal-close"
                    type="button"
                    aria-label="Close"
                >

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>


            <div class="ep-modal-body pt-4">

                <div class="overflow-x-auto">

                    <table class="ep-modal-table">

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Tickets
                                </th>

                                <th class="text-right">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($runner->purchased_events as $item)

                                <tr>

                                    <td class="font-bold text-gray-800">

                                        {{ $item['event_title'] }}

                                    </td>


                                    <td>

                                        <span class="text-xs bg-indigo-50 text-indigo-700 font-bold px-2 py-1 rounded">

                                            {{ $item['categories'] }}

                                        </span>

                                    </td>


                                    <td>

                                        <span
                                            class="text-xs font-extrabold uppercase
                                            {{
                                                strtolower($item['event_status']) === 'live'
                                                ? 'text-emerald-600'
                                                : 'text-gray-500'
                                            }}"
                                        >

                                            {{ $item['event_status'] }}

                                        </span>

                                    </td>


                                    <td class="text-center">

                                        <span class="ep-small-ticket">

                                            {{ $item['ticket_count'] }}

                                        </span>

                                    </td>


                                    <td class="text-right font-bold text-emerald-600">

                                        {{ number_format($item['total_spent']) }}

                                        MMK

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="ep-no-data"
                                    >

                                        <i class="fa-solid fa-calendar-xmark"></i>

                                        No active events found for this runner.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="ep-modal-footer text-right">

                <button
                    onclick="closeModal('runnerEventsModal-{{ $globalIndex }}')"
                    class="ep-close-button"
                    type="button"
                >

                    Close

                </button>

            </div>

        </div>

    </div>

@endforeach


{{-- =========================================================
     REVENUE MODAL
========================================================== --}}

<div
    id="revenueModal"
    class="ep-modal fixed inset-0 hidden items-center justify-center z-50 p-4"
>

    <div class="ep-modal-panel max-w-3xl w-full">


        <div class="ep-modal-header flex justify-between items-center">

            <h3 class="ep-modal-title">

                <i class="fa-solid fa-chart-column"></i>

                Revenue Overview

            </h3>


            <button
                onclick="closeModal('revenueModal')"
                class="ep-modal-close"
                type="button"
                aria-label="Close"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <div class="ep-modal-body">


            {{-- LIVE EVENTS --}}

            <div class="ep-status-section">

                <div class="ep-status-header live">

                    <div class="ep-status-name">

                        <span class="ep-status-dot live"></span>

                        Live Events

                    </div>


                    <span class="ep-status-total live">

                        {{
                            number_format(
                                collect($eventRevenueBreakdown)
                                ->where('status', 'live')
                                ->sum('real_revenue')
                            )
                        }}

                        MMK

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="ep-modal-table">

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-right">
                                    Revenue
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                collect($eventRevenueBreakdown)
                                ->where('status', 'live')
                                as $item
                            )

                                <tr>

                                    <td class="font-semibold text-gray-800">

                                        {{ $item->title }}

                                    </td>


                                    <td>

                                        <span class="text-xs font-bold text-emerald-600">

                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i>

                                            LIVE

                                        </span>

                                    </td>


                                    <td class="text-right ep-revenue">

                                        {{ number_format($item->real_revenue) }}

                                        MMK

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="ep-no-data"
                                    >

                                        <i class="fa-solid fa-calendar-xmark"></i>

                                        No live event revenue yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAST EVENTS --}}

            <div class="ep-status-section">

                <div class="ep-status-header past">

                    <div class="ep-status-name">

                        <span class="ep-status-dot past"></span>

                        Past Events

                    </div>


                    <span class="ep-status-total past">

                        {{
                            number_format(
                                collect($eventRevenueBreakdown)
                                ->where('status', 'past')
                                ->sum('real_revenue')
                            )
                        }}

                        MMK

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="ep-modal-table">

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-right">
                                    Revenue
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                collect($eventRevenueBreakdown)
                                ->where('status', 'past')
                                as $item
                            )

                                <tr>

                                    <td class="font-semibold text-gray-800">

                                        {{ $item->title }}

                                    </td>


                                    <td>

                                        <span class="text-xs font-bold text-slate-500">

                                            <i class="fa-solid fa-clock-rotate-left mr-1"></i>

                                            PAST

                                        </span>

                                    </td>


                                    <td class="text-right ep-past-revenue">

                                        {{ number_format($item->real_revenue) }}

                                        MMK

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="ep-no-data"
                                    >

                                        <i class="fa-solid fa-calendar-xmark"></i>

                                        No past event revenue yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="ep-modal-footer text-right">

            <button
                onclick="closeModal('revenueModal')"
                class="ep-close-button"
                type="button"
            >

                Close

            </button>

        </div>

    </div>

</div>


{{-- =========================================================
     TICKETS MODAL
========================================================== --}}

<div
    id="ticketsModal"
    class="ep-modal fixed inset-0 hidden items-center justify-center z-50 p-4"
>

    <div class="ep-modal-panel max-w-3xl w-full">


        <div class="ep-modal-header flex justify-between items-center">

            <h3 class="ep-modal-title">

                <i class="fa-solid fa-ticket"></i>

                Ticket Sales Overview

            </h3>


            <button
                onclick="closeModal('ticketsModal')"
                class="ep-modal-close"
                type="button"
                aria-label="Close"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <div class="ep-modal-body">


            {{-- LIVE TICKETS --}}

            <div class="ep-status-section">

                <div class="ep-status-header live">

                    <div class="ep-status-name">

                        <span class="ep-status-dot live"></span>

                        Live Events

                    </div>


                    <span class="ep-status-total live">

                        {{
                            collect($categoryTicketBreakdown)
                            ->filter(
                                fn($cat) =>
                                    optional($cat->event)->status === 'live'
                            )
                            ->sum('paid_tickets_count')
                        }}

                        Tickets

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="ep-modal-table">

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Category
                                </th>

                                <th class="text-right">
                                    Paid Tickets
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(

                                collect($categoryTicketBreakdown)
                                ->filter(
                                    fn($cat) =>
                                        optional($cat->event)->status === 'live'
                                )

                                as $cat

                            )

                                <tr>

                                    <td class="font-medium text-gray-800">

                                        {{ $cat->event->title ?? 'N/A' }}

                                    </td>


                                    <td class="font-semibold text-indigo-600">

                                        {{ $cat->name }}

                                    </td>


                                    <td class="text-right">

                                        <span class="ep-small-ticket">

                                            {{ $cat->paid_tickets_count }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="ep-no-data"
                                    >

                                        <i class="fa-solid fa-ticket-slash"></i>

                                        No live event tickets sold yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAST TICKETS --}}

            <div class="ep-status-section">

                <div class="ep-status-header past">

                    <div class="ep-status-name">

                        <span class="ep-status-dot past"></span>

                        Past Events

                    </div>


                    <span class="ep-status-total past">

                        {{
                            collect($categoryTicketBreakdown)
                            ->filter(
                                fn($cat) =>
                                    optional($cat->event)->status === 'past'
                            )
                            ->sum('paid_tickets_count')
                        }}

                        Tickets

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="ep-modal-table">

                        <thead>

                            <tr>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Category
                                </th>

                                <th class="text-right">
                                    Paid Tickets
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(

                                collect($categoryTicketBreakdown)
                                ->filter(
                                    fn($cat) =>
                                        optional($cat->event)->status === 'past'
                                )

                                as $cat

                            )

                                <tr>

                                    <td class="font-medium text-gray-800">

                                        {{ $cat->event->title ?? 'N/A' }}

                                    </td>


                                    <td class="font-semibold text-slate-600">

                                        {{ $cat->name }}

                                    </td>


                                    <td class="text-right">

                                        <span class="ep-small-ticket past">

                                            {{ $cat->paid_tickets_count }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="ep-no-data"
                                    >

                                        <i class="fa-solid fa-ticket-slash"></i>

                                        No past event tickets sold yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="ep-modal-footer text-right">

            <button
                onclick="closeModal('ticketsModal')"
                class="ep-close-button"
                type="button"
            >

                Close

            </button>

        </div>

    </div>

</div>


</div>

{{-- =========================================================
MODAL JAVASCRIPT
========================================================== --}}

<script>

    function openModal(id) {

        const el = document.getElementById(id);

        if (!el) {
            return;
        }

        el.classList.remove('hidden');

        el.classList.add('flex');

        document.body.style.overflow = 'hidden';
    }


    function closeModal(id) {

        const el = document.getElementById(id);

        if (!el) {
            return;
        }

        el.classList.remove('flex');

        el.classList.add('hidden');

        document.body.style.overflow = '';
    }


    /* Close modal when clicking outside the panel */

    document.addEventListener('click', function(event) {

        if (
            event.target.classList &&
            event.target.classList.contains('ep-modal')
        ) {

            const modalId = event.target.id;

            closeModal(modalId);
        }

    });


    /* ESC key */

    document.addEventListener('keydown', function(event) {

        if (event.key !== 'Escape') {
            return;
        }

        const openModalElement =
            document.querySelector('.ep-modal.flex');

        if (openModalElement) {

            closeModal(
                openModalElement.id
            );

        }

    });

</script>

@endsection
