@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('page-title', 'Edit Admin Account')

@section('content')

@php
    $events       = \App\Models\Event::orderBy('title')->get();
    $selectedRole = old('role', $admin->role);
    $isSelf       = auth('admin')->id() === $admin->id;
    $isProtected  = $admin->email === 'admin@gmail.com';
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&display=swap" rel="stylesheet">

<style>

/* =========================================================
   EVENT PLUS - ADMIN FORMS (create + edit share this look)
========================================================= */

.ea {
    --ea-ink: #17122b;
    --ea-muted: #6b6580;
    --ea-pink: #db2777;
    --ea-pink-dark: #be185d;
    --ea-violet: #7c3aed;
    --ea-line: #e6e1f3;
    --ea-field: #d9d3ea;
    --ea-bg: #faf8ff;
    --ea-display: 'Bricolage Grotesque', system-ui, -apple-system, 'Segoe UI', sans-serif;

    width: 100%;
    max-width: 760px;
    color: var(--ea-ink);
}

.ea [hidden] {
    display: none !important;
}


/* ---------- Header ---------- */

.ea-back {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    min-height: 2.5rem;
    margin-bottom: 0.75rem;
    color: var(--ea-muted);
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}

.ea-back:hover {
    color: var(--ea-ink);
}

.ea-back i {
    font-size: 0.75rem;
}

.ea-person {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.ea-avatar {
    display: grid;
    flex: none;
    place-items: center;
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 16px;
    background: #f3edfc;
    color: var(--ea-pink);
    font-family: var(--ea-display);
    font-size: 1.4rem;
    font-weight: 800;
}

.ea-person__text {
    min-width: 0;
}

.ea-title {
    margin: 0;
    font-family: var(--ea-display);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.02em;
    overflow-wrap: anywhere;
}

.ea-sub {
    margin: 0.4rem 0 0;
    color: var(--ea-muted);
    font-size: 0.95rem;
    line-height: 1.5;
    overflow-wrap: anywhere;
}


/* ---------- Notices ---------- */

.ea-notice {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
    padding: 0.95rem 1.1rem;
    border: 1.5px solid;
    border-radius: 16px;
    font-size: 0.95rem;
    line-height: 1.5;
}

.ea-notice i {
    margin-top: 0.2rem;
}

.ea-notice ul {
    margin: 0.35rem 0 0;
    padding-left: 1.1rem;
}

.ea-notice--error {
    border-color: #fecdd3;
    background: #fff1f2;
    color: #9f1239;
}

.ea-notice--warn {
    border-color: #fde68a;
    background: #fffbeb;
    color: #78350f;
}

.ea-notice--info {
    border-color: var(--ea-line);
    background: var(--ea-bg);
    color: #4b4563;
}


/* ---------- Card + sections ---------- */

.ea-card {
    padding: clamp(1.25rem, 3.5vw, 2rem);
    border: 1px solid var(--ea-line);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(23, 18, 43, 0.04), 0 12px 28px rgba(23, 18, 43, 0.05);
}

.ea-section + .ea-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--ea-line);
}

.ea-h {
    margin: 0 0 1.1rem;
    font-family: var(--ea-display);
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -0.01em;
}

.ea-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.25rem;
}

.ea-field--full {
    grid-column: 1 / -1;
}

fieldset.ea-field {
    min-width: 0;
    margin: 0;
    padding: 0;
    border: 0;
}


/* ---------- Fields ---------- */

.ea-label {
    display: block;
    margin: 0 0 0.45rem;
    padding: 0;
    color: var(--ea-ink);
    font-size: 0.95rem;
    font-weight: 700;
}

.ea-label small {
    color: var(--ea-muted);
    font-size: 0.875rem;
    font-weight: 600;
}

.ea-req {
    color: var(--ea-pink);
}

.ea-input,
.ea-select {
    width: 100%;
    height: 3rem;
    padding: 0 1rem;
    border: 1.5px solid var(--ea-field);
    border-radius: 12px;
    background-color: #fff;
    color: var(--ea-ink);
    font-size: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.ea-select {
    cursor: pointer;
}

.ea-input::placeholder {
    color: #8a849c;
}

.ea-input:hover,
.ea-select:hover {
    border-color: #b9aedd;
}

.ea-input:focus,
.ea-select:focus {
    outline: none;
    border-color: var(--ea-violet);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.18);
}

.ea-input[aria-invalid="true"],
.ea-select[aria-invalid="true"] {
    border-color: #e11d48;
}

.ea-help {
    margin: 0.45rem 0 0;
    color: var(--ea-muted);
    font-size: 0.9rem;
    line-height: 1.5;
}

