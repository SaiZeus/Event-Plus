@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('page-title', 'Edit Admin Account')

@section('content')

<style>

    /* =========================================================
       EVENT PLUS - EDIT ADMIN
    ========================================================== */

    .ep-edit-page {
        width: 100%;
        max-width: 920px;

        animation: epPageEnter 0.55s ease both;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .ep-edit-hero {

        position: relative;
        overflow: hidden;

        margin-bottom: 20px;
        padding: 24px 26px;

        border-radius: 18px;

        background:
            radial-gradient(
                circle at 100% 0%,
                rgba(236, 72, 153, 0.17),
                transparent 32%
            ),
            radial-gradient(
                circle at 70% 100%,
                rgba(124, 58, 237, 0.15),
                transparent 32%
            ),
            linear-gradient(
                135deg,
                #111827 0%,
                #172044 55%,
                #241452 100%
            );

        border: 1px solid rgba(255, 255, 255, 0.07);

        box-shadow:
            0 18px 45px rgba(15, 23, 42, 0.12);

        animation: epHeroEnter 0.65s ease both;
    }


    .ep-edit-hero::before {

        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        right: -100px;
        top: -120px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.08);
    }


    .ep-edit-hero::after {

        content: "";

        position: absolute;

        width: 130px;
        height: 130px;

        right: 120px;
        bottom: -100px;

        border-radius: 50%;

        background:
            rgba(236, 72, 153, 0.08);

        filter: blur(2px);
    }


    .ep-edit-hero-content {

        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 15px;
    }


    .ep-edit-hero-icon {

        width: 48px;
        height: 48px;
        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #7c3aed
            );

        color: #ffffff;

        font-size: 17px;

        box-shadow:
            0 10px 25px rgba(236, 72, 153, 0.25);

        animation:
            epIconFloat 3s ease-in-out infinite;
    }


    .ep-edit-hero h2 {

        margin: 0;

        color: #ffffff;

        font-size: 1.15rem;
        font-weight: 850;

        letter-spacing: -0.025em;
    }


    .ep-edit-hero p {

        margin: 5px 0 0;

        color: #a5b4fc;

        font-size: 0.72rem;
        font-weight: 600;
    }


    /* =========================================================
       FORM CARD
    ========================================================== */

    .ep-edit-card {

        position: relative;
        overflow: hidden;

        background: #ffffff;

        border:
            1px solid #e8eaf0;

        border-radius: 18px;

        box-shadow:
            0 8px 28px rgba(15, 23, 42, 0.055);

        animation:
            epCardEnter 0.7s 0.08s ease both;
    }


    .ep-edit-card::before {

        content: "";

        position: absolute;

        top: 0;
        left: 0;
        right: 0;

        height: 3px;

        background:
            linear-gradient(
                90deg,
                #ec4899,
                #8b5cf6,
                #6366f1
            );
    }


    .ep-edit-card-header {

        display: flex;
        align-items: center;
        gap: 11px;

        padding: 20px 24px;

        border-bottom:
            1px solid #eef2f7;
    }


    .ep-card-header-icon {

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                #fce7f3,
                #ede9fe
            );

        color: #db2777;

        font-size: 12px;
    }


    .ep-edit-card-header h3 {

        margin: 0;

        color: #1e293b;

        font-size: 0.78rem;
        font-weight: 850;
    }


    .ep-edit-card-header p {

        margin: 3px 0 0;

        color: #94a3b8;

        font-size: 0.63rem;
        font-weight: 600;
    }


    /* =========================================================
       FORM
    ========================================================== */

    .ep-edit-form {

        padding: 24px;
    }


    .ep-form-grid {

        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 19px;
    }


    .ep-form-group {

        position: relative;
    }


    .ep-form-group.full-width {

        grid-column: 1 / -1;
    }


    /* =========================================================
       LABEL
    ========================================================== */

    .ep-form-label {

        display: flex;
        align-items: center;
        gap: 6px;

        margin-bottom: 7px;

        color: #334155;

        font-size: 0.66rem;
        font-weight: 800;
    }


    .ep-form-label i {

        color: #a855f7;

        font-size: 9px;
    }


    .ep-required {

        color: #e11d48;

        font-size: 9px;
    }


    .ep-label-hint {

        color: #94a3b8;

        font-size: 0.59rem;
        font-weight: 550;
    }


    /* =========================================================
       INPUTS
    ========================================================== */

    .ep-form-input,
    .ep-form-select {

        width: 100%;

        min-height: 44px;

        padding: 0 13px;

        border:
            1px solid #e2e8f0;

        border-radius: 11px;

        background:
            #ffffff;

        color: #334155;

        font-size: 0.7rem;
        font-weight: 600;

        outline: none;

        transition:
            border-color 0.22s ease,
            box-shadow 0.22s ease,
            transform 0.22s ease,
            background 0.22s ease;
    }


    .ep-form-input::placeholder {

        color: #b4bdca;

        font-weight: 500;
    }


    .ep-form-input:hover,
    .ep-form-select:hover {

        border-color: #cbd5e1;
    }


    .ep-form-input:focus,
    .ep-form-select:focus {

        border-color: #d946ef;

        background: #ffffff;

        box-shadow:
            0 0 0 4px rgba(217, 70, 239, 0.08),
            0 5px 15px rgba(124, 58, 237, 0.05);

        transform: translateY(-1px);
    }


    .ep-form-select {

        cursor: pointer;

        appearance: auto;
    }


    /* =========================================================
       PASSWORD NOTE
    ========================================================== */

    .ep-password-note {

        display: flex;
        align-items: center;
        gap: 6px;

        margin-top: 6px;

        color: #94a3b8;

        font-size: 0.59rem;
        font-weight: 550;
    }


    .ep-password-note i {

        color: #a855f7;

        font-size: 8px;
    }


    /* =========================================================
       ROLE SELECTION
    ========================================================== */

    .ep-role-select-wrapper {

        position: relative;
    }


    .ep-role-info {

        display: flex;
        align-items: center;
        gap: 6px;

        margin-top: 7px;

        color: #94a3b8;

        font-size: 0.59rem;
        font-weight: 550;
    }


    .ep-role-info i {

        color: #8b5cf6;

        font-size: 8px;
    }


    /* =========================================================
       EVENT ASSIGNMENT
    ========================================================== */

    .ep-event-wrapper {

        overflow: hidden;

        transition:
            max-height 0.35s ease,
            opacity 0.3s ease,
            transform 0.3s ease;
    }


    .ep-event-wrapper.hidden-event {

        max-height: 0;

        opacity: 0;

        transform: translateY(-8px);

        pointer-events: none;
    }


    .ep-event-wrapper.visible-event {

        max-height: 180px;

        opacity: 1;

        transform: translateY(0);

        pointer-events: auto;
    }


    .ep-event-box {

        padding: 14px;

        border-radius: 13px;

        background:
            linear-gradient(
                135deg,
                #faf5ff,
                #fdf2f8
            );

        border:
            1px solid #ede9fe;
    }


    .ep-event-box-label {

        display: flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 8px;

        color: #6d28d9;

        font-size: 0.63rem;
        font-weight: 800;
    }


    .ep-event-box-label i {

        font-size: 9px;
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .ep-form-actions {

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 12px;

        margin-top: 26px;
        padding-top: 20px;

        border-top:
            1px solid #eef2f7;
    }


    .ep-cancel-btn {

        display: inline-flex;
        align-items: center;
        gap: 7px;

        min-height: 42px;

        padding: 0 16px;

        border-radius: 10px;

        background: #f8fafc;

        border:
            1px solid #e2e8f0;

        color: #64748b !important;

        font-size: 0.68rem;
        font-weight: 800;

        text-decoration: none;

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease;
    }


    .ep-cancel-btn:hover {

        background: #f1f5f9;

        border-color: #cbd5e1;

        color: #334155 !important;

        transform: translateY(-1px);
    }


    .ep-update-btn {

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        min-height: 42px;

        padding: 0 19px;

        border: 0;

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #7c3aed
            );

        color: #ffffff;

        font-size: 0.68rem;
        font-weight: 850;

        cursor: pointer;

        box-shadow:
            0 9px 20px rgba(124, 58, 237, 0.20);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            filter 0.2s ease;
    }


    .ep-update-btn:hover {

        color: #ffffff;

        transform: translateY(-2px);

        filter: brightness(1.04);

        box-shadow:
            0 13px 27px rgba(124, 58, 237, 0.28);
    }


    .ep-update-btn:active {

        transform: translateY(0);
    }


    .ep-update-btn i {

        font-size: 10px;
    }


    /* =========================================================
       SECURITY NOTE
    ========================================================== */

    .ep-security-note {

        display: flex;
        align-items: flex-start;
        gap: 10px;

        margin-top: 18px;
        padding: 12px 14px;

        border-radius: 11px;

        background: #f8fafc;

        border:
            1px solid #eef2f7;

        color: #94a3b8;

        font-size: 0.6rem;
        font-weight: 600;

        line-height: 1.6;
    }


    .ep-security-note i {

        margin-top: 2px;

        color: #8b5cf6;

        font-size: 10px;
    }


    /* =========================================================
       ANIMATIONS
    ========================================================== */

    @keyframes epPageEnter {

        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }


    @keyframes epHeroEnter {

        from {
            opacity: 0;
            transform: translateY(-12px) scale(0.985);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

    }


    @keyframes epCardEnter {

        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }


    @keyframes epIconFloat {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-3px);
        }

    }


    /* =========================================================
       REDUCED MOTION
    ========================================================== */

    @media (prefers-reduced-motion: reduce) {

        .ep-edit-page,
        .ep-edit-hero,
        .ep-edit-card,
        .ep-edit-hero-icon {

            animation: none !important;
        }

        .ep-form-input,
        .ep-form-select,
        .ep-update-btn,
        .ep-cancel-btn {

            transition: none !important;
        }

    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 700px) {

        .ep-form-grid {

            grid-template-columns: 1fr;
        }

        .ep-form-group.full-width {

            grid-column: auto;
        }

        .ep-edit-form {

            padding: 20px;
        }

        .ep-edit-hero {

            padding: 20px;
        }

        .ep-form-actions {

            flex-direction: column-reverse;

            align-items: stretch;
        }

        .ep-cancel-btn,
        .ep-update-btn {

            width: 100%;

            justify-content: center;
        }

    }


    @media (max-width: 480px) {

        .ep-edit-hero-content {

            align-items: flex-start;
        }

        .ep-edit-hero-icon {

            width: 42px;
            height: 42px;
            min-width: 42px;
        }

        .ep-edit-hero h2 {

            font-size: 1rem;
        }

        .ep-edit-hero p {

            font-size: 0.65rem;
        }

    }

