@extends('layouts.master')

@section('title', 'Event PLUS')

@section('content')

@php

$featuredEvent =
    ($liveEvents ?? collect())->first()
    ?? ($upcomingEvents ?? collect())->first()
    ?? ($pastEvents ?? collect())->first();

@endphp


{{-- =========================================================
BANNER SECTION
========================================================= --}}

<section class="banner position-relative pb-0">

    <div class="overlay"></div>

    <div class="container">

        <div class="inner-banner position-relative text-white">

            <div class="row">

                {{-- LEFT --}}
                <div class="col-lg-6 order-2 order-lg-1">

                    <div class="banner-left text-center pb-lg-5 p-md-0">

                        <div class="banner-image">

                            <img
                                src="{{ $featuredEvent && $featuredEvent->image
                                    ? asset('storage/' . $featuredEvent->image)
                                    : asset('assets/eventen/images/team/4.png') }}"
                                alt="{{ $featuredEvent->title ?? 'Event' }}"
                                class="w-50"
                            >

                            <br>

                        </div>


                        {{-- COUNTDOWN --}}
                        <div class="countdown">

                            <div
                                id="countdown"
                                class="countdown-inner d-flex w-100 bg-white p-2 rounded-5 justify-content-center box-shadow position-relative z-2"
                            >

                                <div class="time m-auto py-4">

                                    <span
                                        id="days"
                                        class="lh-1 h1 fw-bold"
                                    >
                                        00
                                    </span>

                                    <br>

                                    <small class="text-secondary">
                                        Days
                                    </small>

                                </div>


                                <div class="time m-auto py-4">

                                    <span
                                        id="hours"
                                        class="lh-1 h1 fw-bold"
                                    >
                                        00
                                    </span>

                                    <br>

                                    <small class="text-secondary">
                                        Hours
                                    </small>

                                </div>


                                <div class="time m-auto py-4">

                                    <span
                                        id="minutes"
                                        class="lh-1 h1 fw-bold"
                                    >
                                        00
                                    </span>

                                    <br>

                                    <small class="text-secondary">
                                        Minutes
                                    </small>

                                </div>


                                <div class="time m-auto py-4">

                                    <span
                                        id="seconds"
                                        class="lh-1 h1 fw-bold"
                                    >
                                        00
                                    </span>

                                    <br>

                                    <small class="text-secondary">
                                        Seconds
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="col-lg-6 order-1 order-lg-2">

                    <div class="banner-right ms-2 text-center text-lg-start pb-8">

                        <div class="banner-title pb-3">

                            <h4 class="text-white pb-3">

                                UPCOMING NEW

                                <span class="pink">
                                    EVENT
                                </span>

                            </h4>


                            <h1 class="text-white">

                                @if($featuredEvent)

                                    {{ strtoupper($featuredEvent->title) }}

                                @else

                                    EVENT

                                    <span class="pink">
                                        TICKETING
                                    </span>

                                @endif

                            </h1>

                        </div>


                        @if($featuredEvent)

                            {{-- EVENT INFORMATION --}}
                            <div class="banner-event-info pb-3">

                                <ul class="m-0 ps-0 d-sm-flex justify-content-center justify-content-lg-start list-unstyled">

                                    <li class="pe-2 border-end border-1 border-lightgrey">

                                        <i class="fa fa-calendar-o pe-1"></i>

                                        {{ \Carbon\Carbon::parse($featuredEvent->event_date)->format('d M Y') }}

                                    </li>


                                    <li class="ps-2">

                                        <i class="fa fa-map-marker pe-1"></i>

                                        {{ $featuredEvent->location ?? 'Event Location' }}

                                    </li>

                                </ul>

                            </div>


                            {{-- EVENT DESCRIPTION --}}
                            <div class="event-discription">

                                <p class="pb-4 m-0">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($featuredEvent->description ?? ''),
                                        220
                                    ) }}

                                </p>


                                {{-- =================================================
                                LET'S GO BUTTON
                                ================================================= --}}
                                <div class="banner-button">

                                    <a
                                        href="#event-overview"
                                        class="btn-event-go"
                                        aria-label="Go to Event Overview"
                                    >
                                        <span>LET'S GO</span>

                                        <i class="fa fa-arrow-down"></i>
                                    </a>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- WAVE --}}
    <div class="wave overflow-hidden position-absolute w-100 z-0">

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1000 100"
            preserveAspectRatio="none"
            class="d-block position-relative"
        >

            <path
                class="elementor-shape-fill"
                d="M790.5,93.1c-59.3-5.3-116.8-18-192.6-50c-29.6-12.7-76.9-31-100.5-35.9c-23.6-4.9-52.6-7.8-75.5-5.3c-10.2,1.1-22.6,1.4-50.1,7.4c-27.2,6.3-58.2,16.6-79.4,24.7c-41.3,15.9-94.9,21.9-134,22.6C72,58.2,0,25.8,0,25.8V100h1000V65.3c0,0-51.5,19.4-106.2,25.7C839.5,97,814.1,95.2,790.5,93.1z"
            ></path>

        </svg>

    </div>