.ea-error {
    margin: 0.45rem 0 0;
    color: #be123c;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.4;
}

.ea-pw {
    position: relative;
}

.ea-pw .ea-input {
    padding-right: 5.25rem;
}

.ea-pw__toggle {
    position: absolute;
    top: 50%;
    right: 0.5rem;
    min-height: 2.25rem;
    padding: 0 0.85rem;
    border: 0;
    border-radius: 999px;
    background: #f1edfb;
    color: var(--ea-ink);
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    transform: translateY(-50%);
    transition: background-color 0.2s ease;
}

.ea-pw__toggle:hover {
    background: #e5defa;
}

.ea-pw__toggle:focus-visible {
    outline: 3px solid var(--ea-violet);
    outline-offset: 2px;
}


/* ---------- Role cards ---------- */

.ea-roles {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.9rem;
}

.ea-role-card {
    position: relative;
    display: block;
    cursor: pointer;
}

.ea-role-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.ea-role-card__body {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    height: 100%;
    padding: 1.1rem;
    border: 1.5px solid var(--ea-field);
    border-radius: 16px;
    background: #fff;
    transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
}

.ea-role-card:hover .ea-role-card__body {
    border-color: #b9aedd;
}

.ea-role-card input:checked + .ea-role-card__body {
    border-color: var(--ea-pink);
    background: #fff7fb;
    box-shadow: 0 0 0 3px rgba(219, 39, 119, 0.14);
}

.ea-role-card input:focus-visible + .ea-role-card__body {
    outline: 3px solid var(--ea-violet);
    outline-offset: 2px;
}

.ea-role-card__icon {
    display: grid;
    flex: none;
    place-items: center;
    width: 2.6rem;
    height: 2.6rem;
    border-radius: 12px;
    background: #f3edfc;
    color: var(--ea-pink);
}

.ea-role-card input:checked + .ea-role-card__body .ea-role-card__icon {
    background: var(--ea-pink);
    color: #fff;
}

.ea-role-card strong {
    display: block;
    font-size: 1rem;
    line-height: 1.3;
}

.ea-role-card small {
    display: block;
    margin-top: 0.2rem;
    color: var(--ea-muted);
    font-size: 0.9rem;
    line-height: 1.45;
}


/* ---------- Event assignment (slides open for organisers) ---------- */

.ea-reveal {
    display: grid;
    grid-template-rows: 0fr;
    visibility: hidden;
    transition: grid-template-rows 0.3s ease, visibility 0s linear 0.3s;
}

.ea-reveal.is-open {
    grid-template-rows: 1fr;
    visibility: visible;
    transition: grid-template-rows 0.3s ease;
}

.ea-reveal__inner {
    min-height: 0;
    overflow: hidden;
}

.ea-event-box {
    margin-top: 1.25rem;
    padding: 1.1rem;
    border: 1.5px solid var(--ea-line);
    border-radius: 16px;
    background: var(--ea-bg);
}

.ea-event-box a {
    color: var(--ea-pink);
    font-weight: 700;
    text-underline-offset: 3px;
}

.ea-callout {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    margin-top: 1.25rem;
    padding: 0.9rem 1.1rem;
    border-radius: 14px;
    background: #fff4e0;
    color: #6b3d06;
    font-size: 0.95rem;
    line-height: 1.5;
}

.ea-callout i {
    margin-top: 0.2rem;
}


/* ---------- Actions ---------- */

.ea-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--ea-line);
}

.ea-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    min-height: 3rem;
    padding: 0.7rem 1.5rem;
    border: 1.5px solid transparent;
    border-radius: 999px;
    background: transparent;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1;
    text-decoration: none !important;
    cursor: pointer;
    transition:
        transform 0.2s ease,
        background-color 0.2s ease,
        color 0.2s ease,
        box-shadow 0.2s ease;
}

.ea-btn:focus-visible {
    outline: 3px solid var(--ea-violet);
    outline-offset: 3px;
}

.ea-btn--primary {
    background: var(--ea-pink);
    color: #fff !important;
    box-shadow: 0 8px 20px rgba(219, 39, 119, 0.28);
}

.ea-btn--primary:hover:not(:disabled) {
    background: var(--ea-pink-dark);
    transform: translateY(-2px);
}

.ea-btn--primary:disabled {
    background: #e4dff0;
    color: #8a849c !important;
    box-shadow: none;
    cursor: wait;
}

.ea-btn--outline {
    border-color: var(--ea-ink);
    color: var(--ea-ink) !important;
}

