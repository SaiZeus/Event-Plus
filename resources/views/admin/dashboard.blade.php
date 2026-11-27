@extends('layouts.admin')

@section('title', 'Event Plus - Admin Dashboard')
@section('page-title', 'Event Overview')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS DASHBOARD (redesigned)
    ========================================================== */
    .ep-dash {
        --ep-purple: #7c3aed;
        --ep-purple-soft: #f5f3ff;
        --ep-pink: #ec4899;
        --ep-text: #1e293b;
        --ep-muted: #64748b;
        --ep-faint: #94a3b8;
        --ep-border: #e8eaf0;
        --ep-bg: #f8f9fc;
        color: var(--ep-text);
        font-size: 14px;
        padding-bottom: 30px;
    }
    .ep-dash * { box-sizing: border-box; }

    /* ---------- Header ---------- */
    .ep-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
    .ep-head h2 { margin: 0; font-size: 1.4rem; font-weight: 800; letter-spacing: -.02em; }
    .ep-head p  { margin: 4px 0 0; color: var(--ep-muted); font-size: .85rem; }
    .ep-date-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px;
        border: 1px solid var(--ep-border); border-radius: 999px; background: #fff;
        color: var(--ep-muted); font-size: .8rem; font-weight: 600;
    }
    .ep-date-pill i { color: var(--ep-purple); }

    /* ---------- Stat cards ---------- */
    .ep-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
    .ep-stat {
        position: relative; display: block; width: 100%; text-align: left;
        padding: 18px 20px; background: #fff; border: 1px solid var(--ep-border);
        border-radius: 16px; font: inherit; color: inherit;
        transition: transform .15s, box-shadow .15s, border-color .15s;
    }
    button.ep-stat { cursor: pointer; }
    button.ep-stat:hover { transform: translateY(-3px); border-color: #c4b5fd; box-shadow: 0 14px 30px rgba(15,23,42,.08); }
    .ep-stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .ep-stat-icon {
        width: 42px; height: 42px; border-radius: 12px; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
    }
    .ep-stat-icon.green  { background: #ecfdf5; color: #059669; }
    .ep-stat-icon.indigo { background: #eef2ff; color: #4f46e5; }
    .ep-stat-icon.amber  { background: #fffbeb; color: #d97706; }
    .ep-stat-icon.pink   { background: #fdf2f8; color: #db2777; }
    .ep-stat-link { color: var(--ep-faint); font-size: .72rem; font-weight: 700; display: inline-flex; gap: 5px; align-items: center; }
    button.ep-stat:hover .ep-stat-link { color: var(--ep-purple); }
    .ep-stat-label { margin: 0 0 3px; color: var(--ep-muted); font-size: .78rem; font-weight: 600; }
    .ep-stat-value { margin: 0; font-size: 1.6rem; font-weight: 800; letter-spacing: -.03em; line-height: 1.15; }
    .ep-stat-value small { color: var(--ep-faint); font-size: .75rem; font-weight: 700; letter-spacing: 0; }

    /* ---------- Panel ---------- */
    .ep-panel { background: #fff; border: 1px solid var(--ep-border); border-radius: 18px; overflow: hidden; box-shadow: 0 10px 35px rgba(15,23,42,.045); }
    .ep-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--ep-border); }
    .ep-toolbar h3 { margin: 0; font-size: 1rem; font-weight: 800; }
    .ep-toolbar p  { margin: 3px 0 0; color: var(--ep-faint); font-size: .78rem; }
    .ep-search { position: relative; flex: 1 1 260px; max-width: 340px; }
    .ep-search i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--ep-faint); font-size: .85rem; }
    .ep-search input {
        width: 100%; padding: 10px 14px 10px 36px; border: 1px solid var(--ep-border); border-radius: 11px;
        background: var(--ep-bg); font-size: .85rem; color: var(--ep-text); outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .ep-search input:focus { border-color: #c4b5fd; background: #fff; box-shadow: 0 0 0 3px rgba(139,92,246,.12); }

    /* ---------- Tables ---------- */
    .ep-scroll { width: 100%; overflow-x: auto; }
    .ep-table { width: 100%; border-collapse: collapse; }
    .ep-table th {
        padding: 12px 20px; background: #fafbfd; border-bottom: 1px solid var(--ep-border);
        color: var(--ep-faint); font-size: .7rem; font-weight: 800; letter-spacing: .07em;
        text-align: left; text-transform: uppercase; white-space: nowrap;
    }
    .ep-table th.r, .ep-table td.r { text-align: right; }
    .ep-table th.c, .ep-table td.c { text-align: center; }
    .ep-table td { padding: 14px 20px; border-bottom: 1px solid #f0f1f5; vertical-align: middle; font-size: .85rem; color: var(--ep-muted); }
    .ep-table tbody tr:last-child td { border-bottom: 0; }
    .ep-table tbody tr.ep-row { cursor: pointer; transition: background .15s; }
    .ep-table tbody tr.ep-row:hover { background: #fbfaff; }

    .ep-runner { display: flex; align-items: center; gap: 12px; min-width: 200px; }
    .ep-avatar {
        width: 38px; height: 38px; flex-shrink: 0; border-radius: 12px; color: #fff;
        background: linear-gradient(135deg, var(--ep-pink), var(--ep-purple));
        display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 800;
    }
    .ep-runner-name { color: var(--ep-text); font-weight: 700; font-size: .88rem; }
    .ep-code {
        display: inline-block; margin-top: 2px; padding: 2px 7px; border: 1px solid #ddd6fe; border-radius: 6px;
        background: var(--ep-purple-soft); color: #6d28d9; font-family: ui-monospace, monospace; font-size: .68rem; font-weight: 700;
    }
    .ep-contact a { color: var(--ep-text); text-decoration: none; font-weight: 600; }
    .ep-contact a:hover { color: var(--ep-purple); }
    .ep-contact div { color: var(--ep-faint); font-size: .78rem; margin-top: 2px; }

    .ep-pill {
        display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 28px;
        padding: 0 10px; border-radius: 8px; background: #eef2ff; color: #4f46e5; font-weight: 800; font-size: .82rem;
    }
    .ep-btn {
        display: inline-flex; align-items: center; gap: 7px; padding: 8px 13px; border: 1px solid #ddd6fe;
        border-radius: 10px; background: var(--ep-purple-soft); color: #6d28d9; font-size: .78rem; font-weight: 700;
        cursor: pointer; transition: all .15s; white-space: nowrap;
    }
    .ep-btn:hover { background: linear-gradient(135deg, var(--ep-pink), var(--ep-purple)); border-color: transparent; color: #fff; }
    .ep-btn-plain { background: #fff; border-color: var(--ep-border); color: var(--ep-muted); }
    .ep-btn-plain:hover { background: #f1f5f9; color: var(--ep-text); border-color: var(--ep-border); }

    .ep-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding: 12px 20px; border-top: 1px solid var(--ep-border); background: #fafbfd; color: var(--ep-faint); font-size: .78rem; }
    .ep-empty { padding: 56px 20px !important; text-align: center; color: var(--ep-faint) !important; }
    .ep-empty i { display: block; margin-bottom: 10px; font-size: 1.6rem; color: #c4b5fd; }
    #ep-runner-empty { display: none; }

    /* ---------- Status badges ---------- */
    .ep-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .03em; white-space: nowrap; }
    .ep-badge i { width: 6px; height: 6px; border-radius: 50%; display: block; }
    .ep-badge.live { background: #fff1f2; color: #e11d48; }
    .ep-badge.live i { background: #f43f5e; }
    .ep-badge.upcoming { background: #ecfdf5; color: #059669; }
    .ep-badge.upcoming i { background: #10b981; }
    .ep-badge.past, .ep-badge.unknown { background: #f1f5f9; color: #64748b; }
    .ep-badge.past i, .ep-badge.unknown i { background: #94a3b8; }

    /* ---------- Modal ---------- */
    .ep-modal {
        position: fixed; inset: 0; z-index: 1000; display: none; align-items: center; justify-content: center;
        padding: 20px; background: rgba(15,23,42,.55); backdrop-filter: blur(3px);
    }
    .ep-modal.open { display: flex; }
    .ep-modal-box {
        display: flex; flex-direction: column; width: 100%; max-width: 720px; max-height: calc(100vh - 40px);
        background: #fff; border-radius: 20px; box-shadow: 0 30px 80px rgba(15,23,42,.3); overflow: hidden;
        animation: ep-pop .18s ease-out;
    }
    @keyframes ep-pop { from { opacity: 0; transform: translateY(10px) scale(.985); } to { opacity: 1; transform: none; } }
    .ep-modal-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; border-bottom: 1px solid var(--ep-border); }
    .ep-modal-head h3 { margin: 0; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .ep-modal-head h3 i { color: var(--ep-pink); }
    .ep-modal-sub { margin: 3px 0 0; color: var(--ep-faint); font-size: .78rem; font-weight: 500; }
    .ep-x { width: 34px; height: 34px; border: 0; border-radius: 9px; background: transparent; color: var(--ep-faint); font-size: 1.05rem; cursor: pointer; flex-shrink: 0; }
    .ep-x:hover { background: #fff1f2; color: #e11d48; }
    .ep-modal-tabs { display: flex; gap: 6px; padding: 12px 22px; border-bottom: 1px solid var(--ep-border); background: #fafbfd; flex-wrap: wrap; }
    .ep-tab {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 13px; border: 1px solid var(--ep-border); border-radius: 999px;
        background: #fff; color: var(--ep-muted); font-size: .78rem; font-weight: 700; cursor: pointer; transition: all .15s;
    }
    .ep-tab:hover { border-color: #c4b5fd; color: var(--ep-purple); }
    .ep-tab.active { background: var(--ep-purple); border-color: var(--ep-purple); color: #fff; }
    .ep-tab .n { padding: 1px 6px; border-radius: 999px; background: rgba(100,116,139,.14); font-size: .68rem; }
    .ep-tab.active .n { background: rgba(255,255,255,.25); }
    .ep-modal-body { flex: 1; overflow-y: auto; }
    .ep-modal-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 22px; border-top: 1px solid var(--ep-border); background: #fafbfd; }
    .ep-total-label { color: var(--ep-muted); font-size: .8rem; font-weight: 600; }
    .ep-total-value { font-size: 1.05rem; font-weight: 800; color: var(--ep-text); }

    /* revenue bar */
    .ep-share { display: flex; align-items: center; gap: 10px; min-width: 130px; }
    .ep-bar { flex: 1; height: 6px; border-radius: 999px; background: #eef0f6; overflow: hidden; }
    .ep-bar > div { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--ep-purple), var(--ep-pink)); }
    .ep-share span { font-size: .72rem; color: var(--ep-faint); font-weight: 700; width: 34px; text-align: right; }
    .ep-money { color: #059669; font-weight: 800; white-space: nowrap; }
    .ep-strong { color: var(--ep-text); font-weight: 700; }

    /* ticket groups */
    .ep-group { padding: 14px 22px; border-bottom: 1px solid #f0f1f5; }
    .ep-group:last-of-type { border-bottom: 0; }
    .ep-group-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
    .ep-group-title { font-weight: 800; color: var(--ep-text); font-size: .9rem; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .ep-cat-line { display: flex; justify-content: space-between; align-items: center; padding: 6px 0 6px 12px; border-left: 2px solid #e9d5ff; margin-left: 2px; color: var(--ep-muted); font-size: .83rem; }
    .ep-cat-line + .ep-cat-line { margin-top: 2px; }
    .ep-cat-line strong { color: var(--ep-text); font-weight: 800; }
    .ep-tab-empty { display: none; padding: 44px 20px; text-align: center; color: var(--ep-faint); font-size: .85rem; }

    /* ---------- Responsive ---------- */
    @media (max-width: 1000px) { .ep-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 720px) {
        .ep-table.stack, .ep-table.stack tbody, .ep-table.stack tr, .ep-table.stack td { display: block; width: 100%; }
        .ep-table.stack thead { display: none; }
        .ep-table.stack tr.ep-row { padding: 14px 16px; border-bottom: 1px solid var(--ep-border); }
        .ep-table.stack td { padding: 6px 0; border: 0; }
        .ep-table.stack td.r, .ep-table.stack td.c { text-align: left; }
        .ep-search { max-width: none; }
    }
    @media (max-width: 480px) {
        .ep-stats { grid-template-columns: 1fr; }
        .ep-modal { padding: 10px; }
        .ep-modal-box { max-height: calc(100vh - 20px); }
    }
</style>

@php
    // ---- Runner data ----
    $runnerItems = collect($loyaltyRunners->items());
    $runnerData = $runnerItems->map(function ($runner, $i) use ($loyaltyRunners) {
        $globalIndex = ($loyaltyRunners->currentPage() - 1) * $loyaltyRunners->perPage() + $i;
        return [
            'name'   => $runner->full_name ?? 'Runner',
            'code'   => $runner->user_code ?? ('EP-' . str_pad($globalIndex + 1, 4, '0', STR_PAD_LEFT)),
            'events' => collect($runner->purchased_events)->map(fn ($e) => [
                'title'    => $e['event_title'] ?? '',
                'category' => $e['categories'] ?? '',
                'status'   => strtolower($e['event_status'] ?? 'past'),
                'tickets'  => $e['ticket_count'] ?? 0,
                'spent'    => $e['total_spent'] ?? 0,
            ])->values(),
        ];
    })->values();

    // ---- Revenue data ----
    $revRows  = collect($eventRevenueBreakdown)->sortByDesc('real_revenue')->values();
    $revTotal = $revRows->sum('real_revenue');
    $revCounts = [
        'all'  => $revRows->count(),
        'live' => $revRows->filter(fn ($r) => strtolower($r->status) === 'live')->count(),
        'past' => $revRows->filter(fn ($r) => strtolower($r->status) === 'past')->count(),
    ];

    // ---- Ticket data (grouped per event, per category) ----
    $ticketGroups = collect($categoryTicketBreakdown)
        ->groupBy(fn ($c) => optional($c->event)->id ?? 'na')
        ->map(fn ($cats) => [
            'title'  => optional($cats->first()->event)->title ?? 'N/A',
            'status' => strtolower(optional($cats->first()->event)->status ?? 'unknown'),
            'total'  => $cats->sum('paid_tickets_count'),
            'cats'   => $cats,
        ])
        ->sortByDesc('total')
        ->values();
    $ticketCounts = [
        'all'  => $ticketGroups->count(),
        'live' => $ticketGroups->where('status', 'live')->count(),
        'past' => $ticketGroups->where('status', 'past')->count(),
    ];

    $avgPerTicket = $totalTicketsSold > 0 ? round($totalRevenue / $totalTicketsSold) : 0;
@endphp

<div class="ep-dash">

    {{-- ===== Header ===== --}}
    <div class="ep-head">
        <div>
            <h2>Dashboard</h2>
            <p>Registrations, ticket sales and revenue at a glance.</p>
        </div>
        <span class="ep-date-pill"><i class="fa-regular fa-calendar"></i> {{ now()->format('l, M d, Y') }}</span>
    </div>

    {{-- ===== Stats ===== --}}
    <div class="ep-stats">
        <button type="button" class="ep-stat" onclick="openModal('revenueModal')">
            <div class="ep-stat-top">
                <div class="ep-stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
                <span class="ep-stat-link">Breakdown <i class="fa-solid fa-arrow-right"></i></span>
            </div>
            <p class="ep-stat-label">Total revenue</p>
            <h3 class="ep-stat-value">{{ number_format($totalRevenue) }} <small>MMK</small></h3>
        </button>

        <button type="button" class="ep-stat" onclick="openModal('ticketsModal')">
            <div class="ep-stat-top">
                <div class="ep-stat-icon indigo"><i class="fa-solid fa-ticket"></i></div>
                <span class="ep-stat-link">Breakdown <i class="fa-solid fa-arrow-right"></i></span>
            </div>
            <p class="ep-stat-label">Tickets sold</p>
            <h3 class="ep-stat-value">{{ number_format($totalTicketsSold) }}</h3>
        </button>

        <div class="ep-stat">
            <div class="ep-stat-top">
                <div class="ep-stat-icon amber"><i class="fa-solid fa-calendar-days"></i></div>
                <span class="ep-stat-link"><i class="fa-solid fa-circle-check"></i> Running</span>
            </div>
            <p class="ep-stat-label">Active events</p>
            <h3 class="ep-stat-value">{{ number_format($activeEventsCount) }}</h3>
        </div>

        <div class="ep-stat">
            <div class="ep-stat-top">
                <div class="ep-stat-icon pink"><i class="fa-solid fa-receipt"></i></div>
            </div>
            <p class="ep-stat-label">Avg. revenue per ticket</p>
            <h3 class="ep-stat-value">{{ number_format($avgPerTicket) }} <small>MMK</small></h3>
        </div>
    </div>

    {{-- ===== Runner directory ===== --}}
    <div class="ep-panel">
        <div class="ep-toolbar">
            <div>
                <h3>Runner Purchases</h3>
                <p>Click a row to see the events a runner joined</p>
            </div>
            <div class="ep-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" id="ep-runner-search" placeholder="Search name, email, phone or code…" autocomplete="off">
            </div>
        </div>

        <div class="ep-scroll">
            <table class="ep-table stack">
                <thead>
                    <tr>
                        <th>Runner</th>
                        <th>Contact</th>
                        <th class="c">Tickets</th>
                        <th class="r">Events</th>
                    </tr>
                </thead>
                <tbody id="ep-runner-body">
                    @forelse($runnerItems as $i => $runner)
                        @php
                            $rd = $runnerData[$i];
                        @endphp
                        <tr class="ep-row" data-index="{{ $i }}"
                            data-search="{{ strtolower($rd['name'] . ' ' . $rd['code'] . ' ' . $runner->email . ' ' . $runner->phone) }}"
                            onclick="openRunner({{ $i }})">
                            <td>
                                <div class="ep-runner">
                                    <div class="ep-avatar">{{ strtoupper(mb_substr($rd['name'], 0, 1)) }}</div>
                                    <div>
                                        <div class="ep-runner-name">{{ $rd['name'] }}</div>
                                        <span class="ep-code">{{ $rd['code'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="ep-contact">
                                <a href="mailto:{{ $runner->email }}" onclick="event.stopPropagation()">{{ $runner->email }}</a>
                                <div>{{ $runner->phone }}</div>
                            </td>
                            <td class="c"><span class="ep-pill">{{ $runner->ticket_count }}</span></td>
                            <td class="r">
                                <button type="button" class="ep-btn" onclick="event.stopPropagation(); openRunner({{ $i }})">
                                    <i class="fa-solid fa-calendar-check"></i> View events ({{ count($rd['events']) }})
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="ep-empty">
                                <i class="fa-solid fa-users-slash"></i>
                                No paid orders registered yet.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="ep-runner-empty">
                        <td colspan="4" class="ep-empty">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            No runners on this page match your search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="ep-footer">
            <span>
                @if($loyaltyRunners->total() > 0)
                    Showing {{ $loyaltyRunners->firstItem() }}–{{ $loyaltyRunners->lastItem() }} of {{ $loyaltyRunners->total() }} runners
                @else
                    No runners yet
                @endif
            </span>
            @if($loyaltyRunners->hasPages())
                <div>{{ $loyaltyRunners->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- =========================================================
     RUNNER EVENTS MODAL (single, filled by JS)
========================================================== --}}
<div class="ep-modal" id="runnerModal" role="dialog" aria-modal="true" aria-labelledby="runnerModalTitle">
    <div class="ep-modal-box">
        <div class="ep-modal-head">
            <div>
                <h3 id="runnerModalTitle"><i class="fa-solid fa-user-tag"></i> <span id="rm-name"></span></h3>
                <p class="ep-modal-sub" id="rm-sub"></p>
            </div>
            <button type="button" class="ep-x" onclick="closeModal('runnerModal')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="ep-modal-body ep-scroll">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="c">Tickets</th>
                        <th class="r">Amount</th>
                    </tr>
                </thead>
                <tbody id="rm-body"></tbody>
            </table>
        </div>
        <div class="ep-modal-foot">
            <div><span class="ep-total-label">Total spent</span> <span class="ep-total-value" id="rm-total"></span></div>
            <button type="button" class="ep-btn ep-btn-plain" onclick="closeModal('runnerModal')">Close</button>
        </div>
    </div>
</div>

{{-- =========================================================
     REVENUE MODAL
========================================================== --}}
<div class="ep-modal" id="revenueModal" role="dialog" aria-modal="true" aria-labelledby="revenueTitle">
    <div class="ep-modal-box" data-tabs>
        <div class="ep-modal-head">
            <div>
                <h3 id="revenueTitle"><i class="fa-solid fa-chart-column"></i> Revenue by event</h3>
                <p class="ep-modal-sub">Sorted by highest revenue</p>
            </div>
            <button type="button" class="ep-x" onclick="closeModal('revenueModal')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="ep-modal-tabs">
            <button type="button" class="ep-tab active" data-filter="all">All <span class="n">{{ $revCounts['all'] }}</span></button>
            <button type="button" class="ep-tab" data-filter="live">Live <span class="n">{{ $revCounts['live'] }}</span></button>
            <button type="button" class="ep-tab" data-filter="past">Past <span class="n">{{ $revCounts['past'] }}</span></button>
        </div>

        <div class="ep-modal-body ep-scroll">
            <table class="ep-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Status</th>
                        <th>Share</th>
                        <th class="r">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revRows as $item)
                        @php
                            $st    = strtolower($item->status);
                            $share = $revTotal > 0 ? round($item->real_revenue / $revTotal * 100) : 0;
                        @endphp
                        <tr data-status="{{ $st }}" data-value="{{ $item->real_revenue }}" class="ep-filter-row">
                            <td class="ep-strong">{{ $item->title }}</td>
                            <td><span class="ep-badge {{ $st }}"><i></i>{{ $st === 'live' ? 'Live' : ucfirst($st) }}</span></td>
                            <td>
                                <div class="ep-share">
                                    <div class="ep-bar"><div style="width: {{ $share }}%"></div></div>
                                    <span>{{ $share }}%</span>
                                </div>
                            </td>
                            <td class="r ep-money">{{ number_format($item->real_revenue) }} MMK</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="ep-tab-empty"><i class="fa-solid fa-calendar-xmark"></i> No revenue for this filter yet.</div>
        </div>

        <div class="ep-modal-foot">
            <div><span class="ep-total-label">Total</span> <span class="ep-total-value"><span class="ep-tab-total">0</span> MMK</span></div>
            <button type="button" class="ep-btn ep-btn-plain" onclick="closeModal('revenueModal')">Close</button>
        </div>
    </div>
</div>

{{-- =========================================================
     TICKETS MODAL
========================================================== --}}
<div class="ep-modal" id="ticketsModal" role="dialog" aria-modal="true" aria-labelledby="ticketsTitle">
    <div class="ep-modal-box" data-tabs>
        <div class="ep-modal-head">
            <div>
                <h3 id="ticketsTitle"><i class="fa-solid fa-ticket"></i> Tickets by event</h3>
                <p class="ep-modal-sub">Paid tickets per category</p>
            </div>
            <button type="button" class="ep-x" onclick="closeModal('ticketsModal')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="ep-modal-tabs">
            <button type="button" class="ep-tab active" data-filter="all">All <span class="n">{{ $ticketCounts['all'] }}</span></button>
            <button type="button" class="ep-tab" data-filter="live">Live <span class="n">{{ $ticketCounts['live'] }}</span></button>
            <button type="button" class="ep-tab" data-filter="past">Past <span class="n">{{ $ticketCounts['past'] }}</span></button>
        </div>

        <div class="ep-modal-body">
            @foreach($ticketGroups as $group)
                <div class="ep-group ep-filter-row" data-status="{{ $group['status'] }}" data-value="{{ $group['total'] }}">
                    <div class="ep-group-head">
                        <div class="ep-group-title">
                            {{ $group['title'] }}
                            <span class="ep-badge {{ $group['status'] }}"><i></i>{{ $group['status'] === 'live' ? 'Live' : ucfirst($group['status']) }}</span>
                        </div>
                        <span class="ep-pill">{{ number_format($group['total']) }}</span>
                    </div>
                    @foreach($group['cats'] as $cat)
                        <div class="ep-cat-line">
                            <span>{{ $cat->name }}</span>
                            <strong>{{ number_format($cat->paid_tickets_count) }}</strong>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <div class="ep-tab-empty"><i class="fa-solid fa-ticket"></i> No tickets sold for this filter yet.</div>
        </div>

        <div class="ep-modal-foot">
            <div><span class="ep-total-label">Total tickets</span> <span class="ep-total-value"><span class="ep-tab-total">0</span></span></div>
            <button type="button" class="ep-btn ep-btn-plain" onclick="closeModal('ticketsModal')">Close</button>
        </div>
    </div>
</div>

<script>
(function () {
    var RUNNERS = @json($runnerData);

    /* ---------- Modal open / close ---------- */
    window.openModal = function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.closeModal = function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('open');
        if (!document.querySelector('.ep-modal.open')) document.body.style.overflow = '';
    };
    document.addEventListener('click', function (e) {
        if (e.target.classList && e.target.classList.contains('ep-modal')) closeModal(e.target.id);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var open = document.querySelector('.ep-modal.open');
        if (open) closeModal(open.id);
    });

    /* ---------- Runner search (current page) ---------- */
    var rows  = Array.prototype.slice.call(document.querySelectorAll('#ep-runner-body tr.ep-row'));
    var empty = document.getElementById('ep-runner-empty');
    var input = document.getElementById('ep-runner-search');
    if (rows.length) {
        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase(), shown = 0;
            rows.forEach(function (r) {
                var ok = !q || r.dataset.search.indexOf(q) !== -1;
                r.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            empty.style.display = shown ? 'none' : 'table-row';
        });
    }

    /* ---------- Runner modal ---------- */
    function fmt(n) { return Number(n || 0).toLocaleString('en-US'); }
    function cell(text, cls) {
        var td = document.createElement('td');
        if (cls) td.className = cls;
        td.textContent = text;
        return td;
    }

    window.openRunner = function (i) {
        var r = RUNNERS[i];
        if (!r) return;
        document.getElementById('rm-name').textContent = r.name;
        document.getElementById('rm-sub').textContent  = r.code + ' · ' + r.events.length + ' event' + (r.events.length === 1 ? '' : 's');

        var body = document.getElementById('rm-body');
        body.innerHTML = '';
        var total = 0;

        if (!r.events.length) {
            var tr = document.createElement('tr');
            var td = cell('No active events found for this runner.', 'ep-empty');
            td.colSpan = 5;
            tr.appendChild(td);
            body.appendChild(tr);
        }

        r.events.forEach(function (e) {
            total += Number(e.spent) || 0;
            var tr = document.createElement('tr');
            tr.appendChild(cell(e.title, 'ep-strong'));
            tr.appendChild(cell(e.category));

            var st = document.createElement('td');
            var badge = document.createElement('span');
            var s = ['live', 'upcoming', 'past'].indexOf(e.status) > -1 ? e.status : 'unknown';
            badge.className = 'ep-badge ' + s;
            badge.appendChild(document.createElement('i'));
            badge.appendChild(document.createTextNode(s === 'unknown' ? e.status : s.charAt(0).toUpperCase() + s.slice(1)));
            st.appendChild(badge);
            tr.appendChild(st);

            var tk = document.createElement('td');
            tk.className = 'c';
            var pill = document.createElement('span');
            pill.className = 'ep-pill';
            pill.textContent = e.tickets;
            tk.appendChild(pill);
            tr.appendChild(tk);

            tr.appendChild(cell(fmt(e.spent) + ' MMK', 'r ep-money'));
            body.appendChild(tr);
        });

        document.getElementById('rm-total').textContent = fmt(total) + ' MMK';
        openModal('runnerModal');
    };

    /* ---------- Tabs inside Revenue / Tickets modals ---------- */
    document.querySelectorAll('[data-tabs]').forEach(function (box) {
        var tabs   = box.querySelectorAll('.ep-tab');
        var items  = box.querySelectorAll('.ep-filter-row');
        var total  = box.querySelector('.ep-tab-total');
        var nothing = box.querySelector('.ep-tab-empty');

        function apply(filter) {
            var sum = 0, shown = 0;
            items.forEach(function (it) {
                var ok = filter === 'all' || it.dataset.status === filter;
                it.style.display = ok ? '' : 'none';
                if (ok) { sum += Number(it.dataset.value) || 0; shown++; }
            });
            total.textContent = fmt(sum);
            nothing.style.display = shown ? 'none' : 'block';
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');
                apply(tab.dataset.filter);
            });
        });
        apply('all');
    });
})();
</script>

@endsection