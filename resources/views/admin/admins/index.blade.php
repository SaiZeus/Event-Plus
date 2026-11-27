@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('page-title', 'Admin Accounts')

@section('content')

@php
    $totalAdmins  = $admins->count();
    $superCount   = $admins->where('role', 'super_admin')->count();
    $eventCount   = $admins->where('role', 'event_admin')->count();
    $otherCount   = $totalAdmins - $superCount - $eventCount;
    $unassigned   = $admins->filter(fn ($a) => $a->role === 'event_admin' && ! $a->event)->count();
    $currentId    = auth('admin')->id();
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">

<style>

/* =========================================================
   EVENT PLUS - ADMIN ACCOUNTS
========================================================= */

.ea {
    --ea-ink: #17122b;
    --ea-muted: #6b6580;
    --ea-pink: #db2777;
    --ea-pink-dark: #be185d;
    --ea-violet: #7c3aed;
    --ea-line: #e6e1f3;
    --ea-bg: #faf8ff;
    --ea-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    width: 100%;
    color: var(--ea-ink);
}

.ea [hidden] {
    display: none !important;
}


/* ---------- Header ---------- */

.ea-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.ea-title {
    margin: 0;
    font-family: var(--ea-display);
    font-size: clamp(1.6rem, 3vw, 2.1rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.ea-sub {
    margin: 0.4rem 0 0;
    color: var(--ea-muted);
    font-size: 1rem;
}

.ea-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    min-height: 2.9rem;
    padding: 0.7rem 1.4rem;
    border: 1.5px solid transparent;
    border-radius: 999px;
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none !important;
    cursor: pointer;
    transition:
        transform 0.2s ease,
        background-color 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease,
        box-shadow 0.2s ease;
}

.ea-btn:focus-visible,
.ea-row-btn:focus-visible,
.ea-tab:focus-visible {
    outline: 3px solid var(--ea-violet);
    outline-offset: 2px;
}

.ea-btn--primary {
    background: var(--ea-pink);
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(219, 39, 119, 0.28);
}

.ea-btn--primary:hover {
    background: var(--ea-pink-dark);
    transform: translateY(-2px);
}

.ea-btn--outline {
    border-color: var(--ea-ink);
    background: transparent;
    color: var(--ea-ink) !important;
}

.ea-btn--outline:hover {
    background: var(--ea-ink);
    color: #fff !important;
}

.ea-btn--danger {
    background: #e11d48;
    color: #fff !important;
}

.ea-btn--danger:hover:not(:disabled) {
    background: #be123c;
}

.ea-btn--danger:disabled {
    opacity: 0.7;
    cursor: wait;
}


/* ---------- Notices ---------- */

.ea-notice {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    margin-bottom: 1rem;
    padding: 0.95rem 1.1rem;
    border: 1.5px solid;
    border-radius: 16px;
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1.5;
}

.ea-notice i {
    margin-top: 0.2rem;
}

.ea-notice--error {
    border-color: #fecdd3;
    background: #fff1f2;
    color: #9f1239;
}

.ea-notice--success {
    border-color: #bbf7d0;
    background: #f0fdf4;
    color: #166534;
}

.ea-notice--warn {
    border-color: #fde68a;
    background: #fffbeb;
    color: #78350f;
}


/* ---------- Toolbar: filters + search ---------- */

.ea-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.9rem 1.25rem;
    margin-bottom: 1rem;
}

.ea-tabs {
    display: flex;
    gap: 0.25rem;
    max-width: 100%;
    padding: 0.3rem;
    overflow-x: auto;
    border-radius: 999px;
    background: #f1edfb;
    scrollbar-width: none;
}

.ea-tabs::-webkit-scrollbar {
    display: none;
}

.ea-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.05rem;
    border: 0;
    border-radius: 999px;
    background: transparent;
    color: #4b4563;
    font-size: 0.95rem;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.ea-tab:hover {
    color: var(--ea-ink);
}