</section>


{{-- =========================================================
EVENT OVERVIEW
========================================================= --}}

<div id="event-overview">

    @include('includes.overview')

</div>


{{-- =========================================================
PARTNERS SECTION
========================================================= --}}

<section class="partners">

    <div class="container">

        <div class="partner-inner">

            <div class="partner-title text-center pb-6 w-lg-60 m-auto">

                <p class="mb-1 pink">
                    OUR PARTNERS
                </p>

                <h2 class="mb-1">

                    SPONSORS AND

                    <span class="pink">
                        PARTNERS
                    </span>

                </h2>

                <p class="m-0">

                    Excepteur sint occaecat cupidatat non proident,
                    sunt in culpa qui officia deserunt mollit anim
                    id est laborum.

                </p>

            </div>


            <div class="partner-img pb-6">

                <div class="row row-cols-1 row-cols-lg-5 row-cols-md-5">

                    <div class="col p-0 border-end border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/1.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/2.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/3.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/4.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/5.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-top border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/3.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-top border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/4.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-top border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/5.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-end border-top border-sm-0">

                        <div class="p-2 partner-img-box text-center">

                            <img
                                src="{{ asset('assets/eventen/images/icon/1.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>


                    <div class="col p-0 border-top border-0 border-sm-0">

                        <div class="p-2 partner-img-box text-center border-sm-0">

                            <img
                                src="{{ asset('assets/eventen/images/icon/2.png') }}"
                                alt="partner-img"
                                class="opacity-75 w-lg-75 w-md-100 w-40"
                            >

                        </div>

                    </div>

                </div>

            </div>


            <div class="partner-button text-center">

                <a class="btn" href="#">
                    VIEW MORE SPONSORS
                </a>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
TICKET BOOK SECTION
========================================================= --}}

<section class="ticket position-relative">

    <div class="overlay"></div>

    <div class="container">

        <div class="ticket-inner w-lg-75 mx-auto text-center position-relative text-white">

            <div class="ticket-title">

                <h5 class="text-white mb-1">
                    LET'S DO IT HURRY
                </h5>

                <h1 class="text-white mb-2">

                    HAVEN'T BOOKED YOUR SEAT YET?

                    <span class="spin pink d-inline-block">G</span>
                    <span class="spin pink d-inline-block">E</span>
                    <span class="spin pink d-inline-block">T</span>
                    <span class="spin pink d-inline-block">T</span>
                    <span class="spin pink d-inline-block">I</span>
                    <span class="spin pink d-inline-block">C</span>
                    <span class="spin pink d-inline-block">K</span>
                    <span class="spin pink d-inline-block">E</span>
                    <span class="spin pink d-inline-block">T</span>

                </h1>

            </div>


            <div class="ticket-info">

                <p>
                    Find your event and secure your place today.
                </p>

                <div class="ticket-button">

                    <a
                        class="btn btn1"
                        href="#events-section"
                    >
                        GET TICKETS NOW
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
SCHEDULES SECTION
========================================================= --}}

