<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', 'Admin Dashboard - Event Plus')</title>

{{-- Event Plus favicon --}}
<link
    rel="icon"
    type="image/png"
    href="{{ asset('assets/img/logo/Eventplus.png') }}"
>

{{-- Tailwind CSS --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- Alpine.js --}}
<script
    defer
    src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
></script>

{{-- Font Awesome --}}
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>

<style>

    /* =========================================================
       EVENT PLUS ADMIN THEME
    ========================================================== */

    :root {
        --ep-navy: #0f172a;
        --ep-navy-dark: #090d1a;
        --ep-purple: #4c1d95;
        --ep-purple-light: #7c3aed;
        --ep-indigo: #6366f1;
        --ep-pink: #ec4899;
        --ep-pink-dark: #db2777;
        --ep-bg: #f8fafc;
        --ep-border: #e5e7eb;
        --ep-text: #1e293b;
        --ep-muted: #64748b;
    }


    /* =========================================================
       GLOBAL
    ========================================================== */

    * {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    *::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    *::-webkit-scrollbar-track {
        background: transparent;
    }

    *::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    html,
    body {
        min-height: 100%;
        height: 100%;
    }

    body {
        margin: 0;
        font-family:
            Inter,
            ui-sans-serif,
            system-ui,
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            sans-serif;
        background: var(--ep-bg);
    }


    /* =========================================================
       SIDEBAR
    ========================================================== */

    .admin-sidebar {
        width: 268px;

        background:
            radial-gradient(
                circle at 10% 5%,
                rgba(236, 72, 153, 0.16),
                transparent 27%
            ),
            radial-gradient(
                circle at 100% 55%,
                rgba(124, 58, 237, 0.20),
                transparent 34%
            ),
            linear-gradient(
                180deg,
                #111827 0%,
                #0f172a 48%,
                #090d1a 100%
            );

        border-right: 1px solid rgba(255, 255, 255, 0.06);

        position: relative;
        overflow: hidden;
    }

    .admin-sidebar::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        border: 1px solid rgba(255, 255, 255, 0.045);

        border-radius: 50%;

        top: 110px;
        right: -190px;

        pointer-events: none;
    }

    .admin-sidebar::after {
        content: "";

        position: absolute;

        width: 330px;
        height: 330px;

        border: 1px solid rgba(236, 72, 153, 0.035);

        border-radius: 50%;

        bottom: -210px;
        left: -210px;

        pointer-events: none;
    }


    /* =========================================================
       BRAND
    ========================================================== */

    .admin-brand {
        height: 76px;

        display: flex;
        align-items: center;

        padding: 0 21px;

        border-bottom:
            1px solid rgba(255, 255, 255, 0.07);

        position: relative;
        z-index: 2;
    }

    .admin-brand-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background:
            linear-gradient(
                135deg,
                var(--ep-pink),
                var(--ep-purple-light)
            );

        color: #ffffff;

        font-size: 16px;

        box-shadow:
            0 10px 25px rgba(236, 72, 153, 0.22);
    }

    .admin-brand-text {
        margin-left: 12px;
    }

    .admin-brand-title {
        color: #ffffff;

        font-size: 0.88rem;
        font-weight: 900;

        letter-spacing: 0.11em;
        line-height: 1.2;
    }

    .admin-brand-subtitle {
        margin-top: 4px;

        color: #94a3b8;

        font-size: 0.57rem;
        font-weight: 700;

        letter-spacing: 0.12em;
        text-transform: uppercase;
    }


    /* =========================================================
       NAVIGATION
    ========================================================== */

    .admin-nav {
        padding: 24px 13px;

        position: relative;
        z-index: 2;
    }

    .admin-nav-label {
        padding: 0 12px;
        margin-bottom: 10px;

        color: #64748b;

        font-size: 0.59rem;
        font-weight: 850;

        letter-spacing: 0.13em;
        text-transform: uppercase;
    }

    .admin-nav-link {
        position: relative;

        display: flex;
        align-items: center;

        min-height: 47px;

        padding: 0 13px;
        margin-bottom: 5px;

        border: 1px solid transparent;
        border-radius: 12px;

        color: #94a3b8;

        font-size: 0.76rem;
        font-weight: 650;

        text-decoration: none;

        transition:
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease,
            border-color 0.18s ease,
            box-shadow 0.18s ease;
    }

    .admin-nav-link i {
        width: 29px;

        margin-right: 8px;

        color: #64748b;

        font-size: 0.84rem;

        transition:
            color 0.18s ease,
            transform 0.18s ease;
    }

    .admin-nav-link:hover {
        background:
            rgba(255, 255, 255, 0.055);

        color: #f1f5f9;

        transform: translateX(2px);

        border-color:
            rgba(255, 255, 255, 0.04);
    }

    .admin-nav-link:hover i {
        color: #f9a8d4;

        transform: scale(1.06);
    }


    /* =========================================================
       ACTIVE NAVIGATION
    ========================================================== */

    .admin-nav-link.active {
        background:
            linear-gradient(
                90deg,
                rgba(236, 72, 153, 0.17),
                rgba(124, 58, 237, 0.10)
            );

        border-color:
            rgba(236, 72, 153, 0.12);

        color: #fce7f3;

        font-weight: 800;

        box-shadow:
            inset 3px 0 0 var(--ep-pink),
            0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .admin-nav-link.active i {
        color: #f472b6;
    }


    /* =========================================================
       EXTERNAL LINK
    ========================================================== */

    .admin-nav-link.external {
        margin-top: 22px;
        color: #64748b;
    }

    .admin-nav-link.external:hover {
        color: #e2e8f0;

        background:
            rgba(255, 255, 255, 0.045);
    }


    /* =========================================================
       SIDEBAR FOOTER
    ========================================================== */

    .admin-sidebar-footer {
        padding: 15px;

        border-top:
            1px solid rgba(255, 255, 255, 0.06);

        position: relative;
        z-index: 2;
    }

    .admin-system-status {
        display: flex;
        align-items: center;

        gap: 9px;

        padding: 11px 12px;

        border-radius: 11px;

        background:
            rgba(255, 255, 255, 0.035);

        border:
            1px solid rgba(255, 255, 255, 0.035);
    }

    .admin-status-dot {
        width: 7px;
        height: 7px;

        border-radius: 999px;

        background: #34d399;

        box-shadow:
            0 0 0 4px rgba(52, 211, 153, 0.08);
    }

    .admin-system-status span {
        color: #64748b;

        font-size: 0.61rem;
        font-weight: 700;
    }


    /* =========================================================
       MAIN AREA
    ========================================================== */

    .admin-main {
        background:
            radial-gradient(
                circle at 85% 0%,
                rgba(124, 58, 237, 0.035),
                transparent 25%
            ),
            linear-gradient(
                180deg,
                #f8fafc 0%,
                #f4f6fa 100%
            );

        min-width: 0;
        min-height: 0;

        position: relative;

        overflow: hidden;
    }


    /* =========================================================
       TOP HEADER
    ========================================================== */

    .admin-header {
        min-height: 76px;

        flex-shrink: 0;

        background:
            rgba(255, 255, 255, 0.96);

        border-bottom:
            1px solid #e5e7eb;

        box-shadow:
            0 3px 18px rgba(15, 23, 42, 0.035);

        backdrop-filter:
            blur(14px);

        position: relative;

        z-index: 1000;
    }


    /* =========================================================
       SCROLLABLE CONTENT
    ========================================================== */

    .admin-scroll-area {
        flex: 1;

        min-height: 0;

        overflow-y: auto;
        overflow-x: hidden;

        position: relative;

        z-index: 1;
    }


    /* =========================================================
       PAGE TITLE
    ========================================================== */

    .admin-page-title-wrapper {
        display: flex;
        align-items: center;

        gap: 11px;
    }

    .admin-page-title-icon {
        width: 37px;
        height: 37px;

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

        color: var(--ep-pink-dark);

        font-size: 0.75rem;
    }

    .admin-page-title {
        color: #1e293b;

        font-size: 1rem;
        font-weight: 850;

        letter-spacing: -0.02em;

        margin: 0;
    }


    /* =========================================================
       ADMIN PROFILE
    ========================================================== */

    .admin-profile-wrapper {
        position: relative;

        z-index: 2000;
    }

    .admin-profile {
        display: flex;
        align-items: center;

        gap: 10px;

        padding-left: 16px;

        border-left:
            1px solid #e5e7eb;

        cursor: pointer;

        user-select: none;

        position: relative;

        z-index: 2001;
    }

    .admin-profile-info {
        text-align: right;
    }

    .admin-profile-name {
        color: #344054;

        font-size: 0.71rem;
        font-weight: 800;
    }

    .admin-profile-role {
        margin-top: 2px;

        color: #98a2b3;

        font-size: 0.58rem;
        font-weight: 650;
    }

    .admin-avatar {
        width: 39px;
        height: 39px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                var(--ep-pink),
                var(--ep-purple-light)
            );

        color: #ffffff;

        font-size: 0.76rem;
        font-weight: 900;

        box-shadow:
            0 7px 17px rgba(236, 72, 153, 0.20);
    }


    /* =========================================================
       CONTENT
    ========================================================== */

    .admin-content {
        padding: 29px;

        position: relative;

        z-index: 1;
    }


    /* =========================================================
       SUCCESS ALERT
    ========================================================== */

    .admin-success-alert {
        display: flex;
        align-items: center;

        gap: 11px;

        margin-bottom: 22px;

        padding: 13px 15px;

        border:
            1px solid #bbf7d0;

        border-radius: 12px;

        background:
            #f0fdf4;

        color: #166534;

        font-size: 0.72rem;
        font-weight: 700;

        box-shadow:
            0 4px 12px rgba(22, 101, 52, 0.035);
    }

    .admin-success-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #dcfce7;

        color: #16a34a;
    }


    /* =========================================================
       DROPDOWN
    ========================================================== */

    .admin-dropdown {
        min-width: 210px;

        background: #ffffff;

        border:
            1px solid #eef2f7;

        border-radius: 14px;

        box-shadow:
            0 20px 45px rgba(15, 23, 42, 0.14),
            0 5px 15px rgba(15, 23, 42, 0.05);

        overflow: hidden;

        position: absolute;

        right: 0;
        top: calc(100% + 12px);

        z-index: 99999;
    }

    .admin-dropdown-header {
        padding: 13px 15px;

        border-bottom:
            1px solid #f1f5f9;
    }

    .admin-dropdown-name {
        color: #1e293b;

        font-size: 0.73rem;
        font-weight: 800;
    }

    .admin-dropdown-email {
        margin-top: 3px;

        color: #94a3b8;

        font-size: 0.62rem;
    }

    .admin-logout-button {
        width: 100%;

        display: flex;
        align-items: center;

        gap: 8px;

        padding: 10px 15px;

        border: 0;

        background: transparent;

        color: #e11d48;

        font-size: 0.72rem;
        font-weight: 750;

        cursor: pointer;

        transition:
            background 0.18s ease,
            color 0.18s ease;
    }

    .admin-logout-button:hover {
        background: #fff1f2;

        color: #be123c;
    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 900px) {

        .admin-sidebar {
            width: 220px;
        }

        .admin-content {
            padding: 22px;
        }

        .admin-profile-info {
            display: none;
        }
    }


    @media (max-width: 640px) {

        .admin-sidebar {
            width: 68px;
        }

        .admin-brand {
            justify-content: center;
            padding: 0;
        }

        .admin-brand-text,
        .admin-nav-label,
        .admin-nav-link span,
        .admin-sidebar-footer {
            display: none;
        }

        .admin-brand-icon {
            width: 38px;
            height: 38px;
        }

        .admin-nav {
            padding: 15px 8px;
        }

        .admin-nav-link {
            justify-content: center;
            padding: 0;
        }

        .admin-nav-link i {
            width: auto;
            margin: 0;
        }

        .admin-nav-link.active {
            box-shadow:
                inset 3px 0 0 var(--ep-pink);
        }

        .admin-header {
            padding-left: 16px;
            padding-right: 16px;
        }

        .admin-content {
            padding: 17px;
        }

        .admin-page-title-icon {
            display: none;
        }

        .admin-page-title {
            font-size: 0.88rem;
        }

        .admin-profile {
            padding-left: 10px;
        }

        .admin-dropdown {
            right: -4px;
        }
    }

