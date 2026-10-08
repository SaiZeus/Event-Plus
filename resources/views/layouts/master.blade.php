<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Event Ticketing')</title>

    <link rel="shortcut icon"
          type="image/x-icon"
          href="{{ asset('assets/img/logo/Eventplus.png') }}">

    {{-- Bootstrap --}}
    <link rel="stylesheet"
          href="{{ asset('assets/eventen/css/bootstrap.min.css') }}">

    {{-- Plugins --}}
    <link rel="stylesheet"
          href="{{ asset('assets/eventen/css/plugin.css') }}">

    {{-- Default CSS --}}
    <link rel="stylesheet"
          href="{{ asset('assets/eventen/css/default.css') }}">

    {{-- Eventen CSS --}}
    <link rel="stylesheet"
          href="{{ asset('assets/eventen/css/styles.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="{{ asset('assets/eventen/icons/font-awesome.min.css') }}">

    @stack('styles')

</head>

<body>

    {{-- HEADER --}}
    @include('includes.header')


    {{-- PAGE CONTENT --}}
    <main>
        @yield('content')
    </main>


    {{-- FOOTER --}}
    @include('includes.footer')


    {{-- Back To Top --}}
    <div id="back-to-top">
        <a href="#"
           class="bg-pink position-relative align-items-center rounded-circle d-block">
        </a>
    </div>


    {{-- JS --}}
    <script src="{{ asset('assets/eventen/js/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('assets/eventen/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/eventen/js/custom-nav.js') }}"></script>

    <script src="{{ asset('assets/eventen/js/plugin.js') }}"></script>

    <script src="{{ asset('assets/eventen/js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>