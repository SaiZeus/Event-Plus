@extends('layouts.admin')

@section('title', 'Add Admin')

@section('page-title', 'Create Admin Account')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS — ADD ADMIN
    ========================================================= */

    .ep-create-page {
        width: 100%;
        animation: epPageEnter .55s ease both;
    }

    /* HERO */
    .ep-create-hero {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        padding: 28px 30px;
        margin-bottom: 24px;
        color: #fff;
        background:
            radial-gradient(
                circle at 85% 20%,
                rgba(236, 72, 153, .30),
                transparent 30%
            ),
            radial-gradient(
                circle at 10% 100%,
                rgba(124, 58, 237, .30),
                transparent 35%
            ),
            linear-gradient(
                135deg,
                #0f172a 0%,
                #24104f 55%,
                #4c1d95 100%
            );
        box-shadow: 0 18px 45px rgba(15, 23, 42, .16);
        animation: epHeroEnter .65s ease both;
    }

    .ep-create-hero::before {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -60px;
        top: -80px;
        border-radius: 50%;
        background: rgba(236, 72, 153, .16);
        filter: blur(4px);
        animation: epGlowFloat 5s ease-in-out infinite;
    }

    .ep-create-hero::after {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        left: 35%;
        bottom: -80px;
        border-radius: 50%;
        background: rgba(99, 102, 241, .16);
        filter: blur(5px);
        animation: epGlowFloat 6s ease-in-out infinite reverse;
    }

    .ep-create-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .ep-create-hero-icon {
        width: 58px;
        height: 58px;
        flex: 0 0 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            #ec4899,
            #7c3aed
        );
        box-shadow:
            0 12px 30px rgba(236, 72, 153, .28),
            inset 0 1px 0 rgba(255,255,255,.25);
        animation: epIconFloat 3.5s ease-in-out infinite;
    }

    .ep-create-hero-icon i {
        font-size: 24px;
    }

    .ep-create-hero h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .ep-create-hero p {
        margin: 5px 0 0;
        color: rgba(255,255,255,.68);
        font-size: 13px;
    }

    /* MAIN CARD */
    .ep-create-card {
        max-width: 760px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        padding: 30px;
        box-shadow:
            0 12px 35px rgba(15, 23, 42, .07),
            0 2px 8px rgba(15, 23, 42, .03);
        animation: epCardEnter .7s ease .12s both;
    }

    /* SECTION */
    .ep-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .ep-section-title-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        color: #7c3aed;
        background: #f3e8ff;
    }

    .ep-section-title-icon i {
        font-size: 14px;
    }

    .ep-section-title h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
    }

    .ep-section-title span {
        display: block;
        margin-top: 2px;
        font-size: 11px;
        color: #94a3b8;
    }

    /* FORM GRID */
    .ep-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .ep-field-full {
        grid-column: 1 / -1;
    }

    .ep-field {
        position: relative;
    }

    .ep-field label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .ep-field label i {
        color: #7c3aed;
        font-size: 11px;
    }

    .ep-required {
        color: #ec4899;
    }

    .ep-input-wrap {
        position: relative;
    }

    .ep-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
        transition: .25s ease;
        z-index: 2;
    }

    .ep-input,
    .ep-select {
        width: 100%;
        height: 46px;
        padding: 0 14px 0 40px;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        background: #f8fafc;
        color: #1e293b;
        font-size: 13px;
        outline: none;
        transition:
            border-color .25s ease,
            box-shadow .25s ease,
            background .25s ease,
            transform .25s ease;
    }

    .ep-select {
        cursor: pointer;
        appearance: auto;
    }

    .ep-input::placeholder {
        color: #a8b2c1;
    }

    .ep-input:hover,
    .ep-select:hover {
        border-color: #c4b5fd;
        background: #fff;
    }

    .ep-input:focus,
    .ep-select:focus {
        border-color: #8b5cf6;
        background: #fff;
        box-shadow:
            0 0 0 4px rgba(124, 58, 237, .10),
            0 5px 15px rgba(124, 58, 237, .05);
        transform: translateY(-1px);
    }

    .ep-input:focus + .ep-focus-line {
        transform: scaleX(1);
    }

    .ep-input-wrap:focus-within .ep-input-icon {
        color: #7c3aed;
    }

    .ep-focus-line {
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 0;
        height: 2px;
        border-radius: 99px;
        background: linear-gradient(
            90deg,
            #7c3aed,
            #ec4899
        );
        transform: scaleX(0);
        transform-origin: center;
        transition: transform .3s ease;
        pointer-events: none;
    }

    .ep-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 10px;
    }

    /* EVENT ASSIGNMENT */
    .ep-event-wrapper {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        margin-top: 0;
        transition:
            max-height .4s ease,
            opacity .3s ease,
            margin-top .4s ease;
    }

    .ep-event-wrapper.visible-event {
        max-height: 180px;
        opacity: 1;
        margin-top: 20px;
    }

    .ep-event-box {
        padding: 18px;
        border: 1px solid #e9d5ff;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                rgba(250,245,255,.95),
                rgba(253,242,248,.95)
            );
        animation: epEventReveal .4s ease both;
    }

    .ep-event-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .ep-event-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #db2777;
        background: #fce7f3;
    }

    .ep-event-header strong {
        display: block;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .ep-event-header span {
        display: block;
        margin-top: 2px;
        color: #94a3b8;
        font-size: 10px;
    }

    /* SECURITY NOTE */
    .ep-security-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 24px;
        padding: 15px 16px;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        background: #faf5ff;
    }

    .ep-security-note-icon {
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #7c3aed;
        background: #ede9fe;
    }

    .ep-security-note strong {
        display: block;
        margin-bottom: 3px;
        color: #4c1d95;
        font-size: 11px;
        font-weight: 800;
    }

    .ep-security-note span {
        color: #7c6f91;
        font-size: 10px;
        line-height: 1.5;
    }

    /* ACTIONS */
    .ep-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid #f1f5f9;
    }

    .ep-btn {
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 19px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            background .25s ease;
    }

    .ep-btn-cancel {
        color: #64748b;
        background: #f1f5f9;
    }

    .ep-btn-cancel:hover {
        color: #334155;
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .ep-btn-save {
        position: relative;
        overflow: hidden;
        color: #fff;
        background:
            linear-gradient(
                135deg,
                #7c3aed,
                #ec4899
            );
        box-shadow:
            0 8px 20px rgba(124, 58, 237, .22);
    }

    .ep-btn-save::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 60%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.22),
            transparent
        );
        transform: skewX(-20deg);
        transition: left .6s ease;
    }

    .ep-btn-save:hover::before {
        left: 140%;
    }

    .ep-btn-save:hover {
        transform: translateY(-2px);
        box-shadow:
            0 12px 28px rgba(236, 72, 153, .25);
    }

    .ep-btn-save i {
        transition: transform .25s ease;
    }

    .ep-btn-save:hover i {
        transform: scale(1.12) rotate(-4deg);
    }

    /* ANIMATIONS */
    @keyframes epPageEnter {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes epHeroEnter {
        from {
            opacity: 0;
            transform: translateY(-16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes epCardEnter {
        from {
            opacity: 0;
            transform: translateY(22px) scale(.985);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes epIconFloat {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-4px) rotate(2deg);
        }
    }

    @keyframes epGlowFloat {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        50% {
            transform: translate(-10px, 12px) scale(1.08);
        }
    }

    @keyframes epEventReveal {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {

        .ep-create-hero {
            padding: 22px;
            border-radius: 20px;
        }

        .ep-create-hero-content {
            gap: 13px;
        }

        .ep-create-hero-icon {
            width: 48px;
            height: 48px;
            flex-basis: 48px;
            border-radius: 14px;
        }

        .ep-create-hero-icon i {
            font-size: 20px;
        }

        .ep-create-hero h2 {
            font-size: 20px;
        }

        .ep-create-hero p {
            font-size: 11px;
        }

        .ep-create-card {
            padding: 22px;
            border-radius: 20px;
        }

        .ep-form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .ep-field-full {
            grid-column: auto;
        }
    }

    @media (max-width: 480px) {

        .ep-create-hero {
            padding: 18px;
        }

        .ep-create-hero-icon {
            width: 44px;
            height: 44px;
            flex-basis: 44px;
        }

        .ep-create-hero h2 {
            font-size: 18px;
        }

        .ep-create-card {
            padding: 18px;
        }

        .ep-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .ep-btn {
            width: 100%;
        }
    }

    /* ACCESSIBILITY */
    @media (prefers-reduced-motion: reduce) {
        .ep-create-page,
        .ep-create-hero,
        .ep-create-card,
        .ep-create-hero-icon,
        .ep-create-hero::before,
        .ep-create-hero::after {
            animation: none !important;
        }

        .ep-input,
        .ep-select,
        .ep-btn,
        .ep-event-wrapper {
            transition: none !important;
        }
    }
</style>


<div class="ep-create-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <div class="ep-create-hero">

        <div class="ep-create-hero-content">

            <div class="ep-create-hero-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <div>
                <h2>Create Admin Account</h2>
                <p>
                    Add a new administrator to the Event Plus management system.
                </p>
            </div>

        </div>

    </div>


    {{-- =====================================================
         CREATE FORM
    ====================================================== --}}
    <div class="ep-create-card">

        <div class="ep-section-title">

            <div class="ep-section-title-icon">
                <i class="fa-solid fa-user-gear"></i>
            </div>

            <div>
                <h3>Administrator Information</h3>
                <span>Enter the account details below</span>
            </div>

        </div>


        <form
            action="{{ route('admin.admins.store') }}"
            method="POST"
        >

            @csrf


            <div class="ep-form-grid">

                {{-- FULL NAME --}}
                <div class="ep-field">

                    <label for="name">
                        <i class="fa-solid fa-user"></i>
                        Full Name
                        <span class="ep-required">*</span>
                    </label>

                    <div class="ep-input-wrap">

                        <i class="ep-input-icon fa-solid fa-user"></i>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            placeholder="Enter full name"
                            class="ep-input"
                        >

                        <span class="ep-focus-line"></span>

                    </div>

                </div>


                {{-- EMAIL --}}
                <div class="ep-field">

                    <label for="email">
                        <i class="fa-solid fa-envelope"></i>
                        Email Address
                        <span class="ep-required">*</span>
                    </label>

                    <div class="ep-input-wrap">

                        <i class="ep-input-icon fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="admin@example.com"
                            class="ep-input"
                        >

                        <span class="ep-focus-line"></span>

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div class="ep-field ep-field-full">

                    <label for="password">
                        <i class="fa-solid fa-lock"></i>
                        Password
                        <span class="ep-required">*</span>
                    </label>

                    <div class="ep-input-wrap">

                        <i class="ep-input-icon fa-solid fa-key"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            autocomplete="new-password"
                            placeholder="Create a secure password"
                            class="ep-input"
                        >

                        <span class="ep-focus-line"></span>

                    </div>

                    <div class="ep-help">
                        Use a strong password containing a combination of letters,
                        numbers and special characters.
                    </div>

                </div>


                {{-- ROLE --}}
                <div class="ep-field ep-field-full">

                    <label for="role">
                        <i class="fa-solid fa-shield-halved"></i>
                        Admin Type
                        <span class="ep-required">*</span>
                    </label>

                    <div class="ep-input-wrap">

                        <i class="ep-input-icon fa-solid fa-user-shield"></i>

                        <select
                            name="role"
                            id="role"
                            required
                            class="ep-select"
                            onchange="toggleEventDropdown(this.value)"
                        >

                            <option
                                value="super_admin"
                                {{ old('role', 'super_admin') === 'super_admin' ? 'selected' : '' }}
                            >
                                Super Admin
                            </option>

                            <option
                                value="event_admin"
                                {{ old('role') === 'event_admin' ? 'selected' : '' }}
                            >
                                Event Organizer
                            </option>

                        </select>

                    </div>

                    <div class="ep-help">
                        Super Admins have full system access. Event Organizers
                        are assigned to a specific event.
                    </div>

                </div>

            </div>


            {{-- =================================================
                 EVENT ASSIGNMENT
            ================================================== --}}
            <div
                id="eventSelectWrapper"
                class="ep-event-wrapper {{ old('role') === 'event_admin' ? 'visible-event' : '' }}"
            >

                <div class="ep-event-box">

                    <div class="ep-event-header">

                        <div class="ep-event-icon">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                        <div>
                            <strong>Event Assignment</strong>
                            <span>
                                Select the event this organizer will manage.
                            </span>
                        </div>

                    </div>


                    <div class="ep-input-wrap">

                        <i class="ep-input-icon fa-solid fa-ticket"></i>

                        <select
                            name="event_id"
                            id="event_id"
                            class="ep-select"
                        >

                            <option value="">
                                -- Select Event --
                            </option>

                            @foreach(\App\Models\Event::all() as $event)

                                <option
                                    value="{{ $event->id }}"
                                    {{ old('event_id') == $event->id ? 'selected' : '' }}
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

                <div class="ep-security-note-icon">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div>

                    <strong>Account Security</strong>

                    <span>
                        This account will be able to access the Event Plus
                        administration system according to the selected role.
                        Make sure the credentials are kept secure.
                    </span>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            <div class="ep-form-actions">

                <a
                    href="{{ route('admin.admins.index') }}"
                    class="ep-btn ep-btn-cancel"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="ep-btn ep-btn-save"
                >
                    <i class="fa-solid fa-user-plus"></i>
                    Save Admin
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

            wrapper.classList.add('visible-event');

        } else {

            wrapper.classList.remove('visible-event');

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