.ea-btn--outline:hover {
    background: var(--ea-ink);
    color: #fff !important;
}


/* ---------- Phones ---------- */

@media (max-width: 640px) {

    .ea-grid,
    .ea-roles {
        grid-template-columns: minmax(0, 1fr);
    }

    .ea-field--full {
        grid-column: auto;
    }

    .ea-actions {
        flex-direction: column-reverse;
        align-items: stretch;
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


<div class="ea">

    <a href="{{ route('admin.admins.index') }}" class="ea-back">
        <i class="fas fa-chevron-left" aria-hidden="true"></i>
        <span>Admin accounts</span>
    </a>

    <div class="ea-person">

        <span class="ea-avatar" aria-hidden="true">
            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($admin->name, 0, 1)) }}
        </span>

        <div class="ea-person__text">

            <h1 class="ea-title">Edit {{ $admin->name }}</h1>

            <p class="ea-sub">
                {{ $admin->email }}
                @if($admin->created_at)
                    &middot; Created {{ $admin->created_at->format('d M Y') }}
                @endif
            </p>

        </div>

    </div>


    {{-- =====================================================
         ABOUT THIS ACCOUNT
    ====================================================== --}}

    @if($isSelf)
        <div class="ea-notice ea-notice--warn">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <span>
                This is your own account. If you change your role, you may lose access to some of the dashboard.
            </span>
        </div>
    @endif

    @if($isProtected)
        <div class="ea-notice ea-notice--info">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <span>This is a protected account, so it can't be deleted.</span>
        </div>
    @endif


    {{-- =====================================================
         VALIDATION SUMMARY
    ====================================================== --}}

    @if($errors->any())
        <div class="ea-notice ea-notice--error" role="alert">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <div>
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" id="ea-form">

        @csrf

        @method('PUT')

        <div class="ea-card">

            {{-- =================================================
                 ACCOUNT DETAILS
            ================================================== --}}

            <section class="ea-section">

                <h2 class="ea-h">Account details</h2>

                <div class="ea-grid">

                    {{-- FULL NAME --}}
                    <div class="ea-field">

                        <label for="name" class="ea-label">
                            Full name <span class="ea-req" aria-hidden="true">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $admin->name) }}"
                            required
                            autocomplete="name"
                            placeholder="Enter the admin's name"
                            class="ea-input"
                            @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                        >

                        @error('name')
                            <p class="ea-error" id="name-error">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- EMAIL --}}
                    <div class="ea-field">

                        <label for="email" class="ea-label">
                            Email address <span class="ea-req" aria-hidden="true">*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $admin->email) }}"
                            required
                            autocomplete="email"
                            placeholder="name@example.com"
                            class="ea-input"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                        >

                        @error('email')
                            <p class="ea-error" id="email-error">{{ $message }}</p>
                        @else
                            <p class="ea-help">They sign in with this address.</p>
                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div class="ea-field ea-field--full">

                        <label for="password" class="ea-label">
                            New password <small>(optional)</small>
                        </label>

                        <div class="ea-pw">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="new-password"
                                placeholder="Leave blank to keep the current password"
                                class="ea-input"
                                @error('password') aria-invalid="true" aria-describedby="password-error" @else aria-describedby="password-help" @enderror
                            >

                            <button type="button" class="ea-pw__toggle" id="ea-pw-toggle" aria-pressed="false" aria-controls="password">
                                Show
                            </button>

                        </div>

                        @error('password')
                            <p class="ea-error" id="password-error">{{ $message }}</p>
                        @else
                            <p class="ea-help" id="password-help">
                                Only fill this in to change the password. If you leave it empty, the current one stays the same.
                            </p>
                        @enderror

                    </div>

                </div>

            </section>


            {{-- =================================================
                 ROLE + EVENT
            ================================================== --}}

            <section class="ea-section">

                <h2 class="ea-h">Access</h2>

                <fieldset class="ea-field">

                    <legend class="ea-label">
                        Role <span class="ea-req" aria-hidden="true">*</span>
                    </legend>

                    <div class="ea-roles">

                        <label class="ea-role-card">

                            <input
                                type="radio"
                                name="role"
                                value="super_admin"
                                required
                                {{ $selectedRole === 'super_admin' ? 'checked' : '' }}
                            >

                            <span class="ea-role-card__body">

                                <span class="ea-role-card__icon" aria-hidden="true">
                                    <i class="fas fa-crown"></i>
                                </span>

                                <span>
                                    <strong>Super admin</strong>
                                    <small>Full access to everything, including other admins.</small>
                                </span>

                            </span>

                        </label>

                        <label class="ea-role-card">

                            <input
                                type="radio"
                                name="role"
                                value="event_admin"
                                {{ $selectedRole === 'event_admin' ? 'checked' : '' }}
                            >

                            <span class="ea-role-card__body">

                                <span class="ea-role-card__icon" aria-hidden="true">
                                    <i class="fas fa-calendar-check"></i>
                                </span>

                                <span>
                                    <strong>Event organiser</strong>
                                    <small>Manages a single event you assign to them.</small>
                                </span>

                            </span>

                        </label>

                    </div>

                    @error('role')
                        <p class="ea-error">{{ $message }}</p>
                    @enderror

                </fieldset>


                {{-- Shown for super admins --}}
                <div class="ea-callout" id="ea-super-note" @if($selectedRole !== 'super_admin') hidden @endif>
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <span>Super admins can change anything in Event Plus. Only give this role to people you trust.</span>
                </div>


                {{-- Shown for event organisers --}}
                <div
                    class="ea-reveal {{ $selectedRole === 'event_admin' ? 'is-open' : '' }}"
                    id="eventSelectWrapper"
                >

                    <div class="ea-reveal__inner">

                        <div class="ea-event-box">

                            <label for="event_id" class="ea-label">Assigned event</label>

                            <select
                                name="event_id"
                                id="event_id"
                                class="ea-select"
                                @error('event_id') aria-invalid="true" aria-describedby="event-error" @else aria-describedby="event-help" @enderror
                            >

                                <option value="">Select an event</option>

                                @foreach($events as $event)
                                    <option
                                        value="{{ $event->id }}"
                                        {{ old('event_id', $admin->event_id) == $event->id ? 'selected' : '' }}
                                    >
                                        {{ $event->title }}
                                    </option>
                                @endforeach

                            </select>

                            @error('event_id')
                                <p class="ea-error" id="event-error">{{ $message }}</p>
                            @else
                                <p class="ea-help" id="event-help">
                                    @if($events->isEmpty())
                                        There are no events yet.
                                        <a href="{{ route('admin.events.create') }}">Create an event</a>
                                        first, then assign it here.
                                    @else
                                        You can leave this empty, but they won't have anything to manage until an event is assigned.
                                    @endif
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="ea-actions">

                <a href="{{ route('admin.admins.index') }}" class="ea-btn ea-btn--outline">
                    <span>Cancel</span>
                </a>

                <button type="submit" class="ea-btn ea-btn--primary" id="ea-submit">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    <span>Save changes</span>
                </button>

            </div>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('ea-form');
    if (!form) return;

    const radios = Array.prototype.slice.call(form.querySelectorAll('input[name="role"]'));
    const reveal = document.getElementById('eventSelectWrapper');
    const eventSelect = document.getElementById('event_id');
    const superNote = document.getElementById('ea-super-note');


    /* ---------- Role: show the event picker for organisers only ---------- */

    function setRole(role) {

        const organiser = role === 'event_admin';

        if (reveal) {
            reveal.classList.toggle('is-open', organiser);

            if (organiser) {
                reveal.removeAttribute('inert');
            } else {
                reveal.setAttribute('inert', '');
            }
        }

        // Only organisers have an assigned event
        if (!organiser && eventSelect) eventSelect.value = '';

        if (superNote) superNote.hidden = role !== 'super_admin';
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            setRole(radio.value);
        });
    });

    const checked = radios.filter(function (r) { return r.checked; })[0];
    setRole(checked ? checked.value : '');


    /* ---------- Password show / hide ---------- */

    const pw = document.getElementById('password');
    const pwToggle = document.getElementById('ea-pw-toggle');

    if (pw && pwToggle) {
        pwToggle.addEventListener('click', function () {
            const show = pw.type === 'password';

            pw.type = show ? 'text' : 'password';
            pwToggle.textContent = show ? 'Hide' : 'Show';
            pwToggle.setAttribute('aria-pressed', show ? 'true' : 'false');
        });
    }


    /* ---------- Browser validation + double-submit protection ---------- */

    const submitBtn = document.getElementById('ea-submit');
    const label = submitBtn ? submitBtn.querySelector('span') : null;
    const defaultLabel = label ? label.textContent : '';

    form.addEventListener('submit', function (e) {

        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            if (label) label.textContent = 'Saving…';
        }

    });

    // Back button restores the page from cache: make the button usable again
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted || !submitBtn) return;

        submitBtn.disabled = false;
        if (label) label.textContent = defaultLabel;
    });

});
</script>

@endsection