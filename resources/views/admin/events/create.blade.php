@extends('layouts.admin')

@section('title', 'Create New Event')
@section('page-title', 'Create Event')

@section('content')

@php
    // True when the page is re-shown after a failed submit.
    $hasOld = session()->hasOldInput();

    $availableFields = [
        'viber' => 'Viber number',
        'father_name' => 'Father name',
        'blood_type' => 'Blood type',
        'tshirt_size' => 'T-shirt size',
        'has_medical_condition' => 'Medical condition',
        'itra' => 'ITRA details',
        'experience' => 'Running experience',
        'address' => 'Address',
    ];

    $enabledFields = $hasOld
        ? (array) old('enabled_fields', [])
        : array_keys($availableFields);

    $standardSizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];

    $selectedTshirtSizes = $hasOld
        ? (array) old('tshirt_sizes', [])
        : $standardSizes;

    $customSizes = array_values(array_diff($selectedTshirtSizes, $standardSizes));

    $enableBib = $hasOld ? (bool) old('enable_bib_number') : true;
    $shareBib = (string) old('share_bib_prefix', '1');

    $oldItemsOpen = is_array(old('items')) && count(old('items')) > 0;

    // GROUP BUY: deals re-shown after a failed submit.
    $groupBuysData = collect((array) old('group_buys', []))->map(fn ($row) => [
        'name' => $row['name'] ?? '',
        'quantity' => $row['quantity'] ?? '',
        'discount_amount' => $row['discount_amount'] ?? '',
        'ticket_scope' => $row['ticket_scope'] ?? 'same_category',
        'allowed' => array_map('strval', (array) ($row['allowed_ticket_category_ids'] ?? [])),
        'is_active' => (string) ($row['is_active'] ?? '1') === '1',
    ])->values()->all();
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
.ce {
    --ce-accent: #4f46e5;
    --ce-accent-dark: #4338ca;
    --ce-accent-soft: #eef0ff;
    --ce-ink: #1b2133;
    --ce-muted: #667085;
    --ce-line: #e3e6ee;
    --ce-bg: #f6f7fb;
    --ce-red: #b42318;
    --ce-red-soft: #fdeceb;
    --ce-green: #067647;
    --ce-green-soft: #e8f7ef;
    color: var(--ce-ink);
    font-size: 14px;
    line-height: 1.5;
    max-width: 1180px;
    margin: 0 auto;
    /* no extra blank space under the page, and nothing sticking out sideways */
    padding-bottom: 0;
    overflow-x: clip;
}
.ce *, .ce *::before, .ce *::after { box-sizing: border-box; }
.ce [hidden] { display: none !important; }
.ce form { margin: 0; padding: 0; }

/* ---------- Page header ---------- */
.ce-head { margin-bottom: 22px; }
.ce-back {
    display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px;
    color: var(--ce-muted); font-weight: 600; text-decoration: none;
}
.ce-back:hover { color: var(--ce-accent); }
.ce-head h1 { margin: 0 0 4px; font-size: 26px; font-weight: 750; letter-spacing: -.4px; }
.ce-head p { margin: 0; color: var(--ce-muted); }