.ea-tab[aria-pressed="true"] {
    background: var(--ea-ink);
    color: #fff;
}

.ea-tab__n {
    min-width: 1.4rem;
    padding: 0.2rem 0.4rem;
    border-radius: 999px;
    background: rgba(23, 18, 43, 0.09);
    font-size: 0.8rem;
    text-align: center;
}

.ea-tab[aria-pressed="true"] .ea-tab__n {
    background: rgba(255, 255, 255, 0.2);
}

.ea-search {
    position: relative;
    width: 100%;
    max-width: 320px;
}

.ea-search i {
    position: absolute;
    top: 50%;
    left: 1rem;
    color: var(--ea-muted);
    pointer-events: none;
    transform: translateY(-50%);
}

.ea-search input {
    width: 100%;
    height: 2.9rem;
    padding: 0 1rem 0 2.6rem;
    border: 1.5px solid #d9d3ea;
    border-radius: 999px;
    background: #fff;
    color: var(--ea-ink);
    font-size: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ea-search input::placeholder {
    color: #756f8a;
}

.ea-search input:focus {
    outline: none;
    border-color: var(--ea-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.18);
}


/* ---------- Table card ---------- */

.ea-card {
    overflow: hidden;
    border: 1px solid var(--ea-line);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.ea-table {
    width: 100%;
    border-collapse: collapse;
}

.ea-table thead th {
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--ea-line);
    background: var(--ea-bg);
    color: var(--ea-muted);
    font-size: 0.85rem;
    font-weight: 700;
    text-align: left;
    white-space: nowrap;
}

.ea-table tbody td {
    padding: 1.05rem 1.25rem;
    border-bottom: 1px solid #f0ecf8;
    color: #4b4563;
    font-size: 0.95rem;
    vertical-align: middle;
}

.ea-table tbody tr:last-child td {
    border-bottom: 0;
}

.ea-table tbody tr {
    transition: background-color 0.15s ease;
}

.ea-table tbody tr:hover {
    background: #fcfaff;
}

.ea-col-actions {
    text-align: right !important;
}


/* ---------- Administrator cell ---------- */

.ea-person {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    min-width: 0;
}

.ea-avatar {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 14px;
    background: #f3edfc;
    color: var(--ea-pink);
    font-family: var(--ea-display);
    font-size: 1.1rem;
    font-weight: 800;
}

.ea-person__text {
    min-width: 0;
}

.ea-name {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    color: var(--ea-ink);
    font-weight: 700;
    overflow-wrap: anywhere;
}

.ea-email {
    margin-top: 0.15rem;
    color: var(--ea-muted);
    font-size: 0.9rem;
    overflow-wrap: anywhere;
}

.ea-you {
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
    background: var(--ea-ink);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
}


/* ---------- Role cell ---------- */

.ea-role {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 700;
    line-height: 1.2;
}

.ea-role i {
    font-size: 0.75rem;
}

.ea-role--super {
    background: #fff4e0;
    color: #92400e;
}

.ea-role--event {
    background: #f1edfb;
    color: #5b21b6;
}

.ea-role--other {
    background: #eef0f4;
    color: #374151;
}

.ea-role-note {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: #4b4563;
    font-size: 0.9rem;
    line-height: 1.4;
}

.ea-role-note i {
    margin-top: 0.2rem;
    color: var(--ea-violet);
    font-size: 0.8rem;
}

.ea-role-note--warn {
    color: #9f1239;
    font-weight: 600;
}

.ea-role-note--warn i {
    color: #e11d48;
}

.ea-role-note a {
    color: inherit;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.ea-date {
    white-space: nowrap;
}


/* ---------- Row actions ---------- */

.ea-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
}

.ea-actions form {
    margin: 0;
}

.ea-row-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    min-height: 2.5rem;
    padding: 0.5rem 0.95rem;
    border: 1.5px solid var(--ea-line);
    border-radius: 999px;
    background: #fff;
    color: var(--ea-ink);
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none !important;
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}

