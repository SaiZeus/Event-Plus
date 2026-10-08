{{-- =========================================================
EVENT PLUS HEADER
========================================================= --}}

<header class="main_header_area position-absolute w-100">

    <div class="header_menu" id="header_menu">

        <div class="container">

            <nav class="navbar navbar-expand-lg py-2">

                <div class="d-flex align-items-center justify-content-between w-100">

                    {{-- LEFT SIDE: LOGO + HOME BUTTON --}}

                    <div class="d-flex align-items-center gap-3">

                        <a href="{{ url('/') }}"
                           class="navbar-brand m-0 d-flex align-items-center">

                            <img
                                src="{{ asset('assets/img/logo/Eventplus.png') }}"
                                alt="Event Plus"
                                class="header-logo-img"
                            >

                        </a>

                        <a class="nav-link eventplus-home-link"
                           href="{{ url('/') }}">

                            <i class="fa-solid fa-house me-2"></i>
                            Home

                        </a>

                    </div>

                    {{-- RIGHT SIDE (Optional placeholder for future nav items) --}}

                    <div></div>

                </div>

            </nav>

        </div>

    </div>

</header>


{{-- =========================================================
EVENT PLUS HEADER STYLE
========================================================= --}}

<style>

    .main_header_area {
        z-index: 9999;
        top: 0;
        left: 0;
        right: 0;
    }

    .header_menu {
        width: 100%;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    /* LOGO */

    .header-logo-img {
        height: 70px;
        width: auto;
        object-fit: contain;
        border-radius: 6px;

        /* Hides white background of logo image over dark header */
        mix-blend-mode: lighten;
    }

    /* HOME BUTTON */

    .eventplus-home-link {
        position: relative;
        display: inline-flex !important;
        align-items: center;

        color: #ffffff !important;

        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.3px;

        padding: 8px 18px !important;

        border-radius: 999px;

        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);

        transition: all 0.3s ease;
    }

    .eventplus-home-link i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .eventplus-home-link:hover {
        color: #ffffff !important;

        background: linear-gradient(
            135deg,
            #ec4899,
            #8b5cf6
        );

        border-color: transparent;

        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3);

        transform: translateY(-1px);
    }

    .eventplus-home-link:hover i {
        transform: translateY(-1px);
    }

    @media (max-width: 767px) {

        .header-logo-img {
            height: 77px;
        }

        .eventplus-home-link {
            font-size: 14px;
            padding: 7px 14px !important;
        }

    }

</style>