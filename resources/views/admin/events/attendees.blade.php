@if(auth('admin')->user()->isEventAdmin())

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Attendees Directory</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="eventplus-standalone-body">

<div class="eventplus-standalone-container">

@else


@extends('layouts.admin')

@section('title', 'Event Attendees & Revenue')
@section('page-title', 'Attendees Directory - ' . $event->title)

@section('content')


@endif

@php
    $isEventAdmin = auth('admin')->user()->isEventAdmin();

    $categoryGroups = $attendees->groupBy(fn ($a) => $a->ticket_category_id ?? 0);

    // GROUP BUY: how many runners came through a group buy order.
    $groupBuyAttendeeCount = $attendees->filter(fn ($a) => $a->order && $a->order->isGroupBuy())->count();
    $singleAttendeeCount = $attendees->count() - $groupBuyAttendeeCount;

    $availableTshirtSizes = $event->tshirt_sizes ?? [];
    if (is_string($availableTshirtSizes)) {
        $availableTshirtSizes = json_decode($availableTshirtSizes, true) ?: [];
    }
    $availableTshirtSizes = is_array($availableTshirtSizes)
        ? array_values(array_unique($availableTshirtSizes))
        : [];
@endphp

<style>
/* =========================================================
   EVENT PLUS - ATTENDEES DIRECTORY
   Self-contained styles (no Tailwind needed)
   ========================================================= */

.eventplus-standalone-body {
    margin: 0;
    min-height: 100vh;
    padding: 28px;
    background: #f6f5fa;
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
}
.eventplus-standalone-container { max-width: 1500px; margin: 0 auto; }

.ep-att {
    --ep-accent: #6d28d9;
    --ep-accent-dark: #5b21b6;
    --ep-accent-soft: #f3effd;
    --ep-ink: #1b2133;
    --ep-muted: #667085;
    --ep-line: #e7e5ef;
    --ep-bg: #f6f5fa;
    --ep-green: #047857;
    --ep-green-soft: #e8f7f0;
    --ep-red: #b42318;
    --ep-red-soft: #fdeceb;
    --ep-amber: #9a5b00;
    --ep-amber-soft: #fff3dc;
    color: var(--ep-ink);
    font-size: 14px;
    line-height: 1.5;
}
.ep-att *, .ep-att *::before, .ep-att *::after { box-sizing: border-box; }
.ep-att .is-hidden { display: none !important; }
.ep-att .ep-inline { display: inline; margin: 0; }

