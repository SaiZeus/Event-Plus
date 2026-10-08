@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('page-title', 'Admin Accounts')

@section('content')

<style>
    /* =========================================================
       EVENT PLUS - ADMIN ACCOUNTS
    ========================================================== */

    .ep-admin-page {
        width: 100%;
    }

    /* =========================================================
       PAGE HEADER CARD
    ========================================================== */

    .ep-admin-hero {
        position: relative;
        overflow: hidden;

        padding: 24px 26px;

        margin-bottom: 20px;

        border-radius: 18px;

        background:
            radial-gradient(
                circle at 100% 0%,
                rgba(236, 72, 153, 0.16),
                transparent 32%
            ),
            radial-gradient(
                circle at 75% 100%,
                rgba(124, 58, 237, 0.14),
                transparent 30%
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
    }

    .ep-admin-hero::before {
        content: "";

        position: absolute;

        width: 210px;
        height: 210px;

        right: -90px;
        top: -110px;

        border-radius: 50%;

        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ep-admin-hero::after {
        content: "";

        position: absolute;

        width: 140px;
        height: 140px;

        right: 90px;
        bottom: -100px;

        border-radius: 50%;

        background:
            rgba(236, 72, 153, 0.08);

        filter: blur(2px);
    }

    .ep-admin-hero-content {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .ep-admin-heading {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .ep-admin-heading-icon {
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

        font-size: 18px;

        box-shadow:
            0 10px 25px rgba(236, 72, 153, 0.25);
    }

    .ep-admin-heading h2 {
        margin: 0;

        color: #ffffff;

        font-size: 1.15rem;
        font-weight: 850;

        letter-spacing: -0.025em;
    }

    .ep-admin-heading p {
        margin: 5px 0 0;

        color: #a5b4fc;

        font-size: 0.72rem;
        font-weight: 600;
    }

    /* =========================================================
       ADD ADMIN BUTTON
    ========================================================== */

    .ep-add-admin-btn {
        position: relative;
        z-index: 3;

        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 11px 17px;

        border-radius: 11px;

        background:
            linear-gradient(
                135deg,
                #ec4899,
                #db2777
            );

        color: #ffffff !important;

        font-size: 0.72rem;
        font-weight: 800;

        text-decoration: none;

        border: 1px solid rgba(255, 255, 255, 0.12);

        box-shadow:
            0 9px 22px rgba(236, 72, 153, 0.25);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            filter 0.2s ease;
    }

    .ep-add-admin-btn:hover {
        color: #ffffff !important;

        transform: translateY(-2px);

        filter: brightness(1.05);

        box-shadow:
            0 13px 28px rgba(236, 72, 153, 0.32);
    }

    .ep-add-admin-btn i {
        font-size: 11px;
    }

    /* =========================================================
       ERROR ALERT
    ========================================================== */

    .ep-error-alert {
        display: flex;
        align-items: center;
        gap: 11px;

        margin-bottom: 18px;

        padding: 13px 15px;

        border:
            1px solid #fecdd3;

        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                #fff1f2,
                #fff7f8
            );

        color: #be123c;

        font-size: 0.72rem;
        font-weight: 700;

        box-shadow:
            0 5px 15px rgba(190, 24, 93, 0.04);
    }

    .ep-error-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #ffe4e6;

        color: #e11d48;
    }

    /* =========================================================
       TABLE CARD
    ========================================================== */

    .ep-admin-table-card {
        overflow: hidden;

        background: #ffffff;

        border:
            1px solid #e8eaf0;

        border-radius: 18px;

        box-shadow:
            0 8px 28px rgba(15, 23, 42, 0.055);
    }

    .ep-table-top {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 17px 21px;

        border-bottom:
            1px solid #eef2f7;
    }

    .ep-table-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .ep-table-title-icon {
        width: 32px;
        height: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background:
            linear-gradient(
                135deg,
                #fce7f3,
                #ede9fe
            );

        color: #db2777;

        font-size: 12px;
    }

    .ep-table-title strong {
        color: #1e293b;

        font-size: 0.75rem;
        font-weight: 850;
    }

    .ep-table-title span {
        margin-left: 5px;

        color: #94a3b8;

        font-size: 0.62rem;
        font-weight: 650;
    }

    /* =========================================================
       TABLE
    ========================================================== */

    .ep-admin-table-wrapper {
        overflow-x: auto;
    }

    .ep-admin-table {
        width: 100%;

        border-collapse: collapse;

        min-width: 760px;
    }

    .ep-admin-table thead th {
        padding: 13px 20px;

        background:
            #fafbfc;

        border-bottom:
            1px solid #eef2f7;

        color: #94a3b8;

        font-size: 0.59rem;
        font-weight: 850;

        letter-spacing: 0.08em;

        text-transform: uppercase;

        white-space: nowrap;
    }

    .ep-admin-table tbody td {
        padding: 15px 20px;

        border-bottom:
            1px solid #f1f5f9;

        color: #64748b;

        font-size: 0.7rem;
        font-weight: 600;

        vertical-align: middle;
    }

    .ep-admin-table tbody tr {
        transition:
            background 0.18s ease;
    }

    .ep-admin-table tbody tr:hover {
        background:
            linear-gradient(
                90deg,
                rgba(236, 72, 153, 0.025),
                rgba(124, 58, 237, 0.025)
            );
    }

    .ep-admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       ADMIN NAME
    ========================================================== */

    .ep-admin-name {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .ep-admin-avatar {
        width: 37px;
        height: 37px;
        min-width: 37px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background:
            linear-gradient(
                135deg,
                #fce7f3,
                #ede9fe
            );

        color: #be185d;

        font-size: 0.68rem;
        font-weight: 900;

        border:
            1px solid #f5d0fe;
    }

    .ep-admin-name-text {
        color: #1e293b;

        font-size: 0.72rem;
        font-weight: 800;
    }

    .ep-admin-email {
        color: #64748b;

        font-size: 0.68rem;
        font-weight: 600;
    }

    /* =========================================================
       ROLE BADGES
    ========================================================== */

    .ep-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 5px 9px;

        border-radius: 999px;

        font-size: 0.57rem;
        font-weight: 850;

        letter-spacing: 0.02em;
    }

    .ep-role-badge i {
        font-size: 8px;
    }

    .ep-role-super {
        color: #9a3412;

        background:
            linear-gradient(
                135deg,
                #fffbeb,
                #fef3c7
            );

        border:
            1px solid #fde68a;
    }

    .ep-role-event {
        color: #6d28d9;

        background:
            linear-gradient(
                135deg,
                #f5f3ff,
                #ede9fe
            );

        border:
            1px solid #ddd6fe;
    }

    /* =========================================================
       EVENT ASSIGNMENT
    ========================================================== */

    .ep-event-assignment {
        margin-top: 6px;

        display: flex;
        align-items: center;
        gap: 6px;

        color: #64748b;

        font-size: 0.65rem;
        font-weight: 650;
    }

    .ep-event-assignment i {
        color: #a855f7;

        font-size: 9px;
    }

    .ep-no-event {
        margin-top: 6px;

        display: flex;
        align-items: center;
        gap: 6px;

        color: #e11d48;

        font-size: 0.62rem;
        font-weight: 700;
    }

    .ep-no-event i {
        font-size: 9px;
    }

    .ep-super-admin-label {
        color: #94a3b8;

        font-size: 0.63rem;
        font-weight: 600;
    }

    /* =========================================================
       CREATED DATE
    ========================================================== */

    .ep-created-date {
        display: flex;
        align-items: center;
        gap: 7px;

        color: #94a3b8;

        white-space: nowrap;
    }

    .ep-created-date i {
        color: #c084fc;

        font-size: 9px;
    }

    /* =========================================================
       ACTIONS
    ========================================================== */

    .ep-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 6px;
    }

    .ep-action-btn {
        width: 32px;
        height: 32px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        text-decoration: none;

        transition:
            transform 0.18s ease,
            background 0.18s ease,
            color 0.18s ease;
    }

    .ep-action-btn:hover {
        transform: translateY(-1px);
    }

    .ep-edit-btn {
        background: #f5f3ff;

        color: #7c3aed;
    }

    .ep-edit-btn:hover {
        background: #ede9fe;

        color: #6d28d9;
    }

    .ep-delete-btn {
        border: 0;

        background: #fff1f2;

        color: #e11d48;

        cursor: pointer;
    }

    .ep-delete-btn:hover {
        background: #ffe4e6;

        color: #be123c;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .ep-empty-state {
        padding: 55px 25px;

        text-align: center;
    }

    .ep-empty-icon {
        width: 58px;
        height: 58px;

        margin: 0 auto 13px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                #fdf2f8,
                #f5f3ff
            );

        color: #c026d3;

        font-size: 20px;
    }

    .ep-empty-state h4 {
        margin: 0;

        color: #334155;

        font-size: 0.85rem;
        font-weight: 850;
    }

    .ep-empty-state p {
        margin: 6px 0 0;

        color: #94a3b8;

        font-size: 0.68rem;
        font-weight: 600;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767px) {

        .ep-admin-hero {
            padding: 20px;
        }

        .ep-admin-hero-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .ep-add-admin-btn {
            width: 100%;

            justify-content: center;
        }

        .ep-admin-heading h2 {
            font-size: 1rem;
        }

        .ep-table-top {
            padding: 15px 17px;
        }
    }

    @media (max-width: 480px) {

        .ep-admin-heading {
            align-items: flex-start;
        }

        .ep-admin-heading-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
        }

        .ep-admin-heading p {
            max-width: 240px;
        }

    }

