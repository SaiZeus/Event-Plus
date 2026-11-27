{{-- =========================================================
     FONT AWESOME
     (Remove this line if your layout already loads Font Awesome,
      which the header's "fas fa-home" icons suggest it does.)
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


{{-- =========================================================
     EVENT PLUS FOOTER
========================================================= --}}

@php
    // Replace each "#" with your real profile URL.
    $socials = [
        ['label' => 'Facebook',  'icon' => 'fa-brands fa-facebook-f',  'url' => '#'],
        ['label' => 'X',         'icon' => 'fa-brands fa-x-twitter',   'url' => '#'],
        ['label' => 'YouTube',   'icon' => 'fa-brands fa-youtube',     'url' => '#'],
        ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram',   'url' => '#'],
    ];

    $email = 'eventplusticketing@gmail.com';
@endphp

<footer class="ef" id="footer">

    <div class="container">

        <div class="ef__grid">

            {{-- BRAND --}}
            <div class="ef__brand-col">

                <a href="{{ url('/') }}" class="ef__brand" aria-label="Event Plus home">
                    <img
                        src="{{ asset('assets/img/logo/Eventplus.png') }}"
                        alt="Event Plus"
                        class="ef__logo"
                        loading="lazy"
                        decoding="async"
                    >
                </a>

                <p class="ef__about">
                    Discover and register for exciting events. Find your next
                    experience and secure your ticket through our event
                    ticketing platform.
                </p>

            </div>

            {{-- NAVIGATION --}}
            <nav class="ef__col" aria-label="Footer">

                <h2 class="ef__title">Explore</h2>

                <ul class="ef__list">
                    <li>
                        <a href="{{ url('/') }}" class="ef__link">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}#events-section" class="ef__link">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            <span>Events</span>
                        </a>
                    </li>
                </ul>

            </nav>

            {{-- CONTACT + SOCIAL --}}
            <div class="ef__col">

                <h2 class="ef__title">Get in touch</h2>

                <ul class="ef__list">
                    <li>
                        <a href="mailto:{{ $email }}" class="ef__link ef__link--email">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <span>{{ $email }}</span>
                        </a>
                    </li>
                </ul>

                <ul class="ef__socials" aria-label="Social media">
                    @foreach ($socials as $social)
                        <li>
                            <a
                                href="{{ $social['url'] }}"
                                class="ef__social"
                                aria-label="{{ $social['label'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>

        </div>

        {{-- BOTTOM BAR --}}
        <div class="ef__bottom">

            <small class="ef__copy">
                &copy; {{ date('Y') }} Event Plus. All rights reserved.
            </small>

            <a href="#top" class="ef__top" aria-label="Back to top">
                <span>Back to top</span>
                <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </a>

        </div>

    </div>

</footer>


{{-- =========================================================
     EVENT PLUS FOOTER STYLE
========================================================= --}}

<style>

    .ef {
        position: relative;
        margin-top: 4rem;
        padding: clamp(3rem, 6vw, 4.5rem) 0 1.5rem;
        background:
            radial-gradient(60rem 22rem at 12% -10%, rgba(219, 39, 119, 0.18), transparent 60%),
            #17122b;
        color: rgba(255, 255, 255, 0.78);
        overflow: hidden;
    }

    /* Thin pink-to-violet line along the top edge */
    .ef::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 2px;
        background: linear-gradient(90deg, #db2777, #7c3aed 60%, transparent);
    }

    /* ---------- Layout ---------- */

    .ef__grid {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr) minmax(0, 1.3fr);
        gap: clamp(2rem, 5vw, 4rem);
        padding-bottom: clamp(2rem, 4vw, 3rem);
    }

    /* ---------- Brand ---------- */

    .ef__brand {
        display: inline-flex;
        align-items: center;
        border-radius: 8px;
    }

    .ef__logo {
        display: block;
        width: auto;
        height: 56px;
        object-fit: contain;
        border-radius: 6px;

        /* Same trick as the header: hides the logo's white background.
           A transparent PNG would make this line unnecessary. */
        mix-blend-mode: lighten;
    }

    .ef__about {
        max-width: 34ch;
        margin: 1.25rem 0 0;
        font-size: 0.95rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.7);
    }

    /* ---------- Columns ---------- */

    .ef__title {
        margin: 0 0 1rem;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
        color: #fff;
    }

    .ef__list {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    /* ---------- Pill links (match the header) ---------- */

    .ef__link {
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
        line-height: 1.25;
        text-decoration: none !important;
        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease;
    }

    .ef__link i {
        flex-shrink: 0;
        font-size: 0.85rem;
        color: #f472b6;
    }

    .ef__link span {
        overflow-wrap: anywhere;
    }

    .ef__link:hover {
        border-color: rgba(255, 255, 255, 0.25);
        background: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    /* Email is the main action in the footer, so it gets the filled look */
    .ef__link--email {
        border-color: rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.06);
    }

    /* ---------- Social icons ---------- */

    .ef__socials {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin: 1.25rem 0 0;
        padding: 0;
        list-style: none;
    }

    .ef__social {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        color: #fff !important;
        text-decoration: none !important;
        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease;
    }

    .ef__social i {
        font-size: 1.05rem;
        line-height: 1;
    }

    .ef__social:hover {
        border-color: transparent;
        background: #fff;
        color: #17122b !important;
    }

    /* ---------- Bottom bar ---------- */

    .ef__bottom {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
    }

    .ef__copy {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .ef__top {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-height: 2.75rem;
        padding: 0.4rem 0.9rem;
        border-radius: 999px;
        color: rgba(255, 255, 255, 0.75) !important;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none !important;
        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }

    .ef__top i {
        font-size: 0.75rem;
    }

    .ef__top:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    /* ---------- Focus ---------- */

    .ef a:focus-visible {
        outline: 3px solid #fff;
        outline-offset: 3px;
    }

    /* ---------- Responsive ---------- */

    @media (max-width: 991px) {

        .ef__grid {
            grid-template-columns: 1fr 1fr;
        }

        .ef__brand-col {
            grid-column: 1 / -1;
        }

    }

    @media (max-width: 575px) {

        .ef {
            margin-top: 3rem;
        }

        .ef__grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .ef__logo {
            height: 48px;
        }

        .ef__bottom {
            flex-direction: column;
            align-items: flex-start;
        }

    }

    @media (prefers-reduced-motion: reduce) {

        .ef__link,
        .ef__social,
        .ef__top {
            transition: none;
        }

    }

</style>