<section class="schedules">

    <div class="container">

        <div class="schedule-inner">

            <div class="schedule-title text-center mb-6 w-lg-60 mx-auto">

                <p class="mb-1 pink">
                    OUR SCHEDULES
                </p>

                <h2 class="mb-1">

                    FOLLOW

                    <span class="pink">
                        EVENT
                    </span>

                    SCHEDULES

                </h2>

                <p class="m-0">

                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Ut elit tellus, luctus nec ullamcorper mattis,
                    pulvinar dapibus leo.

                </p>

            </div>


            {{-- FIRST DAY --}}
            <div class="schedule-list-outer">

                <div class="schedule-list-top mb-7">

                    <div class="row align-items-center">

                        <div class="col-lg-3 col-md-4">

                            <div class="schedule-day text-center text-sm-start py-6 position-relative ps-2 z-1">

                                <h4 class="pink mb-2">
                                    1ST DAY
                                </h4>

                                @if($featuredEvent)

                                    <h6 class="mb-2">

                                        {{ \Carbon\Carbon::parse($featuredEvent->event_date)->format('F d, Y') }}

                                    </h6>

                                    <p>
                                        {{ $featuredEvent->location }}
                                    </p>

                                @else

                                    <h6 class="mb-2">
                                        APRIL 23, 2024
                                    </h6>

                                    <p>

                                        William Mathew Theater<br>

                                        2390 NW 2nd Ave, Miami, FL 33127

                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="col-lg-9 col-md-8">

                            <div class="schedule-list text-center text-sm-start">

                                {{-- SESSION 1 --}}
                                <div class="schedule-list-box bg-lightgrey border border-white border-2 rounded px-6 py-4 mb-5">

                                    <div class="schedule-box-title">

                                        <h5 class="mb-2">

                                            <a href="#" class="black">
                                                REGISTRATION & BREAKFAST
                                            </a>

                                        </h5>

                                    </div>


                                    <div class="schedule-box-info">

                                        <p class="mb-2">

                                            <small>
                                                Registration and event preparation.
                                            </small>

                                        </p>


                                        <ul class="mb-2 p-0">

                                            <li class="d-inline">

                                                <i class="fa fa-clock-o pink me-2"></i>

                                                <small>
                                                    08:30 - 09:30 AM
                                                </small>

                                            </li>


                                            <li class="d-inline">

                                                <i class="fa fa-thumb-tack pink mx-2"></i>

                                                <small>
                                                    Event Hall
                                                </small>

                                            </li>

                                        </ul>

                                    </div>


                                    <div class="schedule-box-bio d-md-flex">

                                        <div class="schedule-bio-image">

                                            <img
                                                src="{{ asset('assets/eventen/images/team/1.jpg') }}"
                                                alt="team-image"
                                                class="me-2 rounded-circle"
                                            >

                                        </div>


                                        <div class="schedule-bio-info">

                                            <p class="mt-1 mb-0">

                                                <a href="#" class="pink">
                                                    EVENT TEAM
                                                </a>

                                            </p>

                                            <small>
                                                Host & Speaker
                                            </small>

                                        </div>

                                    </div>

                                </div>


                                {{-- SESSION 2 --}}
                                <div class="schedule-list-box bg-white border-lightgrey border border-2 rounded px-6 py-4">

                                    <div class="schedule-box-title">

                                        <h5 class="mb-2">

                                            <a href="#" class="black">
                                                EVENT SESSION
                                            </a>

                                        </h5>

                                    </div>


                                    <div class="schedule-box-info">

                                        <p class="mb-2">

                                            <small>
                                                Main event session and activities.
                                            </small>

                                        </p>


                                        <ul class="mb-2 p-0">

                                            <li class="d-inline">

                                                <i class="fa fa-clock-o pink me-2"></i>

                                                <small>
                                                    09:30 - 12:00 PM
                                                </small>

                                            </li>


                                            <li class="d-inline">

                                                <i class="fa fa-thumb-tack pink mx-2"></i>

                                                <small>
                                                    Event Hall
                                                </small>

                                            </li>

                                        </ul>

                                    </div>


                                    <div class="schedule-box-bio d-md-flex">

                                        <div class="schedule-bio-image">

                                            <img
                                                src="{{ asset('assets/eventen/images/team/2.jpg') }}"
                                                alt="team-image"
                                                class="me-2 rounded-circle"
                                            >

                                        </div>


                                        <div class="schedule-bio-info">

                                            <p class="mt-1 mb-0">

                                                <a href="#" class="pink">
                                                    EVENT SPEAKER
                                                </a>

                                            </p>

                                            <small>
                                                Host & Speaker
                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- SECOND DAY --}}
                <div class="schedule-title-bottom">

                    <div class="row align-items-center">

                        <div class="col-lg-3 col-md-4">

                            <div class="schedule-day text-center position-relative text-sm-start py-6 ps-2 z-1">

                                <h4 class="pink mb-2">
                                    2ND DAY
                                </h4>

                                @if($featuredEvent)

                                    <h6 class="mb-2">

                                        {{ \Carbon\Carbon::parse($featuredEvent->event_date)->addDay()->format('F d, Y') }}

                                    </h6>

                                    <p>
                                        {{ $featuredEvent->location }}
                                    </p>

                                @else

                                    <h6 class="mb-2">
                                        APRIL 24, 2024
                                    </h6>

                                    <p>

                                        William Mathew Theater<br>

                                        2390 NW 2nd Ave, Miami, FL 33127

                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="col-lg-9 col-md-8">

                            <div class="schedule-list text-center text-sm-start">

                                {{-- SESSION 1 --}}
                                <div class="schedule-list-box bg-lightgrey border border-white border-2 rounded px-6 py-4 mb-5">

                                    <div class="schedule-box-title">

                                        <h5 class="mb-2">

                                            <a href="#" class="black">
                                                REGISTRATION & BREAKFAST
                                            </a>

                                        </h5>

                                    </div>


                                    <div class="schedule-box-info">

                                        <p class="mb-2">

                                            <small>
                                                Registration and event preparation.
                                            </small>

                                        </p>


                                        <ul class="mb-2 p-0">

                                            <li class="d-inline">

                                                <i class="fa fa-clock-o pink me-2"></i>

                                                <small>
                                                    08:30 - 09:30 AM
                                                </small>

                                            </li>


                                            <li class="d-inline">

                                                <i class="fa fa-thumb-tack pink mx-2"></i>

                                                <small>
                                                    Event Hall
                                                </small>

                                            </li>

                                        </ul>

                                    </div>


                                    <div class="schedule-box-bio d-md-flex">

                                        <div class="schedule-bio-image">

                                            <img
                                                src="{{ asset('assets/eventen/images/team/3.jpg') }}"
                                                alt="team-image"
                                                class="me-2 rounded-circle"
                                            >

                                        </div>


                                        <div class="schedule-bio-info">

                                            <p class="mt-1 mb-0">

                                                <a href="#" class="pink">
                                                    EVENT SPEAKER
                                                </a>

                                            </p>

                                            <small>
                                                Host & Speaker
                                            </small>

                                        </div>

                                    </div>

                                </div>


                                {{-- SESSION 2 --}}
                                <div class="schedule-list-box bg-white border-lightgrey border border-2 rounded px-6 py-4 mb-5">

                                    <div class="schedule-box-title">

                                        <h5 class="mb-2">

                                            <a href="#" class="black">
                                                EXPLORATIONS OF NEW APPROACHES
                                            </a>

                                        </h5>

                                    </div>


                                    <div class="schedule-box-info">

                                        <p class="mb-2">

                                            <small>
                                                Explore new approaches and opportunities.
                                            </small>

                                        </p>


                                        <ul class="mb-2 p-0">

                                            <li class="d-inline">

                                                <i class="fa fa-clock-o pink me-2"></i>

                                                <small>
                                                    09:30 - 12:00 PM
                                                </small>

                                            </li>


                                            <li class="d-inline">

                                                <i class="fa fa-thumb-tack pink mx-2"></i>

                                                <small>
                                                    Event Hall
                                                </small>

                                            </li>

                                        </ul>

                                    </div>


                                    <div class="schedule-box-bio d-md-flex">

                                        <div class="schedule-bio-image">

                                            <img
                                                src="{{ asset('assets/eventen/images/team/1.jpg') }}"
                                                alt="team-image"
                                                class="me-2 rounded-circle"
                                            >

                                        </div>


                                        <div class="schedule-bio-info">

                                            <p class="mt-1 mb-0">

                                                <a href="#" class="pink">
                                                    EVENT TEAM
                                                </a>

                                            </p>

                                            <small>
                                                Host & Speaker
                                            </small>

                                        </div>

                                    </div>

                                </div>


                                <div class="partner-button mt-6">

                                    <a
                                        class="btn"
                                        href="#events-section"
                                    >
                                        VIEW MORE DETAILS
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
GALLERY SECTION
========================================================= --}}