/* ---------- Alerts ---------- */
.ce-alert {
    position: relative; display: flex; align-items: flex-start; gap: 12px;
    margin-bottom: 18px; padding: 14px 44px 14px 16px; border-radius: 12px; border: 1px solid;
}
.ce-alert i.ce-alert-icon { margin-top: 3px; }
.ce-alert-success { background: var(--ce-green-soft); border-color: #b7e4cb; color: #0b5a3a; }
.ce-alert-danger { background: var(--ce-red-soft); border-color: #f5c6c2; color: #8f1d14; }
.ce-alert strong { display: block; margin-bottom: 2px; }
.ce-alert p { margin: 0; }
.ce-alert ul { margin: 6px 0 0; padding-left: 18px; }
.ce-alert-close {
    position: absolute; top: 10px; right: 10px; display: grid; place-items: center;
    width: 30px; height: 30px; border: 0; border-radius: 8px; background: transparent;
    color: inherit; opacity: .7; cursor: pointer;
}
.ce-alert-close:hover { opacity: 1; background: rgba(0, 0, 0, .06); }

/* ---------- Layout ---------- */
.ce-layout { display: grid; grid-template-columns: 200px minmax(0, 1fr); gap: 26px; align-items: start; }
.ce-nav { position: sticky; top: 16px; }
.ce-nav ul { margin: 0; padding: 0; list-style: none; }
.ce-nav a {
    display: block; padding: 9px 12px; border-left: 3px solid var(--ce-line);
    color: var(--ce-muted); font-weight: 600; text-decoration: none;
}
.ce-nav a:hover { color: var(--ce-ink); background: var(--ce-bg); }
.ce-nav a.is-active { border-left-color: var(--ce-accent); color: var(--ce-accent-dark); background: var(--ce-accent-soft); }
.ce-nav small { display: block; margin-top: 12px; padding-left: 15px; color: var(--ce-muted); }
.ce-main { display: flex; flex-direction: column; gap: 18px; min-width: 0; }
.ce-main > :last-child { margin-bottom: 0; }

/* ---------- Cards ---------- */
.ce-card { scroll-margin-top: 16px; overflow: hidden; border: 1px solid var(--ce-line); border-radius: 14px; background: #fff; }
.ce-card-head { padding: 18px 22px; border-bottom: 1px solid var(--ce-line); }
.ce-card-head h2 { margin: 0 0 2px; font-size: 18px; font-weight: 700; }
.ce-card-head p { margin: 0; color: var(--ce-muted); }
.ce-card-body { padding: 22px; }
.ce-sub { margin: 24px 0 12px; padding-top: 22px; border-top: 1px solid var(--ce-line); font-size: 15.5px; font-weight: 700; }
.ce-sub:first-child { margin-top: 0; padding-top: 0; border-top: 0; }
.ce-sub-note { margin: -6px 0 12px; color: var(--ce-muted); }

/* optional (collapsible) sections */
.ce-card > summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; list-style: none; cursor: pointer; }
.ce-card > summary::-webkit-details-marker { display: none; }
.ce-card > summary .ce-card-head-text { min-width: 0; }
.ce-card > summary .ce-opt-tag {
    flex-shrink: 0; padding: 3px 10px; border-radius: 999px; background: var(--ce-bg);
    color: var(--ce-muted); font-size: 12.5px; font-weight: 650;
}
.ce-card > summary .ce-chevron { flex-shrink: 0; color: var(--ce-muted); transition: transform .2s; }
.ce-card[open] > summary .ce-chevron { transform: rotate(180deg); }
.ce-card[open] > summary { border-bottom: 1px solid var(--ce-line); }
.ce-card > summary.ce-card-head { border-bottom: 0; }
.ce-card > summary:focus-visible { outline: 3px solid #b9b5f7; outline-offset: -3px; }

/* ---------- Fields ---------- */
.ce-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.ce-grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
.ce-grid-4 { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
.ce-span-2 { grid-column: span 2; }
.ce-stack > * + * { margin-top: 16px; }

.ce-field { display: block; margin: 0; min-width: 0; }
.ce-field > span:first-child,
.ce-field > label:first-child,
.ce-label {
    display: block; margin-bottom: 6px; color: #3a4357; font-size: 13.5px; font-weight: 650;
}
.ce-req { margin-left: 3px; color: var(--ce-red); }
.ce-help { margin: 6px 0 0; color: var(--ce-muted); font-size: 13px; }
.ce-error { margin: 6px 0 0; color: var(--ce-red); font-size: 13px; font-weight: 600; }

.ce-input, .ce-select, .ce-textarea {
    display: block; width: 100%; min-height: 44px; padding: 9px 12px; border: 1px solid #d5d9e3;
    border-radius: 10px; background: #fff; color: var(--ce-ink); font: inherit; font-size: 14px;
    transition: border-color .15s, box-shadow .15s;
}
.ce-textarea { min-height: 130px; resize: vertical; }
.ce-input:hover, .ce-select:hover, .ce-textarea:hover { border-color: #bcc2d0; }
.ce-input:focus, .ce-select:focus, .ce-textarea:focus {
    border-color: #7c75ee; box-shadow: 0 0 0 3px rgba(79, 70, 229, .15); outline: 0;
}
.ce-input.is-invalid, .ce-select.is-invalid, .ce-textarea.is-invalid { border-color: #e0786f; }
.ce-input::placeholder, .ce-textarea::placeholder { color: #98a1b3; }
.ce-upper { text-transform: uppercase; }

/* ---------- Buttons ---------- */
.ce-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 42px;
    padding: 8px 16px; border: 1px solid transparent; border-radius: 10px; font: inherit;
    font-size: 14px; font-weight: 650; line-height: 1.2; text-decoration: none; white-space: nowrap; cursor: pointer;
    transition: background-color .15s, border-color .15s;
}
.ce-btn-sm { min-height: 36px; padding: 6px 12px; font-size: 13.5px; }
.ce-btn-primary { background: var(--ce-accent); color: #fff; }
.ce-btn-primary:hover { background: var(--ce-accent-dark); color: #fff; }
.ce-btn-soft { background: var(--ce-accent-soft); border-color: #d9dcfb; color: var(--ce-accent-dark); }
.ce-btn-soft:hover { background: #e2e5ff; color: var(--ce-accent-dark); }
.ce-btn-ghost { background: #fff; border-color: var(--ce-line); color: #3a4357; }
.ce-btn-ghost:hover { background: var(--ce-bg); color: #3a4357; }
.ce-btn:disabled { opacity: .55; cursor: not-allowed; }
.ce-icon-btn {
    display: inline-grid; place-items: center; flex-shrink: 0; width: 38px; height: 38px;
    border: 1px solid #f1d0cc; border-radius: 9px; background: #fff; color: var(--ce-red); cursor: pointer;
}
.ce-icon-btn:hover:not(:disabled) { background: var(--ce-red-soft); }
.ce-icon-btn:disabled { opacity: .35; cursor: not-allowed; }
.ce a:focus-visible, .ce button:focus-visible, .ce-file:focus-within, .ce-check:focus-within, .ce-radio:focus-within, .ce-switch:focus-within {
    outline: 3px solid #b9b5f7; outline-offset: 2px;
}

/* ---------- Location ---------- */
.ce-search { position: relative; }
.ce-search > i { position: absolute; top: 50%; left: 14px; transform: translateY(-50%); color: #98a1b3; pointer-events: none; }
.ce-search .ce-input { padding-left: 40px; padding-right: 130px; }
.ce-search .ce-btn { position: absolute; top: 4px; right: 4px; }
.ce-status { display: flex; align-items: center; gap: 7px; min-height: 22px; margin: 8px 0 0; color: var(--ce-muted); font-size: 13px; }
.ce-status.is-loading { color: var(--ce-accent); }
.ce-status.is-success { color: var(--ce-green); }
.ce-status.is-error { color: var(--ce-red); }
.ce-results { margin-top: 8px; overflow: hidden; border: 1px solid var(--ce-line); border-radius: 12px; background: #fff; box-shadow: 0 8px 24px rgba(27, 33, 51, .08); }
.ce-result {
    display: block; width: 100%; padding: 11px 14px; border: 0; border-bottom: 1px solid #eef0f5;
    background: #fff; font: inherit; text-align: left; cursor: pointer;
}
.ce-result:last-child { border-bottom: 0; }
.ce-result:hover { background: var(--ce-bg); }
.ce-result.is-best { background: var(--ce-accent-soft); }
.ce-result strong { display: block; font-size: 14px; }
.ce-result span { display: block; margin-top: 2px; color: var(--ce-muted); font-size: 13px; }
.ce-result em { display: inline-block; margin-left: 8px; padding: 1px 8px; border-radius: 999px; background: #fff; color: var(--ce-accent-dark); font-size: 12px; font-style: normal; font-weight: 650; }
#event-location-map { position: relative; width: 100%; height: 360px; margin-top: 14px; overflow: hidden; border: 1px solid var(--ce-line); border-radius: 12px; background: #eceff4; }
.ce-coords { display: flex; align-items: center; gap: 8px; margin-top: 10px; padding: 10px 13px; border-radius: 10px; background: var(--ce-green-soft); color: #0b5a3a; font-weight: 600; }
.leaflet-popup-content { font-size: 13px; line-height: 1.5; }

/* ---------- Pickers: file, check, radio, switch ---------- */
.ce-file {
    position: relative; display: flex; align-items: center; gap: 12px; min-height: 48px; padding: 6px;
    border: 1px dashed #c3c8d6; border-radius: 10px; background: #fafbfd; cursor: pointer;
    transition: border-color .15s, background-color .15s;
}
.ce-file:hover { border-color: #8f89f0; background: #f7f7ff; }
.ce-file-input { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
.ce-file-btn {
    display: inline-flex; align-items: center; gap: 7px; flex-shrink: 0; padding: 7px 12px;
    border-radius: 8px; background: var(--ce-accent-soft); color: var(--ce-accent-dark); font-weight: 650;
}
.ce-file-name { min-width: 0; overflow: hidden; color: var(--ce-muted); text-overflow: ellipsis; white-space: nowrap; }
.ce-file-name.has-file { color: var(--ce-ink); font-weight: 600; }

.ce-banner-preview { position: relative; margin-top: 12px; overflow: hidden; border: 1px solid var(--ce-line); border-radius: 12px; background: var(--ce-bg); }
.ce-banner-preview img { display: block; width: 100%; max-height: 260px; object-fit: cover; }
.ce-banner-preview .ce-btn { position: absolute; top: 10px; right: 10px; background: #fff; }

.ce-toggle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 10px; }
.ce-check {
    display: flex; align-items: center; gap: 10px; min-height: 44px; margin: 0; padding: 8px 14px;
    border: 1px solid var(--ce-line); border-radius: 10px; background: #fff; font-weight: 600; cursor: pointer;
    transition: border-color .15s, background-color .15s;
}
.ce-check:hover { border-color: #b7b3f5; }
.ce-check:has(input:checked) { border-color: var(--ce-accent); background: var(--ce-accent-soft); }
.ce-check input { width: 17px; height: 17px; accent-color: var(--ce-accent); cursor: pointer; }
.ce-size-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 8px; }
.ce-size-grid .ce-check { min-height: 40px; padding: 6px 12px; }
.ce-add-size { display: flex; align-items: center; gap: 8px; margin-top: 12px; }
.ce-add-size .ce-input { max-width: 280px; }

.ce-panel { padding: 16px; border: 1px solid var(--ce-line); border-radius: 12px; background: var(--ce-bg); }
.ce-panel + .ce-panel { margin-top: 14px; }

.ce-switch { position: relative; display: inline-flex; align-items: center; gap: 12px; margin: 0; font-weight: 650; cursor: pointer; }
.ce-switch input { position: absolute; opacity: 0; width: 1px; height: 1px; }
.ce-switch-ui { position: relative; flex-shrink: 0; width: 42px; height: 24px; border-radius: 999px; background: #c4c9d6; transition: background-color .15s; }
.ce-switch-ui::after {
    content: ""; position: absolute; top: 3px; left: 3px; width: 18px; height: 18px; border-radius: 50%;
    background: #fff; transition: transform .15s;
}
.ce-switch input:checked + .ce-switch-ui { background: var(--ce-accent); }
.ce-switch input:checked + .ce-switch-ui::after { transform: translateX(18px); }

.ce-radio-group { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin: 0 0 14px; padding: 0; border: 0; }
.ce-radio-group legend { margin-bottom: 8px; padding: 0; color: #3a4357; font-size: 13.5px; font-weight: 650; }
.ce-radio {
    display: flex; align-items: flex-start; gap: 10px; margin: 0; padding: 12px 14px; border: 1px solid var(--ce-line);
    border-radius: 10px; background: #fff; cursor: pointer;
}
.ce-radio:has(input:checked) { border-color: var(--ce-accent); background: var(--ce-accent-soft); }
.ce-radio input { margin-top: 3px; accent-color: var(--ce-accent); }
.ce-radio strong { display: block; font-size: 14px; }
.ce-radio small { display: block; margin-top: 2px; color: var(--ce-muted); font-size: 13px; }

/* ---------- Ticket types and items ---------- */
.ce-category, .ce-item { padding: 16px; border: 1px solid var(--ce-line); border-radius: 12px; background: #fff; }
.ce-category + .ce-category, .ce-item + .ce-item { margin-top: 12px; }
.ce-category-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
.ce-category-head strong { font-size: 14.5px; }
.ce-bib-fields { margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--ce-line); }
.ce-list-actions { margin-top: 14px; }
.ce-item { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) 38px; gap: 14px; align-items: end; }
.ce-empty-note { padding: 18px; border: 1px dashed #cfd4e0; border-radius: 12px; background: var(--ce-bg); color: var(--ce-muted); text-align: center; }

/* ---------- Group buy deals ---------- */
.ce-gb-preview { margin: 12px 0 0; padding: 10px 13px; border-radius: 10px; background: var(--ce-green-soft); color: #0b5a3a; font-weight: 600; }
.ce-gb-preview.is-warning { background: var(--ce-red-soft); color: #8f1d14; }
.ce-gb-scope { margin: 14px 0 0; }
.ce-gb-categories { margin: 0 0 14px; }
.ce-gb-footer { margin-top: 2px; }

/* ---------- Documents ---------- */
.ce-note { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 18px; padding: 12px 14px; border-radius: 10px; background: var(--ce-accent-soft); color: #3730a3; }
.ce-note i { margin-top: 3px; }
.ce-doc { margin: 0 0 18px; padding: 0; border: 0; }
.ce-doc:last-child { margin-bottom: 0; }
.ce-doc legend { margin-bottom: 8px; padding: 0; font-size: 14.5px; font-weight: 700; }

/* ---------- Action bar ---------- */
.ce-actionbar {
    position: sticky; bottom: 0; z-index: 20; display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin: 18px 0 0; padding: 14px 20px; border: 1px solid var(--ce-line);
    border-radius: 14px; background: rgba(255, 255, 255, .96); box-shadow: 0 -6px 24px rgba(27, 33, 51, .08);
}
.ce-actionbar p { margin: 0; color: var(--ce-muted); }
.ce-actionbar-buttons { display: flex; align-items: center; gap: 10px; }

/* ---------- Responsive ---------- */
@media (max-width: 1099px) {
    .ce-layout { grid-template-columns: 1fr; }
    .ce-nav { display: none; }
}
@media (max-width: 767px) {
    .ce-grid-2, .ce-grid-3, .ce-radio-group { grid-template-columns: 1fr; }
    .ce-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .ce-span-2 { grid-column: span 2; }
    .ce-card-head { padding: 16px; }
    .ce-card-body { padding: 16px; }
    .ce-item { grid-template-columns: 1fr; }
    .ce-item .ce-icon-btn { width: 100%; }
    #event-location-map { height: 290px; }
    .ce-search .ce-input { padding-right: 112px; }
    .ce-actionbar { padding: 12px; }
    .ce-actionbar p { display: none; }
    .ce-actionbar-buttons { width: 100%; }
    .ce-actionbar-buttons .ce-btn { flex: 1; }
}
@media (prefers-reduced-motion: reduce) {
    .ce * { transition: none !important; }
}
</style>

<div class="ce">

    <header class="ce-head">
        <a href="{{ route('admin.events.index') }}" class="ce-back">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to events
        </a>
        <h1>Create event</h1>
        <p>Add the event details, tickets and registration form. Fields marked <span class="ce-req" style="margin-left:0;">*</span> are required.</p>
    </header>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="ce-alert ce-alert-success" role="status">
            <i class="fa-solid fa-circle-check ce-alert-icon" aria-hidden="true"></i>
            <div>
                <strong>Success</strong>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="ce-alert-close" aria-label="Dismiss" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="ce-alert ce-alert-danger" role="alert">
            <i class="fa-solid fa-circle-exclamation ce-alert-icon" aria-hidden="true"></i>
            <div>
                <strong>The event could not be published</strong>
                <p>{{ session('error') }}</p>
            </div>
            <button type="button" class="ce-alert-close" aria-label="Dismiss" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="ce-alert ce-alert-danger" role="alert">
            <i class="fa-solid fa-triangle-exclamation ce-alert-icon" aria-hidden="true"></i>
            <div>
                <strong>Please fix the following</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @if($hasOld)
                    <p style="margin-top:8px;">Files such as the banner and PDFs can't be kept by the browser, so please choose them again.</p>
                @endif
            </div>
            <button type="button" class="ce-alert-close" aria-label="Dismiss" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    <form id="eventForm"
          action="{{ route('admin.events.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="ce-layout">

            {{-- SECTION NAV --}}
            <nav class="ce-nav" aria-label="Form sections">
                <ul>
                    <li><a href="#sec-details" class="is-active">Event details</a></li>
                    <li><a href="#sec-location">Location</a></li>
                    <li><a href="#sec-tickets">Tickets</a></li>
                    <li><a href="#sec-group-buy">Group buy</a></li>
                    <li><a href="#sec-registration">Registration form</a></li>
                    <li><a href="#sec-organizer">Organizer</a></li>
                    <li><a href="#sec-items">Included items</a></li>
                    <li><a href="#sec-documents">Documents</a></li>
                </ul>
                <small>Publish when you're ready from the bar at the bottom.</small>
            </nav>

            <div class="ce-main">

                {{-- =================================================
                     EVENT DETAILS
                ================================================== --}}
                <section class="ce-card" id="sec-details">
                    <div class="ce-card-head">
                        <h2>Event details</h2>
                        <p>The basics runners see first.</p>
                    </div>

                    <div class="ce-card-body ce-stack">

                        <div class="ce-field">
                            <label for="title">Event title <span class="ce-req" aria-hidden="true">*</span></label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Yangon International Marathon"
                                   class="ce-input @error('title') is-invalid @enderror">
                            @error('title')<p class="ce-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ce-grid-2">

                            <div class="ce-field">
                                <label for="event_date">Date and time <span class="ce-req" aria-hidden="true">*</span></label>
                                <input type="datetime-local" id="event_date" name="event_date" required
                                       value="{{ old('event_date') }}"
                                       class="ce-input @error('event_date') is-invalid @enderror">
                                @error('event_date')<p class="ce-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ce-field">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="ce-select">
                                    <option value="upcoming" {{ old('status', 'upcoming') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="live" {{ old('status') === 'live' ? 'selected' : '' }}>Live now</option>
                                    <option value="past" {{ old('status') === 'past' ? 'selected' : '' }}>Past</option>
                                </select>
                            </div>

                        </div>

                        <div class="ce-field">
                            <label for="overall_capacity">Overall capacity</label>
                            <input type="number" id="overall_capacity" name="overall_capacity" min="1"
                                   value="{{ old('overall_capacity') }}"
                                   placeholder="Unlimited"
                                   class="ce-input @error('overall_capacity') is-invalid @enderror"
                                   style="max-width:260px;">
                            <p class="ce-help">Optional. The total number of participants across all ticket types. Leave empty for no limit.</p>
                            @error('overall_capacity')<p class="ce-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ce-field">
                            <label for="description">Description <span class="ce-req" aria-hidden="true">*</span></label>
                            <textarea id="description" name="description" rows="5" required
                                      placeholder="Tell runners what they need to know about this event."
                                      class="ce-textarea @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="ce-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ce-field">
                            <span class="ce-label">Banner image</span>
                            <label class="ce-file">
                                <input type="file" id="bannerInput" name="image"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="ce-file-input">
                                <span class="ce-file-btn"><i class="fa-solid fa-image" aria-hidden="true"></i> Choose image</span>
                                <span class="ce-file-name">No image chosen</span>
                            </label>
                            <p class="ce-help">JPG, PNG or GIF. A wide, high-quality image works best.</p>
                            @error('image')<p class="ce-error">{{ $message }}</p>@enderror

                            <div class="ce-banner-preview" id="bannerPreview" hidden>
                                <img src="" alt="Banner preview" id="bannerPreviewImg">
                                <button type="button" class="ce-btn ce-btn-ghost ce-btn-sm" id="bannerRemoveBtn">
                                    <i class="fa-solid fa-xmark" aria-hidden="true"></i> Remove
                                </button>
                            </div>
                        </div>

                    </div>
                </section>

                {{-- =================================================
                     LOCATION
                ================================================== --}}
                <section class="ce-card" id="sec-location">
                    <div class="ce-card-head">
                        <h2>Location</h2>
                        <p>Type the venue and the map finds it for you.</p>
                    </div>

                    <div class="ce-card-body">

                        <div class="ce-field">
                            <label for="location">Venue or address <span class="ce-req" aria-hidden="true">*</span></label>

                            <div class="ce-search">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                <input type="text" id="location" name="location" required
                                       value="{{ old('location') }}"
                                       placeholder="e.g. Shwedagon Pagoda, Yangon"
                                       autocomplete="off"
                                       class="ce-input @error('location') is-invalid @enderror">
                                <button type="button" id="locationSearchBtn" class="ce-btn ce-btn-primary ce-btn-sm">
                                    Find on map
                                </button>
                            </div>

                            @error('location')<p class="ce-error">{{ $message }}</p>@enderror

                            <p id="locationStatus" class="ce-status" role="status" aria-live="polite"></p>
                            <div id="locationResults" class="ce-results" hidden></div>
                        </div>

                        <div id="event-location-map" aria-label="Map showing the event location"></div>

                        <p class="ce-help">
                            Click the map or drag the pin to fine-tune the exact spot. Your typed text stays as written.
                            Pick a suggestion above if you want to use its full address.
                        </p>

                        <div class="ce-coords" id="coordsBox" hidden>
                            <i class="fa-solid fa-location-crosshairs" aria-hidden="true"></i>
                            <span id="coordsText"></span>
                        </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                    </div>
                </section>

                {{-- =================================================
                     TICKETS
                ================================================== --}}
                <section class="ce-card" id="sec-tickets">
                    <div class="ce-card-head">
                        <h2>Tickets</h2>
                        <p>Add each distance or registration option with its price and capacity.</p>
                    </div>

                    <div class="ce-card-body">

                        <div id="categoriesContainer"></div>

                        <div class="ce-list-actions">
                            <button type="button" id="addCategoryBtn" class="ce-btn ce-btn-soft">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add another ticket type
                            </button>
                        </div>

                    </div>
                </section>

                {{-- =================================================
                     GROUP BUY (optional)
                ================================================== --}}
                <details class="ce-card" id="sec-group-buy" {{ count($groupBuysData) ? 'open' : '' }}>
                    <summary class="ce-card-head">
                        <span class="ce-card-head-text">
                            <h2>Group buy deals</h2>
                            <p>Give a discount when runners buy several tickets together.</p>
                        </span>
                        <span class="ce-opt-tag">Optional</span>
                        <i class="fa-solid fa-chevron-down ce-chevron" aria-hidden="true"></i>
                    </summary>

                    <div class="ce-card-body">

                        <div class="ce-note">
                            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                            <div>
                                Each deal sets how many tickets a group must buy (2 or more) and how much is taken off the total.
                                You can add several deals. Group buy orders can't use promo codes.
                            </div>
                        </div>

                        @error('group_buys')<p class="ce-error" style="margin:0 0 12px;">{{ $message }}</p>@enderror

                        <div id="groupBuyContainer"></div>

                        <div class="ce-empty-note" id="emptyGroupBuyMessage" hidden>
                            <i class="fa-solid fa-users" aria-hidden="true"></i>
                            No group buy deals yet.
                        </div>

                        <div class="ce-list-actions">
                            <button type="button" id="addGroupBuyBtn" class="ce-btn ce-btn-soft">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add a group buy deal
                            </button>
                        </div>

                    </div>
                </details>

                {{-- =================================================
                     REGISTRATION FORM
                ================================================== --}}
                <section class="ce-card" id="sec-registration">
                    <div class="ce-card-head">
                        <h2>Registration form</h2>
                        <p>Choose what runners fill in, and how BIB numbers are made.</p>
                    </div>

                    <div class="ce-card-body">

                        <h3 class="ce-sub">Fields to show</h3>
                        <p class="ce-sub-note">Name, email, phone and ID are always asked. Turn extra fields on or off.</p>

                        <div class="ce-toggle-grid">
                            @foreach($availableFields as $fieldKey => $fieldLabel)
                                <label class="ce-check">
                                    <input type="checkbox"
                                           name="enabled_fields[]"
                                           value="{{ $fieldKey }}"
                                           id="field_toggle_{{ $fieldKey }}"
                                           {{ in_array($fieldKey, $enabledFields) ? 'checked' : '' }}>
                                    <span>{{ $fieldLabel }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- T-shirt sizes --}}
                        <div id="tshirt_sizes_config_box" class="ce-panel" style="margin-top:16px;"
                             {{ in_array('tshirt_size', $enabledFields) ? '' : 'hidden' }}>

                            <span class="ce-label">Available T-shirt sizes</span>
                            <p class="ce-help" style="margin:-2px 0 12px;">Pick the sizes runners can choose from.</p>

                            <div class="ce-size-grid" id="tshirt_sizes_grid">
                                @foreach($standardSizes as $size)
                                    <label class="ce-check">
                                        <input type="checkbox" name="tshirt_sizes[]" value="{{ $size }}"
                                               {{ in_array($size, $selectedTshirtSizes) ? 'checked' : '' }}>
                                        <span>{{ $size }}</span>
                                    </label>
                                @endforeach

                                @foreach($customSizes as $customSize)
                                    <label class="ce-check">
                                        <input type="checkbox" name="tshirt_sizes[]" value="{{ $customSize }}" checked>
                                        <span>{{ $customSize }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="ce-add-size">
                                <input type="text" id="custom_tshirt_input" class="ce-input"
                                       placeholder="Add a custom size, e.g. 4XL or Kids M"
                                       aria-label="Custom T-shirt size">
                                <button type="button" id="add_custom_tshirt_btn" class="ce-btn ce-btn-soft">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add
                                </button>
                            </div>
                        </div>

                        {{-- BIB --}}
                        <h3 class="ce-sub">BIB numbers</h3>

                        <label class="ce-switch">
                            <input type="checkbox" name="enable_bib_number" id="enable_bib_number" value="1"
                                   {{ $enableBib ? 'checked' : '' }}>
                            <span class="ce-switch-ui" aria-hidden="true"></span>
                            <span>Generate BIB numbers automatically</span>
                        </label>

                        <div id="bib_config_wrapper" class="ce-panel" style="margin-top:14px;" {{ $enableBib ? '' : 'hidden' }}>

                            <fieldset class="ce-radio-group">
                                <legend>Prefix mode</legend>

                                <label class="ce-radio">
                                    <input type="radio" name="share_bib_prefix" value="1" {{ $shareBib !== '0' ? 'checked' : '' }}>
                                    <span>
                                        <strong>One prefix for the whole event</strong>
                                        <small>For example NA-0001, NA-0002</small>
                                    </span>
                                </label>

                                <label class="ce-radio">
                                    <input type="radio" name="share_bib_prefix" value="0" {{ $shareBib === '0' ? 'checked' : '' }}>
                                    <span>
                                        <strong>A prefix for each ticket type</strong>
                                        <small>Set it on each ticket type above</small>
                                    </span>
                                </label>
                            </fieldset>

                            <div id="shared_prefix_box" class="ce-grid-2" {{ $shareBib === '0' ? 'hidden' : '' }}>

                                <div class="ce-field">
                                    <label for="event_bib_prefix">Event BIB prefix</label>
                                    <input type="text" id="event_bib_prefix" name="event_bib_prefix" maxlength="3"
                                           value="{{ old('event_bib_prefix') }}"
                                           placeholder="e.g. NA"
                                           class="ce-input ce-upper">
                                    <p class="ce-help">Up to 3 characters.</p>
                                </div>

                                <div class="ce-field">
                                    <label for="event_bib_start_number">Start number</label>
                                    <input type="number" id="event_bib_start_number" name="event_bib_start_number" min="1"
                                           value="{{ old('event_bib_start_number', 1) }}"
                                           class="ce-input">
                                </div>

                            </div>

                        </div>

                    </div>
                </section>

                {{-- =================================================
                     ORGANIZER
                ================================================== --}}
                <section class="ce-card" id="sec-organizer">
                    <div class="ce-card-head">
                        <h2>Organizer</h2>
                        <p>Runners can use these details to contact the person responsible for the event.</p>
                    </div>

                    <div class="ce-card-body">
                        <div class="ce-grid-3">

                            <div class="ce-field">
                                <label for="creator_name">Name <span class="ce-req" aria-hidden="true">*</span></label>
                                <input type="text" id="creator_name" name="creator_name" required
                                       value="{{ old('creator_name') }}"
                                       placeholder="e.g. John Doe"
                                       class="ce-input @error('creator_name') is-invalid @enderror">
                                @error('creator_name')<p class="ce-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ce-field">
                                <label for="creator_phone">Phone number <span class="ce-req" aria-hidden="true">*</span></label>
                                <input type="text" id="creator_phone" name="creator_phone" required
                                       value="{{ old('creator_phone') }}"
                                       placeholder="e.g. 09 123 456 789"
                                       class="ce-input @error('creator_phone') is-invalid @enderror">
                                @error('creator_phone')<p class="ce-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ce-field">
                                <label for="creator_email">Email <span class="ce-req" aria-hidden="true">*</span></label>
                                <input type="email" id="creator_email" name="creator_email" required
                                       value="{{ old('creator_email') }}"
                                       placeholder="organizer@example.com"
                                       class="ce-input @error('creator_email') is-invalid @enderror">
                                @error('creator_email')<p class="ce-error">{{ $message }}</p>@enderror
                            </div>

                        </div>
                    </div>
                </section>

                {{-- =================================================
                     INCLUDED ITEMS (optional)
                ================================================== --}}
                <details class="ce-card" id="sec-items" {{ $oldItemsOpen ? 'open' : '' }}>
                    <summary class="ce-card-head">
                        <span class="ce-card-head-text">
                            <h2>Included items</h2>
                            <p>What runners get with their ticket, like a shirt or medal.</p>
                        </span>
                        <span class="ce-opt-tag">Optional</span>
                        <i class="fa-solid fa-chevron-down ce-chevron" aria-hidden="true"></i>
                    </summary>

                    <div class="ce-card-body">

                        <div id="itemsContainer"></div>

                        <div class="ce-empty-note" id="emptyItemsMessage" hidden>
                            <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                            No items added yet.
                        </div>

                        <div class="ce-list-actions">
                            <button type="button" id="addItemBtn" class="ce-btn ce-btn-soft">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add an item
                            </button>
                        </div>

                    </div>
                </details>

                {{-- =================================================
                     DOCUMENTS (optional)
                ================================================== --}}
                <details class="ce-card" id="sec-documents">
                    <summary class="ce-card-head">
                        <span class="ce-card-head-text">
                            <h2>Waivers, consent forms and race guides</h2>
                            <p>PDF documents for runners, in English and Burmese.</p>
                        </span>
                        <span class="ce-opt-tag">Optional</span>
                        <i class="fa-solid fa-chevron-down ce-chevron" aria-hidden="true"></i>
                    </summary>

                    <div class="ce-card-body">

                        <div class="ce-note">
                            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                            <div>
                                Runners see the waiver and consent form before they pay. Race guides give event-day details.
                                If you skip the consent form, that step is left out automatically.
                            </div>
                        </div>

                        <fieldset class="ce-doc">
                            <legend>Waiver</legend>
                            <div class="ce-grid-2">
                                <div class="ce-field">
                                    <span class="ce-label">English (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="english_waiver" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                                <div class="ce-field">
                                    <span class="ce-label">Burmese (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="burmese_waiver" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="ce-doc">
                            <legend>Consent form</legend>
                            <div class="ce-grid-2">
                                <div class="ce-field">
                                    <span class="ce-label">English (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="english_consent" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                                <div class="ce-field">
                                    <span class="ce-label">Burmese (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="burmese_consent" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="ce-doc">
                            <legend>Race guide</legend>
                            <div class="ce-grid-2">
                                <div class="ce-field">
                                    <span class="ce-label">English (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="english_race_guide" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                                <div class="ce-field">
                                    <span class="ce-label">Burmese (PDF)</span>
                                    <label class="ce-file">
                                        <input type="file" name="burmese_race_guide" accept="application/pdf" class="ce-file-input">
                                        <span class="ce-file-btn"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Choose PDF</span>
                                        <span class="ce-file-name">No file chosen</span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </details>

            </div>
        </div>

        {{-- PUBLISH BAR --}}
        <div class="ce-actionbar">
            <p>Review your details, then publish. You can edit the event afterwards.</p>
            <div class="ce-actionbar-buttons">
                <a href="{{ route('admin.events.index') }}" class="ce-btn ce-btn-ghost">Cancel</a>
                <button type="submit" id="publishBtn" class="ce-btn ce-btn-primary">
                    <i class="fa-solid fa-rocket" aria-hidden="true"></i> Publish event
                </button>
            </div>
        </div>

    </form>

</div>


{{-- =========================================================
     TEMPLATES
========================================================= --}}
<template id="categoryTemplate">
    <div class="ce-category">
        <div class="ce-category-head">
            <strong class="ce-category-title">Ticket type</strong>
            <button type="button" class="ce-icon-btn ce-remove-category" aria-label="Remove this ticket type">
                <i class="fa-solid fa-trash" aria-hidden="true"></i>
            </button>
        </div>

        <div class="ce-grid-4">
            <label class="ce-field ce-span-2">
                <span>Name <span class="ce-req" aria-hidden="true">*</span></span>
                <input type="text" name="categories[__INDEX__][name]" required placeholder="e.g. 10 km" class="ce-input">
            </label>

            <label class="ce-field">
                <span>Local price (MMK) <span class="ce-req" aria-hidden="true">*</span></span>
                <input type="number" step="0.01" name="categories[__INDEX__][local_price]" required placeholder="0" class="ce-input">
            </label>

            <label class="ce-field">
                <span>Foreign price</span>
                <input type="number" step="0.01" name="categories[__INDEX__][foreign_price]" placeholder="Optional" class="ce-input">
            </label>

            <label class="ce-field">
                <span>Capacity</span>
                <input type="number" min="1" name="categories[__INDEX__][capacity]" placeholder="Unlimited" class="ce-input">
            </label>
        </div>

        <div class="ce-bib-fields" hidden>
            <div class="ce-grid-4">
                <label class="ce-field">
                    <span>BIB prefix</span>
                    <input type="text" maxlength="3" name="categories[__INDEX__][bib_prefix]" placeholder="e.g. 10K" class="ce-input ce-upper">
                </label>

                <label class="ce-field">
                    <span>BIB start number</span>
                    <input type="number" min="1" value="1" name="categories[__INDEX__][bib_start_number]" class="ce-input">
                </label>
            </div>
        </div>
    </div>
</template>

<template id="groupBuyTemplate">
    <div class="ce-category ce-groupbuy">
        <div class="ce-category-head">
            <strong class="ce-gb-title">Group buy deal</strong>
            <button type="button" class="ce-icon-btn ce-remove-groupbuy" aria-label="Remove this group buy deal">
                <i class="fa-solid fa-trash" aria-hidden="true"></i>
            </button>
        </div>

        <div class="ce-grid-4">
            <label class="ce-field ce-span-2">
                <span>Deal name</span>
                <input type="text" name="group_buys[__INDEX__][name]" placeholder="e.g. Squad of 5" class="ce-input">
            </label>

            <label class="ce-field">
                <span>Tickets required <span class="ce-req" aria-hidden="true">*</span></span>
                <input type="number" min="2" max="100" step="1" name="group_buys[__INDEX__][quantity]" required placeholder="5" class="ce-input">
            </label>

            <label class="ce-field">
                <span>Discount (MMK) <span class="ce-req" aria-hidden="true">*</span></span>
                <input type="number" min="0.01" step="0.01" name="group_buys[__INDEX__][discount_amount]" required placeholder="20" class="ce-input">
            </label>
        </div>

        <p class="ce-gb-preview" hidden></p>

        <fieldset class="ce-radio-group ce-gb-scope">
            <legend>Which tickets can be bought together?</legend>

            <label class="ce-radio">
                <input type="radio" name="group_buys[__INDEX__][ticket_scope]" value="same_category" checked>
                <span>
                    <strong>Same ticket type only</strong>
                    <small>Every ticket in the group must be the same type</small>
                </span>
            </label>

            <label class="ce-radio">
                <input type="radio" name="group_buys[__INDEX__][ticket_scope]" value="selected_categories">
                <span>
                    <strong>Selected ticket types</strong>
                    <small>Choose which types can be mixed, like VIP and Regular</small>
                </span>
            </label>
        </fieldset>

        <div class="ce-gb-categories" hidden>
            <span class="ce-label">Ticket types allowed in this deal</span>
            <div class="ce-toggle-grid ce-gb-cat-list"></div>
            <p class="ce-help">Tick at least one ticket type. Add ticket types in the Tickets section above.</p>
        </div>

        <div class="ce-gb-footer">
            <label class="ce-switch">
                <input type="hidden" name="group_buys[__INDEX__][is_active]" value="0">
                <input type="checkbox" name="group_buys[__INDEX__][is_active]" value="1" checked>
                <span class="ce-switch-ui" aria-hidden="true"></span>
                <span>Deal is active</span>
            </label>
        </div>
    </div>
</template>

<template id="itemTemplate">
    <div class="ce-item">
        <label class="ce-field">
            <span>Item name</span>
            <input type="text" name="items[__INDEX__][title]" placeholder="e.g. Finisher medal" class="ce-input">
        </label>

        <div class="ce-field">
            <span class="ce-label">Item image</span>
            <label class="ce-file">
                <input type="file" name="items[__INDEX__][image]" accept="image/jpeg,image/png,image/jpg,image/gif" class="ce-file-input">
                <span class="ce-file-btn"><i class="fa-solid fa-image" aria-hidden="true"></i> Choose image</span>
                <span class="ce-file-name">No image chosen</span>
            </label>
        </div>

        <button type="button" class="ce-icon-btn ce-remove-item" aria-label="Remove this item">
            <i class="fa-solid fa-trash" aria-hidden="true"></i>
        </button>
    </div>
</template>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $ = id => document.getElementById(id);

    const form = $('eventForm');
    let dirty = false;
    let submitting = false;

    form.addEventListener('input', () => { dirty = true; });
    form.addEventListener('change', () => { dirty = true; });

    window.addEventListener('beforeunload', function (event) {
        if (dirty && !submitting) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    form.addEventListener('submit', function () {
        submitting = true;

        const button = $('publishBtn');
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Publishing...';
    });

    /* ---------------------------------------------------------
       Uppercase BIB prefixes
    --------------------------------------------------------- */
    document.addEventListener('input', function (event) {
        if (event.target.classList && event.target.classList.contains('ce-upper')) {
            event.target.value = event.target.value.toUpperCase();
        }
    });

    /* ---------------------------------------------------------
       File pickers (show chosen file name)
    --------------------------------------------------------- */
    document.addEventListener('change', function (event) {
        const input = event.target;
        if (!input.classList || !input.classList.contains('ce-file-input')) return;

        const label = input.closest('.ce-file');
        const name = label ? label.querySelector('.ce-file-name') : null;
        if (!name) return;

        const file = input.files && input.files[0];
        const empty = input.accept.indexOf('pdf') !== -1 ? 'No file chosen' : 'No image chosen';

        name.textContent = file ? file.name : empty;
        name.classList.toggle('has-file', !!file);
    });

    /* ---------------------------------------------------------
       Banner preview
    --------------------------------------------------------- */
    const bannerInput = $('bannerInput');
    const bannerPreview = $('bannerPreview');
    const bannerImg = $('bannerPreviewImg');
    let bannerUrl = null;

    function clearBanner() {
        if (bannerUrl) URL.revokeObjectURL(bannerUrl);
        bannerUrl = null;
        bannerInput.value = '';
        bannerImg.removeAttribute('src');
        bannerPreview.hidden = true;

        const name = bannerInput.closest('.ce-file').querySelector('.ce-file-name');
        name.textContent = 'No image chosen';
        name.classList.remove('has-file');
    }

    bannerInput.addEventListener('change', function () {
        const file = bannerInput.files[0];

        if (!file || file.type.indexOf('image/') !== 0) {
            clearBanner();
            return;
        }

        if (bannerUrl) URL.revokeObjectURL(bannerUrl);
        bannerUrl = URL.createObjectURL(file);
        bannerImg.src = bannerUrl;
        bannerPreview.hidden = false;
    });

    $('bannerRemoveBtn').addEventListener('click', clearBanner);

    /* ---------------------------------------------------------
       T-shirt sizes
    --------------------------------------------------------- */
    const tshirtToggle = $('field_toggle_tshirt_size');
    const tshirtBox = $('tshirt_sizes_config_box');
    const tshirtGrid = $('tshirt_sizes_grid');
    const customInput = $('custom_tshirt_input');
    const addCustomBtn = $('add_custom_tshirt_btn');

    if (tshirtToggle && tshirtBox) {
        tshirtToggle.addEventListener('change', function () {
            tshirtBox.hidden = !this.checked;
        });
    }

    function addCustomSize() {
        const value = customInput.value.trim().toUpperCase();
        if (!value) return;

        const existing = Array.prototype.find.call(
            tshirtGrid.querySelectorAll('input[type="checkbox"]'),
            input => input.value.toUpperCase() === value
        );

        if (existing) {
            existing.checked = true;
            customInput.value = '';
            return;
        }

        const label = document.createElement('label');
        label.className = 'ce-check';

        const input = document.createElement('input');
        input.type = 'checkbox';
        input.name = 'tshirt_sizes[]';
        input.value = value;
        input.checked = true;

        const text = document.createElement('span');
        text.textContent = value;

        label.append(input, text);
        tshirtGrid.appendChild(label);
        customInput.value = '';
        dirty = true;
    }

    addCustomBtn.addEventListener('click', addCustomSize);
    customInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addCustomSize();
        }
    });

    /* ---------------------------------------------------------
       BIB settings
    --------------------------------------------------------- */
    const enableBib = $('enable_bib_number');
    const bibWrapper = $('bib_config_wrapper');
    const sharedBox = $('shared_prefix_box');
    const bibRadios = document.querySelectorAll('input[name="share_bib_prefix"]');

    function separateMode() {
        const checked = document.querySelector('input[name="share_bib_prefix"]:checked');
        return enableBib.checked && checked && checked.value === '0';
    }

    function applyBibState() {
        bibWrapper.hidden = !enableBib.checked;

        const checked = document.querySelector('input[name="share_bib_prefix"]:checked');
        sharedBox.hidden = !!(checked && checked.value === '0');

        const separate = separateMode();
        document.querySelectorAll('.ce-bib-fields').forEach(function (box) {
            box.hidden = !separate;
        });
    }

    enableBib.addEventListener('change', applyBibState);
    bibRadios.forEach(radio => radio.addEventListener('change', applyBibState));

    /* ---------------------------------------------------------
       Ticket types
    --------------------------------------------------------- */
    const categoriesContainer = $('categoriesContainer');
    const categoryTemplate = $('categoryTemplate');
    let categoryIndex = 0;

    // GROUP BUY: set to true once the group buy section is ready.
    let groupBuysReady = false;

    // GROUP BUY: maps a category key from old input ("new-3") to its key on this page.
    const categoryKeyMap = {};

    function fill(row, key, value) {
        const input = row.querySelector('[name$="[' + key + ']"]');
        if (input && value !== undefined && value !== null) input.value = value;
    }

    function refreshCategories() {
        const rows = categoriesContainer.querySelectorAll('.ce-category');

        rows.forEach(function (row, position) {
            row.querySelector('.ce-category-title').textContent = 'Ticket type ' + (position + 1);
            row.querySelector('.ce-remove-category').disabled = rows.length === 1;
        });

        applyBibState();
        refreshGroupBuyOptions();
    }

    function addCategory(values, originalKey) {
        const holder = document.createElement('div');
        holder.innerHTML = categoryTemplate.innerHTML.replace(/__INDEX__/g, categoryIndex).trim();

        const row = holder.firstElementChild;

        if (values) {
            fill(row, 'name', values.name);
            fill(row, 'local_price', values.local_price);
            fill(row, 'foreign_price', values.foreign_price);
            fill(row, 'capacity', values.capacity);
            fill(row, 'bib_prefix', values.bib_prefix);
            fill(row, 'bib_start_number', values.bib_start_number);
        }

        // GROUP BUY: remember this row's form index so deals can point at it.
        row.dataset.catIndex = categoryIndex;

        if (originalKey !== undefined && originalKey !== null) {
            categoryKeyMap['new-' + originalKey] = 'new-' + categoryIndex;
        }

        categoryIndex++;
        categoriesContainer.appendChild(row);
        refreshCategories();

        return row;
    }

    $('addCategoryBtn').addEventListener('click', function () {
        const row = addCategory();
        row.querySelector('input').focus();
        dirty = true;
    });

    categoriesContainer.addEventListener('click', function (event) {
        const button = event.target.closest('.ce-remove-category');
        if (!button || button.disabled) return;

        button.closest('.ce-category').remove();
        refreshCategories();
        dirty = true;
    });

    const oldCategories = Object.values(@json(old('categories', [])) || {});
    const oldCategoryKeys = @json(array_keys((array) old('categories', [])));

    if (oldCategories.length) {
        oldCategories.forEach(function (values, position) {
            addCategory(values, oldCategoryKeys[position]);
        });
    } else {
        addCategory();
    }

    /* ---------------------------------------------------------
       Group buy deals
    --------------------------------------------------------- */
    const groupBuyContainer = $('groupBuyContainer');
    const groupBuyTemplate = $('groupBuyTemplate');
    const emptyGroupBuys = $('emptyGroupBuyMessage');
    let groupBuyIndex = 0;

    function money(value) {
        return Number(value).toLocaleString(undefined, { maximumFractionDigits: 2 });
    }

    // Ticket types currently on the page, each with a key the server understands.
    function categoryOptions() {
        return Array.prototype.map.call(
            categoriesContainer.querySelectorAll('.ce-category'),
            function (row, position) {
                const nameInput = row.querySelector('[name$="[name]"]');
                const priceInput = row.querySelector('[name$="[local_price]"]');
                const idInput = row.querySelector('.ce-id');

                const key = (idInput && !idInput.disabled && idInput.value)
                    ? String(idInput.value)
                    : 'new-' + row.dataset.catIndex;

                return {
                    key: key,
                    label: (nameInput && nameInput.value.trim()) || ('Ticket type ' + (position + 1)),
                    price: parseFloat(priceInput ? priceInput.value : '')
                };
            }
        );
    }

    function selectedKeys(row) {
        return new Set(
            Array.prototype.map.call(
                row.querySelectorAll('.ce-gb-cat-list input:checked'),
                input => input.value
            )
        );
    }

    function currentScope(row) {
        const checked = row.querySelector('input[type="radio"]:checked');
        return checked ? checked.value : 'same_category';
    }

    function renderGroupBuyOptions(row, selected) {
        const list = row.querySelector('.ce-gb-cat-list');
        const useSelected = currentScope(row) === 'selected_categories';

        list.replaceChildren();

        categoryOptions().forEach(function (option) {
            const label = document.createElement('label');
            label.className = 'ce-check';

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.name = 'group_buys[' + row.dataset.gbIndex + '][allowed_ticket_category_ids][]';
            input.value = option.key;
            input.checked = selected.has(option.key);
            input.disabled = !useSelected;

            const text = document.createElement('span');
            text.textContent = option.label;

            label.append(input, text);
            list.appendChild(label);
        });

        row.querySelector('.ce-gb-categories').hidden = !useSelected;
    }

    function updateGroupBuyPreview(row) {
        const preview = row.querySelector('.ce-gb-preview');
        const quantity = parseInt(row.querySelector('[name$="[quantity]"]').value, 10);
        const discount = parseFloat(row.querySelector('[name$="[discount_amount]"]').value);

        if (!(quantity >= 2) || !(discount > 0)) {
            preview.hidden = true;
            return;
        }

        let options = categoryOptions();

        if (currentScope(row) === 'selected_categories') {
            const selected = selectedKeys(row);
            options = options.filter(option => selected.has(option.key));
        }

        const prices = options
            .map(option => option.price)
            .filter(price => Number.isFinite(price) && price > 0);

        preview.classList.remove('is-warning');

        if (!prices.length) {
            preview.textContent = 'Runners save ' + money(discount) + ' MMK when they buy ' + quantity + ' tickets together.';
            preview.hidden = false;
            return;
        }

        const lowest = Math.min.apply(null, prices);
        const total = quantity * lowest;

        if (discount >= total) {
            preview.classList.add('is-warning');
            preview.textContent = 'The discount must be less than the group total (' + quantity + ' x ' + money(lowest) + ' = ' + money(total) + ' MMK).';
        } else {
            preview.textContent = 'Example at ' + money(lowest) + ' MMK per ticket: ' + quantity + ' x ' + money(lowest) +
                ' = ' + money(total) + ', minus ' + money(discount) + ' = ' + money(total - discount) + ' MMK.';
        }

        preview.hidden = false;
    }

    // Rebuild every deal's ticket type list (called when ticket types change).
    function refreshGroupBuyOptions() {
        if (!groupBuysReady) return;

        groupBuyContainer.querySelectorAll('.ce-groupbuy').forEach(function (row) {
            renderGroupBuyOptions(row, selectedKeys(row));
            updateGroupBuyPreview(row);
        });
    }

    function refreshGroupBuys() {
        const rows = groupBuyContainer.querySelectorAll('.ce-groupbuy');

        rows.forEach(function (row, position) {
            row.querySelector('.ce-gb-title').textContent = 'Group buy deal ' + (position + 1);
        });

        emptyGroupBuys.hidden = rows.length > 0;
    }

    function addGroupBuy(values) {
        const holder = document.createElement('div');
        holder.innerHTML = groupBuyTemplate.innerHTML.replace(/__INDEX__/g, groupBuyIndex).trim();

        const row = holder.firstElementChild;
        row.dataset.gbIndex = groupBuyIndex;

        let selected = new Set();

        if (values) {
            fill(row, 'name', values.name);
            fill(row, 'quantity', values.quantity);
            fill(row, 'discount_amount', values.discount_amount);

            const scopeRadio = row.querySelector('input[type="radio"][value="' + values.ticket_scope + '"]');
            if (scopeRadio) scopeRadio.checked = true;

            row.querySelector('input[type="checkbox"][name$="[is_active]"]').checked = values.is_active !== false;

            selected = new Set((values.allowed || []).map(key => categoryKeyMap[key] || key));
        }

        groupBuyIndex++;
        groupBuyContainer.appendChild(row);

        renderGroupBuyOptions(row, selected);
        updateGroupBuyPreview(row);
        refreshGroupBuys();

        return row;
    }

    $('addGroupBuyBtn').addEventListener('click', function () {
        const row = addGroupBuy();
        row.querySelector('input[type="text"]').focus();
        dirty = true;
    });

    groupBuyContainer.addEventListener('click', function (event) {
        const button = event.target.closest('.ce-remove-groupbuy');
        if (!button) return;

        button.closest('.ce-groupbuy').remove();
        refreshGroupBuys();
        dirty = true;
    });

    groupBuyContainer.addEventListener('input', function (event) {
        const row = event.target.closest('.ce-groupbuy');
        if (row) updateGroupBuyPreview(row);
    });

    groupBuyContainer.addEventListener('change', function (event) {
        const row = event.target.closest('.ce-groupbuy');
        if (!row) return;

        if (event.target.type === 'radio') {
            renderGroupBuyOptions(row, selectedKeys(row));
        }

        updateGroupBuyPreview(row);
    });

    // Keep deal lists in step when ticket names or prices change.
    categoriesContainer.addEventListener('input', function (event) {
        if (event.target.matches('[name$="[name]"], [name$="[local_price]"]')) {
            refreshGroupBuyOptions();
        }
    });

    groupBuysReady = true;

    Object.values(@json($groupBuysData) || {}).forEach(function (values) {
        addGroupBuy(values);
    });

    refreshGroupBuys();

    /* ---------------------------------------------------------
       Included items
    --------------------------------------------------------- */
    const itemsContainer = $('itemsContainer');
    const itemTemplate = $('itemTemplate');
    const emptyItems = $('emptyItemsMessage');
    let itemIndex = 0;

    function refreshItems() {
        emptyItems.hidden = itemsContainer.querySelectorAll('.ce-item').length > 0;
    }

    function addItem(values) {
        const holder = document.createElement('div');
        holder.innerHTML = itemTemplate.innerHTML.replace(/__INDEX__/g, itemIndex).trim();

        const row = holder.firstElementChild;

        if (values && values.title) {
            row.querySelector('[name$="[title]"]').value = values.title;
        }

        itemIndex++;
        itemsContainer.appendChild(row);
        refreshItems();

        return row;
    }

    $('addItemBtn').addEventListener('click', function () {
        const row = addItem();
        row.querySelector('input[type="text"]').focus();
        dirty = true;
    });

    itemsContainer.addEventListener('click', function (event) {
        const button = event.target.closest('.ce-remove-item');
        if (!button) return;

        button.closest('.ce-item').remove();
        refreshItems();
        dirty = true;
    });

    const oldItems = Object.values(@json(old('items', [])) || {});
    oldItems.forEach(addItem);
    refreshItems();

    /* ---------------------------------------------------------
       Section nav (scroll spy)
    --------------------------------------------------------- */
    const navLinks = Array.prototype.slice.call(document.querySelectorAll('.ce-nav a'));

    navLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;

            event.preventDefault();

            if (target.tagName === 'DETAILS') target.open = true;

            const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
        });
    });

    if ('IntersectionObserver' in window && navLinks.length) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                navLinks.forEach(function (link) {
                    link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id);
                });
            });
        }, { rootMargin: '-15% 0px -70% 0px' });

        navLinks.forEach(function (link) {
            const section = document.querySelector(link.getAttribute('href'));
            if (section) observer.observe(section);
        });
    }

    /* ---------------------------------------------------------
       Location picker (OpenStreetMap)
    --------------------------------------------------------- */
    const mapElement = $('event-location-map');
    const locationInput = $('location');
    const searchButton = $('locationSearchBtn');
    const resultsBox = $('locationResults');
    const statusLine = $('locationStatus');
    const latInput = $('latitude');
    const lngInput = $('longitude');
    const coordsBox = $('coordsBox');
    const coordsText = $('coordsText');

    function setStatus(message, type, icon) {
        statusLine.className = 'ce-status' + (type ? ' is-' + type : '');
        statusLine.replaceChildren();

        if (!message) return;

        if (icon) {
            const i = document.createElement('i');
            i.className = 'fa-solid ' + icon;
            i.setAttribute('aria-hidden', 'true');
            statusLine.appendChild(i);
        }

        statusLine.appendChild(document.createTextNode(message));
    }

    if (typeof L === 'undefined') {
        setStatus('The map could not load. You can still type the location and publish.', 'error', 'fa-circle-exclamation');
        mapElement.hidden = true;
        searchButton.disabled = true;
        return;
    }

    const oldLat = parseFloat(@json(old('latitude')));
    const oldLng = parseFloat(@json(old('longitude')));
    const hasOldPin = Number.isFinite(oldLat) && Number.isFinite(oldLng);

    const map = L.map('event-location-map').setView(
        hasOldPin ? [oldLat, oldLng] : [16.8409, 96.1735],
        hasOldPin ? 16 : 12
    );

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker(map.getCenter(), { draggable: true }).addTo(map);

    function saveCoordinates(lat, lng) {
        latInput.value = Number(lat).toFixed(7);
        lngInput.value = Number(lng).toFixed(7);

        coordsText.textContent = 'Pin saved at ' + Number(lat).toFixed(5) + ', ' + Number(lng).toFixed(5);
        coordsBox.hidden = false;
    }

    function clearCoordinates() {
        latInput.value = '';
        lngInput.value = '';
        coordsBox.hidden = true;
    }

    function placePin(lat, lng, zoom) {
        marker.setLatLng([lat, lng]);
        saveCoordinates(lat, lng);

        if (zoom) {
            map.setView([lat, lng], zoom, { animate: true });
        }
    }

    if (hasOldPin) {
        placePin(oldLat, oldLng, 16);
    }

    map.on('click', function (event) {
        placePin(event.latlng.lat, event.latlng.lng);
        setStatus('Pin moved. Coordinates saved.', 'success', 'fa-circle-check');
    });

    marker.on('dragend', function () {
        const position = marker.getLatLng();
        saveCoordinates(position.lat, position.lng);
        setStatus('Pin moved. Coordinates saved.', 'success', 'fa-circle-check');
    });

    function renderResults(results) {
        resultsBox.replaceChildren();

        results.forEach(function (result, index) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'ce-result' + (index === 0 ? ' is-best' : '');

            const title = document.createElement('strong');
            title.textContent = result.name || (result.display_name || '').split(',')[0] || 'Location';

            if (index === 0) {
                const badge = document.createElement('em');
                badge.textContent = 'Best match';
                title.appendChild(badge);
            }

            const address = document.createElement('span');
            address.textContent = result.display_name || '';

            button.append(title, address);

            button.addEventListener('click', function () {
                locationInput.value = result.display_name || result.name || locationInput.value;
                placePin(parseFloat(result.lat), parseFloat(result.lon), 16);
                resultsBox.hidden = true;
                setStatus('Location selected and pin saved.', 'success', 'fa-circle-check');
                dirty = true;
            });

            resultsBox.appendChild(button);
        });

        resultsBox.hidden = results.length === 0;
    }

    let searchTimer = null;
    let controller = null;

    async function searchLocation(automatic) {
        const query = locationInput.value.trim();

        if (query.length < 3) {
            if (!automatic) setStatus('Type at least 3 characters.', '', 'fa-circle-info');
            return;
        }

        if (controller) controller.abort();
        controller = new AbortController();

        if (!automatic) {
            searchButton.disabled = true;
            searchButton.textContent = 'Searching...';
        }

        setStatus('Finding location...', 'loading', 'fa-spinner fa-spin');

        try {
            const response = await fetch(
                'https://nominatim.openstreetmap.org/search?' + new URLSearchParams({
                    format: 'jsonv2',
                    q: query,
                    limit: 5,
                    addressdetails: 1
                }),
                { headers: { 'Accept': 'application/json' }, signal: controller.signal }
            );

            if (!response.ok) throw new Error('Location search failed.');

            const results = await response.json();

            if (!results.length) {
                resultsBox.hidden = true;
                setStatus('Location not found. Try adding the city or country.', 'error', 'fa-circle-exclamation');
                return;
            }

            placePin(parseFloat(results[0].lat), parseFloat(results[0].lon), 16);
            renderResults(results);
            setStatus('Pin placed at the best match. Pick another suggestion if it is wrong.', 'success', 'fa-circle-check');

        } catch (error) {
            if (error.name === 'AbortError') return;

            resultsBox.hidden = true;
            setStatus('Unable to search right now. Please try again.', 'error', 'fa-circle-exclamation');

        } finally {
            if (!automatic) {
                searchButton.disabled = false;
                searchButton.textContent = 'Find on map';
            }
        }
    }

    locationInput.addEventListener('input', function () {
        clearTimeout(searchTimer);

        if (locationInput.value.trim().length < 3) {
            resultsBox.hidden = true;
            setStatus('', '');
            return;
        }

        // The typed place changed, so the old pin no longer applies.
        clearCoordinates();

        searchTimer = setTimeout(function () { searchLocation(true); }, 800);
    });

    locationInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(searchTimer);
            searchLocation(false);
        }
    });

    searchButton.addEventListener('click', function () {
        clearTimeout(searchTimer);
        searchLocation(false);
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('#locationResults') && !event.target.closest('.ce-search')) {
            resultsBox.hidden = true;
        }
    });

    // Re-shown after an error with text but no pin: find it again.
    if (!hasOldPin && locationInput.value.trim().length >= 3) {
        setTimeout(function () { searchLocation(true); }, 500);
    }

    setTimeout(function () { map.invalidateSize(); }, 300);

    applyBibState();
});
</script>

@endsection