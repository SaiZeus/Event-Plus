{{-- =========================================================
EVENT PLUS HEADER
========================================================= --}}

@php
    $isHome = request()->is('/');
@endphp

<header class="eh">

    <div class="eh__bar" id="header_menu">

        <div class="container">

            <nav class="eh__nav" aria-label="Main">

                {{-- LOGO --}}
                <a href="{{ url('/') }}" class="eh__brand" aria-label="Event Plus home">

                    <img
                        src="{{ asset('assets/img/logo/Eventplus.png') }}"
                        alt="Event Plus"
                        class="header-logo-img"
                        decoding="async"
                    >

                </a>

                {{-- LINKS --}}
                <ul class="eh__links">

                    <li>
                        <a
                            href="{{ url('/') }}"
                            class="eh__link eventplus-home-link {{ $isHome ? 'is-active' : '' }}"
                            @if($isHome) aria-current="page" @endif
                        >
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>Home</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/') }}#events-section" class="eh__link">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            <span>Events</span>
                        </a>
                    </li>

                </ul>

            </nav>

        </div>

    </div>

</header>


{{-- =========================================================
EVENT PLUS HEADER STYLE
========================================================= --}}

<style>

    :root {
        --eh-height: 72px;
    }

    /* The header sits on top of each page's dark hero */
    .eh {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 9999;
        width: 100%;
    }

    .eh__bar {
        background: rgba(23, 18, 43, 0.72);
        -webkit-backdrop-filter: blur(14px);
        backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .eh__nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        min-height: var(--eh-height);
    }

    /* ---------- Logo ---------- */

    .eh__brand {
        display: inline-flex;
        align-items: center;
        border-radius: 8px;
    }

    .header-logo-img {
        display: block;
        width: auto;
        height: 52px;
        object-fit: contain;
        border-radius: 6px;

        /* Hides the white background of the logo image over the dark header.
           A transparent PNG would make this line unnecessary. */
        mix-blend-mode: lighten;
    }

    /* ---------- Links ---------- */

    .eh__links {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .eh__link {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        min-height: 2.75rem;
        padding: 0.55rem 1.1rem;
        border: 1px solid transparent;
        border-radius: 999px;
        color: rgba(255, 255, 255, 0.88) !important;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1;
        text-decoration: none !important;
        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease;
    }

    .eh__link i {
        font-size: 0.85rem;
        opacity: 0.85;
    }

    .eh__link:hover {
        border-color: rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    .eh__link.is-active {
        border-color: transparent;
        background: #fff;
        color: #17122b !important;
    }

    .eh__link.is-active i {
        color: #db2777;
        opacity: 1;
    }

    .eh__link:focus-visible,
    .eh__brand:focus-visible {
        outline: 3px solid #fff;
        outline-offset: 3px;
    }

    /* ---------- Small screens ---------- */

    @media (max-width: 575px) {

        :root {
            --eh-height: 64px;
        }

        .header-logo-img {
            height: 44px;
        }

        .eh__link {
            padding: 0.5rem 0.85rem;
            font-size: 0.9rem;
        }

    }

    @media (max-width: 360px) {

        /* Icons only on very narrow phones */
        .eh__link span {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
        }

        .eh__link {
            padding: 0.6rem 0.8rem;
        }

    }

    @media (prefers-reduced-motion: reduce) {

        .eh__link {
            transition: none;
        }

    }


    /* =========================================================
       HERO OFFSET
       The header floats over the page, so heroes that start at
       the very top need room underneath it.
    ========================================================= */

    body .ev-hero,
    body .ww-hero,
    body .rg-hero,
    body .pay-hero,
    body .suc-hero {
        padding-top: calc(var(--eh-height) + clamp(1.25rem, 3vw, 2rem));
    }

</style>