<section class="gallery" id="gallery-section">

    <div class="container">

        <div class="galler-inner">

            <div class="section-title text-center">

                <div class="row align-items-center">

                    <div class="col-lg-6 pb-2">

                        <div class="title-content text-lg-start">

                            <p class="mb-1 pink">
                                EVENT GALLERY
                            </p>

                            <h2 class="mb-1">

                                WAS AN AMAZING

                                <span class="pink">
                                    GALLERY
                                </span>

                            </h2>

                            <p class="m-0">
                                Take a look at some of our memorable events.
                            </p>

                        </div>

                    </div>


                    <div class="col-lg-6">

                        <div class="speaker-button text-lg-end">

                            <a
                                class="btn"
                                href="#gallery-section"
                            >
                                VIEW MORE SHOTS
                            </a>

                        </div>

                    </div>

                </div>

            </div>


            <div class="gallerybox pt-4">

                <div id="selector1" class="grid row">

                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/4.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>


                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/7.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>


                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/6.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>


                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/7.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>


                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/6.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>


                    <div class="item grid-item col-lg-4 col-md-6 px-1 mb-2">

                        <img
                            src="{{ asset('assets/eventen/images/thumbnail/1.jpg') }}"
                            class="w-100 rounded"
                            alt="Event Gallery"
                        >

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection


{{-- =========================================================
EVENT PLUS BUTTON STYLE
========================================================= --}}

@push('styles')

<style>

    /*
    =========================================================
    LET'S GO BUTTON
    =========================================================
    */

    .banner-button {
        position: relative;
        z-index: 50;
        pointer-events: auto;
    }


    .btn-event-go {
        position: relative;
        z-index: 55;

        display: inline-flex !important;

        align-items: center;
        justify-content: center;
        gap: 12px;

        width: auto !important;
        min-width: 145px;
        max-width: 180px;

        min-height: 44px;

        padding: 10px 22px !important;

        border: 0;
        border-radius: 50px;

        background: linear-gradient(
            135deg,
            #ec4899 0%,
            #f43f5e 50%,
            #8b5cf6 100%
        );

        color: #ffffff !important;

        font-size: 13px;
        line-height: 1;
        font-weight: 800;

        letter-spacing: 1.2px;

        text-decoration: none !important;

        cursor: pointer;

        pointer-events: auto !important;

        box-shadow:
            0 8px 22px rgba(236, 72, 153, 0.30);

        overflow: hidden;

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }


    .btn-event-go::before {
        content: "";

        position: absolute;

        top: 0;
        left: -120%;

        width: 80%;
        height: 100%;

        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.35),
            transparent
        );

        transform: skewX(-20deg);

        transition:
            left 0.6s ease;

        pointer-events: none;
    }


    .btn-event-go:hover {
        color: #ffffff !important;

        transform: translateY(-3px);

        box-shadow:
            0 14px 30px rgba(236, 72, 153, 0.42);
    }


    .btn-event-go:hover::before {
        left: 140%;
    }


    .btn-event-go i {
        position: relative;
        z-index: 2;

        font-size: 13px;

        transition:
            transform 0.3s ease;
    }


    .btn-event-go:hover i {
        transform: translateY(3px);
    }


    .btn-event-go span {
        position: relative;
        z-index: 2;
    }


    /*
    =========================================================
    MAKE SURE BANNER CONTENT IS ABOVE OVERLAY
    =========================================================
    */

    .banner .container {
        position: relative;
        z-index: 10;
    }


    .inner-banner {
        position: relative;
        z-index: 15;
    }


    .banner-right {
        position: relative;
        z-index: 20;
    }


    .event-discription {
        position: relative;
        z-index: 30;
    }


    /*
    =========================================================
    PREVENT THE BACKGROUND OVERLAY FROM BLOCKING CLICKS
    =========================================================
    */

    .banner > .overlay {
        position: absolute;
        z-index: 1;
        pointer-events: none !important;
    }


    /*
    =========================================================
    WAVE MUST STAY BEHIND CONTENT
    =========================================================
    */

    .banner .wave {
        z-index: 2 !important;
        pointer-events: none !important;
    }


    /*
    =========================================================
    SMOOTH SCROLL
    =========================================================
    */

    html {
        scroll-behavior: smooth;
    }


    /*
    =========================================================
    SCROLL OFFSET
    =========================================================
    */

    #event-overview {
        scroll-margin-top: 90px;
    }


    /*
    =========================================================
    MOBILE
    =========================================================
    */

    @media (max-width: 767px) {

        .btn-event-go {

            min-width: 135px;
            max-width: 160px;

            min-height: 42px;

            padding: 9px 19px !important;

            font-size: 12px;

            letter-spacing: 1px;

        }

    }