</style>


<div class="ep-edit-page">

    {{-- =====================================================
         PAGE HERO
    ====================================================== --}}

    <div class="ep-edit-hero">

        <div class="ep-edit-hero-content">

            <div class="ep-edit-hero-icon">
                <i class="fa-solid fa-user-pen"></i>
            </div>

            <div>

                <h2>
                    Edit Admin Account
                </h2>

                <p>
                    Update administrator details, permissions and event access.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FORM CARD
    ====================================================== --}}

    <div class="ep-edit-card">

        <div class="ep-edit-card-header">

            <div class="ep-card-header-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div>

                <h3>
                    Administrator Information
                </h3>

                <p>
                    Modify the account information below.
                </p>

            </div>

        </div>


        <form
            action="{{ route('admin.admins.update', $admin->id) }}"
            method="POST"
            class="ep-edit-form"
        >

            @csrf

            @method('PUT')


            <div class="ep-form-grid">

                {{-- =================================================
                     FULL NAME
                ================================================== --}}

                <div class="ep-form-group">

                    <label
                        for="name"
                        class="ep-form-label"
                    >

                        <i class="fa-solid fa-user"></i>

                        Full Name

                        <span class="ep-required">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $admin->name) }}"
                        required
                        autocomplete="name"
                        class="ep-form-input"
                        placeholder="Enter administrator name"
                    >

                </div>


                {{-- =================================================
                     EMAIL
                ================================================== --}}

                <div class="ep-form-group">

                    <label
                        for="email"
                        class="ep-form-label"
                    >

                        <i class="fa-solid fa-envelope"></i>

                        Email Address

                        <span class="ep-required">
                            *
                        </span>

                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $admin->email) }}"
                        required
                        autocomplete="email"
                        class="ep-form-input"
                        placeholder="admin@example.com"
                    >

                </div>


                {{-- =================================================
                     PASSWORD
                ================================================== --}}

                <div class="ep-form-group full-width">

                    <label
                        for="password"
                        class="ep-form-label"
                    >

                        <i class="fa-solid fa-lock"></i>

                        New Password

                        <span class="ep-label-hint">
                            Leave blank to keep current password
                        </span>

                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="new-password"
                        class="ep-form-input"
                        placeholder="Enter a new password"
                    >

                    <div class="ep-password-note">

                        <i class="fa-solid fa-circle-info"></i>

                        Your existing password will remain unchanged if this field is empty.

                    </div>

                </div>


                {{-- =================================================
                     ROLE
                ================================================== --}}

                <div class="ep-form-group">

                    <label
                        for="role"
                        class="ep-form-label"
                    >

                        <i class="fa-solid fa-shield-halved"></i>

                        Admin Type

                        <span class="ep-required">
                            *
                        </span>

                    </label>

                    <div class="ep-role-select-wrapper">

                        <select
                            name="role"
                            id="role"
                            required
                            class="ep-form-select"
                            onchange="toggleEventDropdown(this.value)"
                        >

                            <option
                                value="super_admin"
                                {{ old('role', $admin->role) === 'super_admin' ? 'selected' : '' }}
                            >
                                Super Admin
                            </option>

                            <option
                                value="event_admin"
                                {{ old('role', $admin->role) === 'event_admin' ? 'selected' : '' }}
                            >
                                Event Organizer
                            </option>

                        </select>

                    </div>

                    <div class="ep-role-info">

                        <i class="fa-solid fa-circle-info"></i>

                        Choose the level of access for this account.

                    </div>

                </div>


                {{-- =================================================
                     EVENT ASSIGNMENT
                ================================================== --}}

                <div
                    class="ep-form-group ep-event-wrapper
                    {{ old('role', $admin->role) === 'event_admin'
                        ? 'visible-event'
                        : 'hidden-event' }}"
                    id="eventSelectWrapper"
                >

                    <div class="ep-event-box">

                        <label
                            for="event_id"
                            class="ep-event-box-label"
                        >

                            <i class="fa-solid fa-calendar-days"></i>

                            Assign Event

                        </label>

                        <select
                            name="event_id"
                            id="event_id"
                            class="ep-form-select"
                        >

                            <option value="">
                                -- Select Event --
                            </option>

                            @foreach(\App\Models\Event::all() as $event)

                                <option
                                    value="{{ $event->id }}"
                                    {{ old('event_id', $admin->event_id) == $event->id ? 'selected' : '' }}
                                >
                                    {{ $event->title }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SECURITY NOTE
            ================================================== --}}

            <div class="ep-security-note">

                <i class="fa-solid fa-shield-halved"></i>

                <span>
                    Super Admin accounts have full access to Event Plus administration.
                    Event Organizers should only be assigned to the events they manage.
                </span>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="ep-form-actions">

                <a
                    href="{{ route('admin.admins.index') }}"
                    class="ep-cancel-btn"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Cancel

                </a>


                <button
                    type="submit"
                    class="ep-update-btn"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Update Admin

                </button>

            </div>

        </form>

    </div>

</div>


<script>

    function toggleEventDropdown(role) {

        const wrapper = document.getElementById('eventSelectWrapper');
        const eventSelect = document.getElementById('event_id');

        if (!wrapper || !eventSelect) {
            return;
        }

        if (role === 'event_admin') {

            wrapper.classList.remove('hidden-event');

            wrapper.classList.add('visible-event');

        } else {

            wrapper.classList.remove('visible-event');

            wrapper.classList.add('hidden-event');

            eventSelect.value = '';

        }

    }


    document.addEventListener('DOMContentLoaded', function () {

        const roleSelect = document.getElementById('role');

        if (roleSelect) {

            toggleEventDropdown(roleSelect.value);

        }

    });

</script>

@endsection