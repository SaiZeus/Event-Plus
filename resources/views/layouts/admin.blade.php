<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', 'Admin Dashboard - Event Plus')</title>

{{-- Event Plus favicon --}}
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo/Eventplus.png') }}">

{{-- Tailwind CSS (kept so existing pages using utility classes keep working) --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* =========================================================
       EVENT PLUS ADMIN LAYOUT (redesigned)
    ========================================================== */
    :root {
        --adm-purple: #7c3aed;
        --adm-pink: #ec4899;
        --adm-bg: #f5f6fa;
        --adm-border: #e8eaf0;
        --adm-text: #1e293b;
        --adm-muted: #64748b;
        --adm-sidebar-w: 264px;
        --adm-sidebar-w-collapsed: 78px;
        --adm-header-h: 68px;
    }
    [x-cloak] { display: none !important; }

    * { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    *::-webkit-scrollbar { width: 6px; height: 6px; }
    *::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }

    html, body { height: 100%; }
    body {
        margin: 0; background: var(--adm-bg); color: var(--adm-text);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
        font-size: 15px;
    }

    .adm-shell { display: flex; height: 100vh; overflow: hidden; }

    /* ---------- Sidebar ---------- */
    .adm-sidebar {
        position: relative; z-index: 60; flex-shrink: 0; display: flex; flex-direction: column;
        width: var(--adm-sidebar-w); color: #cbd5e1; overflow: hidden;
        background:
            radial-gradient(circle at 10% 0%, rgba(236,72,153,.18), transparent 30%),
            radial-gradient(circle at 100% 60%, rgba(124,58,237,.2), transparent 35%),
            linear-gradient(180deg, #111827, #0b1020);
        transition: width .2s ease, transform .25s ease;
    }
    .adm-collapsed .adm-sidebar { width: var(--adm-sidebar-w-collapsed); }

    .adm-brand { display: flex; align-items: center; gap: 12px; height: var(--adm-header-h); padding: 0 20px; border-bottom: 1px solid rgba(255,255,255,.07); text-decoration: none; }
    .adm-brand-icon {
        width: 40px; height: 40px; flex-shrink: 0; border-radius: 12px; color: #fff; font-size: 1rem;
        background: linear-gradient(135deg, var(--adm-pink), var(--adm-purple));
        display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(236,72,153,.25);
    }
    .adm-brand-title { color: #fff; font-weight: 800; letter-spacing: .1em; font-size: .9rem; line-height: 1.15; white-space: nowrap; }
    .adm-brand-sub { margin-top: 3px; color: #94a3b8; font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; white-space: nowrap; }

    .adm-nav { flex: 1; padding: 18px 12px; overflow-y: auto; }
    .adm-nav-label { padding: 0 12px; margin: 0 0 8px; color: #64748b; font-size: .66rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; white-space: nowrap; }
    .adm-link {
        position: relative; display: flex; align-items: center; gap: 12px; height: 46px; padding: 0 14px; margin-bottom: 4px;
        border: 1px solid transparent; border-radius: 12px; color: #a8b3c7; font-size: .92rem; font-weight: 600;
        text-decoration: none; white-space: nowrap; transition: background .15s, color .15s, border-color .15s;
    }
    .adm-link i { width: 20px; flex-shrink: 0; text-align: center; color: #64748b; font-size: .95rem; transition: color .15s; }
    .adm-link:hover { background: rgba(255,255,255,.06); color: #f1f5f9; }
    .adm-link:hover i { color: #f9a8d4; }
    .adm-link.active {
        background: linear-gradient(90deg, rgba(236,72,153,.2), rgba(124,58,237,.1));
        border-color: rgba(236,72,153,.15); color: #fff; font-weight: 700;
        box-shadow: inset 3px 0 0 var(--adm-pink);
    }
    .adm-link.active i { color: #f472b6; }
    .adm-nav-sep { height: 1px; margin: 14px 8px; background: rgba(255,255,255,.07); }

    .adm-sidebar-foot { padding: 14px; border-top: 1px solid rgba(255,255,255,.07); }
    .adm-status { display: flex; align-items: center; gap: 10px; padding: 11px 12px; border-radius: 11px; background: rgba(255,255,255,.04); color: #94a3b8; font-size: .78rem; font-weight: 600; white-space: nowrap; }
    .adm-status i { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 4px rgba(52,211,153,.12); flex-shrink: 0; }

    /* collapsed: hide text, show tooltips */
    .adm-collapsed .adm-brand { justify-content: center; padding: 0; }
    .adm-collapsed .adm-brand-text,
    .adm-collapsed .adm-nav-label,
    .adm-collapsed .adm-link span,
    .adm-collapsed .adm-status span { display: none; }
    .adm-collapsed .adm-link { justify-content: center; padding: 0; }
    .adm-collapsed .adm-status { justify-content: center; }
    .adm-collapsed .adm-nav { overflow: visible; }
    .adm-collapsed .adm-sidebar { overflow: visible; }
    .adm-collapsed .adm-link::after {
        content: attr(data-tip); position: absolute; left: calc(100% + 12px); top: 50%; transform: translateY(-50%);
        padding: 6px 11px; border-radius: 8px; background: #0f172a; color: #fff; font-size: .78rem; font-weight: 600;
        white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .12s; box-shadow: 0 8px 20px rgba(0,0,0,.25);
    }
    .adm-collapsed .adm-link:hover::after { opacity: 1; }

    /* ---------- Main ---------- */
    .adm-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }

    .adm-header {
        position: relative; z-index: 50; flex-shrink: 0; height: var(--adm-header-h);
        display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 0 24px;
        background: rgba(255,255,255,.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--adm-border);
    }
    .adm-header-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .adm-icon-btn {
        width: 40px; height: 40px; flex-shrink: 0; border: 1px solid var(--adm-border); border-radius: 11px;
        background: #fff; color: var(--adm-muted); font-size: .95rem; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; transition: all .15s;
    }
    .adm-icon-btn:hover { border-color: #c4b5fd; color: var(--adm-purple); background: #faf5ff; }
    .adm-title-wrap { min-width: 0; }
    .adm-crumb { margin: 0 0 2px; color: #94a3b8; font-size: .72rem; font-weight: 600; white-space: nowrap; }
    .adm-crumb i { font-size: .55rem; margin: 0 5px; }
    .adm-title { margin: 0; font-size: 1.15rem; font-weight: 800; letter-spacing: -.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .adm-header-right { display: flex; align-items: center; gap: 12px; }
    .adm-site-btn {
        display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 14px; border: 1px solid var(--adm-border);
        border-radius: 11px; background: #fff; color: var(--adm-muted); font-size: .82rem; font-weight: 700; text-decoration: none; transition: all .15s;
    }
    .adm-site-btn:hover { border-color: #c4b5fd; color: var(--adm-purple); background: #faf5ff; }

    .adm-profile-wrap { position: relative; }
    .adm-profile {
        display: flex; align-items: center; gap: 10px; padding: 5px 10px 5px 5px; border: 1px solid var(--adm-border);
        border-radius: 14px; background: #fff; cursor: pointer; transition: border-color .15s;
    }
    .adm-profile:hover { border-color: #c4b5fd; }
    .adm-avatar {
        width: 36px; height: 36px; border-radius: 10px; color: #fff; font-size: .85rem; font-weight: 800;
        background: linear-gradient(135deg, var(--adm-pink), var(--adm-purple));
        display: flex; align-items: center; justify-content: center;
    }
    .adm-profile-info { text-align: left; line-height: 1.2; }
    .adm-profile-name { font-size: .82rem; font-weight: 700; max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .adm-profile-role { margin-top: 2px; color: #94a3b8; font-size: .68rem; font-weight: 600; }
    .adm-chev { color: #94a3b8; font-size: .65rem; transition: transform .15s; }
    .adm-menu {
        position: absolute; right: 0; top: calc(100% + 10px); width: 250px; z-index: 100;
        background: #fff; border: 1px solid var(--adm-border); border-radius: 14px; overflow: hidden;
        box-shadow: 0 20px 45px rgba(15,23,42,.15);
    }
    .adm-menu-head { display: flex; align-items: center; gap: 11px; padding: 14px 16px; border-bottom: 1px solid #f1f5f9; }
    .adm-menu-name { font-size: .85rem; font-weight: 700; }
    .adm-menu-email { margin-top: 2px; color: #94a3b8; font-size: .74rem; max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .adm-menu a, .adm-menu button {
        width: 100%; display: flex; align-items: center; gap: 10px; padding: 11px 16px; border: 0; background: transparent;
        color: var(--adm-text); font-size: .85rem; font-weight: 600; text-decoration: none; cursor: pointer; text-align: left;
    }
    .adm-menu a:hover { background: #f8fafc; }
    .adm-menu a i, .adm-menu button i { width: 16px; color: #94a3b8; }
    .adm-menu button { color: #e11d48; }
    .adm-menu button i { color: #e11d48; }
    .adm-menu button:hover { background: #fff1f2; }

    /* ---------- Content ---------- */
    .adm-scroll { flex: 1; min-height: 0; overflow-y: auto; overflow-x: hidden; }
    .adm-content { max-width: 1440px; margin: 0 auto; padding: 26px 28px 40px; }

    /* ---------- Toasts ---------- */
    .adm-toasts { position: fixed; top: 82px; right: 20px; z-index: 2000; display: flex; flex-direction: column; gap: 10px; width: min(380px, calc(100vw - 40px)); }
    .adm-toast {
        display: flex; align-items: flex-start; gap: 12px; padding: 13px 14px; border-radius: 14px; background: #fff;
        border: 1px solid var(--adm-border); box-shadow: 0 16px 40px rgba(15,23,42,.14); font-size: .86rem; font-weight: 600;
    }
    .adm-toast-icon { width: 30px; height: 30px; flex-shrink: 0; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: .8rem; }
    .adm-toast.success { border-color: #bbf7d0; }
    .adm-toast.success .adm-toast-icon { background: #dcfce7; color: #16a34a; }
    .adm-toast.error { border-color: #fecdd3; }
    .adm-toast.error .adm-toast-icon { background: #ffe4e6; color: #e11d48; }
    .adm-toast p { flex: 1; margin: 5px 0 0; line-height: 1.4; }
    .adm-toast-x { border: 0; background: transparent; color: #94a3b8; cursor: pointer; font-size: .9rem; padding: 4px; }
    .adm-toast-x:hover { color: #475569; }

    /* ---------- Mobile drawer ---------- */
    .adm-overlay { position: fixed; inset: 0; z-index: 55; background: rgba(15,23,42,.55); backdrop-filter: blur(2px); }
    .adm-desktop-only { display: inline-flex; }
    .adm-mobile-only  { display: none; }

    @media (max-width: 900px) {
        .adm-sidebar { position: fixed; top: 0; bottom: 0; left: 0; width: var(--adm-sidebar-w) !important; transform: translateX(-100%); box-shadow: 0 0 60px rgba(0,0,0,.4); }
        .adm-sidebar.open { transform: translateX(0); }
        /* ignore the collapsed look inside the drawer */
        .adm-collapsed .adm-brand { justify-content: flex-start; padding: 0 20px; }
        .adm-collapsed .adm-brand-text, .adm-collapsed .adm-nav-label, .adm-collapsed .adm-link span, .adm-collapsed .adm-status span { display: initial; }
        .adm-collapsed .adm-link { justify-content: flex-start; padding: 0 14px; }
        .adm-collapsed .adm-link::after { display: none; }
        .adm-desktop-only { display: none; }
        .adm-mobile-only  { display: inline-flex; }
        .adm-header { padding: 0 16px; }
        .adm-content { padding: 18px 16px 32px; }
        .adm-profile-info, .adm-chev { display: none; }
        .adm-profile { padding: 4px; }
        .adm-site-btn span { display: none; }
        .adm-site-btn { width: 40px; padding: 0; justify-content: center; }
        .adm-crumb { display: none; }
    }
</style>

@stack('styles')
</head>

<body class="antialiased">

@if(auth('admin')->check())

@php
    $admin   = auth('admin')->user();
    $isSuper = method_exists($admin, 'isSuperAdmin') && $admin->isSuperAdmin();
    $pageTitle = trim($__env->yieldContent('page-title')) ?: 'Dashboard';
@endphp

<div class="adm-shell"
     x-data="{
        mobileOpen: false,
        collapsed: false,
        init() {
            try { this.collapsed = localStorage.getItem('ep_sidebar_collapsed') === '1'; } catch (e) {}
            this.$watch('collapsed', v => { try { localStorage.setItem('ep_sidebar_collapsed', v ? '1' : '0'); } catch (e) {} });
        },
        toggleSidebar() {
            if (window.matchMedia('(max-width: 900px)').matches) { this.mobileOpen = !this.mobileOpen; }
            else { this.collapsed = !this.collapsed; }
        }
     }"
     :class="{ 'adm-collapsed': collapsed }"
     @keydown.escape.window="mobileOpen = false">

    {{-- ===== Mobile overlay ===== --}}
    <div class="adm-overlay" x-show="mobileOpen" x-cloak @click="mobileOpen = false"
         x-transition.opacity></div>

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    <aside class="adm-sidebar" :class="{ 'open': mobileOpen }">

        <a href="{{ route('admin.dashboard') }}" class="adm-brand">
            <div class="adm-brand-icon"><i class="fa-solid fa-ticket"></i></div>
            <div class="adm-brand-text">
                <div class="adm-brand-title">EVENT PLUS</div>
                <div class="adm-brand-sub">Event Management</div>
            </div>
        </a>

        <nav class="adm-nav">
            <p class="adm-nav-label">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="adm-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               data-tip="Dashboard">
                <i class="fa-solid fa-chart-line"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('admin.events.index') }}"
               class="adm-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
               data-tip="Manage Events">
                <i class="fa-solid fa-calendar-days"></i><span>Manage Events</span>
            </a>

            {{-- Super admin only --}}
            @if($isSuper)
                <a href="{{ route('admin.admins.index') }}"
                   class="adm-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                   data-tip="Manage Admins">
                    <i class="fa-solid fa-shield-halved"></i><span>Manage Admins</span>
                </a>
            @endif

            <div class="adm-nav-sep"></div>

            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
               class="adm-link" data-tip="Visit Event Plus">
                <i class="fa-solid fa-arrow-up-right-from-square"></i><span>Visit Event Plus</span>
            </a>
        </nav>

        <div class="adm-sidebar-foot">
            <div class="adm-status"><i></i><span>System Online</span></div>
        </div>
    </aside>

    {{-- =====================================================
         MAIN
    ====================================================== --}}
    <div class="adm-main">

        <header class="adm-header">

            <div class="adm-header-left">
                <button type="button" class="adm-icon-btn" @click="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="adm-title-wrap">
                    <p class="adm-crumb">Event Plus <i class="fa-solid fa-chevron-right"></i> Admin</p>
                    <h1 class="adm-title">{{ $pageTitle }}</h1>
                </div>
            </div>

            <div class="adm-header-right">

                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="adm-site-btn" title="Open the public site">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i><span>View site</span>
                </a>

                {{-- Profile dropdown --}}
                <div class="adm-profile-wrap" x-data="{ open: false }" @keydown.escape.window="open = false">
                    <div class="adm-profile" @click="open = !open" role="button" tabindex="0"
                         @keydown.enter="open = !open" aria-haspopup="true" :aria-expanded="open">
                        <div class="adm-avatar">{{ strtoupper(mb_substr($admin->name ?? 'A', 0, 1)) }}</div>
                        <div class="adm-profile-info">
                            <div class="adm-profile-name">{{ $admin->name ?? 'Admin User' }}</div>
                            <div class="adm-profile-role">{{ $isSuper ? 'Super Administrator' : 'Event Administrator' }}</div>
                        </div>
                        <i class="fa-solid fa-chevron-down adm-chev" :style="open ? 'transform: rotate(180deg)' : ''"></i>
                    </div>

                    <div class="adm-menu" x-show="open" x-cloak @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">

                        <div class="adm-menu-head">
                            <div class="adm-avatar">{{ strtoupper(mb_substr($admin->name ?? 'A', 0, 1)) }}</div>
                            <div>
                                <div class="adm-menu-name">{{ $admin->name ?? 'Admin User' }}</div>
                                <div class="adm-menu-email">{{ $admin->email ?? 'admin@example.com' }}</div>
                            </div>
                        </div>

                        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                        <a href="{{ route('admin.events.index') }}"><i class="fa-solid fa-calendar-days"></i> Manage Events</a>

                        <form method="POST" action="{{ route('admin.logout') }}" style="border-top: 1px solid #f1f5f9">
                            @csrf
                            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ===== Toasts (success / error flash messages) ===== --}}
        @if(session('success') || session('error'))
            <div class="adm-toasts">
                @if(session('success'))
                    <div class="adm-toast success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-4" role="status">
                        <div class="adm-toast-icon"><i class="fa-solid fa-check"></i></div>
                        <p>{{ session('success') }}</p>
                        <button type="button" class="adm-toast-x" @click="show = false" aria-label="Dismiss"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="adm-toast error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-4" role="alert">
                        <div class="adm-toast-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <p>{{ session('error') }}</p>
                        <button type="button" class="adm-toast-x" @click="show = false" aria-label="Dismiss"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== Page content ===== --}}
        <div class="adm-scroll">
            <main class="adm-content">
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

@stack('scripts')
</body>
</html>