</style>


<div class="ep-admin-page">

    {{-- =====================================================
         PAGE HERO
    ====================================================== --}}

    <div class="ep-admin-hero">

        <div class="ep-admin-hero-content">

            <div class="ep-admin-heading">

                <div class="ep-admin-heading-icon">
                    <i class="fa-solid fa-users-gear"></i>
                </div>

                <div>

                    <h2>
                        System Administrators
                    </h2>

                    <p>
                        Manage dashboard users and access permissions.
                    </p>

                </div>

            </div>


            <a
                href="{{ route('admin.admins.create') }}"
                class="ep-add-admin-btn"
            >

                <i class="fa-solid fa-plus"></i>

                Add New Admin

            </a>

        </div>

    </div>


    {{-- =====================================================
         ERROR
    ====================================================== --}}

    @if(session('error'))

        <div class="ep-error-alert">

            <div class="ep-error-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         ADMIN TABLE
    ====================================================== --}}

    <div class="ep-admin-table-card">

        {{-- Table Header --}}

        <div class="ep-table-top">

            <div class="ep-table-title">

                <div class="ep-table-title-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <div>

                    <strong>
                        Admin Accounts
                    </strong>

                    <span>
                        {{ $admins->count() }}
                        {{ $admins->count() === 1 ? 'account' : 'accounts' }}
                    </span>

                </div>

            </div>

        </div>


        <div class="ep-admin-table-wrapper">

            <table class="ep-admin-table">

                <thead>

                    <tr>

                        <th>
                            Administrator
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Role & Assigned Event
                        </th>

                        <th>
                            Created
                        </th>

                        <th style="text-align: right;">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($admins as $admin)

                        <tr>

                            {{-- Administrator --}}

                            <td>

                                <div class="ep-admin-name">

                                    <div class="ep-admin-avatar">

                                        {{ strtoupper(substr($admin->name, 0, 1)) }}

                                    </div>

                                    <div>

                                        <div class="ep-admin-name-text">
                                            {{ $admin->name }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}

                            <td>

                                <div class="ep-admin-email">
                                    {{ $admin->email }}
                                </div>

                            </td>


                            {{-- Role --}}

                            <td>

                                @if($admin->role === 'event_admin')

                                    <span class="ep-role-badge ep-role-event">

                                        <i class="fa-solid fa-calendar-check"></i>

                                        Event Organizer

                                    </span>


                                    @if($admin->event)

                                        <div class="ep-event-assignment">

                                            <i class="fa-solid fa-location-dot"></i>

                                            <span>
                                                {{ $admin->event->title }}
                                            </span>

                                        </div>

                                    @else

                                        <div class="ep-no-event">

                                            <i class="fa-solid fa-circle-exclamation"></i>

                                            <span>
                                                No event assigned
                                            </span>

                                        </div>

                                    @endif

                                @elseif($admin->role === 'super_admin')

                                    <span class="ep-role-badge ep-role-super">

                                        <i class="fa-solid fa-crown"></i>

                                        Super Admin

                                    </span>

                                    <div class="ep-super-admin-label">
                                        Full system access
                                    </div>

                                @else

                                    <span class="ep-role-badge ep-role-super">

                                        <i class="fa-solid fa-user-shield"></i>

                                        {{ ucwords(str_replace('_', ' ', $admin->role)) }}

                                    </span>

                                @endif

                            </td>


                            {{-- Created --}}

                            <td>

                                <div class="ep-created-date">

                                    <i class="fa-regular fa-calendar"></i>

                                    {{ $admin->created_at->format('M d, Y') }}

                                </div>

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="ep-actions">

                                    {{-- Edit --}}

                                    <a
                                        href="{{ route('admin.admins.edit', $admin->id) }}"
                                        class="ep-action-btn ep-edit-btn"
                                        title="Edit Admin"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    {{-- Delete --}}

                                    @if(
                                        $admin->email !== 'admin@gmail.com'
                                        &&
                                        auth('admin')->id() !== $admin->id
                                    )

                                        <form
                                            action="{{ route('admin.admins.destroy', $admin->id) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this admin?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="ep-action-btn ep-delete-btn"
                                                title="Delete Admin"
                                            >

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="ep-empty-state">

                                    <div class="ep-empty-icon">

                                        <i class="fa-solid fa-user-shield"></i>

                                    </div>

                                    <h4>
                                        No administrator accounts
                                    </h4>

                                    <p>
                                        Add your first administrator to manage Event Plus.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection