{{-- =========================================================
     FONT AWESOME
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


{{-- =========================================================
     FOOTER
========================================================= --}}

<footer id="footer"
        class="pt-9 text-center text-white position-relative z-1">

    <div class="overlay z-n1 start-0"></div>

    <div class="container">

        <div class="footer-content w-lg-50 m-auto">


            {{-- =====================================================
                 LOGO
            ====================================================== --}}

            <div class="footer-logo mb-4 pt-1">

                <a href="{{ url('/') }}">

                    <img
                        src="{{ asset('assets/img/logo/Eventplus.png') }}"
                        class="w-50"
                        alt="Event Plus Logo">

                </a>

            </div>


            {{-- =====================================================
                 DESCRIPTION
            ====================================================== --}}

            <div class="footer-disciption border-bottom border-white border-opacity-25 m-auto mb-6">

                <p class="mb-6">

                    Discover and register for exciting events.
                    Find your next experience and secure your
                    ticket through our event ticketing platform.

                </p>


                {{-- =================================================
                     SOCIAL MEDIA
                ================================================== --}}

                <div class="footer-socials pb-6">

                    <ul class="m-0 p-0 list-unstyled">

                        {{-- Facebook --}}
                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                               aria-label="Facebook">

                                <i class="fa-brands fa-facebook-f"></i>

                            </a>

                        </li>


                        {{-- Twitter / X --}}
                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                               aria-label="Twitter">

                                <i class="fa-brands fa-twitter"></i>

                            </a>

                        </li>


                        {{-- YouTube --}}
                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                               aria-label="YouTube">

                                <i class="fa-brands fa-youtube"></i>

                            </a>

                        </li>


                        {{-- Instagram --}}
                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25"
                               aria-label="Instagram">

                                <i class="fa-brands fa-instagram"></i>

                            </a>

                        </li>

                    </ul>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER MENU
            ====================================================== --}}

            <div class="footer-menu pb-9">

                {{-- EMAIL --}}
                <div class="footer-email mb-3">

                    <a href="mailto:eventplusticketing@gmail.com"
                       class="text-white text-decoration-none">

                        <i class="fa-solid fa-envelope me-2"></i>

                        eventplusticketing@gmail.com

                    </a>

                </div>


                {{-- HOME --}}
                <ul class="p-0 m-0 list-unstyled">

                    <li class="d-inline mx-2">

                        <a href="{{ url('/') }}">

                            <small>
                                Home
                            </small>

                        </a>

                    </li>

                </ul>

            </div>

        </div>


        {{-- =====================================================
             COPYRIGHT
        ====================================================== --}}

        <div class="copyright pb-6 pt-1">

            <small>

                Copyright © {{ date('Y') }}
                Event Plus All Rights Reserved

            </small>

        </div>

    </div>

</footer>


{{-- =========================================================
     FOOTER ICON STYLING
========================================================= --}}

<style>

    .footer-socials ul {
        list-style: none;
    }

    .footer-socials li {
        list-style: none;
    }

    .footer-socials a {
        width: 44px;
        height: 44px;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-socials a i {
        font-size: 17px;
        line-height: 1;
    }

    .footer-socials a:hover {
        background-color: #ffffff !important;
        color: #3b0764 !important;
        transform: translateY(-4px);
    }

    .footer-email a {
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .footer-email a:hover {
        opacity: 0.75;
    }

    .footer-menu a {
        color: #ffffff;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .footer-menu a:hover {
        opacity: 0.7;
    }

</style>