/* ---------- Buttons ---------- */
.ep-att .ep-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 40px; padding: 8px 14px; border: 1px solid transparent; border-radius: 9px;
    font: inherit; font-size: 14px; font-weight: 650; line-height: 1.2;
    text-decoration: none; white-space: nowrap; cursor: pointer;
    transition: background-color .15s, border-color .15s, color .15s;
}
.ep-att .ep-btn-sm { min-height: 34px; padding: 6px 11px; font-size: 13px; }
.ep-att .ep-btn-primary { background: var(--ep-accent); color: #fff; }
.ep-att .ep-btn-primary:hover { background: var(--ep-accent-dark); color: #fff; }
.ep-att .ep-btn-green { background: #047857; color: #fff; }
.ep-att .ep-btn-green:hover { background: #065f46; color: #fff; }
.ep-att .ep-btn-ghost { background: #fff; border-color: var(--ep-line); color: #3d4457; }
.ep-att .ep-btn-ghost:hover { background: var(--ep-bg); color: #3d4457; }
.ep-att .ep-btn-soft { background: var(--ep-accent-soft); border-color: #e0d6fa; color: var(--ep-accent-dark); }
.ep-att .ep-btn-soft:hover { background: #ebe3fc; color: var(--ep-accent-dark); }
.ep-att .ep-btn-danger { background: #fff; border-color: #f1d4d1; color: var(--ep-red); }
.ep-att .ep-btn-danger:hover { background: var(--ep-red-soft); }
.ep-att .ep-btn:disabled { opacity: .45; cursor: not-allowed; }
.ep-att .ep-btn:focus-visible,
.ep-att .ep-filter:focus-visible,
.ep-att input:focus-visible,
.ep-att select:focus-visible {
    outline: 3px solid #bfa8f5; outline-offset: 2px;
}

/* ---------- Header ---------- */
.ep-att .ep-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 16px; margin-bottom: 22px;
}
.ep-att .ep-header h1 { margin: 0 0 4px; font-size: 26px; font-weight: 750; letter-spacing: -.4px; }
.ep-att .ep-header p { margin: 0; color: var(--ep-muted); }
.ep-att .ep-header p strong { color: var(--ep-ink); }
.ep-att .ep-header-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }

.ep-att .ep-alert {
    display: flex; align-items: center; gap: 10px; margin-bottom: 18px; padding: 12px 15px;
    border: 1px solid #bfe8d4; border-radius: 12px; background: var(--ep-green-soft);
    color: #0b5a40; font-weight: 600;
}

/* ---------- Stats ---------- */
.ep-att .ep-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.ep-att .ep-stat {
    display: flex; align-items: center; justify-content: space-between; gap: 14px;
    padding: 18px 20px; border: 1px solid var(--ep-line); border-radius: 14px; background: #fff;
}
.ep-att .ep-stat-label { margin: 0 0 4px; color: var(--ep-muted); font-weight: 600; }
.ep-att .ep-stat-value { margin: 0; font-size: 28px; line-height: 1.1; font-weight: 750; letter-spacing: -.5px; }
.ep-att .ep-stat-value.is-status { font-size: 22px; text-transform: capitalize; }
.ep-att .ep-stat-icon {
    display: grid; place-items: center; flex-shrink: 0; width: 46px; height: 46px;
    border-radius: 12px; font-size: 18px;
}
.ep-att .ep-stat-revenue .ep-stat-icon { background: var(--ep-green-soft); color: var(--ep-green); }
.ep-att .ep-stat-runners .ep-stat-icon { background: var(--ep-accent-soft); color: var(--ep-accent); }
.ep-att .ep-stat-status .ep-stat-icon { background: #e8f0fd; color: #1d4ed8; }

/* ---------- Card + toolbar ---------- */
.ep-att .ep-card { overflow: hidden; border: 1px solid var(--ep-line); border-radius: 14px; background: #fff; }
.ep-att .ep-toolbar { padding: 16px 18px; border-bottom: 1px solid var(--ep-line); }
.ep-att .ep-toolbar-top { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; }
.ep-att .ep-search { position: relative; flex: 1 1 280px; max-width: 460px; }
.ep-att .ep-search i { position: absolute; top: 50%; left: 13px; transform: translateY(-50%); color: #98a2b3; pointer-events: none; }
.ep-att .ep-search input {
    width: 100%; min-height: 42px; padding: 8px 38px 8px 38px; border: 1px solid var(--ep-line);
    border-radius: 10px; background: #fff; color: var(--ep-ink); font: inherit; font-size: 14px;
}
.ep-att .ep-search input:focus { border-color: #a58aee; }
.ep-att .ep-search-clear {
    position: absolute; top: 50%; right: 6px; transform: translateY(-50%);
    display: grid; place-items: center; width: 30px; height: 30px; border: 0; border-radius: 8px;
    background: transparent; color: #667085; cursor: pointer;
}
.ep-att .ep-search-clear:hover { background: var(--ep-bg); }
.ep-att .ep-pagesize { display: inline-flex; align-items: center; gap: 8px; margin-left: auto; color: var(--ep-muted); font-weight: 600; }
.ep-att .ep-pagesize select {
    min-height: 40px; padding: 6px 10px; border: 1px solid var(--ep-line); border-radius: 9px;
    background: #fff; color: var(--ep-ink); font: inherit; font-size: 14px;
}
.ep-att .ep-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
.ep-att .ep-filter {
    display: inline-flex; align-items: center; gap: 8px; min-height: 36px; padding: 5px 13px;
    border: 1px solid var(--ep-line); border-radius: 999px; background: #fff;
    color: #3d4457; font: inherit; font-size: 13.5px; font-weight: 650; cursor: pointer;
    transition: background-color .15s, border-color .15s;
}
.ep-att .ep-filter span {
    min-width: 24px; padding: 1px 7px; border-radius: 999px; background: var(--ep-bg);
    color: var(--ep-muted); font-size: 12.5px; text-align: center;
}
.ep-att .ep-filter:hover { border-color: #cdbcf6; }
.ep-att .ep-filter.is-active { background: var(--ep-accent); border-color: var(--ep-accent); color: #fff; }
.ep-att .ep-filter.is-active span { background: rgba(255, 255, 255, .22); color: #fff; }

/* ---------- Table ---------- */
.ep-att .ep-table-wrap { overflow-x: auto; }
.ep-att .att-table { width: 100%; border-collapse: collapse; }
.ep-att .att-table th {
    padding: 12px 16px; border-bottom: 1px solid var(--ep-line); background: #faf9fc;
    color: var(--ep-muted); font-size: 13px; font-weight: 650; text-align: left; white-space: nowrap;
}
.ep-att .att-table td { padding: 14px 16px; border-bottom: 1px solid #efedf5; vertical-align: top; }
.ep-att .att-table .att-main:hover td { background: #fcfbfe; }
.ep-att .att-table th.att-col-actions { text-align: right; }

.ep-att .att-runner { display: flex; align-items: flex-start; gap: 12px; min-width: 230px; }
.ep-att .att-avatar {
    display: grid; place-items: center; flex-shrink: 0; width: 40px; height: 40px;
    border-radius: 50%; background: var(--ep-accent-soft); color: var(--ep-accent-dark);
    font-size: 15px; font-weight: 750;
}
.ep-att .att-name { margin: 0 0 4px; font-size: 14.5px; font-weight: 700; overflow-wrap: anywhere; }
.ep-att .att-code {
    display: inline-block; padding: 2px 8px; border-radius: 6px; background: var(--ep-accent-soft);
    color: var(--ep-accent-dark); font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 12.5px; font-weight: 700;
}
.ep-att .att-bib { margin-top: 6px; color: var(--ep-muted); font-size: 13px; font-weight: 600; }

.ep-att .att-line { margin: 0 0 4px; color: #3d4457; overflow-wrap: anywhere; }
.ep-att .att-line:last-child { margin-bottom: 0; }
.ep-att .att-line i { width: 16px; margin-right: 6px; color: #98a2b3; text-align: center; }
.ep-att .att-muted { color: var(--ep-muted); }
.ep-att .att-strong { font-weight: 650; }
.ep-att .att-contact { min-width: 210px; }

.ep-att .ep-tag {
    display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 999px;
    background: var(--ep-bg); color: #3d4457; font-size: 12.5px; font-weight: 650;
}
.ep-att .ep-tag-green { background: var(--ep-green-soft); color: var(--ep-green); }
.ep-att .ep-tag-red { background: var(--ep-red-soft); color: var(--ep-red); }
.ep-att .ep-tag-blue { background: #e8f0fd; color: #1d4ed8; }
.ep-att .ep-tag-purple { background: var(--ep-accent-soft); color: var(--ep-accent-dark); }
.ep-att .ep-tag-amber { background: var(--ep-amber-soft); color: var(--ep-amber); }
.ep-att .att-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
.ep-att .att-tags:first-child { margin-top: 0; }

.ep-att .att-actions { display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 6px; }
.ep-att .att-toggle i { transition: transform .2s; }
.ep-att .att-group.is-open .att-toggle i { transform: rotate(180deg); }
.ep-att .att-group.is-open .att-main td { background: #fcfbfe; border-bottom-color: transparent; }

/* expandable details */
.ep-att .att-table .att-detail { display: none; }
.ep-att .att-table .att-group.is-open .att-detail { display: table-row; }
.ep-att .att-detail td { padding: 4px 20px 20px 68px; background: #fcfbfe; }
.ep-att .att-detail-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 14px 28px;
    padding: 16px 18px; border: 1px solid var(--ep-line); border-radius: 12px; background: #fff;
}
.ep-att .att-detail-item dt { margin: 0 0 2px; color: var(--ep-muted); font-size: 12.5px; font-weight: 600; }
.ep-att .att-detail-item dd { margin: 0; overflow-wrap: anywhere; }
.ep-att .att-detail-item.is-warning dd { color: var(--ep-red); font-weight: 650; }

/* ---------- Pagination ---------- */
.ep-att .ep-pagination {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;
    gap: 12px 18px; padding: 14px 18px; border-top: 1px solid var(--ep-line); background: #faf9fc;
}
.ep-att .ep-page-info { margin: 0; color: var(--ep-muted); font-weight: 600; }
.ep-att .ep-page-info strong { color: var(--ep-ink); }
.ep-att .ep-page-nav { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
.ep-att .ep-page-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    min-width: 40px; min-height: 40px; padding: 6px 12px; border: 1px solid var(--ep-line);
    border-radius: 9px; background: #fff; color: #3d4457; font: inherit; font-size: 14px;
    font-weight: 650; cursor: pointer; transition: background-color .15s, border-color .15s;
}
.ep-att .ep-page-btn:hover:not(:disabled):not(.is-current) { background: var(--ep-accent-soft); border-color: #cdbcf6; }
.ep-att .ep-page-btn.is-current { background: var(--ep-accent); border-color: var(--ep-accent); color: #fff; cursor: default; }
.ep-att .ep-page-btn:disabled { opacity: .45; cursor: not-allowed; }
.ep-att .ep-page-btn:focus-visible { outline: 3px solid #bfa8f5; outline-offset: 2px; }
.ep-att .ep-page-gap { padding: 0 4px; color: var(--ep-muted); }

/* ---------- Empty states ---------- */
.ep-att .ep-empty { padding: 56px 20px; text-align: center; }
.ep-att .ep-empty i {
    display: inline-grid; place-items: center; width: 60px; height: 60px; margin-bottom: 14px;
    border-radius: 16px; background: var(--ep-accent-soft); color: var(--ep-accent); font-size: 24px;
}
.ep-att .ep-empty h3 { margin: 0 0 4px; font-size: 17px; font-weight: 700; }
.ep-att .ep-empty p { margin: 0 0 14px; color: var(--ep-muted); }

/* ---------- Edit modal ---------- */
.ep-att .ep-modal {
    position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center;
    padding: 16px; background: rgba(20, 16, 40, .62);
}
.ep-att .ep-modal.is-open { display: flex; }
.ep-att .ep-modal-card {
    display: flex; flex-direction: column; width: 100%; max-width: 900px; max-height: 92vh;
    overflow: hidden; border-radius: 16px; background: #fff; box-shadow: 0 30px 80px rgba(20, 16, 40, .35);
}
.ep-att .ep-modal-head {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 16px 22px; border-bottom: 1px solid var(--ep-line);
}
.ep-att .ep-modal-head h3 { margin: 0; font-size: 18px; font-weight: 700; }
.ep-att .ep-modal-head p { margin: 2px 0 0; color: var(--ep-muted); font-size: 13px; }
.ep-att .ep-modal-close {
    display: grid; place-items: center; flex-shrink: 0; width: 38px; height: 38px;
    border: 0; border-radius: 9px; background: var(--ep-bg); color: #3d4457; cursor: pointer;
}
.ep-att .ep-modal-close:hover { background: #ebe8f3; }
.ep-att .ep-modal-form { display: flex; flex-direction: column; min-height: 0; margin: 0; }
.ep-att .ep-modal-body { overflow-y: auto; padding: 6px 22px 10px; }
.ep-att .ep-form-section { padding: 16px 0; border-bottom: 1px solid #efedf5; }
.ep-att .ep-form-section:last-child { border-bottom: 0; }
.ep-att .ep-form-section h4 { margin: 0 0 12px; font-size: 14.5px; font-weight: 700; }
.ep-att .ep-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
.ep-att .ep-field { display: block; margin: 0; }
.ep-att .ep-field > span { display: block; margin-bottom: 5px; color: #4d5568; font-size: 13px; font-weight: 600; }
.ep-att .ep-field input,
.ep-att .ep-field select,
.ep-att .ep-field textarea {
    display: block; width: 100%; min-height: 42px; padding: 8px 11px; border: 1px solid #d9d6e4;
    border-radius: 9px; background: #fff; color: var(--ep-ink); font: inherit; font-size: 14px;
}
.ep-att .ep-field textarea { resize: vertical; }
.ep-att .ep-field input:focus,
.ep-att .ep-field select:focus,
.ep-att .ep-field textarea:focus { border-color: #a58aee; box-shadow: 0 0 0 3px rgba(109, 40, 217, .14); outline: 0; }
.ep-att .ep-field small { display: block; margin-top: 5px; color: var(--ep-muted); font-size: 12.5px; }
.ep-att .ep-field .ep-static { padding: 9px 11px; border: 1px dashed #d9d6e4; border-radius: 9px; background: var(--ep-bg); color: var(--ep-muted); }
.ep-att .ep-modal-foot {
    display: flex; justify-content: flex-end; gap: 10px; padding: 14px 22px;
    border-top: 1px solid var(--ep-line); background: #faf9fc;
}
body.ep-lock { overflow: hidden; }

/* ---------- Tablet / mobile ---------- */
@media (max-width: 900px) {
    .ep-att .ep-stats { grid-template-columns: 1fr; }
    .ep-att .ep-pagesize { margin-left: 0; }
}

@media (max-width: 860px) {
    .ep-att .att-table thead { display: none; }
    .ep-att .att-table,
    .ep-att .att-table tbody,
    .ep-att .att-table tr,
    .ep-att .att-table td { display: block; width: 100%; }
    .ep-att .att-group { padding: 8px 0; border-bottom: 8px solid var(--ep-bg); }
    .ep-att .att-table .att-main td { padding: 8px 16px; border: 0; }
    .ep-att .att-table .att-main td[data-label]::before {
        content: attr(data-label); display: block; margin-bottom: 3px;
        color: var(--ep-muted); font-size: 12.5px; font-weight: 600;
    }
    .ep-att .att-table .att-main:hover td { background: transparent; }
    .ep-att .att-group.is-open .att-main td { background: transparent; }
    .ep-att .att-actions { justify-content: flex-start; }
    .ep-att .att-table .att-group.is-open .att-detail { display: block; }
    .ep-att .att-detail td { padding: 4px 16px 14px; background: transparent; }
    .ep-att .att-runner, .ep-att .att-contact { min-width: 0; }
}

@media (max-width: 640px) {
    .eventplus-standalone-body { padding: 14px; }
    .ep-att .ep-header h1 { font-size: 22px; }
    .ep-att .ep-header-actions { width: 100%; }
    .ep-att .ep-header-actions .ep-btn,
    .ep-att .ep-header-actions form { flex: 1 1 100%; width: 100%; }
    .ep-att .ep-search { max-width: none; flex-basis: 100%; }
    .ep-att .ep-pagination { justify-content: center; }
    .ep-att .ep-page-nav { justify-content: center; }
    .ep-att .ep-modal { align-items: flex-end; padding: 0; }
    .ep-att .ep-modal-card { max-height: 94vh; border-radius: 16px 16px 0 0; }
    .ep-att .ep-modal-foot .ep-btn { flex: 1; }
}

@media (prefers-reduced-motion: reduce) {
    .ep-att * { transition: none !important; }
}
</style>

<div class="attendees-page ep-att">


{{-- =====================================================
     HEADER
     ===================================================== --}}

<header class="ep-header">

    <div>
        <h1>Attendees</h1>
        <p>Registered runners, revenue and details for <strong>{{ $event->title }}</strong></p>
    </div>

    <div class="ep-header-actions">

        <a href="{{ route('admin.events.attendees.export', $event->slug) }}"
           class="ep-btn ep-btn-green">
            <i class="fa-solid fa-file-excel" aria-hidden="true"></i>
            Download Excel report
        </a>

        @if(!$isEventAdmin)

            <a href="{{ route('admin.events.index') }}" class="ep-btn ep-btn-ghost">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back to events
            </a>

        @else

            <form action="{{ route('admin.logout') }}" method="POST" class="ep-inline">
                @csrf
                <button type="submit" class="ep-btn ep-btn-danger">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                    Logout
                </button>
            </form>

        @endif

    </div>

</header>


@if(session('success'))
    <div class="ep-alert" role="status">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif


{{-- =====================================================
     STATISTICS
     ===================================================== --}}

<section class="ep-stats" aria-label="Summary">

    <div class="ep-stat ep-stat-revenue">
        <div>
            <p class="ep-stat-label">Total revenue</p>
            <p class="ep-stat-value">{{ number_format($totalRevenue) }} MMK</p>
        </div>
        <div class="ep-stat-icon"><i class="fa-solid fa-sack-dollar" aria-hidden="true"></i></div>
    </div>

    <div class="ep-stat ep-stat-runners">
        <div>
            <p class="ep-stat-label">Confirmed runners</p>
            <p class="ep-stat-value">{{ number_format($attendees->count()) }}</p>
        </div>
        <div class="ep-stat-icon"><i class="fa-solid fa-person-running" aria-hidden="true"></i></div>
    </div>

    <div class="ep-stat ep-stat-status">
        <div>
            <p class="ep-stat-label">Event status</p>
            <p class="ep-stat-value is-status">{{ $event->status }}</p>
        </div>
        <div class="ep-stat-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div>
    </div>

</section>


{{-- =====================================================
     RUNNERS
     ===================================================== --}}

@if($attendees->isEmpty())

    <div class="ep-card">
        <div class="ep-empty">
            <i class="fa-solid fa-users-slash" aria-hidden="true"></i>
            <h3>No runners registered</h3>
            <p>No runners have registered for this event yet.</p>
        </div>
    </div>

@else

<section class="ep-card" id="runnersCard" aria-label="Registered runners">

    {{-- Search, filters, page size --}}
    <div class="ep-toolbar">

        <div class="ep-toolbar-top">

            <div class="ep-search">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search"
                       id="runnerSearch"
                       placeholder="Search name, ticket code, email, phone, NRC or BIB"
                       aria-label="Search runners"
                       autocomplete="off">
                <button type="button" class="ep-search-clear is-hidden" id="runnerSearchClear" aria-label="Clear search">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>

            <label class="ep-pagesize">
                Rows per page
                <select id="pageSize">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </label>

        </div>

        @if($categoryGroups->count() > 1)
            <div class="ep-filters" role="group" aria-label="Filter by ticket category">

                <button type="button" class="ep-filter is-active" data-category="all">
                    All runners <span>{{ $attendees->count() }}</span>
                </button>

                @foreach($categoryGroups as $categoryId => $group)
                    <button type="button" class="ep-filter" data-category="{{ $categoryId }}">
                        {{ $group->first()->ticketCategory->name ?? 'Uncategorized' }}
                        <span>{{ $group->count() }}</span>
                    </button>
                @endforeach

            </div>
        @endif

        {{-- GROUP BUY: filter by how the ticket was bought (only when this event has group buy orders) --}}
        @if($groupBuyAttendeeCount > 0)
            <div class="ep-filters" role="group" aria-label="Filter by purchase type">

                <button type="button" class="ep-filter is-active" data-buytype="all">
                    All purchases <span>{{ $attendees->count() }}</span>
                </button>

                <button type="button" class="ep-filter" data-buytype="group">
                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                    Group buy <span>{{ $groupBuyAttendeeCount }}</span>
                </button>

                <button type="button" class="ep-filter" data-buytype="single">
                    Individual <span>{{ $singleAttendeeCount }}</span>
                </button>

            </div>
        @endif

    </div>


    <div class="ep-table-wrap">

        <table class="att-table">

            <thead>
                <tr>
                    <th>Runner</th>
                    <th>Category and promo</th>
                    <th>Contact</th>
                    <th>ID and nationality</th>
                    <th>Profile</th>
                    <th class="att-col-actions">Actions</th>
                </tr>
            </thead>

            @foreach($attendees as $attendee)

                @php
                    $categoryName = $attendee->ticketCategory->name ?? 'Uncategorized';
                    $isForeigner = strtolower((string) $attendee->nationality) === 'foreigner';
                    $hasCondition = strtolower((string) $attendee->has_medical_condition) === 'yes';
                    $isItra = strtolower((string) $attendee->itra) === 'yes';
                    $dob = $attendee->date_of_birth
                        ? \Carbon\Carbon::parse($attendee->date_of_birth)->format('Y-m-d')
                        : null;

                    // GROUP BUY: did this runner buy through a group buy order?
                    $attendeeOrder = $attendee->order;
                    $isGroupBuy = $attendeeOrder && $attendeeOrder->isGroupBuy();
                    $groupBuyLabel = $isGroupBuy
                        ? ($attendeeOrder->group_buy_name ?: $attendeeOrder->group_buy_quantity . '-ticket group')
                        : null;

                    $searchText = mb_strtolower(implode(' ', array_filter([
                        $attendee->full_name,
                        $attendee->father_name,
                        $attendee->ticket_code,
                        $attendee->email,
                        $attendee->phone,
                        $attendee->viber,
                        $attendee->nrc_passport,
                        $attendee->bib_number,
                        $attendee->bib_name,
                        $attendee->country,
                        $categoryName,
                        $attendeeOrder?->order_number,
                        $isGroupBuy ? 'group buy ' . $groupBuyLabel : null,
                    ])));
                @endphp

                <tbody class="att-group"
                       data-category="{{ $attendee->ticket_category_id ?? 0 }}"
                       data-buytype="{{ $isGroupBuy ? 'group' : 'single' }}"
                       data-search="{{ $searchText }}">

                    {{-- ===== Main row ===== --}}
                    <tr class="att-main">

                        {{-- Runner --}}
                        <td data-label="Runner">
                            <div class="att-runner">
                                <div class="att-avatar" aria-hidden="true">
                                    {{ strtoupper(mb_substr($attendee->full_name ?? 'R', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="att-name">{{ $attendee->full_name }}</p>
                                    <span class="att-code">{{ $attendee->ticket_code }}</span>

                                    @if(!empty($attendee->bib_number))
                                        <div class="att-bib">BIB {{ $attendee->bib_number }}</div>
                                    @elseif(!empty($attendee->bib_name))
                                        <div class="att-bib">BIB name: {{ $attendee->bib_name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Category and promo --}}
                        <td data-label="Category and promo">
                            <p class="att-line att-strong">
                                <i class="fa-solid fa-ticket" aria-hidden="true"></i>{{ $categoryName }}
                            </p>

                            {{-- GROUP BUY badge --}}
                            @if($isGroupBuy)
                                <p class="att-line">
                                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                                    <span class="ep-tag ep-tag-amber">Group buy</span>
                                </p>
                                <p class="att-line att-muted">{{ $groupBuyLabel }}</p>
                            @endif

                            @if($attendee->promoCode)
                                <p class="att-line">
                                    <i class="fa-solid fa-tags" aria-hidden="true"></i>
                                    <span class="ep-tag ep-tag-purple">{{ $attendee->promoCode->code }}</span>
                                </p>
                                <p class="att-line att-muted">
                                    Discount:
                                    {{ $attendee->promoCode->discount_type === 'percentage'
                                        ? $attendee->promoCode->discount_value . '%'
                                        : number_format($attendee->promoCode->discount_value) . ' MMK'
                                    }}
                                </p>
                            @else
                                <p class="att-line att-muted">No promo code</p>
                            @endif
                        </td>

                        {{-- Contact --}}
                        <td data-label="Contact">
                            <div class="att-contact">
                                <p class="att-line"><i class="fa-solid fa-envelope" aria-hidden="true"></i>{{ $attendee->email }}</p>
                                <p class="att-line"><i class="fa-solid fa-phone" aria-hidden="true"></i>{{ $attendee->phone }}</p>
                                @if($attendee->viber)
                                    <p class="att-line"><i class="fa-brands fa-viber" aria-hidden="true"></i>{{ $attendee->viber }}</p>
                                @endif
                            </div>
                        </td>

                        {{-- ID and nationality --}}
                        <td data-label="ID and nationality">
                            <p class="att-line att-strong">{{ $attendee->nrc_passport }}</p>

                            <div class="att-tags">
                                <span class="ep-tag {{ $isForeigner ? 'ep-tag-blue' : 'ep-tag-green' }}">
                                    <i class="fa-solid {{ $isForeigner ? 'fa-earth-americas' : 'fa-flag' }}" aria-hidden="true"></i>
                                    {{ $attendee->nationality }}
                                </span>
                            </div>

                            @if($attendee->country)
                                <p class="att-line att-muted" style="margin-top:6px;">
                                    <i class="fa-solid fa-globe" aria-hidden="true"></i>{{ $attendee->country }}
                                </p>
                            @endif
                        </td>

                        {{-- Profile --}}
                        <td data-label="Profile">
                            <div class="att-tags">
                                @if($attendee->tshirt_size)
                                    <span class="ep-tag"><i class="fa-solid fa-shirt" aria-hidden="true"></i>{{ $attendee->tshirt_size }}</span>
                                @endif
                                @if($attendee->gender)
                                    <span class="ep-tag" style="text-transform:capitalize;">{{ str_replace('_', ' ', $attendee->gender) }}</span>
                                @endif
                                @if($attendee->blood_type)
                                    <span class="ep-tag ep-tag-red"><i class="fa-solid fa-droplet" aria-hidden="true"></i>{{ $attendee->blood_type }}</span>
                                @endif
                            </div>

                            <div class="att-tags">
                                @if($hasCondition)
                                    <span class="ep-tag ep-tag-red">
                                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>Medical condition
                                    </span>
                                @else
                                    <span class="ep-tag ep-tag-green">
                                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>Health clear
                                    </span>
                                @endif

                                @if($isItra)
                                    <span class="ep-tag ep-tag-purple">
                                        <i class="fa-solid fa-award" aria-hidden="true"></i>ITRA
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td data-label="Actions">
                            <div class="att-actions">

                                <button type="button"
                                        class="ep-btn ep-btn-ghost ep-btn-sm att-toggle"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                    Details
                                </button>

                                @if(!$isEventAdmin)

                                    <button type="button"
                                            class="ep-btn ep-btn-soft ep-btn-sm"
                                            data-open-modal="{{ $attendee->id }}">
                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        Edit
                                    </button>

                                    <a href="{{ route('admin.attendees.download_ticket', $attendee->id) }}"
                                       class="ep-btn ep-btn-ghost ep-btn-sm"
                                       title="Download ticket PDF">
                                        <i class="fa-solid fa-download" aria-hidden="true"></i>
                                        Ticket
                                    </a>

                                    <form action="{{ route('admin.attendees.destroy', $attendee->id) }}"
                                          method="POST"
                                          class="ep-inline"
                                          data-group-buy="{{ $isGroupBuy ? $attendeeOrder->group_buy_quantity : '' }}"
                                          onsubmit="return confirmAttendeeDeletion(event, this);">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="ep-btn ep-btn-danger ep-btn-sm">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>
                        </td>

                    </tr>

                    {{-- ===== Expandable details ===== --}}
                    <tr class="att-detail">
                        <td colspan="6">
                            <dl class="att-detail-grid">

                                {{-- GROUP BUY details --}}
                                @if($isGroupBuy)
                                    <div class="att-detail-item">
                                        <dt>Group buy</dt>
                                        <dd>{{ $groupBuyLabel }} ({{ $attendeeOrder->group_buy_quantity }} tickets)</dd>
                                    </div>

                                    <div class="att-detail-item">
                                        <dt>Group discount on the order</dt>
                                        <dd>{{ number_format($attendeeOrder->group_buy_discount, 2) }} MMK</dd>
                                    </div>
                                @endif

                                @if($attendeeOrder)
                                    <div class="att-detail-item">
                                        <dt>Order number</dt>
                                        <dd>{{ $attendeeOrder->order_number }}</dd>
                                    </div>
                                @endif

                                @if($attendee->father_name)
                                    <div class="att-detail-item">
                                        <dt>Father's name</dt>
                                        <dd>{{ $attendee->father_name }}</dd>
                                    </div>
                                @endif

                                @if($dob)
                                    <div class="att-detail-item">
                                        <dt>Date of birth</dt>
                                        <dd>{{ $dob }}</dd>
                                    </div>
                                @endif

                                <div class="att-detail-item">
                                    <dt>Emergency contact</dt>
                                    <dd>{{ $attendee->emergency_contact ?? 'N/A' }}</dd>
                                </div>

                                <div class="att-detail-item">
                                    <dt>Address</dt>
                                    <dd>{{ $attendee->address ?? 'N/A' }}</dd>
                                </div>

                                @if($attendee->experience)
                                    <div class="att-detail-item">
                                        <dt>Running experience</dt>
                                        <dd>{{ $attendee->experience }}</dd>
                                    </div>
                                @endif

                                @if($hasCondition)
                                    <div class="att-detail-item is-warning">
                                        <dt>Medical details</dt>
                                        <dd>{{ $attendee->medical_details ?: 'No details given' }}</dd>
                                    </div>
                                @endif

                                @if($isItra)
                                    <div class="att-detail-item">
                                        <dt>ITRA details</dt>
                                        <dd>{{ $attendee->itra_details ?: 'No details given' }}</dd>
                                    </div>
                                @endif

                                @if($attendee->promoCode && $attendee->promoCode->company_name)
                                    <div class="att-detail-item">
                                        <dt>Promo company</dt>
                                        <dd>{{ $attendee->promoCode->company_name }}</dd>
                                    </div>
                                @endif

                            </dl>
                        </td>
                    </tr>

                </tbody>

            @endforeach

        </table>

    </div>


    {{-- No matches --}}
    <div class="ep-empty is-hidden" id="noResults">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <h3>No runners match your search</h3>
        <p>Try a different name, code or category.</p>
        <button type="button" class="ep-btn ep-btn-soft" id="clearFilters">Clear search and filters</button>
    </div>


    {{-- Pagination --}}
    <nav class="ep-pagination" aria-label="Runner pages">
        <p class="ep-page-info" id="pageInfo" aria-live="polite"></p>
        <div class="ep-page-nav" id="pageNav"></div>
    </nav>

</section>

@endif


{{-- =====================================================
     EDIT RUNNER MODALS - SUPER ADMIN ONLY
     ===================================================== --}}

@if(!$isEventAdmin)

    @foreach($attendees as $attendee)

        @php
            $modalDob = $attendee->date_of_birth
                ? \Carbon\Carbon::parse($attendee->date_of_birth)->format('Y-m-d')
                : '';
        @endphp

        <div class="ep-modal"
             id="edit-modal-{{ $attendee->id }}"
             role="dialog"
             aria-modal="true"
             aria-labelledby="edit-title-{{ $attendee->id }}">

            <div class="ep-modal-card">

                <div class="ep-modal-head">
                    <div>
                        <h3 id="edit-title-{{ $attendee->id }}">Edit {{ $attendee->full_name }}</h3>
                        <p>Ticket {{ $attendee->ticket_code }}</p>
                    </div>
                    <button type="button" class="ep-modal-close" data-close-modal aria-label="Close">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>

                <form action="{{ route('admin.attendees.update', $attendee->id) }}"
                      method="POST"
                      class="ep-modal-form">

                    @csrf
                    @method('PUT')

                    <div class="ep-modal-body">

                        {{-- Runner --}}
                        <div class="ep-form-section">
                            <h4>Runner</h4>
                            <div class="ep-grid">

                                <label class="ep-field">
                                    <span>Full name</span>
                                    <input type="text" name="full_name" value="{{ $attendee->full_name }}" required>
                                </label>

                                <label class="ep-field">
                                    <span>Father's name</span>
                                    <input type="text" name="father_name" value="{{ $attendee->father_name }}">
                                </label>

                                <label class="ep-field">
                                    <span>Gender</span>
                                    <select name="gender">
                                        <option value="male" {{ $attendee->gender === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $attendee->gender === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="prefer_not_to_say" {{ $attendee->gender === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                    </select>
                                </label>

                                <label class="ep-field">
                                    <span>Date of birth</span>
                                    <input type="date" name="date_of_birth" value="{{ $modalDob }}">
                                </label>

                            </div>
                        </div>

                        {{-- Contact --}}
                        <div class="ep-form-section">
                            <h4>Contact</h4>
                            <div class="ep-grid">

                                <label class="ep-field">
                                    <span>Email</span>
                                    <input type="email" name="email" value="{{ $attendee->email }}" required>
                                </label>

                                <label class="ep-field">
                                    <span>Phone</span>
                                    <input type="text" name="phone" value="{{ $attendee->phone }}" required>
                                </label>

                                <label class="ep-field">
                                    <span>Viber number</span>
                                    <input type="text" name="viber" value="{{ $attendee->viber }}">
                                </label>

                                <label class="ep-field">
                                    <span>Emergency contact</span>
                                    <input type="text" name="emergency_contact" value="{{ $attendee->emergency_contact }}" required>
                                </label>

                            </div>
                        </div>

                        {{-- Identity and race --}}
                        <div class="ep-form-section">
                            <h4>Identity and race details</h4>
                            <div class="ep-grid">

                                <label class="ep-field">
                                    <span>Nationality</span>
                                    <select name="nationality">
                                        <option value="Myanmar" {{ $attendee->nationality === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                        <option value="Foreigner" {{ $attendee->nationality === 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                    </select>
                                </label>

                                <label class="ep-field">
                                    <span>NRC / Passport</span>
                                    <input type="text" name="nrc_passport" value="{{ $attendee->nrc_passport }}" required>
                                </label>

                                <label class="ep-field">
                                    <span>Country</span>
                                    <input type="text" name="country" value="{{ $attendee->country }}">
                                </label>

                                <label class="ep-field">
                                    <span>BIB number</span>
                                    <input type="text"
                                           name="bib_number"
                                           value="{{ $attendee->bib_number ?: $attendee->bib_name }}"
                                           maxlength="50"
                                           placeholder="e.g. GH-0001 or 1001">
                                    <small>The runner's BIB code or number.</small>
                                </label>

                                <label class="ep-field">
                                    <span>BIB name</span>
                                    <input type="text" name="bib_name" maxlength="10" value="{{ $attendee->bib_name }}">
                                    <small>Up to 10 characters.</small>
                                </label>

                                <div class="ep-field">
                                    <span>T-shirt size</span>

                                    @if(count($availableTshirtSizes))
                                        <select name="tshirt_size">
                                            <option value="">Select size</option>
                                            @foreach($availableTshirtSizes as $size)
                                                <option value="{{ $size }}" {{ $attendee->tshirt_size === $size ? 'selected' : '' }}>
                                                    {{ $size }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="tshirt_size" value="{{ $attendee->tshirt_size }}">
                                        <div class="ep-static">No T-shirt sizes set for this event</div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        {{-- Health and ITRA --}}
                        <div class="ep-form-section">
                            <h4>Health and ITRA</h4>
                            <div class="ep-grid">

                                <label class="ep-field">
                                    <span>Blood type</span>
                                    <select name="blood_type">
                                        @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $type)
                                            <option value="{{ $type }}" {{ $attendee->blood_type === $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="ep-field">
                                    <span>Has a medical condition?</span>
                                    <select name="has_medical_condition">
                                        <option value="no" {{ strtolower((string) $attendee->has_medical_condition) !== 'yes' ? 'selected' : '' }}>No</option>
                                        <option value="yes" {{ strtolower((string) $attendee->has_medical_condition) === 'yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </label>

                                <label class="ep-field">
                                    <span>Medical details</span>
                                    <input type="text" name="medical_details" value="{{ $attendee->medical_details }}">
                                </label>

                                <label class="ep-field">
                                    <span>ITRA registered?</span>
                                    <select name="itra">
                                        <option value="no" {{ strtolower((string) $attendee->itra) !== 'yes' ? 'selected' : '' }}>No</option>
                                        <option value="yes" {{ strtolower((string) $attendee->itra) === 'yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </label>

                                <label class="ep-field">
                                    <span>ITRA details</span>
                                    <input type="text" name="itra_details" value="{{ $attendee->itra_details }}">
                                </label>

                            </div>
                        </div>

                        {{-- About --}}
                        <div class="ep-form-section">
                            <h4>Address and experience</h4>
                            <div class="ep-grid">

                                <label class="ep-field">
                                    <span>Address</span>
                                    <textarea name="address" rows="3">{{ $attendee->address }}</textarea>
                                </label>

                                <label class="ep-field">
                                    <span>Running experience</span>
                                    <textarea name="experience" rows="3">{{ $attendee->experience }}</textarea>
                                </label>

                            </div>
                        </div>

                    </div>

                    <div class="ep-modal-foot">
                        <button type="button" class="ep-btn ep-btn-ghost" data-close-modal>Cancel</button>
                        <button type="submit" class="ep-btn ep-btn-primary">
                            <i class="fa-solid fa-check" aria-hidden="true"></i>
                            Save changes
                        </button>
                    </div>

                </form>

            </div>

        </div>

    @endforeach

@endif


</div>

<script>
function confirmAttendeeDeletion(event, form) {
    event.preventDefault();

    // GROUP BUY: extra warning when the runner is part of a group buy order.
    const groupSize = form.dataset.groupBuy;
    const groupWarning = groupSize
        ? '\n\nThis runner is part of a group buy order of ' + groupSize + ' tickets. Removing one ticket breaks the group. The order keeps its group discount, and the order total drops by this ticket\'s full price.'
        : '';

    if (!window.confirm(
        'Are you sure you want to delete this attendee? This action cannot be undone and may update the order revenue.' + groupWarning
    )) {
        return false;
    }

    const adminPassword = window.prompt(
        'Super Admin verification required.\n\nEnter your Super Admin password to confirm deletion:'
    );

    if (adminPassword === null || adminPassword.trim() === '') {
        window.alert('Password verification was cancelled. The attendee was not deleted.');
        return false;
    }

    // Send the password to Laravel for secure server-side Hash::check verification.
    let passwordInput = form.querySelector('input[name="admin_password"]');

    if (!passwordInput) {
        passwordInput = document.createElement('input');
        passwordInput.type = 'hidden';
        passwordInput.name = 'admin_password';
        form.appendChild(passwordInput);
    }

    passwordInput.value = adminPassword;

    try { sessionStorage.setItem('ep-attendees-restore-{{ $event->id }}', '1'); } catch (e) {}

    form.submit();

    return false;
}

(function () {
    /* ---------------------------------------------------------
       Expand / collapse details
    --------------------------------------------------------- */
    document.addEventListener('click', function (event) {
        const toggle = event.target.closest('.att-toggle');
        if (!toggle) return;

        const group = toggle.closest('.att-group');
        const open = group.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    /* ---------------------------------------------------------
       Edit modals
    --------------------------------------------------------- */
    let lastFocus = null;

    function openModal(id) {
        const modal = document.getElementById('edit-modal-' + id);
        if (!modal) return;

        lastFocus = document.activeElement;
        modal.classList.add('is-open');
        document.body.classList.add('ep-lock');

        const first = modal.querySelector('input:not([type="hidden"]), select, textarea');
        if (first) first.focus();
    }

    function closeModal(modal) {
        if (!modal) return;

        modal.classList.remove('is-open');

        if (!document.querySelector('.ep-modal.is-open')) {
            document.body.classList.remove('ep-lock');
        }

        if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    document.addEventListener('click', function (event) {
        const opener = event.target.closest('[data-open-modal]');
        if (opener) {
            openModal(opener.dataset.openModal);
            return;
        }

        const closer = event.target.closest('[data-close-modal]');
        if (closer) {
            closeModal(closer.closest('.ep-modal'));
            return;
        }

        if (event.target.classList && event.target.classList.contains('ep-modal')) {
            closeModal(event.target);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        closeModal(document.querySelector('.ep-modal.is-open'));
    });

    document.querySelectorAll('.ep-modal-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            try { sessionStorage.setItem('ep-attendees-restore-{{ $event->id }}', '1'); } catch (e) {}
        });
    });

    /* ---------------------------------------------------------
       Search, category filter and pagination
    --------------------------------------------------------- */
    const groups = Array.prototype.slice.call(document.querySelectorAll('.att-group'));
    if (!groups.length) return;

    const card = document.getElementById('runnersCard');
    const searchInput = document.getElementById('runnerSearch');
    const searchClear = document.getElementById('runnerSearchClear');
    const sizeSelect = document.getElementById('pageSize');
    const pageInfo = document.getElementById('pageInfo');
    const pageNav = document.getElementById('pageNav');
    const noResults = document.getElementById('noResults');
    const tableWrap = card.querySelector('.ep-table-wrap');

    // GROUP BUY: category and purchase-type filters are separate button sets.
    const filterButtons = Array.prototype.slice.call(card.querySelectorAll('.ep-filter[data-category]'));
    const buyTypeButtons = Array.prototype.slice.call(card.querySelectorAll('.ep-filter[data-buytype]'));

    const STORE_KEY = 'ep-attendees-state-{{ $event->id }}';
    const RESTORE_KEY = 'ep-attendees-restore-{{ $event->id }}';

    const state = { page: 1, size: 10, query: '', category: 'all', buyType: 'all' };

    // After saving or deleting a runner, return to the same page.
    try {
        if (sessionStorage.getItem(RESTORE_KEY) === '1') {
            const saved = JSON.parse(sessionStorage.getItem(STORE_KEY) || '{}');

            if ([10, 25, 50].indexOf(saved.size) !== -1) state.size = saved.size;
            if (saved.page > 0) state.page = saved.page;
            if (typeof saved.query === 'string') state.query = saved.query;
            if (typeof saved.category === 'string') state.category = saved.category;
            if (typeof saved.buyType === 'string') state.buyType = saved.buyType;
        }
        sessionStorage.removeItem(RESTORE_KEY);
    } catch (e) {}

    // GROUP BUY: ignore a saved purchase-type filter when there are no group buy runners.
    if (!buyTypeButtons.length) state.buyType = 'all';

    function saveState() {
        try { sessionStorage.setItem(STORE_KEY, JSON.stringify(state)); } catch (e) {}
    }

    function matching() {
        const query = state.query.trim().toLowerCase();

        return groups.filter(function (group) {
            const inCategory = state.category === 'all' || group.dataset.category === state.category;
            const inBuyType = state.buyType === 'all' || group.dataset.buytype === state.buyType;
            const inSearch = !query || group.dataset.search.indexOf(query) !== -1;
            return inCategory && inBuyType && inSearch;
        });
    }

    function pageList(page, pages) {
        const items = [];
        let last = 0;

        for (let i = 1; i <= pages; i++) {
            if (i === 1 || i === pages || Math.abs(i - page) <= 1) {
                if (i - last > 1) items.push('gap');
                items.push(i);
                last = i;
            }
        }

        return items;
    }

    function pageButton(label, onClick, options) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'ep-page-btn' + (options.current ? ' is-current' : '');
        button.disabled = !!options.disabled;

        if (options.html) button.innerHTML = label;
        else button.textContent = label;

        if (options.aria) button.setAttribute('aria-label', options.aria);
        if (options.current) button.setAttribute('aria-current', 'page');

        button.addEventListener('click', onClick);
        return button;
    }

    function goTo(page) {
        state.page = page;
        render();

        const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        card.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
    }

    function render() {
        const list = matching();
        const total = list.length;
        const pages = Math.max(1, Math.ceil(total / state.size));

        if (state.page > pages) state.page = pages;
        if (state.page < 1) state.page = 1;

        const start = (state.page - 1) * state.size;
        const end = Math.min(start + state.size, total);
        const visible = list.slice(start, end);

        groups.forEach(function (group) {
            group.classList.toggle('is-hidden', visible.indexOf(group) === -1);
        });

        tableWrap.classList.toggle('is-hidden', total === 0);
        noResults.classList.toggle('is-hidden', total !== 0);
        searchClear.classList.toggle('is-hidden', state.query === '');

        filterButtons.forEach(function (button) {
            button.classList.toggle('is-active', button.dataset.category === state.category);
        });

        buyTypeButtons.forEach(function (button) {
            button.classList.toggle('is-active', button.dataset.buytype === state.buyType);
        });

        // Info text
        pageInfo.replaceChildren();

        if (total === 0) {
            pageInfo.textContent = 'No runners to show';
        } else {
            const strong = document.createElement('strong');
            strong.textContent = (start + 1) + '–' + end;

            pageInfo.append('Showing ', strong, ' of ' + total + ' runner' + (total === 1 ? '' : 's'));

            if (total !== groups.length) {
                pageInfo.append(' (filtered from ' + groups.length + ')');
            }
        }

        // Page buttons
        pageNav.replaceChildren();

        if (pages > 1) {
            pageNav.appendChild(pageButton(
                '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Previous',
                function () { goTo(state.page - 1); },
                { html: true, disabled: state.page === 1, aria: 'Previous page' }
            ));

            pageList(state.page, pages).forEach(function (item) {
                if (item === 'gap') {
                    const gap = document.createElement('span');
                    gap.className = 'ep-page-gap';
                    gap.textContent = '…';
                    pageNav.appendChild(gap);
                    return;
                }

                pageNav.appendChild(pageButton(
                    String(item),
                    function () { goTo(item); },
                    { current: item === state.page, aria: 'Page ' + item }
                ));
            });

            pageNav.appendChild(pageButton(
                'Next <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>',
                function () { goTo(state.page + 1); },
                { html: true, disabled: state.page === pages, aria: 'Next page' }
            ));
        }

        saveState();
    }

    let searchTimer = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {
            state.query = searchInput.value;
            state.page = 1;
            render();
        }, 150);
    });

    searchClear.addEventListener('click', function () {
        searchInput.value = '';
        state.query = '';
        state.page = 1;
        render();
        searchInput.focus();
    });

    sizeSelect.addEventListener('change', function () {
        state.size = Number(sizeSelect.value) || 10;
        state.page = 1;
        render();
    });

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            state.category = button.dataset.category;
            state.page = 1;
            render();
        });
    });

    buyTypeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            state.buyType = button.dataset.buytype;
            state.page = 1;
            render();
        });
    });

    document.getElementById('clearFilters').addEventListener('click', function () {
        searchInput.value = '';
        state.query = '';
        state.category = 'all';
        state.buyType = 'all';
        state.page = 1;
        render();
    });

    searchInput.value = state.query;
    sizeSelect.value = String(state.size);
    render();
})();
</script>

@if(auth('admin')->user()->isEventAdmin())

</div>

</body>
</html>

@else


@endsection


@endif