@extends('layouts.admin')

@section('title', 'Manage Events')

@section('page-title', 'Events Directory')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS — EVENTS DIRECTORY
       ========================================================= */

    .ep-events {
        --ep-navy: #0f172a;
        --ep-navy-2: #1e1b4b;
        --ep-purple: #7c3aed;
        --ep-violet: #8b5cf6;
        --ep-pink: #ec4899;
        --ep-rose: #f43f5e;
        --ep-indigo: #4f46e5;
        --ep-text: #172033;
        --ep-muted: #718096;
        --ep-border: #e8eaf0;
        --ep-bg: #f7f7fb;
        --ep-white: #ffffff;
    }

    /* =========================================================
       PAGE BACKGROUND
       ========================================================= */

    .ep-events {
        position: relative;
        min-height: 100%;
        padding-bottom: 30px;
        color: var(--ep-text);
    }

    /* =========================================================
       HERO / HEADER
       ========================================================= */

    .ep-events-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 28px 30px;
        border-radius: 24px;
        background:
            radial-gradient(circle at 88% 15%, rgba(236, 72, 153, .25), transparent 25%),
            radial-gradient(circle at 70% 100%, rgba(139, 92, 246, .30), transparent 30%),
            linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #312e81 100%);
        box-shadow: 0 18px 45px rgba(30, 27, 75, .18);
    }

    .ep-events-hero::before {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -75px;
        top: -105px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
    }

    .ep-events-hero::after {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        right: 90px;
        bottom: -80px;
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 50%;
    }

    .ep-events-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .ep-events-hero-left {
        min-width: 0;
    }

    .ep-events-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 10px;
        padding: 6px 10px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 999px;
        background: rgba(255,255,255,.08);
        color: #f5d0fe;
        font-size: .61rem;
        font-weight: 850;
        letter-spacing: .12em;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
    }

    .ep-events-eyebrow i {
        color: #f9a8d4;
    }

    .ep-events-hero h2 {
        margin: 0;
        color: #ffffff;
        font-size: clamp(1.35rem, 2vw, 1.75rem);
        font-weight: 900;
        letter-spacing: -.035em;
        line-height: 1.15;
    }

    .ep-events-hero p {
        max-width: 650px;
        margin: 9px 0 0;
        color: rgba(255,255,255,.68);
        font-size: .78rem;
        line-height: 1.65;
    }

    .ep-events-hero-right {
        flex-shrink: 0;
    }

    .ep-create-event {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 43px;
        padding: 11px 17px;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 12px;
        background: linear-gradient(135deg, #ec4899, #8b5cf6);
        color: #ffffff !important;
        font-size: .72rem;
        font-weight: 850;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(236,72,153,.25);
        transition: all .2s ease;
    }

    .ep-create-event:hover {
        transform: translateY(-2px);
        color: #ffffff !important;
        box-shadow: 0 14px 32px rgba(236,72,153,.35);
    }

    .ep-create-event i {
        font-size: .72rem;
    }

    /* =========================================================
       QUICK STRIP
       ========================================================= */

    .ep-event-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    .ep-event-strip-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        padding: 15px 17px;
        border: 1px solid var(--ep-border);
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 25px rgba(15,23,42,.045);
    }

    .ep-strip-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #f3e8ff;
        color: #7c3aed;
        font-size: .85rem;
    }

    .ep-strip-icon.pink {
        background: #fce7f3;
        color: #db2777;
    }

    .ep-strip-icon.blue {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .ep-strip-content {
        min-width: 0;
    }

    .ep-strip-label {
        margin: 0 0 2px;
        color: #94a3b8;
        font-size: .6rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .ep-strip-value {
        margin: 0;
        color: #1e293b;
        font-size: .82rem;
        font-weight: 850;
    }

    /* =========================================================
       SUCCESS MESSAGE
       ========================================================= */

    .ep-success {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
        padding: 13px 16px;
        border: 1px solid #bbf7d0;
        border-radius: 14px;
        background: linear-gradient(135deg, #f0fdf4, #f7fee7);
        color: #166534;
        font-size: .75rem;
        font-weight: 700;
        box-shadow: 0 7px 20px rgba(22,101,52,.045);
    }

    .ep-success-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #dcfce7;
        color: #16a34a;
    }

    /* =========================================================
       DIRECTORY CARD
       ========================================================= */

    .ep-directory {
        overflow: hidden;
        border: 1px solid var(--ep-border);
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 14px 45px rgba(15,23,42,.055);
    }

    .ep-directory-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--ep-border);
        background:
            linear-gradient(135deg, #ffffff 0%, #faf8ff 100%);
    }

    .ep-directory-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ep-directory-heading-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: linear-gradient(135deg, #ede9fe, #fce7f3);
        color: #7c3aed;
        font-size: .88rem;
    }

    .ep-directory-heading h3 {
        margin: 0;
        color: #1e293b;
        font-size: .88rem;
        font-weight: 900;
        letter-spacing: -.015em;
    }

    .ep-directory-heading p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: .65rem;
    }

    .ep-event-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border: 1px solid #ddd6fe;
        border-radius: 999px;
        background: #f5f3ff;
        color: #6d28d9;
        font-size: .62rem;
        font-weight: 850;
        white-space: nowrap;
    }

    .ep-event-count i {
        font-size: .58rem;
    }

    /* =========================================================
       TABLE
       ========================================================= */

    .ep-table-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .ep-table {
        width: 100%;
        min-width: 1250px;
        border-collapse: collapse;
    }

    .ep-table thead {
        background: #fafafa;
    }

    .ep-table thead tr {
        border-bottom: 1px solid var(--ep-border);
    }

    .ep-table th {
        padding: 13px 18px;
        color: #94a3b8;
        font-size: .59rem;
        font-weight: 900;
        letter-spacing: .095em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ep-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #f0f1f5;
        color: #64748b;
        font-size: .74rem;
        vertical-align: middle;
    }

    .ep-table tbody tr {
        transition: all .18s ease;
    }

    .ep-table tbody tr:hover {
        background: linear-gradient(
            90deg,
            #faf8ff 0%,
            #ffffff 70%
        );
    }

    .ep-table tbody tr:last-child td {
        border-bottom: 0;
    }

    /* =========================================================
       EVENT INFO
       ========================================================= */

    .ep-event-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 265px;
    }

    .ep-event-image-wrap {
        position: relative;
        width: 56px;
        height: 56px;
        min-width: 56px;
    }

    .ep-event-image {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 6px 15px rgba(15,23,42,.09);
    }

    .ep-event-image-status {
        position: absolute;
        right: -2px;
        bottom: -2px;
        width: 12px;
        height: 12px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        background: #10b981;
    }

    .ep-event-copy {
        min-width: 0;
    }

    .ep-event-title {
        overflow: hidden;
        margin: 0 0 4px;
        color: #1e293b;
        font-size: .78rem;
        font-weight: 850;
        line-height: 1.35;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ep-event-description {
        max-width: 235px;
        margin: 0;
        overflow: hidden;
        color: #94a3b8;
        font-size: .64rem;
        line-height: 1.45;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* =========================================================
       LOCATION / DATE
       ========================================================= */

    .ep-location {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        max-width: 220px;
        color: #334155;
        font-size: .7rem;
        font-weight: 750;
        line-height: 1.4;
    }

    .ep-location i {
        margin-top: 2px;
        color: #ec4899;
        font-size: .68rem;
    }

    .ep-date {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 7px;
        color: #94a3b8;
        font-size: .62rem;
        white-space: nowrap;
    }

    .ep-date i {
        color: #8b5cf6;
    }

    /* =========================================================
       CATEGORIES
       ========================================================= */

    .ep-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        min-width: 190px;
        max-width: 275px;
    }

    .ep-category {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 8px;
        border: 1px solid #e9d5ff;
        border-radius: 8px;
        background: #faf5ff;
        color: #6b21a8;
        font-size: .6rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .ep-category i {
        color: #8b5cf6;
        font-size: .52rem;
    }

    .ep-no-data {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #a1a1aa;
        font-size: .65rem;
        font-style: italic;
    }

    .ep-no-data i {
        color: #c4b5fd;
    }

    /* =========================================================
       EVENT ITEMS
       ========================================================= */

    .ep-items {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        min-width: 250px;
        max-width: 370px;
    }

    .ep-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px 5px 5px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 3px 9px rgba(15,23,42,.035);
        white-space: nowrap;
    }

    .ep-item-image {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
    }

    .ep-item-placeholder {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #f3e8ff;
        color: #8b5cf6;
        font-size: .62rem;
    }

    .ep-item-title {
        color: #475569;
        font-size: .61rem;
        font-weight: 800;
    }

    .ep-items-more {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 9px;
        border-radius: 8px;
        background: #fce7f3;
        color: #be185d;
        font-size: .59rem;
        font-weight: 850;
    }

    /* =========================================================
       STATUS
       ========================================================= */

    .ep-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: .59rem;
        font-weight: 900;
        letter-spacing: .035em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ep-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .ep-status-live {
        background: #fff1f2;
        color: #e11d48;
    }

    .ep-status-live .ep-status-dot {
        background: #f43f5e;
        box-shadow: 0 0 0 3px rgba(244,63,94,.13);
    }

    .ep-status-upcoming {
        background: #ecfdf5;
        color: #059669;
    }

    .ep-status-upcoming .ep-status-dot {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,.12);
    }

    .ep-status-past {
        background: #f1f5f9;
        color: #64748b;
    }

    .ep-status-past .ep-status-dot {
        background: #94a3b8;
    }

    /* =========================================================
       ACTIONS
       ========================================================= */

    .ep-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        min-width: 325px;
    }

    .ep-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 7px 10px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: .59rem;
        font-weight: 850;
        text-decoration: none;
        transition: all .18s ease;
    }

    .ep-action:hover {
        transform: translateY(-1px);
    }

    .ep-action-promo {
        border-color: #e9d5ff;
        background: #faf5ff;
        color: #7e22ce;
    }

    .ep-action-promo:hover {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .ep-action-attendees {
        border-color: #dbeafe;
        background: #eff6ff;
        color: #2563eb;
    }

    .ep-action-attendees:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .ep-action-edit {
        border-color: #fde68a;
        background: #fffbeb;
        color: #b45309;
    }

    .ep-action-edit:hover {
        background: #fef3c7;
        color: #92400e;
    }

    .ep-action-delete {
        border-color: #fecdd3;
        background: #fff1f2;
        color: #e11d48;
        cursor: pointer;
    }

    .ep-action-delete:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .ep-empty {
        padding: 70px 20px !important;
        text-align: center;
    }

    .ep-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background:
            linear-gradient(135deg, #ede9fe, #fce7f3);
        color: #7c3aed;
        font-size: 1.35rem;
        box-shadow: 0 10px 25px rgba(124,58,237,.10);
    }

    .ep-empty h3 {
        margin: 0 0 6px;
        color: #1e293b;
        font-size: .92rem;
        font-weight: 900;
    }

    .ep-empty p {
        margin: 0;
        color: #94a3b8;
        font-size: .7rem;
    }

    .ep-empty a {
        color: #7c3aed;
        font-weight: 850;
        text-decoration: none;
    }

    .ep-empty a:hover {
        color: #db2777;
        text-decoration: underline;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 900px) {
        .ep-events-hero-inner {
            align-items: flex-start;
            flex-direction: column;
        }

        .ep-events-hero-right {
            width: 100%;
        }

        .ep-create-event {
            width: 100%;
        }

        .ep-event-strip {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .ep-events-hero {
            padding: 23px 20px;
            border-radius: 20px;
        }

        .ep-directory-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 17px;
        }

        .ep-event-count {
            align-self: flex-start;
        }

        .ep-table th,
        .ep-table td {
            padding: 13px 14px;
        }

        .ep-actions {
            min-width: 250px;
        }
    }
</style>

<div class="ep-events">


{{-- =========================================================
     EVENT PLUS HERO
     ========================================================= --}}

<div class="ep-events-hero">

    <div class="ep-events-hero-inner">

        <div class="ep-events-hero-left">

            <div class="ep-events-eyebrow">
                <i class="fa-solid fa-bolt"></i>
                Event Plus Operations
            </div>

            <h2>Manage Your Events</h2>

            <p>
                Keep your events, ticket categories, merchandise,
                attendees and event details organized in one place.
            </p>

        </div>

        <div class="ep-events-hero-right">

            <a
                href="{{ route('admin.events.create') }}"
                class="ep-create-event"
            >
                <i class="fa-solid fa-plus"></i>
                Create New Event
            </a>

        </div>

    </div>

</div>


{{-- =========================================================
     QUICK INFORMATION
     ========================================================= --}}

<div class="ep-event-strip">

    <div class="ep-event-strip-card">

        <div class="ep-strip-icon">
            <i class="fa-solid fa-calendar-days"></i>
        </div>

        <div class="ep-strip-content">

            <p class="ep-strip-label">
                Event Directory
            </p>

            <p class="ep-strip-value">
                {{ $events->count() }} Events
            </p>

        </div>

    </div>


    <div class="ep-event-strip-card">

        <div class="ep-strip-icon pink">
            <i class="fa-solid fa-ticket"></i>
        </div>

        <div class="ep-strip-content">

            <p class="ep-strip-label">
                Ticket Management
            </p>

            <p class="ep-strip-value">
                Categories & Capacity
            </p>

        </div>

    </div>


    <div class="ep-event-strip-card">

        <div class="ep-strip-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="ep-strip-content">

            <p class="ep-strip-label">
                Event Operations
            </p>

            <p class="ep-strip-value">
                Attendees & Promo Codes
            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
     ========================================================= --}}

