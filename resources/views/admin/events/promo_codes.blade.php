@extends('layouts.admin')

@section('title', 'Manage Promo Codes - ' . $event->title)
@section('page-title', 'Promo Codes')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS — PROMO CODES (redesigned)
       Classes are prefixed "pc-" so they never clash with the layout
    ========================================================== */
    .pc-page {
        --pc-primary: #7c3aed;
        --pc-primary-soft: #f5f3ff;
        --pc-pink: #ec4899;
        --pc-text: #1e293b;
        --pc-muted: #64748b;
        --pc-faint: #94a3b8;
        --pc-border: #e8eaf0;
        --pc-bg: #f8f9fc;
        color: var(--pc-text);
        font-size: 14px;
        padding-bottom: 30px;
    }
    .pc-page * { box-sizing: border-box; }

    /* ---------- Header ---------- */
    .pc-head { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
    .pc-head h2 { margin: 0; font-size: 1.4rem; font-weight: 800; letter-spacing: -.02em; }
    .pc-head p  { margin: 4px 0 0; color: var(--pc-muted); font-size: .85rem; }
    .pc-head p strong { color: var(--pc-text); }
    .pc-back {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 15px; border: 1px solid var(--pc-border);
        border-radius: 11px; background: #fff; color: var(--pc-muted); font-size: .82rem; font-weight: 700;
        text-decoration: none; transition: all .15s;
    }
    .pc-back:hover { border-color: #c4b5fd; color: var(--pc-primary); background: var(--pc-primary-soft); }

    /* ---------- Stats ---------- */
    .pc-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .pc-stat { display: flex; align-items: center; gap: 14px; padding: 16px 18px; background: #fff; border: 1px solid var(--pc-border); border-radius: 16px; }
    .pc-stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: var(--pc-primary-soft); color: var(--pc-primary); }
    .pc-stat-icon.green { background: #ecfdf5; color: #059669; }
    .pc-stat-icon.red   { background: #fff1f2; color: #e11d48; }
    .pc-stat-icon.pink  { background: #fdf2f8; color: #db2777; }
    .pc-stat-num { font-size: 1.35rem; font-weight: 800; line-height: 1.1; }
    .pc-stat-label { color: var(--pc-muted); font-size: .75rem; margin-top: 2px; }

    /* ---------- Alerts ---------- */
    .pc-alert { display: flex; gap: 10px; margin-bottom: 16px; padding: 13px 16px; border-radius: 12px; font-size: .85rem; font-weight: 600; background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }
    .pc-alert ul { margin: 0; padding-left: 18px; }
    .pc-alert i { margin-top: 3px; }

    /* ---------- Panel ---------- */
    .pc-panel { background: #fff; border: 1px solid var(--pc-border); border-radius: 18px; overflow: hidden; margin-bottom: 22px; box-shadow: 0 10px 35px rgba(15,23,42,.045); }
    .pc-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 22px; border-bottom: 1px solid var(--pc-border); }
    .pc-panel-title { display: flex; align-items: center; gap: 12px; }
    .pc-panel-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #ede9fe, #fce7f3); color: var(--pc-primary); }
    .pc-panel-title h3 { margin: 0; font-size: 1rem; font-weight: 800; }
    .pc-panel-title p  { margin: 2px 0 0; color: var(--pc-faint); font-size: .78rem; }
    .pc-panel-body { padding: 22px; }

    /* ---------- Form ---------- */
    .pc-section-label { margin: 0 0 12px; color: var(--pc-faint); font-size: .7rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .pc-section + .pc-section { margin-top: 24px; padding-top: 22px; border-top: 1px dashed var(--pc-border); }
    .pc-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; }
    .pc-field label { display: block; margin-bottom: 6px; font-size: .8rem; font-weight: 700; }
    .pc-field label small { color: var(--pc-faint); font-weight: 600; }
    .pc-hint { margin: 6px 0 0; color: var(--pc-faint); font-size: .74rem; line-height: 1.4; }
    .pc-input, .pc-select {
        width: 100%; height: 44px; padding: 0 13px; border: 1px solid #dfe3ea; border-radius: 11px; background: #fff;
        font-size: .88rem; color: var(--pc-text); outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .pc-input:focus, .pc-select:focus { border-color: #c4b5fd; box-shadow: 0 0 0 3px rgba(139,92,246,.14); }
    .pc-input.upper { text-transform: uppercase; font-family: ui-monospace, monospace; font-weight: 700; letter-spacing: .04em; }
    .pc-input:disabled { background: #f8fafc; color: var(--pc-faint); }
    .pc-field.dim { opacity: .55; }

    /* segmented toggle (discount type) */
    .pc-seg { display: inline-flex; width: 100%; padding: 4px; border: 1px solid #dfe3ea; border-radius: 12px; background: var(--pc-bg); }
    .pc-seg label { flex: 1; margin: 0; }
    .pc-seg input { position: absolute; opacity: 0; pointer-events: none; }
    .pc-seg span { display: block; padding: 8px 10px; border-radius: 9px; text-align: center; color: var(--pc-muted); font-size: .8rem; font-weight: 700; cursor: pointer; transition: all .15s; }
    .pc-seg input:checked + span { background: #fff; color: var(--pc-primary); box-shadow: 0 2px 8px rgba(15,23,42,.1); }
    .pc-seg input:focus-visible + span { outline: 2px solid #c4b5fd; }

    /* input with suffix */
    .pc-suffix { position: relative; }
    .pc-suffix .pc-input { padding-right: 58px; }
    .pc-suffix em { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: var(--pc-faint); font-size: .78rem; font-style: normal; font-weight: 700; }

    /* scope cards */
    .pc-scope { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; max-width: 640px; }
    .pc-scope label { margin: 0; cursor: pointer; }
    .pc-scope input { position: absolute; opacity: 0; pointer-events: none; }
    .pc-scope-card { display: flex; gap: 12px; align-items: flex-start; padding: 14px; border: 1.5px solid var(--pc-border); border-radius: 13px; background: #fff; transition: all .15s; height: 100%; }
    .pc-scope-card i { margin-top: 2px; width: 34px; height: 34px; flex-shrink: 0; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--pc-bg); color: var(--pc-muted); }
    .pc-scope-card strong { display: block; font-size: .85rem; }
    .pc-scope-card small { display: block; margin-top: 2px; color: var(--pc-faint); font-size: .74rem; line-height: 1.35; }
    .pc-scope input:checked + .pc-scope-card { border-color: var(--pc-primary); background: var(--pc-primary-soft); box-shadow: 0 0 0 3px rgba(139,92,246,.1); }
    .pc-scope input:checked + .pc-scope-card i { background: var(--pc-primary); color: #fff; }
    .pc-scope input:focus-visible + .pc-scope-card { outline: 2px solid #c4b5fd; }
    #pc-category-wrap { display: none; max-width: 640px; margin-top: 14px; }

    /* live preview */
    .pc-preview { display: flex; align-items: center; gap: 12px; margin-top: 24px; padding: 14px 16px; border-radius: 13px; background: linear-gradient(135deg, #faf5ff, #fdf2f8); border: 1px solid #f0e4ff; color: #6b21a8; font-size: .85rem; font-weight: 600; line-height: 1.45; }
    .pc-preview i { font-size: 1.05rem; }
    .pc-preview strong { color: #4c1d95; }

    .pc-submit-row { display: flex; align-items: center; gap: 12px; margin-top: 18px; flex-wrap: wrap; }
    .pc-btn-primary {
        display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border: 0; border-radius: 12px;
        background: linear-gradient(135deg, var(--pc-pink), var(--pc-primary)); color: #fff; font-size: .88rem; font-weight: 700;
        cursor: pointer; box-shadow: 0 8px 20px rgba(124,58,237,.25); transition: transform .15s, box-shadow .15s;
    }
    .pc-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 26px rgba(124,58,237,.32); }
    .pc-btn-primary:disabled { opacity: .6; cursor: wait; transform: none; }

    /* ---------- List toolbar ---------- */
    .pc-search { position: relative; flex: 1 1 240px; max-width: 320px; }
    .pc-search i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--pc-faint); font-size: .85rem; }
    .pc-search input { width: 100%; padding: 10px 14px 10px 36px; border: 1px solid var(--pc-border); border-radius: 11px; background: var(--pc-bg); font-size: .85rem; outline: none; transition: all .15s; }
    .pc-search input:focus { border-color: #c4b5fd; background: #fff; box-shadow: 0 0 0 3px rgba(139,92,246,.12); }
    .pc-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .pc-tab { display: inline-flex; align-items: center; gap: 7px; padding: 8px 13px; border: 1px solid var(--pc-border); border-radius: 999px; background: #fff; color: var(--pc-muted); font-size: .8rem; font-weight: 600; cursor: pointer; transition: all .15s; }
    .pc-tab:hover { border-color: #c4b5fd; color: var(--pc-primary); }
    .pc-tab.active { background: var(--pc-primary); border-color: var(--pc-primary); color: #fff; }
    .pc-tab .n { min-width: 20px; padding: 1px 6px; border-radius: 999px; background: rgba(100,116,139,.12); font-size: .7rem; text-align: center; }
    .pc-tab.active .n { background: rgba(255,255,255,.25); }
    .pc-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 16px 22px; border-bottom: 1px solid var(--pc-border); }
    .pc-btn-plain {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 13px; border: 1px solid var(--pc-border); border-radius: 10px;
        background: #fff; color: var(--pc-muted); font-size: .8rem; font-weight: 700; cursor: pointer; transition: all .15s; white-space: nowrap;
    }
    .pc-btn-plain:hover { border-color: #c4b5fd; color: var(--pc-primary); background: var(--pc-primary-soft); }

    /* ---------- Table ---------- */
    .pc-scroll { width: 100%; overflow-x: auto; }
    .pc-table { width: 100%; min-width: 860px; border-collapse: collapse; }
    .pc-table th { padding: 12px 20px; background: #fafbfd; border-bottom: 1px solid var(--pc-border); color: var(--pc-faint); font-size: .7rem; font-weight: 800; letter-spacing: .07em; text-align: left; text-transform: uppercase; white-space: nowrap; }
    .pc-table th.r, .pc-table td.r { text-align: right; }
    .pc-table td { padding: 14px 20px; border-bottom: 1px solid #f0f1f5; vertical-align: middle; font-size: .85rem; color: var(--pc-muted); }
    .pc-table tbody tr { transition: background .15s; }
    .pc-table tbody tr:hover { background: #fbfaff; }
    .pc-table tbody tr:last-child td { border-bottom: 0; }

    .pc-code { display: inline-flex; align-items: center; gap: 8px; }
    .pc-code code { padding: 5px 10px; border: 1px solid #ddd6fe; border-radius: 8px; background: var(--pc-primary-soft); color: #5b21b6; font-family: ui-monospace, monospace; font-size: .85rem; font-weight: 800; letter-spacing: .03em; }
    .pc-copy { width: 30px; height: 30px; border: 1px solid var(--pc-border); border-radius: 8px; background: #fff; color: var(--pc-faint); cursor: pointer; transition: all .15s; }
    .pc-copy:hover { color: var(--pc-primary); border-color: #c4b5fd; }
    .pc-copy.done { color: #059669; border-color: #bbf7d0; background: #f0fdf4; }
    .pc-strong { color: var(--pc-text); font-weight: 700; }
    .pc-discount { color: var(--pc-text); font-weight: 800; white-space: nowrap; }

    .pc-chip { display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: .75rem; font-weight: 700; white-space: nowrap; }
    .pc-chip.cat { background: #eef2ff; color: #4f46e5; }
    .pc-chip.all { background: #f1f5f9; color: #475569; }

    .pc-usage { min-width: 120px; }
    .pc-usage-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: .78rem; }
    .pc-usage-row strong { color: var(--pc-text); font-weight: 800; }
    .pc-bar { height: 5px; border-radius: 999px; background: #eef0f6; overflow: hidden; }
    .pc-bar > div { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--pc-primary), var(--pc-pink)); }
    .pc-bar > div.full { background: #f43f5e; }

    .pc-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; font-size: .72rem; font-weight: 800; white-space: nowrap; }
    .pc-badge i { width: 6px; height: 6px; border-radius: 50%; display: block; }
    .pc-badge.available { background: #ecfdf5; color: #059669; }
    .pc-badge.available i { background: #10b981; }
    .pc-badge.used { background: #fff1f2; color: #e11d48; }
    .pc-badge.used i { background: #f43f5e; }

    .pc-del { width: 34px; height: 34px; border: 1px solid var(--pc-border); border-radius: 9px; background: #fff; color: #e11d48; cursor: pointer; transition: all .15s; }
    .pc-del:hover { background: #fff1f2; border-color: #fecdd3; }

    .pc-empty { padding: 56px 20px !important; text-align: center; color: var(--pc-faint) !important; }
    .pc-empty i { display: block; margin-bottom: 10px; font-size: 1.6rem; color: #c4b5fd; }
    .pc-empty a { color: var(--pc-primary); font-weight: 700; text-decoration: none; }
    #pc-no-results { display: none; }
    .pc-footer { padding: 12px 22px; border-top: 1px solid var(--pc-border); background: #fafbfd; color: var(--pc-faint); font-size: .78rem; }

    /* ---------- Delete modal ---------- */
    .pc-modal { position: fixed; inset: 0; z-index: 1000; display: none; align-items: center; justify-content: center; padding: 20px; background: rgba(15,23,42,.55); backdrop-filter: blur(3px); }
    .pc-modal.open { display: flex; }
    .pc-modal-box { width: 100%; max-width: 420px; padding: 26px; border-radius: 20px; background: #fff; box-shadow: 0 30px 70px rgba(15,23,42,.3); }
    .pc-modal-icon { width: 50px; height: 50px; margin-bottom: 14px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: #fff1f2; color: #e11d48; font-size: 1.15rem; }
    .pc-modal h3 { margin: 0 0 6px; font-size: 1.1rem; font-weight: 800; }
    .pc-modal p  { margin: 0; color: var(--pc-muted); font-size: .85rem; line-height: 1.55; }
    .pc-modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 22px; }
    .pc-btn-danger { padding: 10px 16px; border: 0; border-radius: 10px; background: #e11d48; color: #fff; font-size: .82rem; font-weight: 700; cursor: pointer; }
    .pc-btn-danger:hover { background: #be123c; }

    /* ---------- Responsive ---------- */
    @media (max-width: 1000px) { .pc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 720px) {
        .pc-scope { grid-template-columns: 1fr; }
        .pc-panel-body { padding: 16px; }
        .pc-table-wrap .pc-table, .pc-table-wrap .pc-table tbody, .pc-table-wrap .pc-table tr, .pc-table-wrap .pc-table td { display: block; width: 100%; min-width: 0; }
        .pc-table-wrap .pc-table thead { display: none; }
        .pc-table-wrap tr.pc-row { padding: 14px 16px; border-bottom: 1px solid var(--pc-border); }
        .pc-table-wrap td { padding: 6px 0 !important; border: 0 !important; }
        .pc-table-wrap td[data-label]::before { content: attr(data-label); display: block; margin-bottom: 4px; color: var(--pc-faint); font-size: .65rem; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
        .pc-table-wrap td.r { text-align: left; }
        .pc-search { max-width: none; }
    }
    @media (max-width: 480px) { .pc-stats { grid-template-columns: 1fr 1fr; gap: 10px; } .pc-stat { padding: 12px; gap: 10px; } }
</style>

@php
    $promos        = $event->promoCodes;
    $isUsedFn      = fn ($p) => $p->max_uses > 0 && $p->uses_count >= $p->max_uses;
    $usedCount     = $promos->filter($isUsedFn)->count();
    $availableCount = $promos->count() - $usedCount;
    $totalUses     = $promos->sum('uses_count');
    $oldScope      = old('promo_scope', 'event');
    $oldType       = old('discount_type', 'fixed');
@endphp

<div class="pc-page">

    {{-- ===== Header ===== --}}
    <div class="pc-head">
        <div>
            <h2>Manage Promo Codes</h2>
            <p>Partner and single-use codes for <strong>{{ $event->title }}</strong></p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="pc-back">
            <i class="fa-solid fa-arrow-left"></i> Back to Events
        </a>
    </div>

    {{-- ===== Stats ===== --}}
    <div class="pc-stats">
        <div class="pc-stat">
            <div class="pc-stat-icon"><i class="fa-solid fa-tags"></i></div>
            <div><div class="pc-stat-num">{{ $promos->count() }}</div><div class="pc-stat-label">Total codes</div></div>
        </div>
        <div class="pc-stat">
            <div class="pc-stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
            <div><div class="pc-stat-num">{{ $availableCount }}</div><div class="pc-stat-label">Available</div></div>
        </div>
        <div class="pc-stat">
            <div class="pc-stat-icon red"><i class="fa-solid fa-ban"></i></div>
            <div><div class="pc-stat-num">{{ $usedCount }}</div><div class="pc-stat-label">Redeemed</div></div>
        </div>
        <div class="pc-stat">
            <div class="pc-stat-icon pink"><i class="fa-solid fa-receipt"></i></div>
            <div><div class="pc-stat-num">{{ number_format($totalUses) }}</div><div class="pc-stat-label">Total redemptions</div></div>
        </div>
    </div>

    {{-- ===== Validation errors (success messages are shown as a toast by the layout) ===== --}}
    @if($errors->any())
        <div class="pc-alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =====================================================
         GENERATE FORM
    ====================================================== --}}
    <div class="pc-panel">
        <div class="pc-panel-head">
            <div class="pc-panel-title">
                <div class="pc-panel-icon"><i class="fa-solid fa-circle-plus"></i></div>
                <div>
                    <h3>Generate promo codes</h3>
                    <p>Create one code or a whole batch for a partner</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.events.promo_codes.store', $event) }}" method="POST" id="pc-form" class="pc-panel-body">
            @csrf

            {{-- 1. Who & code --}}
            <div class="pc-section">
                <p class="pc-section-label">1 · Code details</p>
                <div class="pc-grid">
                    <div class="pc-field">
                        <label for="company_name">Partner / company <small>(optional)</small></label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                               placeholder="e.g. KBZ Bank, Yoma" class="pc-input">
                    </div>

                    <div class="pc-field">
                        <label for="code">Code or prefix</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
                               placeholder="e.g. KBZ or RUN2026" class="pc-input upper" autocomplete="off">
                        <p class="pc-hint">Letters and numbers. Shown in uppercase.</p>
                    </div>

                    <div class="pc-field" id="pc-qty-field">
                        <label for="promo_quantity">How many codes?</label>
                        <input type="number" id="promo_quantity" name="promo_quantity" value="{{ old('promo_quantity', 1) }}" min="1" max="500" class="pc-input">
                        <p class="pc-hint">1 to 500. More than one creates a batch from your prefix.</p>
                    </div>

                    <div class="pc-field" id="pc-uses-field">
                        <label for="max_uses">Max uses per code</label>
                        <input type="number" id="max_uses" name="max_uses" value="{{ old('max_uses', 1) }}" min="1" class="pc-input">
                        <p class="pc-hint" id="pc-uses-hint">How many times this code can be redeemed.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Discount --}}
            <div class="pc-section">
                <p class="pc-section-label">2 · Discount</p>
                <div class="pc-grid">
                    <div class="pc-field">
                        <label>Discount type</label>
                        <div class="pc-seg" role="radiogroup" aria-label="Discount type">
                            <label>
                                <input type="radio" name="discount_type" value="fixed" {{ $oldType === 'fixed' ? 'checked' : '' }}>
                                <span>Fixed (MMK)</span>
                            </label>
                            <label>
                                <input type="radio" name="discount_type" value="percentage" {{ $oldType === 'percentage' ? 'checked' : '' }}>
                                <span>Percentage (%)</span>
                            </label>
                        </div>
                    </div>

                    <div class="pc-field">
                        <label for="discount_value">Discount value</label>
                        <div class="pc-suffix">
                            <input type="number" id="discount_value" name="discount_value" value="{{ old('discount_value') }}"
                                   step="0.01" min="0" required placeholder="5000" class="pc-input">
                            <em id="pc-unit">MMK</em>
                        </div>
                        <p class="pc-hint" id="pc-value-hint">Amount taken off the ticket price.</p>
                    </div>
                </div>
            </div>

            {{-- 3. Scope --}}
            <div class="pc-section">
                <p class="pc-section-label">3 · Where does it apply?</p>

                <div class="pc-scope" role="radiogroup" aria-label="Promo code scope">
                    <label>
                        <input type="radio" name="promo_scope" value="event" id="scope_event" {{ $oldScope === 'event' ? 'checked' : '' }}>
                        <div class="pc-scope-card">
                            <i class="fa-solid fa-calendar-check"></i>
                            <div><strong>Entire event</strong><small>Works on every ticket category</small></div>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="promo_scope" value="ticket" id="scope_ticket" {{ $oldScope === 'ticket' ? 'checked' : '' }}>
                        <div class="pc-scope-card">
                            <i class="fa-solid fa-ticket"></i>
                            <div><strong>One ticket category</strong><small>Only works on the category you pick</small></div>
                        </div>
                    </label>
                </div>

                <div id="pc-category-wrap" class="pc-field">
                    <label for="ticket_category_id">Ticket category</label>
                    <select name="ticket_category_id" id="ticket_category_id" class="pc-select">
                        <option value="">— Choose a ticket category —</option>
                        @foreach($event->ticketCategories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) old('ticket_category_id') === (string) $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Live summary --}}
            <div class="pc-preview" aria-live="polite">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span id="pc-preview-text">Fill in the form to see a summary.</span>
            </div>

            <div class="pc-submit-row">
                <button type="submit" class="pc-btn-primary" id="pc-submit">
                    <i class="fa-solid fa-bolt"></i> <span id="pc-submit-label">Generate codes</span>
                </button>
            </div>
        </form>
    </div>

    {{-- =====================================================
         EXISTING CODES
    ====================================================== --}}
    <div class="pc-panel">
        <div class="pc-panel-head">
            <div class="pc-panel-title">
                <div class="pc-panel-icon"><i class="fa-solid fa-list"></i></div>
                <div>
                    <h3>Existing codes</h3>
                    <p>{{ $promos->count() }} {{ Str::plural('code', $promos->count()) }} for this event</p>
                </div>
            </div>
            @if($promos->count() > 0)
                <button type="button" class="pc-btn-plain" id="pc-copy-visible">
                    <i class="fa-regular fa-copy"></i> <span>Copy visible codes</span>
                </button>
            @endif
        </div>

        @if($promos->count() > 0)
            <div class="pc-toolbar">
                <div class="pc-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="pc-search" placeholder="Search code or company…" autocomplete="off">
                </div>
                <div class="pc-tabs" id="pc-tabs">
                    <button type="button" class="pc-tab active" data-filter="all">All <span class="n">{{ $promos->count() }}</span></button>
                    <button type="button" class="pc-tab" data-filter="available">Available <span class="n">{{ $availableCount }}</span></button>
                    <button type="button" class="pc-tab" data-filter="used">Redeemed <span class="n">{{ $usedCount }}</span></button>
                </div>
            </div>
        @endif

        <div class="pc-scroll pc-table-wrap">
            <table class="pc-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Company</th>
                        <th>Applies to</th>
                        <th>Discount</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th class="r">Action</th>
                    </tr>
                </thead>
                <tbody id="pc-body">
                    @forelse($promos as $promo)
                        @php
                            $isUsed   = $isUsedFn($promo);
                            $usagePct = $promo->max_uses > 0 ? min(100, round($promo->uses_count / $promo->max_uses * 100)) : 0;
                            $discount = $promo->discount_type === 'percentage'
                                ? rtrim(rtrim(number_format($promo->discount_value, 2), '0'), '.') . '%'
                                : number_format($promo->discount_value) . ' MMK';
                        @endphp
                        <tr class="pc-row"
                            data-status="{{ $isUsed ? 'used' : 'available' }}"
                            data-code="{{ $promo->code }}"
                            data-search="{{ strtolower($promo->code . ' ' . ($promo->company_name ?? '')) }}">

                            <td data-label="Code">
                                <div class="pc-code">
                                    <code>{{ $promo->code }}</code>
                                    <button type="button" class="pc-copy" data-copy="{{ $promo->code }}" title="Copy code" aria-label="Copy code {{ $promo->code }}">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>

                            <td data-label="Company" class="pc-strong">{{ $promo->company_name ?: '—' }}</td>

                            <td data-label="Applies to">
                                @if($promo->ticket_category_id && $promo->ticketCategory)
                                    <span class="pc-chip cat"><i class="fa-solid fa-ticket" style="font-size:.65rem"></i> {{ $promo->ticketCategory->name }}</span>
                                @else
                                    <span class="pc-chip all">Entire event</span>
                                @endif
                            </td>

                            <td data-label="Discount" class="pc-discount">{{ $discount }}</td>

                            <td data-label="Usage">
                                <div class="pc-usage">
                                    <div class="pc-usage-row">
                                        <strong>{{ $promo->uses_count }}</strong>
                                        <span>of {{ $promo->max_uses > 0 ? $promo->max_uses : '∞' }}</span>
                                    </div>
                                    @if($promo->max_uses > 0)
                                        <div class="pc-bar"><div class="{{ $usagePct >= 100 ? 'full' : '' }}" style="width: {{ $usagePct }}%"></div></div>
                                    @endif
                                </div>
                            </td>

                            <td data-label="Status">
                                @if($isUsed)
                                    <span class="pc-badge used"><i></i> Redeemed</span>
                                @else
                                    <span class="pc-badge available"><i></i> Available</span>
                                @endif
                            </td>

                            <td data-label="Action" class="r">
                                <button type="button" class="pc-del" title="Delete code" aria-label="Delete code {{ $promo->code }}"
                                        data-action="{{ route('admin.promo_codes.destroy', $promo->id) }}"
                                        data-code="{{ $promo->code }}"
                                        onclick="openPromoDelete(this)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="pc-empty">
                                <i class="fa-solid fa-tags"></i>
                                No promo codes yet. Use the form above to
                                <a href="#code" onclick="document.getElementById('code').focus()">create the first one</a>.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="pc-no-results">
                        <td colspan="7" class="pc-empty">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            No codes match your search or filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($promos->count() > 0)
            <div class="pc-footer">
                Showing <strong id="pc-shown">{{ $promos->count() }}</strong> of {{ $promos->count() }} codes
            </div>
        @endif
    </div>
</div>

{{-- ===== Delete confirmation ===== --}}
<div class="pc-modal" id="pc-delete-modal" role="dialog" aria-modal="true" aria-labelledby="pc-delete-title">
    <div class="pc-modal-box">
        <div class="pc-modal-icon"><i class="fa-solid fa-trash"></i></div>
        <h3 id="pc-delete-title">Delete this code?</h3>
        <p>
            You are about to delete <strong id="pc-delete-name"></strong>.
            Anyone holding it will no longer be able to use it.
        </p>
        <form method="POST" id="pc-delete-form" action="">
            @csrf
            @method('DELETE')
            <div class="pc-modal-actions">
                <button type="button" class="pc-btn-plain" onclick="closePromoDelete()">Cancel</button>
                <button type="submit" class="pc-btn-danger"><i class="fa-solid fa-trash"></i> Delete code</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    /* ========== FORM BEHAVIOUR ========== */
    var form       = document.getElementById('pc-form');
    var codeInput  = document.getElementById('code');
    var qtyInput   = document.getElementById('promo_quantity');
    var usesInput  = document.getElementById('max_uses');
    var usesField  = document.getElementById('pc-uses-field');
    var usesHint   = document.getElementById('pc-uses-hint');
    var valueInput = document.getElementById('discount_value');
    var unit       = document.getElementById('pc-unit');
    var valueHint  = document.getElementById('pc-value-hint');
    var scopeEvent = document.getElementById('scope_event');
    var scopeTicket = document.getElementById('scope_ticket');
    var catWrap    = document.getElementById('pc-category-wrap');
    var catSelect  = document.getElementById('ticket_category_id');
    var preview    = document.getElementById('pc-preview-text');
    var submitBtn  = document.getElementById('pc-submit');
    var submitLbl  = document.getElementById('pc-submit-label');

    function typeValue() {
        var el = form.querySelector('input[name="discount_type"]:checked');
        return el ? el.value : 'fixed';
    }
    function fmt(n) { return Number(n).toLocaleString('en-US'); }

    function refresh() {
        var isPct = typeValue() === 'percentage';
        var qty   = Math.max(1, parseInt(qtyInput.value, 10) || 1);
        var val   = parseFloat(valueInput.value);

        /* discount unit + limits */
        unit.textContent = isPct ? '%' : 'MMK';
        valueInput.max = isPct ? 100 : '';
        valueInput.placeholder = isPct ? '10' : '5000';
        valueHint.textContent = isPct ? 'Percent taken off (0–100).' : 'Amount taken off the ticket price.';

        /* max uses only matters for a single code */
        var single = qty === 1;
        usesField.classList.toggle('dim', !single);
        usesHint.textContent = single ? 'How many times this code can be redeemed.' : 'Only used when generating a single code.';

        /* scope */
        var ticketScope = scopeTicket.checked;
        catWrap.style.display = ticketScope ? 'block' : 'none';
        catSelect.required = ticketScope;

        /* button label */
        submitLbl.textContent = qty > 1 ? 'Generate ' + qty + ' codes' : 'Generate code';

        /* summary */
        var code = (codeInput.value || '').trim().toUpperCase();
        if (!code || isNaN(val)) {
            preview.textContent = 'Fill in the code and discount to see a summary.';
            return;
        }
        var off   = isPct ? val + '%' : fmt(val) + ' MMK';
        var where = 'the entire event';
        if (ticketScope) {
            var opt = catSelect.options[catSelect.selectedIndex];
            where = (opt && opt.value) ? 'the “' + opt.text.trim() + '” ticket' : 'the selected ticket category';
        }
        preview.innerHTML = '';
        var t = document.createElement('span');
        t.appendChild(document.createTextNode(qty > 1 ? 'Create ' : 'Create '));
        var s1 = document.createElement('strong'); s1.textContent = qty + (qty > 1 ? ' codes' : ' code');
        t.appendChild(s1);
        t.appendChild(document.createTextNode(qty > 1 ? ' starting with ' : ' named '));
        var s2 = document.createElement('strong'); s2.textContent = code;
        t.appendChild(s2);
        t.appendChild(document.createTextNode(', each giving '));
        var s3 = document.createElement('strong'); s3.textContent = off + ' off';
        t.appendChild(s3);
        t.appendChild(document.createTextNode(' on ' + where + (single && usesInput.value ? ', usable ' + usesInput.value + '×.' : '.')));
        preview.appendChild(t);
    }

    codeInput.addEventListener('input', function () {
        var pos = codeInput.selectionStart;
        codeInput.value = codeInput.value.toUpperCase().replace(/\s+/g, '');
        try { codeInput.setSelectionRange(pos, pos); } catch (e) {}
        refresh();
    });
    [qtyInput, usesInput, valueInput, catSelect].forEach(function (el) {
        el.addEventListener('input', refresh);
        el.addEventListener('change', refresh);
    });
    form.querySelectorAll('input[name="discount_type"], input[name="promo_scope"]').forEach(function (el) {
        el.addEventListener('change', refresh);
    });

    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitLbl.textContent = 'Generating…';
    });
    refresh();

    /* ========== LIST: SEARCH + FILTER ========== */
    var body   = document.getElementById('pc-body');
    var rows   = Array.prototype.slice.call(body.querySelectorAll('tr.pc-row'));
    var search = document.getElementById('pc-search');
    var tabs   = document.querySelectorAll('#pc-tabs .pc-tab');
    var none   = document.getElementById('pc-no-results');
    var shown  = document.getElementById('pc-shown');
    var filter = 'all';

    function applyFilter() {
        var q = search ? search.value.trim().toLowerCase() : '';
        var visible = 0;
        rows.forEach(function (r) {
            var ok = (filter === 'all' || r.dataset.status === filter) && (!q || r.dataset.search.indexOf(q) !== -1);
            r.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        if (shown) shown.textContent = visible;
        none.style.display = (rows.length && visible === 0) ? 'table-row' : 'none';
    }

    if (rows.length) {
        search.addEventListener('input', applyFilter);
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');
                filter = tab.dataset.filter;
                applyFilter();
            });
        });
    }

    /* ========== COPY ========== */
    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }
        return new Promise(function (resolve, reject) {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy') ? resolve() : reject(); } catch (e) { reject(e); }
            document.body.removeChild(ta);
        });
    }

    body.addEventListener('click', function (e) {
        var btn = e.target.closest('.pc-copy');
        if (!btn) return;
        copyText(btn.dataset.copy).then(function () {
            var icon = btn.querySelector('i');
            btn.classList.add('done');
            icon.className = 'fa-solid fa-check';
            setTimeout(function () { btn.classList.remove('done'); icon.className = 'fa-regular fa-copy'; }, 1400);
        });
    });

    var copyAll = document.getElementById('pc-copy-visible');
    if (copyAll) {
        copyAll.addEventListener('click', function () {
            var codes = rows.filter(function (r) { return r.style.display !== 'none'; })
                            .map(function (r) { return r.dataset.code; });
            if (!codes.length) return;
            var label = copyAll.querySelector('span');
            copyText(codes.join('\n')).then(function () {
                label.textContent = 'Copied ' + codes.length + ' ' + (codes.length === 1 ? 'code' : 'codes');
                setTimeout(function () { label.textContent = 'Copy visible codes'; }, 1800);
            });
        });
    }

    /* ========== DELETE MODAL ========== */
    var modal   = document.getElementById('pc-delete-modal');
    var delForm = document.getElementById('pc-delete-form');
    var delName = document.getElementById('pc-delete-name');

    window.openPromoDelete = function (btn) {
        delForm.action = btn.dataset.action;
        delName.textContent = btn.dataset.code;
        modal.classList.add('open');
    };
    window.closePromoDelete = function () { modal.classList.remove('open'); };
    modal.addEventListener('click', function (e) { if (e.target === modal) closePromoDelete(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePromoDelete(); });
})();
</script>

@endsection