</style>

@endpush


{{-- =========================================================
COUNTDOWN
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    @if($featuredEvent && $featuredEvent->event_date)

        const eventDate = new Date(
            "{{ \Carbon\Carbon::parse($featuredEvent->event_date)->format('Y-m-d H:i:s') }}"
                .replace(' ', 'T')
        ).getTime();


        function updateCountdown() {

            const now = new Date().getTime();

            const distance = eventDate - now;


            if (distance <= 0) {

                document.getElementById('days').innerText = '00';

                document.getElementById('hours').innerText = '00';

                document.getElementById('minutes').innerText = '00';

                document.getElementById('seconds').innerText = '00';

                return;

            }


            const days = Math.floor(
                distance / (1000 * 60 * 60 * 24)
            );


            const hours = Math.floor(
                (distance % (1000 * 60 * 60 * 24))
                / (1000 * 60 * 60)
            );


            const minutes = Math.floor(
                (distance % (1000 * 60 * 60))
                / (1000 * 60)
            );


            const seconds = Math.floor(
                (distance % (1000 * 60))
                / 1000
            );


            document.getElementById('days').innerText =
                String(days).padStart(2, '0');


            document.getElementById('hours').innerText =
                String(hours).padStart(2, '0');


            document.getElementById('minutes').innerText =
                String(minutes).padStart(2, '0');


            document.getElementById('seconds').innerText =
                String(seconds).padStart(2, '0');

        }


        updateCountdown();

        setInterval(updateCountdown, 1000);

    @endif

});

</script>

@endpush