@if(session('success'))

    <div class="ep-success">

        <div class="ep-success-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


{{-- =========================================================
     EVENT DIRECTORY
     ========================================================= --}}

<div class="ep-directory">

    <div class="ep-directory-header">

        <div class="ep-directory-heading">

            <div class="ep-directory-heading-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>

            <div>

                <h3>
                    Event Directory
                </h3>

                <p>
                    View and manage all registered events
                </p>

            </div>

        </div>


        <span class="ep-event-count">

            <i class="fa-solid fa-calendar-check"></i>

            {{ $events->count() }} Marathon Events

        </span>

    </div>


    <div class="ep-table-scroll">

        <table class="ep-table">

            <thead>

                <tr>

                    <th>
                        Event
                    </th>

                    <th>
                        Location & Date
                    </th>

                    <th>
                        Categories
                    </th>

                    <th>
                        Event Items
                    </th>

                    <th>
                        Status
                    </th>

                    <th class="text-right">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($events as $event)

                    <tr>

                        {{-- =================================================
                             EVENT
                             ================================================= --}}

                        <td>

                            <div class="ep-event-info">

                                <div class="ep-event-image-wrap">

                                    <img
                                        src="{{ $event->image
                                            ? asset('storage/' . $event->image)
                                            : asset('assets/img/about/img06.jpg') }}"
                                        alt="{{ $event->title }}"
                                        class="ep-event-image"
                                    >

                                    <span class="ep-event-image-status"></span>

                                </div>


                                <div class="ep-event-copy">

                                    <p class="ep-event-title">
                                        {{ $event->title }}
                                    </p>

                                    <p class="ep-event-description">
                                        {{ Str::limit($event->description, 40) }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- =================================================
                             LOCATION & DATE
                             ================================================= --}}

                        <td>

                            <div class="ep-location">

                                <i class="fa-solid fa-location-dot"></i>

                                <span>
                                    {{ $event->location }}
                                </span>

                            </div>


                            <div class="ep-date">

                                <i class="fa-regular fa-calendar"></i>

                                <span>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y - h\:i A') }}
                                </span>

                            </div>

                        </td>


                        {{-- =================================================
                             TICKET CATEGORIES
                             ================================================= --}}

                        <td>

                            <div class="ep-categories">

                                @if($event->ticketCategories->count() > 0)

                                    @foreach($event->ticketCategories as $category)

                                        <span class="ep-category">

                                            <i class="fa-solid fa-ticket"></i>

                                            {{ $category->name }}

                                            ({{ $category->tickets_sold }}/{{ $category->capacity ?? '∞' }})

                                        </span>

                                    @endforeach

                                @else

                                    <span class="ep-no-data">

                                        <i class="fa-solid fa-circle-info"></i>

                                        No categories

                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- =================================================
                             EVENT ITEMS
                             ================================================= --}}

                        <td>

                            <div class="ep-items">

                                @if($event->items->count() > 0)

                                    @foreach($event->items->take(4) as $item)

                                        <div
                                            class="ep-item"
                                            title="{{ $item->title }}"
                                        >

                                            @if($item->image)

                                                <img
                                                    src="{{ asset('storage/' . $item->image) }}"
                                                    alt="{{ $item->title }}"
                                                    class="ep-item-image"
                                                >

                                            @else

                                                <div class="ep-item-placeholder">

                                                    <i class="fa-solid fa-gift"></i>

                                                </div>

                                            @endif


                                            <span class="ep-item-title">

                                                {{ Str::limit($item->title, 18) }}

                                            </span>

                                        </div>

                                    @endforeach


                                    @if($event->items->count() > 4)

                                        <span class="ep-items-more">

                                            <i class="fa-solid fa-plus"></i>

                                            {{ $event->items->count() - 4 }} more

                                        </span>

                                    @endif

                                @else

                                    <span class="ep-no-data">

                                        <i class="fa-solid fa-circle-info"></i>

                                        No items

                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- =================================================
                             STATUS
                             ================================================= --}}

                        <td>

                            @if($event->status === 'live')

                                <span class="ep-status ep-status-live">

                                    <span class="ep-status-dot"></span>

                                    Live Now

                                </span>

                            @elseif($event->status === 'upcoming')

                                <span class="ep-status ep-status-upcoming">

                                    <span class="ep-status-dot"></span>

                                    Upcoming

                                </span>

                            @else

                                <span class="ep-status ep-status-past">

                                    <span class="ep-status-dot"></span>

                                    Past

                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             ACTIONS
                             ================================================= --}}

                        <td>

                            <div class="ep-actions">

                                {{-- Promo Codes --}}

                                <a
                                    href="{{ route('admin.events.promo_codes', $event) }}"
                                    class="ep-action ep-action-promo"
                                >

                                    <i class="fa-solid fa-tags"></i>

                                    Promo Codes

                                </a>


                                {{-- Attendees --}}

                                <a
                                    href="{{ route('admin.events.attendees', $event) }}"
                                    class="ep-action ep-action-attendees"
                                >

                                    <i class="fa-solid fa-users"></i>

                                    Attendees

                                </a>


                                {{-- Edit --}}

                                <a
                                    href="{{ route('admin.events.edit', $event) }}"
                                    class="ep-action ep-action-edit"
                                >

                                    <i class="fa-solid fa-pen-to-square"></i>

                                    Edit

                                </a>


                                {{-- Delete --}}

                                <form
                                    action="{{ route('admin.events.destroy', $event) }}"
                                    method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this event?');"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="ep-action ep-action-delete"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    {{-- =================================================
                         EMPTY STATE
                         ================================================= --}}

                    <tr>

                        <td
                            colspan="6"
                            class="ep-empty"
                        >

                            <div class="ep-empty-icon">

                                <i class="fa-solid fa-calendar-xmark"></i>

                            </div>


                            <h3>
                                No Events Created Yet
                            </h3>


                            <p>

                                Get started by

                                <a href="{{ route('admin.events.create') }}">
                                    creating your first event
                                </a>.

                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>

@endsection