</style>


</head>

<body class="font-sans antialiased">

@if(auth('admin')->check())


<div class="flex h-screen overflow-hidden">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="admin-sidebar text-slate-200 flex flex-col flex-shrink-0">

        {{-- BRAND --}}

        <div class="admin-brand">

            <div class="admin-brand-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <div class="admin-brand-text">

                <div class="admin-brand-title">
                    EVENT PLUS
                </div>

                <div class="admin-brand-subtitle">
                    Event Management
                </div>

            </div>

        </div>


        {{-- NAVIGATION --}}

        <nav class="admin-nav flex-1">

            <div class="admin-nav-label">
                Main Menu
            </div>


            {{-- DASHBOARD --}}

            <a
                href="{{ route('admin.dashboard') }}"
                class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >

                <i class="fa-solid fa-chart-line"></i>

                <span>
                    Dashboard
                </span>

            </a>


            {{-- MANAGE EVENTS --}}

            <a
                href="{{ route('admin.events.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
            >

                <i class="fa-solid fa-calendar-days"></i>

                <span>
                    Manage Events
                </span>

            </a>


            {{-- MANAGE ADMINS --}}
            {{-- ONLY SUPER ADMIN --}}

            @if(
                method_exists(auth('admin')->user(), 'isSuperAdmin')
                &&
                auth('admin')->user()->isSuperAdmin()
            )

                <a
                    href="{{ route('admin.admins.index') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-shield-halved"></i>

                    <span>
                        Manage Admins
                    </span>

                </a>

            @endif


            {{-- VISIT MAIN SITE --}}

            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="admin-nav-link external"
            >

                <i class="fa-solid fa-arrow-up-right-from-square"></i>

                <span>
                    Visit Event Plus
                </span>

            </a>

        </nav>


        {{-- SIDEBAR FOOTER --}}

        <div class="admin-sidebar-footer">

            <div class="admin-system-status">

                <span class="admin-status-dot"></span>

                <span>
                    System Online
                </span>

            </div>

        </div>

    </aside>


    {{-- =====================================================
         MAIN CONTENT AREA
    ====================================================== --}}

    <div class="admin-main flex-1 flex flex-col">


        {{-- =================================================
             HEADER
        ================================================== --}}

        <header
            class="admin-header px-7 flex items-center justify-between"
        >

            {{-- PAGE TITLE --}}

            <div class="admin-page-title-wrapper">

                <div class="admin-page-title-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <h1 class="admin-page-title">
                    @yield('page-title', 'Dashboard')
                </h1>

            </div>


            {{-- ADMIN PROFILE DROPDOWN --}}

            <div
                class="admin-profile-wrapper"
                x-data="{ open: false }"
            >

                <div
                    class="admin-profile"
                    @click="open = !open"
                >

                    <div class="admin-profile-info">

                        <div class="admin-profile-name">

                            {{ Auth::guard('admin')->user()->name ?? 'Admin User' }}

                        </div>

                        <div class="admin-profile-role">

                            @if(
                                method_exists(
                                    Auth::guard('admin')->user(),
                                    'isSuperAdmin'
                                )
                                &&
                                Auth::guard('admin')->user()->isSuperAdmin()
                            )

                                Super Administrator

                            @else

                                Event Administrator

                            @endif

                        </div>

                    </div>


                    <div class="admin-avatar">

                        {{
                            strtoupper(
                                substr(
                                    Auth::guard('admin')->user()->name ?? 'A',
                                    0,
                                    1
                                )
                            )
                        }}

                    </div>


                    <i
                        class="fa-solid fa-chevron-down text-slate-400 text-[10px] ml-1 transition-transform"
                        :class="{ 'rotate-180': open }"
                    ></i>

                </div>


                {{-- DROPDOWN --}}

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="admin-dropdown"
                    style="display: none;"
                >

                    <div class="admin-dropdown-header">

                        <p class="admin-dropdown-name">

                            {{ Auth::guard('admin')->user()->name ?? 'Admin User' }}

                        </p>

                        <p class="admin-dropdown-email truncate">

                            {{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}

                        </p>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('admin.logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="admin-logout-button"
                        >

                            <i class="fa-solid fa-right-from-bracket"></i>

                            Log Out

                        </button>

                    </form>

                </div>

            </div>

        </header>


        {{-- =================================================
             SCROLLABLE PAGE CONTENT
        ================================================== --}}

        <div class="admin-scroll-area">

            <main class="admin-content">

                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="admin-success-alert">

                        <div class="admin-success-icon">

                            <i class="fa-solid fa-check"></i>

                        </div>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                @endif


                @yield('content')

            </main>

        </div>

    </div>

</div>


@else


{{-- NOT AUTHENTICATED --}}

<main class="w-full">
    @yield('content')
</main>

@endif

</body>

</html>
