{{-- =========================================================
     FOOTER
========================================================= --}}

<footer id="footer"
        class="pt-9 text-center text-white position-relative z-1">

    <div class="overlay z-n1 start-0"></div>

    <div class="container">

        <div class="footer-content w-lg-50 m-auto">


            {{-- LOGO --}}
            <div class="footer-logo mb-4 pt-1">

                <a href="{{ url('/') }}">

                    <img
                        src="{{ asset('assets/img/logo/Eventplus.png') }}"
                        class="w-50"
                        alt="Footer Logo">

                </a>

            </div>


            {{-- DESCRIPTION --}}
            <div class="footer-disciption border-bottom border-white border-opacity-25 m-auto mb-6">

                <p class="mb-6">

                    Discover and register for exciting events.
                    Find your next experience and secure your
                    ticket through our event ticketing platform.

                </p>


                {{-- SOCIAL --}}
                <div class="footer-socials pb-6">

                    <ul class="m-0 p-0">

                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-block rounded-circle bg-white bg-opacity-25">

                                <i class="fa-brands fa-facebook-f"></i>

                            </a>

                        </li>


                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-block rounded-circle bg-white bg-opacity-25">

                                <i class="fa-brands fa-twitter"></i>

                            </a>

                        </li>


                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-block rounded-circle bg-white bg-opacity-25">

                                <i class="fa-brands fa-youtube"></i>

                            </a>

                        </li>


                        <li class="d-inline me-2">

                            <a href="#"
                               class="d-inline-block rounded-circle bg-white bg-opacity-25">

                                <i class="fa-brands fa-instagram"></i>

                            </a>

                        </li>

                    </ul>

                </div>

            </div>


            {{-- FOOTER MENU --}}
            <div class="footer-menu pb-9">

                <ul class="p-0 m-0">

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


        {{-- COPYRIGHT --}}
        <div class="copyright pb-6 pt-1">

            <small>

                Copyright © {{ date('Y') }}
                Event Plus All Rights Reserved

            </small>

        </div>

    </div>

</footer>