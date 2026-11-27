@extends('layouts.admin')

@section('title', 'Manage Events')

@section('page-title', 'Events')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS — EVENTS DIRECTORY (redesigned)
       ========================================================= */
    .ep-events {
        --ep-purple: #7c3aed;
        --ep-purple-soft: #f5f3ff;
        --ep-pink: #ec4899;
        --ep-text: #1e293b;
        --ep-muted: #64748b;
        --ep-faint: #94a3b8;
        --ep-border: #e8eaf0;
        --ep-bg: #f8f9fc;
        padding-bottom: 30px;
        color: var(--ep-text);
        font-size: 14px;
    }
    .ep-events * { box-sizing: border-box; }

    /* ---------- Top bar ---------- */
    .ep-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .ep-top h2 { margin: 0; font-size: 1.4rem; font-weight: 800; letter-spacing: -.02em; }
    .ep-top p  { margin: 4px 0 0; color: var(--ep-muted); font-size: .85rem; }
    .ep-btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 18px; border-radius: 12px; border: 0;
        background: linear-gradient(135deg, var(--ep-pink), var(--ep-purple));
        color: #fff !important; font-size: .85rem; font-weight: 700; text-decoration: none;
        box-shadow: 0 8px 20px rgba(124,58,237,.25);
        transition: transform .15s, box-shadow .15s;
    }
    .ep-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 26px rgba(124,58,237,.32); }

    /* ---------- Stats ---------- */
    .ep-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    .ep-stat {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 18px;
        background: #fff; border: 1px solid var(--ep-border); border-radius: 16px;
    }
    .ep-stat-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: var(--ep-purple-soft); color: var(--ep-purple); font-size: 1rem;
    }
    .ep-stat-icon.live  { background: #fff1f2; color: #e11d48; }
    .ep-stat-icon.soon  { background: #ecfdf5; color: #059669; }
    .ep-stat-icon.sold  { background: #fce7f3; color: #db2777; }
    .ep-stat-num   { font-size: 1.35rem; font-weight: 800; line-height: 1.1; }
    .ep-stat-label { color: var(--ep-muted); font-size: .75rem; margin-top: 2px; }

    /* ---------- Alerts ---------- */
    .ep-alert {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px; padding: 13px 16px; border-radius: 12px;
        font-size: .85rem; font-weight: 600;
    }
    .ep-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .ep-alert.error   { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }

    /* ---------- Panel + toolbar ---------- */
    .ep-panel {
        background: #fff; border: 1px solid var(--ep-border);
        border-radius: 18px; overflow: hidden;
        box-shadow: 0 10px 35px rgba(15,23,42,.045);
    }
    .ep-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        padding: 16px 18px; border-bottom: 1px solid var(--ep-border);
    }
    .ep-search { position: relative; flex: 1 1 280px; max-width: 380px; }
    .ep-search i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--ep-faint); font-size: .85rem;
    }
    .ep-search input {
        width: 100%; padding: 10px 14px 10px 38px;
        border: 1px solid var(--ep-border); border-radius: 11px;
        background: var(--ep-bg); font-size: .85rem; color: var(--ep-text);
        outline: none; transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .ep-search input:focus {
        border-color: #c4b5fd; background: #fff; box-shadow: 0 0 0 3px rgba(139,92,246,.12);
    }
    .ep-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .ep-tab {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 13px; border: 1px solid var(--ep-border); border-radius: 999px;
        background: #fff; color: var(--ep-muted);
        font-size: .8rem; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .ep-tab:hover { border-color: #c4b5fd; color: var(--ep-purple); }
    .ep-tab.active { background: var(--ep-purple); border-color: var(--ep-purple); color: #fff; }
    .ep-tab .n {
        min-width: 20px; padding: 1px 6px; border-radius: 999px;
        background: rgba(100,116,139,.12); font-size: .7rem; text-align: center;
    }
    .ep-tab.active .n { background: rgba(255,255,255,.25); }
    .ep-sort {
        padding: 9px 12px; border: 1px solid var(--ep-border); border-radius: 11px;
        background: #fff; font-size: .8rem; color: var(--ep-muted); cursor: pointer;
    }

    /* ---------- Table ---------- */
    .ep-table-scroll { width: 100%; overflow-x: auto; }
    .ep-table { width: 100%; min-width: 980px; border-collapse: collapse; }
    .ep-table th {
        padding: 12px 18px; background: #fafbfd; border-bottom: 1px solid var(--ep-border);
        color: var(--ep-faint); font-size: .7rem; font-weight: 800;
        letter-spacing: .07em; text-align: left; text-transform: uppercase; white-space: nowrap;
    }
    .ep-table td {
        padding: 16px 18px; border-bottom: 1px solid #f0f1f5; vertical-align: middle;
    }
    .ep-table tbody tr { transition: background .15s; }
    .ep-table tbody tr:hover { background: #fbfaff; }
    .ep-table tbody tr:last-child td { border-bottom: 0; }

    /* Event cell */
    .ep-event { display: flex; align-items: center; gap: 14px; min-width: 260px; }
    .ep-event img {
        width: 56px; height: 56px; object-fit: cover; flex-shrink: 0;
        border-radius: 14px; border: 1px solid var(--ep-border);
    }
    .ep-event-title {
        margin: 0 0 3px; font-weight: 700; font-size: .92rem; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }
    .ep-event-desc { margin: 0; color: var(--ep-faint); font-size: .78rem; max-width: 260px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* When / where */
    .ep-when { font-weight: 700; font-size: .85rem; white-space: nowrap; }
    .ep-when-time { color: var(--ep-muted); font-weight: 500; font-size: .78rem; margin-top: 2px; }
    .ep-where { display: flex; gap: 6px; align-items: flex-start; margin-top: 6px;
        color: var(--ep-muted); font-size: .78rem; max-width: 220px; }
    .ep-where i { color: var(--ep-pink); margin-top: 3px; font-size: .72rem; }

    /* Tickets */
    .ep-tickets { min-width: 190px; }
    .ep-tickets-row { display: flex; justify-content: space-between; font-size: .8rem; margin-bottom: 6px; }
    .ep-tickets-row strong { font-weight: 800; }
    .ep-tickets-row span { color: var(--ep-faint); }
    .ep-bar { height: 6px; border-radius: 999px; background: #eef0f6; overflow: hidden; }
    .ep-bar > div { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--ep-purple), var(--ep-pink)); }
    .ep-bar > div.full { background: #f43f5e; }
    .ep-cat-list { display: flex; flex-direction: column; gap: 9px; }
    .ep-cat-row { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; margin-bottom: 4px; }
    .ep-cat-name { font-size: .8rem; font-weight: 700; color: var(--ep-text);
        max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ep-cat-count { font-size: .75rem; color: var(--ep-faint); white-space: nowrap; }
    .ep-cat-count strong { color: var(--ep-text); font-weight: 800; }
    .ep-bar.sm { height: 4px; }
    .ep-chips { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 8px; }
    .ep-chip {
        padding: 3px 8px; border-radius: 7px; background: var(--ep-purple-soft);
        color: #6b21a8; font-size: .7rem; font-weight: 700; white-space: nowrap;
    }
    .ep-chip.more { background: #fce7f3; color: #be185d; }

    /* Items */
    .ep-avatars { display: flex; align-items: center; min-width: 120px; }
    .ep-avatar {
        width: 34px; height: 34px; margin-left: -8px; border-radius: 50%;
        border: 2px solid #fff; object-fit: cover; background: #f3e8ff; color: #8b5cf6;
        display: flex; align-items: center; justify-content: center; font-size: .72rem;
    }
    .ep-avatar:first-child { margin-left: 0; }
    .ep-avatar.more { background: #fce7f3; color: #be185d; font-weight: 800; font-size: .7rem; }
    .ep-none { color: #a1a1aa; font-size: .78rem; font-style: italic; }

    /* Status */
    .ep-status {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 11px; border-radius: 999px;
        font-size: .72rem; font-weight: 800; white-space: nowrap;
    }
    .ep-status i { width: 7px; height: 7px; border-radius: 50%; display: block; }
    .ep-status.live     { background: #fff1f2; color: #e11d48; }
    .ep-status.live i   { background: #f43f5e; animation: ep-pulse 1.6s infinite; }
    .ep-status.upcoming { background: #ecfdf5; color: #059669; }
    .ep-status.upcoming i { background: #10b981; }
    .ep-status.past     { background: #f1f5f9; color: #64748b; }
    .ep-status.past i   { background: #94a3b8; }
    @keyframes ep-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(244,63,94,.5); }
        70%  { box-shadow: 0 0 0 6px rgba(244,63,94,0); }
        100% { box-shadow: 0 0 0 0 rgba(244,63,94,0); }
    }

    /* Actions: two primary buttons + icon buttons with tooltips */
    .ep-actions { display: flex; align-items: center; justify-content: flex-end; gap: 6px; min-width: 270px; }
    .ep-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 12px; border-radius: 10px; border: 1px solid var(--ep-border);
        background: #fff; color: var(--ep-text); font-size: .78rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: all .15s; white-space: nowrap;
    }
    .ep-btn:hover { border-color: #c4b5fd; color: var(--ep-purple); background: var(--ep-purple-soft); }
    .ep-btn.edit { background: #fffbeb; border-color: #fde68a; color: #b45309; }
    .ep-btn.edit:hover { background: #fef3c7; color: #92400e; }
    .ep-icon-btn {
        position: relative; width: 36px; height: 36px; padding: 0; justify-content: center;
        color: var(--ep-muted);
    }
    .ep-icon-btn.danger { color: #e11d48; }
    .ep-icon-btn.danger:hover { background: #fff1f2; border-color: #fecdd3; color: #be123c; }
    .ep-icon-btn::after {
        content: attr(data-tip);
        position: absolute; bottom: calc(100% + 7px); left: 50%; transform: translateX(-50%);
        padding: 5px 9px; border-radius: 7px; background: #0f172a; color: #fff;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
        opacity: 0; pointer-events: none; transition: opacity .12s; z-index: 5;
    }
    .ep-icon-btn:hover::after, .ep-icon-btn:focus-visible::after { opacity: 1; }

    /* Empty */
    .ep-empty { padding: 64px 20px !important; text-align: center; }
    .ep-empty-icon {
        width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #ede9fe, #fce7f3); color: var(--ep-purple); font-size: 1.4rem;
    }
    .ep-empty h3 { margin: 0 0 6px; font-size: 1rem; font-weight: 800; }
    .ep-empty p { margin: 0; color: var(--ep-faint); font-size: .85rem; }
    .ep-empty a { color: var(--ep-purple); font-weight: 700; text-decoration: none; }
    #ep-no-results { display: none; }

    .ep-footer {
        padding: 12px 18px; border-top: 1px solid var(--ep-border);
        color: var(--ep-faint); font-size: .78rem; background: #fafbfd;
    }

    /* ---------- Delete modal ---------- */
    .ep-modal {
        position: fixed; inset: 0; z-index: 1000; display: none;
        align-items: center; justify-content: center; padding: 20px;
        background: rgba(15,23,42,.55); backdrop-filter: blur(3px);
    }
    .ep-modal.open { display: flex; }
    .ep-modal-box {
        width: 100%; max-width: 440px; padding: 26px; border-radius: 20px;
        background: #fff; box-shadow: 0 30px 70px rgba(15,23,42,.3);
    }
    .ep-modal-icon {
        width: 50px; height: 50px; margin-bottom: 14px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        background: #fff1f2; color: #e11d48; font-size: 1.15rem;
    }
    .ep-modal h3 { margin: 0 0 6px; font-size: 1.1rem; font-weight: 800; }
    .ep-modal p  { margin: 0 0 16px; color: var(--ep-muted); font-size: .85rem; line-height: 1.55; }
    .ep-modal label { display: block; margin-bottom: 6px; font-size: .8rem; font-weight: 700; }
    .ep-modal input[type=password] {
        width: 100%; padding: 11px 13px; border: 1px solid var(--ep-border); border-radius: 10px;
        font-size: .9rem; outline: none;
    }
    .ep-modal input[type=password]:focus { border-color: #fda4af; box-shadow: 0 0 0 3px rgba(244,63,94,.12); }
    .ep-modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px; }
    .ep-btn-danger {
        padding: 10px 16px; border: 0; border-radius: 10px; background: #e11d48; color: #fff;
        font-size: .82rem; font-weight: 700; cursor: pointer;
    }
    .ep-btn-danger:hover { background: #be123c; }

    /* ---------- Responsive: table becomes cards ---------- */
    @media (max-width: 1000px) { .ep-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 820px) {
        .ep-table-scroll { overflow: visible; }
        .ep-table, .ep-table tbody, .ep-table tr, .ep-table td { display: block; width: 100%; min-width: 0; }
        .ep-table thead { display: none; }
        .ep-table tr.ep-row { padding: 14px 16px; border-bottom: 1px solid var(--ep-border); }
        .ep-table td { padding: 8px 0; border: 0; }
        .ep-table td[data-label]::before {
            content: attr(data-label); display: block; margin-bottom: 5px;
            color: var(--ep-faint); font-size: .65rem; font-weight: 800;
            letter-spacing: .07em; text-transform: uppercase;
        }
        .ep-actions { justify-content: flex-start; flex-wrap: wrap; min-width: 0; }
        .ep-event-desc { max-width: none; }
        .ep-search { max-width: none; }
    }
    @media (max-width: 480px) {
        .ep-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
        .ep-stat { padding: 12px; gap: 10px; }
        .ep-btn-primary { width: 100%; justify-content: center; }
    }
</style>

@php
    $liveCount     = $events->where('status', 'live')->count();
    $upcomingCount = $events->where('status', 'upcoming')->count();
    $pastCount     = $events->count() - $liveCount - $upcomingCount;
    $totalSold     = $events->sum(fn ($e) => $e->ticketCategories->sum('tickets_sold'));
@endphp

<div class="ep-events">

    {{-- ===== Top bar ===== --}}
    <div class="ep-top">
        <div>
            <h2>Manage Events</h2>
            <p>Create, edit and run all your events from one place.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="ep-btn-primary">
            <i class="fa-solid fa-plus"></i> Create New Event
        </a>
    </div>

    {{-- ===== Stats ===== --}}
    <div class="ep-stats">
        <div class="ep-stat">
            <div class="ep-stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <div><div class="ep-stat-num">{{ $events->count() }}</div><div class="ep-stat-label">Total events</div></div>
        </div>
        <div class="ep-stat">
            <div class="ep-stat-icon live"><i class="fa-solid fa-tower-broadcast"></i></div>
            <div><div class="ep-stat-num">{{ $liveCount }}</div><div class="ep-stat-label">Live now</div></div>
        </div>
        <div class="ep-stat">
            <div class="ep-stat-icon soon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><div class="ep-stat-num">{{ $upcomingCount }}</div><div class="ep-stat-label">Upcoming</div></div>
        </div>
        <div class="ep-stat">
            <div class="ep-stat-icon sold"><i class="fa-solid fa-ticket"></i></div>
            <div><div class="ep-stat-num">{{ number_format($totalSold) }}</div><div class="ep-stat-label">Tickets sold</div></div>
        </div>
    </div>

    {{-- ===== Alerts ===== --}}
    @if(session('success'))
        <div class="ep-alert success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->has('admin_password'))
        <div class="ep-alert error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('admin_password') }}
        </div>
    @endif

    {{-- ===== Directory ===== --}}
    <div class="ep-panel">

        <div class="ep-toolbar">
            <div class="ep-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" id="ep-search" placeholder="Search by name or location…" autocomplete="off">
            </div>

            <div class="ep-tabs" id="ep-tabs">
                <button type="button" class="ep-tab active" data-filter="all">All <span class="n">{{ $events->count() }}</span></button>
                <button type="button" class="ep-tab" data-filter="live">Live <span class="n">{{ $liveCount }}</span></button>
                <button type="button" class="ep-tab" data-filter="upcoming">Upcoming <span class="n">{{ $upcomingCount }}</span></button>
                <button type="button" class="ep-tab" data-filter="past">Past <span class="n">{{ $pastCount }}</span></button>
            </div>

            <select class="ep-sort" id="ep-sort" aria-label="Sort events">
                <option value="default">Default order</option>
                <option value="date-asc">Date: soonest first</option>
                <option value="date-desc">Date: latest first</option>
                <option value="name">Name: A–Z</option>
            </select>
        </div>

        <div class="ep-table-scroll">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date &amp; Location</th>
                        <th>Tickets</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody id="ep-body">
                    @forelse($events as $event)
                        @php
                            $date       = \Carbon\Carbon::parse($event->event_date);
                            $cats       = $event->ticketCategories;
                            $sold       = $cats->sum('tickets_sold');
                            $unlimited  = $cats->contains(fn ($c) => is_null($c->capacity));
                            $capacity   = $cats->sum('capacity');
                            $pct        = (! $unlimited && $capacity > 0) ? min(100, round($sold / $capacity * 100)) : 0;
                            $status     = in_array($event->status, ['live', 'upcoming']) ? $event->status : 'past';
                        @endphp

                        <tr class="ep-row"
                            data-status="{{ $status }}"
                            data-name="{{ strtolower($event->title) }}"
                            data-search="{{ strtolower($event->title . ' ' . $event->location) }}"
                            data-date="{{ $date->timestamp }}"
                            data-index="{{ $loop->index }}">

                            {{-- Event --}}
                            <td data-label="Event">
                                <div class="ep-event">
                                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}"
                                         alt="{{ $event->title }}">
                                    <div style="min-width:0">
                                        <p class="ep-event-title">{{ $event->title }}</p>
                                        <p class="ep-event-desc">{{ Str::limit($event->description, 60) }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Date & location --}}
                            <td data-label="Date & Location">
                                <div class="ep-when">{{ $date->format('M d, Y') }}</div>
                                <div class="ep-when-time">{{ $date->format('h:i A') }}</div>
                                <div class="ep-where">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            </td>

                            {{-- Tickets --}}
                            <td data-label="Tickets">
                                <div class="ep-tickets">
                                    @if($cats->count() > 0)
                                        <div class="ep-cat-list">
                                            @foreach($cats as $category)
                                                @php
                                                    $catPct = $category->capacity
                                                        ? min(100, round($category->tickets_sold / $category->capacity * 100))
                                                        : 0;
                                                @endphp
                                                <div class="ep-cat">
                                                    <div class="ep-cat-row">
                                                        <span class="ep-cat-name" title="{{ $category->name }}">{{ $category->name }}</span>
                                                        <span class="ep-cat-count">
                                                            <strong>{{ number_format($category->tickets_sold) }}</strong>
                                                            / {{ $category->capacity ? number_format($category->capacity) : '∞' }}
                                                        </span>
                                                    </div>
                                                    @if($category->capacity)
                                                        <div class="ep-bar sm"><div class="{{ $catPct >= 100 ? 'full' : '' }}" style="width: {{ $catPct }}%"></div></div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="ep-none">No ticket categories</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Items --}}
                            <td data-label="Items">
                                @if($event->items->count() > 0)
                                    <div class="ep-avatars" title="{{ $event->items->pluck('title')->implode(', ') }}">
                                        @foreach($event->items->take(4) as $item)
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="ep-avatar">
                                            @else
                                                <span class="ep-avatar"><i class="fa-solid fa-gift"></i></span>
                                            @endif
                                        @endforeach
                                        @if($event->items->count() > 4)
                                            <span class="ep-avatar more">+{{ $event->items->count() - 4 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="ep-none">No items</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td data-label="Status">
                                <span class="ep-status {{ $status }}">
                                    <i></i>
                                    {{ $status === 'live' ? 'Live Now' : ucfirst($status) }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td data-label="Actions">
                                <div class="ep-actions">
                                    <a href="{{ route('admin.events.attendees', $event) }}" class="ep-btn">
                                        <i class="fa-solid fa-users"></i> Attendees
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="ep-btn edit">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.events.promo_codes', $event) }}" class="ep-btn ep-icon-btn" data-tip="Promo codes" aria-label="Promo codes">
                                        <i class="fa-solid fa-tags"></i>
                                    </a>
                                    <a href="{{ route('admin.events.ticket-design', $event) }}" class="ep-btn ep-icon-btn" data-tip="Ticket design" aria-label="Ticket design">
                                        <i class="fa-solid fa-palette"></i>
                                    </a>
                                    <button type="button"
                                            class="ep-btn ep-icon-btn danger"
                                            data-tip="Delete event"
                                            aria-label="Delete event"
                                            data-action="{{ route('admin.events.destroy', $event) }}"
                                            data-title="{{ $event->title }}"
                                            onclick="openDeleteModal(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="ep-empty">
                                <div class="ep-empty-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                                <h3>No events yet</h3>
                                <p>Get started by <a href="{{ route('admin.events.create') }}">creating your first event</a>.</p>
                            </td>
                        </tr>
                    @endforelse

                    {{-- Shown by JS when search/filter matches nothing --}}
                    <tr id="ep-no-results">
                        <td colspan="6" class="ep-empty">
                            <div class="ep-empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <h3>No matching events</h3>
                            <p>Try a different search or <a href="#" id="ep-reset">clear the filters</a>.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($events->count() > 0)
            <div class="ep-footer">
                Showing <strong id="ep-shown">{{ $events->count() }}</strong> of {{ $events->count() }} events
            </div>
        @endif
    </div>
</div>

{{-- ===== Delete confirmation modal ===== --}}
<div class="ep-modal" id="ep-delete-modal" role="dialog" aria-modal="true" aria-labelledby="ep-delete-title">
    <div class="ep-modal-box">
        <div class="ep-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3 id="ep-delete-title">Delete this event?</h3>
        <p>
            You are about to delete <strong id="ep-delete-name"></strong>.
            This may permanently remove its tickets, items and related data and cannot be undone.
        </p>

        <form method="POST" id="ep-delete-form" action="">
            @csrf
            @method('DELETE')

            <label for="ep-delete-password">Enter your admin password to confirm</label>
            <input type="password" id="ep-delete-password" name="admin_password" required autocomplete="current-password">

            <div class="ep-modal-actions">
                <button type="button" class="ep-btn" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="ep-btn-danger">
                    <i class="fa-solid fa-trash"></i> Delete event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    /* ---------- Search, filter, sort ---------- */
    var body     = document.getElementById('ep-body');
    var rows     = Array.prototype.slice.call(body.querySelectorAll('tr.ep-row'));
    var search   = document.getElementById('ep-search');
    var sort     = document.getElementById('ep-sort');
    var tabs     = document.querySelectorAll('#ep-tabs .ep-tab');
    var noResult = document.getElementById('ep-no-results');
    var shown    = document.getElementById('ep-shown');
    var filter   = 'all';

    function apply() {
        var q = (search.value || '').trim().toLowerCase();
        var visible = 0;

        rows.forEach(function (row) {
            var okStatus = filter === 'all' || row.dataset.status === filter;
            var okSearch = !q || row.dataset.search.indexOf(q) !== -1;
            var show = okStatus && okSearch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (shown) shown.textContent = visible;
        noResult.style.display = (rows.length && visible === 0) ? 'table-row' : 'none';
    }

    function applySort() {
        var mode = sort.value;
        var sorted = rows.slice().sort(function (a, b) {
            if (mode === 'date-asc')  return a.dataset.date - b.dataset.date;
            if (mode === 'date-desc') return b.dataset.date - a.dataset.date;
            if (mode === 'name')      return a.dataset.name.localeCompare(b.dataset.name);
            return a.dataset.index - b.dataset.index;
        });
        sorted.forEach(function (row) { body.insertBefore(row, noResult); });
    }

    if (rows.length) {
        search.addEventListener('input', apply);
        sort.addEventListener('change', applySort);
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');
                filter = tab.dataset.filter;
                apply();
            });
        });
        document.getElementById('ep-reset').addEventListener('click', function (e) {
            e.preventDefault();
            search.value = '';
            filter = 'all';
            tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.filter === 'all'); });
            apply();
        });
    }

    /* ---------- Delete modal ---------- */
    var modal = document.getElementById('ep-delete-modal');
    var form  = document.getElementById('ep-delete-form');
    var pwd   = document.getElementById('ep-delete-password');
    var name  = document.getElementById('ep-delete-name');

    window.openDeleteModal = function (btn) {
        form.action = btn.dataset.action;
        name.textContent = btn.dataset.title;
        pwd.value = '';
        modal.classList.add('open');
        setTimeout(function () { pwd.focus(); }, 50);
    };

    window.closeDeleteModal = function () {
        modal.classList.remove('open');
        pwd.value = '';
    };

    modal.addEventListener('click', function (e) { if (e.target === modal) closeDeleteModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDeleteModal(); });
})();
</script>

@endsection