.ea-row-btn i {
    font-size: 0.8rem;
}

.ea-row-btn:hover {
    border-color: var(--ea-ink);
    background: var(--ea-ink);
    color: #fff;
}

.ea-row-btn--delete {
    color: #be123c;
}

.ea-row-btn--delete:hover {
    border-color: #e11d48;
    background: #e11d48;
    color: #fff;
}

.ea-protected {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    min-height: 2.5rem;
    padding: 0.5rem 0.4rem;
    color: var(--ea-muted);
    font-size: 0.875rem;
    font-weight: 600;
}


/* ---------- Empty + no match ---------- */

.ea-empty {
    padding: 3.25rem 1.5rem;
    text-align: center;
}

.ea-empty__icon {
    display: grid;
    place-items: center;
    width: 3.5rem;
    height: 3.5rem;
    margin: 0 auto 1rem;
    border-radius: 16px;
    background: #f3edfc;
    color: var(--ea-pink);
    font-size: 1.3rem;
}

.ea-empty h2,
.ea-empty h3 {
    margin: 0 0 0.35rem;
    font-family: var(--ea-display);
    font-size: 1.25rem;
    font-weight: 800;
}

.ea-empty p {
    max-width: 40ch;
    margin: 0 auto;
    color: var(--ea-muted);
}

.ea-empty .ea-btn {
    margin-top: 1.1rem;
}

.ea-count {
    margin: 0.9rem 0.25rem 0;
    color: var(--ea-muted);
    font-size: 0.9rem;
}


/* ---------- Delete dialog ---------- */

.ea-dialog {
    width: min(440px, calc(100% - 2rem));
    padding: 0;
    border: 0;
    border-radius: 22px;
    background: #fff;
    color: var(--ea-ink);
    box-shadow: 0 30px 80px rgba(8, 4, 24, 0.4);
}

.ea-dialog::backdrop {
    background: rgba(23, 18, 43, 0.6);
    -webkit-backdrop-filter: blur(4px);
    backdrop-filter: blur(4px);
}

.ea-dialog__body {
    padding: 1.75rem 1.75rem 1.25rem;
}

.ea-dialog__icon {
    display: grid;
    place-items: center;
    width: 3rem;
    height: 3rem;
    margin-bottom: 1rem;
    border-radius: 14px;
    background: #ffe4e6;
    color: #e11d48;
    font-size: 1.15rem;
}

.ea-dialog h2 {
    margin: 0 0 0.5rem;
    font-family: var(--ea-display);
    font-size: 1.35rem;
    font-weight: 800;
}

.ea-dialog p {
    margin: 0;
    color: #4b4563;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.ea-dialog__actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 0 1.75rem 1.75rem;
}


/* =========================================================
   PHONES: THE TABLE BECOMES CARDS
========================================================= */

@media (max-width: 767px) {

    .ea-table thead {
        display: none;
    }

    .ea-table,
    .ea-table tbody,
    .ea-table tr,
    .ea-table td {
        display: block;
        width: 100%;
    }

    .ea-table tbody tr {
        padding: 1.1rem 1.1rem 0.9rem;
        border-bottom: 1px solid var(--ea-line);
    }

    .ea-table tbody tr:last-child {
        border-bottom: 0;
    }

    .ea-table tbody td {
        padding: 0.4rem 0;
        border: 0;
    }

    .ea-table td[data-label]::before {
        content: attr(data-label);
        display: block;
        margin-bottom: 0.25rem;
        color: var(--ea-muted);
        font-size: 0.8rem;
        font-weight: 700;
    }

    .ea-table td.ea-col-actions {
        padding-top: 0.75rem;
        text-align: left !important;
    }

    .ea-table td.ea-col-actions::before {
        display: none;
    }

    .ea-actions {
        justify-content: flex-start;
    }

    .ea-search {
        max-width: none;
    }

    .ea-head .ea-btn {
        width: 100%;
    }

    .ea-dialog__actions {
        flex-direction: column-reverse;
    }

    .ea-dialog__actions .ea-btn {
        width: 100%;
    }

}


@media (prefers-reduced-motion: reduce) {

    .ea *,
    .ea *::before,
    .ea *::after {
        transition: none !important;
    }

}

</style>


<div class="ea" id="ea">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="ea-head">

        <div>
            <h1 class="ea-title">System administrators</h1>
            <p class="ea-sub">Manage who can sign in to the dashboard and what they can access.</p>
        </div>

        <a href="{{ route('admin.admins.create') }}" class="ea-btn ea-btn--primary">
            <i class="fas fa-plus" aria-hidden="true"></i>
            <span>Add new admin</span>
        </a>

    </div>


    {{-- =====================================================
         NOTICES
    ====================================================== --}}

    @if(session('error'))
        <div class="ea-notice ea-notice--error" role="alert">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="ea-notice ea-notice--success" role="status">
            <i class="fas fa-check-circle" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($unassigned > 0)
        <div class="ea-notice ea-notice--warn">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <span>
                {{ $unassigned }} event {{ $unassigned === 1 ? 'organiser has' : 'organisers have' }}
                no event assigned, so they have nothing to manage yet.
            </span>
        </div>
    @endif


    @if($totalAdmins > 0)

        {{-- =====================================================
             FILTERS + SEARCH
        ====================================================== --}}

        <div class="ea-toolbar">

            <div class="ea-tabs" role="group" aria-label="Filter by role">

                <button type="button" class="ea-tab" data-ea-filter="all" aria-pressed="true">
                    All <span class="ea-tab__n">{{ $totalAdmins }}</span>
                </button>

                @if($superCount > 0)
                    <button type="button" class="ea-tab" data-ea-filter="super" aria-pressed="false">
                        Super admins <span class="ea-tab__n">{{ $superCount }}</span>
                    </button>
                @endif

                @if($eventCount > 0)
                    <button type="button" class="ea-tab" data-ea-filter="event" aria-pressed="false">
                        Event organisers <span class="ea-tab__n">{{ $eventCount }}</span>
                    </button>
                @endif

                @if($otherCount > 0)
                    <button type="button" class="ea-tab" data-ea-filter="other" aria-pressed="false">
                        Other <span class="ea-tab__n">{{ $otherCount }}</span>
                    </button>
                @endif

            </div>

            <div class="ea-search">
                <label for="ea-search" class="visually-hidden">Search administrators</label>
                <i class="fas fa-search" aria-hidden="true"></i>
                <input
                    type="search"
                    id="ea-search"
                    placeholder="Search by name or email"
                    autocomplete="off"
                >
            </div>

        </div>

    @endif


    {{-- =====================================================
         ADMIN TABLE
    ====================================================== --}}

    <div class="ea-card">

        @if($totalAdmins > 0)

            <table class="ea-table">

                <thead>
                    <tr>
                        <th scope="col">Administrator</th>
                        <th scope="col">Role and event</th>
                        <th scope="col">Created</th>
                        <th scope="col" class="ea-col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($admins as $admin)

                        @php
                            $roleKey = $admin->role === 'super_admin'
                                ? 'super'
                                : ($admin->role === 'event_admin' ? 'event' : 'other');

                            $isSelf      = $currentId === $admin->id;
                            $isProtected = $admin->email === 'admin@gmail.com';
                            $canDelete   = ! $isProtected && ! $isSelf;
                        @endphp

                        <tr
                            data-ea-row
                            data-role="{{ $roleKey }}"
                            data-search="{{ \Illuminate\Support\Str::lower($admin->name . ' ' . $admin->email) }}"
                        >

                            {{-- Administrator --}}
                            <td data-label="Administrator">

                                <div class="ea-person">

                                    <span class="ea-avatar" aria-hidden="true">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($admin->name, 0, 1)) }}
                                    </span>

                                    <div class="ea-person__text">

                                        <div class="ea-name">
                                            <span>{{ $admin->name }}</span>

                                            @if($isSelf)
                                                <span class="ea-you">You</span>
                                            @endif
                                        </div>

                                        <div class="ea-email">{{ $admin->email }}</div>

                                    </div>

                                </div>

                            </td>


                            {{-- Role --}}
                            <td data-label="Role and event">

                                @if($admin->role === 'event_admin')

                                    <span class="ea-role ea-role--event">
                                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                        Event organiser
                                    </span>

                                    @if($admin->event)

                                        <div class="ea-role-note">
                                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                            <span>{{ $admin->event->title }}</span>
                                        </div>

                                    @else

                                        <div class="ea-role-note ea-role-note--warn">
                                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                            <span>
                                                No event assigned.
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}">Assign one</a>
                                            </span>
                                        </div>

                                    @endif

                                @elseif($admin->role === 'super_admin')

                                    <span class="ea-role ea-role--super">
                                        <i class="fas fa-crown" aria-hidden="true"></i>
                                        Super admin
                                    </span>

                                    <div class="ea-role-note">
                                        <i class="fas fa-key" aria-hidden="true"></i>
                                        <span>Full system access</span>
                                    </div>

                                @else

                                    <span class="ea-role ea-role--other">
                                        <i class="fas fa-user-shield" aria-hidden="true"></i>
                                        {{ ucwords(str_replace('_', ' ', $admin->role)) }}
                                    </span>

                                @endif

                            </td>


                            {{-- Created --}}
                            <td data-label="Created">

                                <span class="ea-date">{{ $admin->created_at->format('d M Y') }}</span>

                            </td>


                            {{-- Actions --}}
                            <td class="ea-col-actions">

                                <div class="ea-actions">

                                    <a
                                        href="{{ route('admin.admins.edit', $admin->id) }}"
                                        class="ea-row-btn"
                                        aria-label="Edit {{ $admin->name }}"
                                    >
                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                        <span>Edit</span>
                                    </a>

                                    @if($canDelete)

                                        <form
                                            action="{{ route('admin.admins.destroy', $admin->id) }}"
                                            method="POST"
                                            data-ea-delete
                                            data-name="{{ $admin->name }}"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="ea-row-btn ea-row-btn--delete"
                                                aria-label="Delete {{ $admin->name }}"
                                            >
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                <span>Delete</span>
                                            </button>

                                        </form>

                                    @elseif($isProtected)

                                        <span class="ea-protected" title="This account can't be deleted">
                                            <i class="fas fa-lock" aria-hidden="true"></i>
                                            <span>Protected</span>
                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            {{-- Shown when search or filters match nobody --}}
            <div class="ea-empty" id="ea-none" hidden>

                <span class="ea-empty__icon" aria-hidden="true"><i class="fas fa-search"></i></span>

                <h3>No administrators match</h3>

                <p>Try a different name or email, or switch the role filter.</p>

                <button type="button" class="ea-btn ea-btn--outline" id="ea-reset">Clear filters</button>

            </div>

        @else

            <div class="ea-empty">

                <span class="ea-empty__icon" aria-hidden="true"><i class="fas fa-user-shield"></i></span>

                <h2>No administrator accounts yet</h2>

                <p>Add your first administrator to start managing Event Plus.</p>

                <a href="{{ route('admin.admins.create') }}" class="ea-btn ea-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    <span>Add new admin</span>
                </a>

            </div>

        @endif

    </div>

    @if($totalAdmins > 0)
        <p class="ea-count" id="ea-count" role="status">
            Showing {{ $totalAdmins }} of {{ $totalAdmins }} {{ $totalAdmins === 1 ? 'account' : 'accounts' }}
        </p>
    @endif


    {{-- =====================================================
         DELETE CONFIRMATION
    ====================================================== --}}

    <dialog class="ea-dialog" id="ea-dialog" aria-labelledby="ea-dialog-title">

        <div class="ea-dialog__body">

            <span class="ea-dialog__icon" aria-hidden="true"><i class="fas fa-trash"></i></span>

            <h2 id="ea-dialog-title">Delete this admin?</h2>

            <p>
                <strong id="ea-dialog-name"></strong> will lose access to the dashboard straight away.
                This can't be undone.
            </p>

        </div>

        <div class="ea-dialog__actions">

            <button type="button" class="ea-btn ea-btn--outline" id="ea-dialog-cancel">Cancel</button>

            <button type="button" class="ea-btn ea-btn--danger" id="ea-dialog-confirm">Delete admin</button>

        </div>

    </dialog>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const root = document.getElementById('ea');
    if (!root) return;

    const rows = Array.prototype.slice.call(root.querySelectorAll('[data-ea-row]'));
    const tabs = Array.prototype.slice.call(root.querySelectorAll('[data-ea-filter]'));
    const input = document.getElementById('ea-search');
    const none = document.getElementById('ea-none');
    const table = root.querySelector('.ea-table');
    const countEl = document.getElementById('ea-count');
    const resetBtn = document.getElementById('ea-reset');

    let role = 'all';


    /* ---------- Filter + search ---------- */

    function apply() {

        const q = input ? input.value.trim().toLowerCase() : '';
        let shown = 0;

        rows.forEach(function (row) {
            const matchRole = role === 'all' || row.getAttribute('data-role') === role;
            const matchText = !q || row.getAttribute('data-search').indexOf(q) !== -1;
            const show = matchRole && matchText;

            row.hidden = !show;
            if (show) shown++;
        });

        if (none) none.hidden = shown !== 0;
        if (table) table.hidden = shown === 0;

        if (countEl) {
            countEl.textContent =
                'Showing ' + shown + ' of ' + rows.length + ' ' + (rows.length === 1 ? 'account' : 'accounts');
        }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            role = tab.getAttribute('data-ea-filter');

            tabs.forEach(function (t) {
                t.setAttribute('aria-pressed', t === tab ? 'true' : 'false');
            });

            apply();
        });
    });

    if (input) input.addEventListener('input', apply);

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            role = 'all';
            if (input) input.value = '';

            tabs.forEach(function (t) {
                t.setAttribute('aria-pressed', t.getAttribute('data-ea-filter') === 'all' ? 'true' : 'false');
            });

            apply();
        });
    }


    /* ---------- Delete confirmation ---------- */

    const dialog = document.getElementById('ea-dialog');
    const nameEl = document.getElementById('ea-dialog-name');
    const confirmBtn = document.getElementById('ea-dialog-confirm');
    const cancelBtn = document.getElementById('ea-dialog-cancel');

    let pendingForm = null;

    root.querySelectorAll('form[data-ea-delete]').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            const name = form.getAttribute('data-name') || 'This admin';

            // Older browsers without <dialog>: fall back to the browser prompt
            if (!dialog || typeof dialog.showModal !== 'function') {
                if (window.confirm('Delete ' + name + '? This cannot be undone.')) form.submit();
                return;
            }

            pendingForm = form;
            nameEl.textContent = name;
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Delete admin';

            dialog.showModal();
            cancelBtn.focus();

        });

    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            dialog.close();
            pendingForm = null;
        });
    }

    if (dialog) {
        // Click on the dark backdrop closes it
        dialog.addEventListener('click', function (e) {
            if (e.target === dialog) {
                dialog.close();
                pendingForm = null;
            }
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!pendingForm) return;

            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Deleting…';

            pendingForm.submit();
        });
    }

});
</script>

@endsection