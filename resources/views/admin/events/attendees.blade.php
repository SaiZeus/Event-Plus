@if(auth('admin')->user()->isEventAdmin())

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Attendees Directory</title>


<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>

<body class="eventplus-standalone-body">

<div class="eventplus-standalone-container">

@else


@extends('layouts.admin')

@section('title', 'Event Attendees & Revenue')
@section('page-title', 'Attendees Directory - ' . $event->title)

@section('content')


@endif

<style>
/* =========================================================
   EVENT PLUS — ATTENDEES DIRECTORY
   UI REDESIGN ONLY
   ========================================================= */

:root {
    --ep-navy: #0f172a;
    --ep-navy-2: #17113b;
    --ep-purple: #6d28d9;
    --ep-violet: #8b5cf6;
    --ep-pink: #ec4899;
    --ep-rose: #f43f5e;
    --ep-lavender: #f5f3ff;
    --ep-light: #f8f7fc;
    --ep-border: #e9e7f1;
    --ep-text: #172033;
    --ep-muted: #7a8192;
    --ep-green: #10b981;
    --ep-orange: #f59e0b;
}

/* ---------------------------------------------------------
   PAGE
   --------------------------------------------------------- */

.eventplus-standalone-body {
    margin: 0;
    min-height: 100vh;
    background:
        radial-gradient(circle at 10% 0%, rgba(139, 92, 246, .08), transparent 28%),
        radial-gradient(circle at 95% 10%, rgba(236, 72, 153, .07), transparent 25%),
        #f7f7fb;
    color: var(--ep-text);
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    padding: 28px;
}

.eventplus-standalone-container {
    max-width: 1500px;
    margin: 0 auto;
}

.attendees-page {
    width: 100%;
}

/* ---------------------------------------------------------
   TOP HERO
   --------------------------------------------------------- */

.ep-attendees-hero {
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    padding: 27px 30px;
    border-radius: 26px;
    background:
        radial-gradient(circle at 82% 15%, rgba(236,72,153,.24), transparent 24%),
        radial-gradient(circle at 100% 100%, rgba(139,92,246,.25), transparent 32%),
        linear-gradient(135deg, #0f172a 0%, #21134d 52%, #32145e 100%);
    color: #fff;
    box-shadow: 0 22px 55px rgba(30, 20, 70, .18);
}

.ep-attendees-hero::before {
    content: "";
    position: absolute;
    width: 260px;
    height: 260px;
    right: -95px;
    top: -135px;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 50%;
}

.ep-attendees-hero::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: 80px;
    bottom: -135px;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 50%;
}

.ep-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.ep-hero-left {
    min-width: 0;
}

.ep-brand-line {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 11px;
    padding: 6px 10px;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 999px;
    background: rgba(255,255,255,.08);
    color: #e9d5ff;
    font-size: .63rem;
    font-weight: 850;
    letter-spacing: .1em;
    text-transform: uppercase;
}

.ep-brand-line i {
    color: #f9a8d4;
}

.ep-hero-title {
    margin: 0;
    font-size: clamp(1.35rem, 2.3vw, 2rem);
    line-height: 1.15;
    font-weight: 900;
    letter-spacing: -.04em;
}

.ep-hero-description {
    margin: 8px 0 0;
    max-width: 680px;
    color: #cbd5e1;
    font-size: .78rem;
    line-height: 1.6;
}

.ep-event-pill {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 16px;
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(12px);
}

.ep-event-pill-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #ec4899, #8b5cf6);
    color: #fff;
    box-shadow: 0 8px 20px rgba(236,72,153,.25);
}

.ep-event-pill-label {
    margin: 0;
    color: #a5b4fc;
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
}

.ep-event-pill-name {
    margin: 3px 0 0;
    max-width: 260px;
    overflow: hidden;
    color: #fff;
    font-size: .76rem;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ---------------------------------------------------------
   HEADER ACTIONS
   --------------------------------------------------------- */

.attendees-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
}

.attendees-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.attendees-title-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: linear-gradient(135deg, #ede9fe, #fce7f3);
    color: var(--ep-purple);
    font-size: 1rem;
    box-shadow: 0 8px 18px rgba(109,40,217,.08);
}

.attendees-title h2 {
    margin: 0;
    color: var(--ep-text);
    font-size: 1rem;
    font-weight: 900;
    letter-spacing: -.025em;
}

.attendees-title p {
    margin: 3px 0 0;
    color: var(--ep-muted);
    font-size: .68rem;
}

.attendees-action-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.ep-excel-button,
.ep-logout-button,
.back-events-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 38px;
    padding: 9px 13px;
    border-radius: 11px;
    font-size: .66rem;
    font-weight: 850;
    transition: all .2s ease;
}

.ep-excel-button {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff;
    box-shadow: 0 8px 20px rgba(16,185,129,.18);
}

.ep-excel-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(16,185,129,.25);
}

.ep-logout-button {
    background: linear-gradient(135deg, #e11d48, #f43f5e);
    color: #fff;
    box-shadow: 0 8px 20px rgba(244,63,94,.16);
}

.ep-logout-button:hover {
    transform: translateY(-1px);
}

.back-events-button {
    border: 1px solid #ddd8ed;
    background: #fff;
    color: #667085;
}

.back-events-button:hover {
    border-color: #c4b5fd;
    color: var(--ep-purple);
    transform: translateX(-2px);
}

/* ---------------------------------------------------------
   SUCCESS
   --------------------------------------------------------- */

.attendee-success-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 12px 15px;
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    color: #166534;
    font-size: .72rem;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(16,185,129,.06);
}

.attendee-success-icon {
    width: 31px;
    height: 31px;
    min-width: 31px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #dcfce7;
    color: #16a34a;
}

/* ---------------------------------------------------------
   STAT CARDS
   --------------------------------------------------------- */

.ep-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 24px;
}

.attendee-stat-card {
    position: relative;
    overflow: hidden;
    min-height: 130px;
    padding: 20px;
    border: 1px solid var(--ep-border);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 10px 35px rgba(31, 25, 60, .055);
    transition: transform .22s ease, box-shadow .22s ease;
}

.attendee-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 17px 42px rgba(31,25,60,.09);
}

.attendee-stat-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 4px;
}

.attendee-stat-card::after {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    right: -65px;
    bottom: -75px;
    border-radius: 50%;
}

.stat-revenue::before {
    background: linear-gradient(90deg, #10b981, #34d399);
}

.stat-revenue::after {
    background: #d1fae5;
}

.stat-runners::before {
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
}

.stat-runners::after {
    background: #ede9fe;
}

.stat-status::before {
    background: linear-gradient(90deg, #3b82f6, #6366f1);
}

.stat-status::after {
    background: #dbeafe;
}

.attendee-stat-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    height: 100%;
}

.stat-label {
    margin: 0;
    color: #8a91a1;
    font-size: .6rem;
    font-weight: 900;
    letter-spacing: .075em;
    text-transform: uppercase;
}

.stat-value {
    margin: 7px 0 0;
    font-size: 1.55rem;
    line-height: 1;
    font-weight: 950;
    letter-spacing: -.045em;
}

.stat-revenue .stat-value {
    color: #059669;
}

.stat-runners .stat-value {
    color: #6d28d9;
}

.stat-status .stat-value {
    color: #2563eb;
    font-size: 1.05rem;
}

.stat-icon {
    width: 49px;
    height: 49px;
    min-width: 49px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    font-size: 1rem;
}

.stat-revenue .stat-icon {
    background: #ecfdf5;
    color: #059669;
}

.stat-runners .stat-icon {
    background: linear-gradient(135deg, #ede9fe, #fce7f3);
    color: #7c3aed;
}

.stat-status .stat-icon {
    background: #eff6ff;
    color: #2563eb;
}

/* ---------------------------------------------------------
   PARTICIPANTS CARD
   --------------------------------------------------------- */

.participants-card {
    overflow: hidden;
    border: 1px solid var(--ep-border);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 15px 45px rgba(31,25,60,.06);
}

.participants-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--ep-border);
    background:
        radial-gradient(circle at 100% 0%, rgba(236,72,153,.06), transparent 25%),
        linear-gradient(135deg, #fff, #faf9ff);
}

.participants-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.participants-title-icon {
    width: 37px;
    height: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: linear-gradient(135deg, #ede9fe, #fce7f3);
    color: #7c3aed;
    font-size: .82rem;
}

.participants-title h3 {
    margin: 0;
    color: #252b3a;
    font-size: .84rem;
    font-weight: 900;
}

.participants-title p {
    margin: 3px 0 0;
    color: #969cac;
    font-size: .63rem;
}

.participant-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 11px;
    border: 1px solid #e9d5ff;
    border-radius: 999px;
    background: #faf5ff;
    color: #7e22ce;
    font-size: .61rem;
    font-weight: 900;
}

.participant-count::before {
    content: "\f183";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
}

/* ---------------------------------------------------------
   TABLE
   --------------------------------------------------------- */

.participants-table {
    width: 100%;
    min-width: 1250px;
    border-collapse: separate;
    border-spacing: 0;
}

.participants-table thead {
    background: #faf9fd;
}

.participants-table thead tr {
    border-bottom: 1px solid var(--ep-border);
}

.participants-table th {
    padding: 13px 15px;
    border-bottom: 1px solid var(--ep-border);
    color: #858b9b;
    font-size: .58rem;
    font-weight: 900;
    letter-spacing: .075em;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.participants-table td {
    padding: 15px;
    border-bottom: 1px solid #f0eef5;
    color: #596273;
    font-size: .69rem;
    vertical-align: middle;
}

.participants-table tbody tr {
    transition: background .18s ease;
}

.participants-table tbody tr:hover {
    background: linear-gradient(90deg, #fcfbff, #fff);
}

.participants-table tbody tr:last-child td {
    border-bottom: 0;
}

/* ---------------------------------------------------------
   RUNNER IDENTITY
   --------------------------------------------------------- */

.runner-identity {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 205px;
}

.runner-avatar {
    width: 37px;
    height: 37px;
    min-width: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #4c1d95, #ec4899);
    color: #fff;
    font-size: .72rem;
    font-weight: 900;
    box-shadow: 0 6px 14px rgba(124,58,237,.18);
}

.runner-identity-content {
    min-width: 0;
}

.runner-code {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border: 1px solid #e9d5ff;
    border-radius: 6px;
    background: #faf5ff;
    color: #7e22ce;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: .58rem;
    font-weight: 900;
}

.runner-name {
    max-width: 190px;
    margin: 5px 0 0;
    overflow: hidden;
    color: #202738;
    font-size: .72rem;
    font-weight: 850;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.runner-father {
    margin-top: 3px;
    color: #9299a8;
    font-size: .59rem;
}

/* ---------------------------------------------------------
   BIB / CATEGORY
   --------------------------------------------------------- */

.bib-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 8px;
    border: 1px solid #ddd6fe;
    border-radius: 8px;
    background: #f5f3ff;
    color: #6d28d9;
    font-size: .6rem;
    font-weight: 900;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    padding: 5px 8px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f8fafc;
    color: #475467;
    font-size: .59rem;
    font-weight: 800;
}

/* ---------------------------------------------------------
   PROMO
   --------------------------------------------------------- */

.promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border: 1px dashed #d8b4fe;
    border-radius: 8px;
    background: #faf5ff;
    color: #7e22ce;
    font-size: .6rem;
    font-weight: 900;
}

.promo-discount {
    margin-top: 4px;
    color: #9333ea;
    font-size: .6rem;
    font-weight: 850;
}

.promo-company {
    margin-top: 3px;
    color: #9ca3af;
    font-size: .58rem;
}

/* ---------------------------------------------------------
   CONTACT
   --------------------------------------------------------- */

.contact-stack {
    min-width: 205px;
}

.contact-line {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
    color: #3f4858;
    font-size: .62rem;
    font-weight: 700;
}

.contact-line:last-child {
    margin-bottom: 0;
}

.contact-line i {
    width: 13px;
    text-align: center;
    color: #8b5cf6;
}

.contact-line.phone {
    color: #64748b;
}

.contact-line.phone i {
    color: #94a3b8;
}

.contact-line.viber {
    color: #7360f2;
}

.contact-line.viber i {
    color: #7360f2;
}

.contact-line.emergency {
    color: #dc2626;
}

.contact-line.emergency i {
    color: #ef4444;
}

/* ---------------------------------------------------------
   DOCUMENT
   --------------------------------------------------------- */

.document-number {
    display: inline-flex;
    align-items: center;
    padding: 6px 8px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f8fafc;
    color: #475467;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: .59rem;
    font-weight: 800;
}

.country-line {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #7b8494;
    font-size: .59rem;
}

/* ---------------------------------------------------------
   NATIONALITY
   --------------------------------------------------------- */

.nationality-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: .59rem;
    font-weight: 900;
}

.nationality-foreigner {
    border: 1px solid #bfdbfe;
    background: #eff6ff;
    color: #2563eb;
}

.nationality-myanmar {
    border: 1px solid #bbf7d0;
    background: #ecfdf5;
    color: #059669;
}

.demographic-row {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
}

.shirt-size {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 7px;
    border-radius: 7px;
    background: #f1f5f9;
    color: #344054;
    font-size: .59rem;
    font-weight: 900;
}

.gender-label {
    color: #667085;
    font-size: .59rem;
    font-weight: 700;
}

.dob-line {
    margin-top: 5px;
    color: #7b8494;
    font-size: .59rem;
}

/* ---------------------------------------------------------
   HEALTH
   --------------------------------------------------------- */

.blood-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 8px;
    background: #fef2f2;
    color: #dc2626;
    font-size: .59rem;
    font-weight: 900;
}

.health-line {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    font-size: .59rem;
    font-weight: 800;
}

.health-clear {
    color: #059669;
}

.health-warning {
    color: #dc2626;
}

.itra-box {
    margin-top: 7px;
    padding-top: 7px;
    border-top: 1px solid #f0eef5;
}

.itra-label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #6d28d9;
    font-size: .59rem;
    font-weight: 900;
}

.small-truncate {
    display: block;
    max-width: 175px;
    margin-top: 3px;
    overflow: hidden;
    color: #8a91a0;
    font-size: .57rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ---------------------------------------------------------
   ADDRESS
   --------------------------------------------------------- */

.address-line,
.experience-line {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    max-width: 190px;
    color: #596273;
    font-size: .59rem;
    line-height: 1.45;
}

.address-line i,
.experience-line i {
    flex-shrink: 0;
    margin-top: 2px;
    color: #a1a1aa;
}

.experience-line {
    margin-top: 7px;
    color: #7b8494;
}

/* ---------------------------------------------------------
   ACTIONS
   --------------------------------------------------------- */

.attendee-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
    white-space: nowrap;
}

.attendee-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-height: 31px;
    padding: 7px 9px;
    border-radius: 8px;
    font-size: .59rem;
    font-weight: 900;
    transition: all .18s ease;
}

.download-ticket-button {
    background: #ecfdf5;
    color: #059669;
}

.download-ticket-button:hover {
    background: #d1fae5;
    transform: translateY(-1px);
}

.edit-attendee-button {
    border: 0;
    background: #fff7ed;
    color: #d97706;
    cursor: pointer;
}

.edit-attendee-button:hover {
    background: #ffedd5;
    transform: translateY(-1px);
}

.delete-attendee-button {
    border: 0;
    background: #fef2f2;
    color: #dc2626;
    cursor: pointer;
}

.delete-attendee-button:hover {
    background: #fee2e2;
    transform: translateY(-1px);
}

/* ---------------------------------------------------------
   EMPTY STATE
   --------------------------------------------------------- */

.attendees-empty-state {
    padding: 75px 20px !important;
    text-align: center;
}

.attendees-empty-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    background: linear-gradient(135deg, #ede9fe, #fce7f3);
    color: #7c3aed;
    font-size: 1.25rem;
}

.attendees-empty-state h3 {
    margin: 0;
    color: #252b3a;
    font-size: .85rem;
    font-weight: 900;
}

.attendees-empty-state p {
    margin: 5px 0 0;
    color: #9299a8;
    font-size: .66rem;
}

/* ---------------------------------------------------------
   EDIT MODAL
   --------------------------------------------------------- */

.edit-runner-modal {
    backdrop-filter: blur(7px);
    background: rgba(15, 23, 42, .66) !important;
}

.edit-runner-modal-card {
    overflow: hidden;
    width: 100%;
    max-width: 850px !important;
    max-height: 91vh;
    overflow-y: auto;
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 24px !important;
    background: #fff;
    box-shadow: 0 35px 100px rgba(15,23,42,.28);
}

.edit-modal-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 23px;
    border-bottom: 1px solid #eeeaf6;
    background:
        radial-gradient(circle at 90% 0%, rgba(236,72,153,.13), transparent 25%),
        linear-gradient(135deg, #17113b, #312e81);
    color: #fff;
}

.edit-modal-header::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 150px;
    height: 3px;
    background: linear-gradient(90deg, #ec4899, #8b5cf6);
}

.edit-modal-title-wrapper {
    display: flex;
    align-items: center;
    gap: 11px;
}

.edit-modal-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255,255,255,.12);
    color: #f9a8d4;
}

.edit-modal-title h3 {
    margin: 0;
    color: #fff;
    font-size: .9rem;
    font-weight: 900;
}

.edit-modal-title p {
    margin: 3px 0 0;
    color: #cbd5e1;
    font-size: .61rem;
}

.edit-modal-close {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    color: #cbd5e1;
    cursor: pointer;
    transition: all .18s ease;
}

.edit-modal-close:hover {
    background: rgba(255,255,255,.17);
    color: #fff;
}

.edit-modal-body {
    padding: 23px;
    background: #fcfbfe;
}

.edit-field {
    margin-bottom: 0;
}

.edit-label {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 6px;
    color: #475467;
    font-size: .62rem;
    font-weight: 850;
}

.edit-input,
.edit-select,
.edit-textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 9px 11px;
    border: 1px solid #ddd9e8;
    border-radius: 10px;
    background: #fff;
    color: #293445;
    font-size: .68rem;
    outline: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.edit-input:focus,
.edit-select:focus,
.edit-textarea:focus {
    border-color: #a78bfa;
    box-shadow: 0 0 0 3px rgba(139,92,246,.10);
}

.edit-textarea {
    resize: vertical;
    border-radius: 12px;
}

.edit-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin: 22px -23px -23px;
    padding: 16px 23px;
    border-top: 1px solid #eeeaf6;
    background: #fff;
}

.cancel-edit-button,
.save-edit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 9px 15px;
    border-radius: 10px;
    font-size: .64rem;
    font-weight: 900;
    cursor: pointer;
    transition: all .18s ease;
}

.cancel-edit-button {
    border: 1px solid #ddd9e8;
    background: #fff;
    color: #667085;
}

.cancel-edit-button:hover {
    border-color: #c4b5fd;
    color: #6d28d9;
}

.save-edit-button {
    border: 0;
    background: linear-gradient(135deg, #6d28d9, #ec4899);
    color: #fff;
    box-shadow: 0 8px 20px rgba(109,40,217,.18);
}

.save-edit-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 25px rgba(109,40,217,.25);
}

/* ---------------------------------------------------------
   RESPONSIVE
   --------------------------------------------------------- */

@media (max-width: 900px) {

    .ep-hero-content {
        align-items: flex-start;
        flex-direction: column;
    }

    .ep-event-pill {
        width: 100%;
    }

    .ep-stats-grid {
        grid-template-columns: 1fr;
    }

    .attendees-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .attendees-action-group {
        width: 100%;
    }
}

@media (max-width: 640px) {

    .eventplus-standalone-body {
        padding: 14px;
    }

    .ep-attendees-hero {
        padding: 22px 20px;
        border-radius: 20px;
    }

    .attendees-title-wrapper {
        align-items: flex-start;
    }

    .attendees-action-group {
        display: grid;
        grid-template-columns: 1fr;
        width: 100%;
    }

    .ep-excel-button,
    .ep-logout-button,
    .back-events-button {
        width: 100%;
    }

    .participants-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .participant-count {
        align-self: flex-start;
    }

    .edit-runner-modal-card {
        max-height: 95vh;
        border-radius: 18px !important;
    }

    .edit-modal-body {
        padding: 17px;
    }

    .edit-modal-footer {
        margin-left: -17px;
        margin-right: -17px;
        margin-bottom: -17px;
        padding-left: 17px;
        padding-right: 17px;
    }
}
</style>

<div class="attendees-page">


{{-- =====================================================
     EVENT PLUS HERO
     ===================================================== --}}

<section class="ep-attendees-hero">

    <div class="ep-hero-content">

        <div class="ep-hero-left">

            <div class="ep-brand-line">
                <i class="fa-solid fa-bolt"></i>
                Event Plus Operations
            </div>

            <h1 class="ep-hero-title">
                Attendee Command Center
            </h1>

            <p class="ep-hero-description">
                Manage registrations, runner information, tickets and event revenue
                from one centralized Event Plus workspace.
            </p>

        </div>

        <div class="ep-event-pill">

            <div class="ep-event-pill-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>
                <p class="ep-event-pill-label">
                    Current Event
                </p>

                <p class="ep-event-pill-name">
                    {{ $event->title }}
                </p>
            </div>

        </div>

    </div>

</section>


{{-- =====================================================
     PAGE HEADER
     ===================================================== --}}

<div class="attendees-header">

    <div class="attendees-title-wrapper">

        <div class="attendees-title-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="attendees-title">

            <h2>
                {{ $event->title }}
            </h2>

            <p>
                Registered attendees, revenue and detailed runner information
            </p>

        </div>

    </div>


    <div class="attendees-action-group">

        {{-- Excel Download --}}

        <a href="{{ route('admin.events.attendees.export', $event->slug) }}"
           class="ep-excel-button">

            <i class="fa-solid fa-file-excel"></i>

            Download Excel Report

        </a>


        @if(!auth('admin')->user()->isEventAdmin())

            <a href="{{ route('admin.events.index') }}"
               class="back-events-button">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Events

            </a>

        @else

            <form action="{{ route('admin.logout') }}"
                  method="POST"
                  class="inline-block">

                @csrf

                <button type="submit"
                        class="ep-logout-button">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </button>

            </form>

        @endif

    </div>

</div>


{{-- =====================================================
     SUCCESS MESSAGE
     ===================================================== --}}

@if(session('success'))

    <div class="attendee-success-alert">

        <div class="attendee-success-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


{{-- =====================================================
     STATISTICS
     ===================================================== --}}

<div class="ep-stats-grid">

    {{-- Revenue --}}

    <div class="attendee-stat-card stat-revenue">

        <div class="attendee-stat-content">

            <div>

                <p class="stat-label">
                    Total Revenue Generated
                </p>

                <h3 class="stat-value">
                    {{ number_format($totalRevenue) }} MMK
                </h3>

            </div>

            <div class="stat-icon">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>

        </div>

    </div>


    {{-- Runners --}}

    <div class="attendee-stat-card stat-runners">

        <div class="attendee-stat-content">

            <div>

                <p class="stat-label">
                    Total Confirmed Runners
                </p>

                <h3 class="stat-value">
                    {{ $attendees->count() }}
                </h3>

            </div>

            <div class="stat-icon">
                <i class="fa-solid fa-person-running"></i>
            </div>

        </div>

    </div>


    {{-- Status --}}

    <div class="attendee-stat-card stat-status">

        <div class="attendee-stat-content">

            <div>

                <p class="stat-label">
                    Event Status
                </p>

                <h3 class="stat-value capitalize">
                    {{ $event->status }}
                </h3>

            </div>

            <div class="stat-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     REGISTERED PARTICIPANTS
     ===================================================== --}}

<div class="participants-card">

    <div class="participants-header">

        <div class="participants-title">

            <div class="participants-title-icon">
                <i class="fa-solid fa-person-running"></i>
            </div>

            <div>

                <h3>
                    Registered Participants
                </h3>

                <p>
                    Manage runners registered for this event
                </p>

            </div>

        </div>

        <span class="participant-count">
            {{ $attendees->count() }} Runners
        </span>

    </div>


    <div class="overflow-x-auto">

        <table class="participants-table">

            <thead>

                <tr>

                    <th>
                        Reg Code / Runner / Father
                    </th>

                    <th>
                        BIB & Category
                    </th>

                    <th>
                        Promo Applied
                    </th>

                    <th>
                        Contact & Emergency
                    </th>

                    <th>
                        NRC / Passport / Country
                    </th>

                    <th>
                        Demographics
                    </th>

                    <th>
                        Health & ITRA
                    </th>

                    <th>
                        Address & Experience
                    </th>

                    @if(!auth('admin')->user()->isEventAdmin())

                        <th class="text-right">
                            Actions
                        </th>

                    @endif

                </tr>

            </thead>


            <tbody>

                @forelse($attendees as $attendee)

                    <tr>

                        {{-- =================================================
                             RUNNER INFO
                             ================================================= --}}

                        <td>

                            <div class="runner-identity">

                                <div class="runner-avatar">

                                    {{ strtoupper(substr($attendee->full_name ?? 'R', 0, 1)) }}

                                </div>

                                <div class="runner-identity-content">

                                    <span class="runner-code">
                                        {{ $attendee->ticket_code }}
                                    </span>

                                    <p class="runner-name">
                                        {{ $attendee->full_name }}
                                    </p>

                                    @if($attendee->father_name)

                                        <p class="runner-father">

                                            <i class="fa-solid fa-user-group"></i>

                                            Father:
                                            {{ $attendee->father_name }}

                                        </p>

                                    @endif

                                </div>

                            </div>

                        </td>


                        {{-- =================================================
                             BIB & CATEGORY
                             ================================================= --}}

                        <td>

                            @if(!empty($attendee->bib_number))

                                <div>

                                    <span class="bib-badge">

                                        <i class="fa-solid fa-hashtag"></i>

                                        BIB:
                                        {{ $attendee->bib_number }}

                                    </span>

                                </div>

                            @elseif(!empty($attendee->bib_name))

                                <div>

                                    <span class="bib-badge">

                                        <i class="fa-solid fa-id-badge"></i>

                                        {{ $attendee->bib_name }}

                                    </span>

                                </div>

                            @endif


                            <span class="category-badge">

                                <i class="fa-solid fa-ticket"></i>

                                {{ $attendee->ticketCategory->name ?? 'N/A' }}

                            </span>

                        </td>


                        {{-- =================================================
                             PROMO
                             ================================================= --}}

                        <td>

                            @if($attendee->promoCode)

                                <div>

                                    <span class="promo-badge">

                                        <i class="fa-solid fa-tags"></i>

                                        {{ $attendee->promoCode->code }}

                                    </span>

                                </div>

                                <p class="promo-discount">

                                    Off:

                                    {{ $attendee->promoCode->discount_type === 'percentage'
                                        ? $attendee->promoCode->discount_value . '%'
                                        : number_format($attendee->promoCode->discount_value) . ' MMK'
                                    }}

                                </p>


                                @if($attendee->promoCode->company_name)

                                    <p class="promo-company">

                                        <i class="fa-solid fa-building"></i>

                                        {{ $attendee->promoCode->company_name }}

                                    </p>

                                @endif

                            @else

                                <span class="text-xs text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             CONTACT
                             ================================================= --}}

                        <td>

                            <div class="contact-stack">

                                <p class="contact-line">

                                    <i class="fa-solid fa-envelope"></i>

                                    {{ $attendee->email }}

                                </p>

                                <p class="contact-line phone">

                                    <i class="fa-solid fa-phone"></i>

                                    {{ $attendee->phone }}

                                </p>


                                @if($attendee->viber)

                                    <p class="contact-line viber">

                                        <i class="fa-brands fa-viber"></i>

                                        Viber:
                                        {{ $attendee->viber }}

                                    </p>

                                @endif


                                <p class="contact-line emergency">

                                    <i class="fa-solid fa-phone-volume"></i>

                                    ICE:
                                    {{ $attendee->emergency_contact ?? 'N/A' }}

                                </p>

                            </div>

                        </td>


                        {{-- =================================================
                             NRC / PASSPORT
                             ================================================= --}}

                        <td>

                            <span class="document-number">

                                {{ $attendee->nrc_passport }}

                            </span>


                            @if($attendee->country)

                                <p class="country-line">

                                    <i class="fa-solid fa-globe"></i>

                                    {{ $attendee->country }}

                                </p>

                            @endif

                        </td>


                        {{-- =================================================
                             DEMOGRAPHICS
                             ================================================= --}}

                        <td>

                            <div>

                                <span class="nationality-badge
                                    {{ strtolower($attendee->nationality) === 'foreigner'
                                        ? 'nationality-foreigner'
                                        : 'nationality-myanmar'
                                    }}">

                                    <i class="fa-solid
                                        {{ strtolower($attendee->nationality) === 'foreigner'
                                            ? 'fa-earth-americas'
                                            : 'fa-flag'
                                        }}">
                                    </i>

                                    {{ $attendee->nationality }}

                                </span>


                                <div class="demographic-row">

                                    <span class="shirt-size">

                                        Size:
                                        {{ $attendee->tshirt_size }}

                                    </span>

                                    <span class="gender-label capitalize">

                                        {{ $attendee->gender ?? 'N/A' }}

                                    </span>

                                </div>


                                @if($attendee->date_of_birth)

                                    <span class="dob-line">

                                        <i class="fa-solid fa-cake-candles"></i>

                                        {{ \Carbon\Carbon::parse($attendee->date_of_birth)->format('Y-m-d') }}

                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- =================================================
                             HEALTH & ITRA
                             ================================================= --}}

                        <td>

                            <div>

                                <span class="blood-badge">

                                    <i class="fa-solid fa-droplet"></i>

                                    {{ $attendee->blood_type ?? 'N/A' }}

                                </span>

                            </div>


                            @if(strtolower($attendee->has_medical_condition) === 'yes')

                                <span class="health-line health-warning">

                                    <i class="fa-solid fa-triangle-exclamation"></i>

                                    Has Condition

                                </span>

                                <p class="small-truncate"
                                   title="{{ $attendee->medical_details }}">

                                    {{ $attendee->medical_details }}

                                </p>

                            @else

                                <span class="health-line health-clear">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Clear

                                </span>

                            @endif


                            @if(strtolower($attendee->itra) === 'yes')

                                <div class="itra-box">

                                    <span class="itra-label">

                                        <i class="fa-solid fa-award"></i>

                                        ITRA Member

                                    </span>


                                    @if($attendee->itra_details)

                                        <p class="small-truncate"
                                           title="{{ $attendee->itra_details }}">

                                            {{ $attendee->itra_details }}

                                        </p>

                                    @endif

                                </div>

                            @endif

                        </td>


                        {{-- =================================================
                             ADDRESS & EXPERIENCE
                             ================================================= --}}

                        <td>

                            <p class="address-line"
                               title="{{ $attendee->address }}">

                                <i class="fa-solid fa-location-dot"></i>

                                <span>
                                    {{ $attendee->address ?? 'N/A' }}
                                </span>

                            </p>


                            @if($attendee->experience)

                                <p class="experience-line"
                                   title="{{ $attendee->experience }}">

                                    <i class="fa-solid fa-person-running"></i>

                                    <span>
                                        {{ $attendee->experience }}
                                    </span>

                                </p>

                            @endif

                        </td>


                        {{-- =================================================
                             ACTIONS — SUPER ADMIN ONLY
                             ================================================= --}}

                        @if(!auth('admin')->user()->isEventAdmin())

                            <td>

                                <div class="attendee-actions">

                                    {{-- Download Ticket --}}

                                    <a href="{{ route('admin.attendees.download_ticket', $attendee->id) }}"
                                       class="attendee-action download-ticket-button"
                                       title="Download Ticket PDF">

                                        <i class="fa-solid fa-download"></i>

                                        Ticket

                                    </a>


                                    {{-- Edit --}}

                                    <button type="button"
                                            onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.remove('hidden')"
                                            class="attendee-action edit-attendee-button">

                                        <i class="fa-solid fa-pen-to-square"></i>

                                        Edit

                                    </button>


                                    {{-- Delete --}}

                                    <form action="{{ route('admin.attendees.destroy', $attendee->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this attendee?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="attendee-action delete-attendee-button">

                                            <i class="fa-solid fa-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        @endif

                    </tr>


                @empty

                    <tr>

                        <td colspan="{{ auth('admin')->user()->isEventAdmin() ? 8 : 9 }}"
                            class="attendees-empty-state">

                            <div class="attendees-empty-icon">

                                <i class="fa-solid fa-users-slash"></i>

                            </div>

                            <h3>
                                No Runners Registered
                            </h3>

                            <p>
                                No runners registered for this event yet.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- =====================================================
     EDIT RUNNER MODALS — SUPER ADMIN ONLY
     ===================================================== --}}

@if(!auth('admin')->user()->isEventAdmin())

    @foreach($attendees as $attendee)

        <div id="edit-modal-{{ $attendee->id }}"
             class="edit-runner-modal fixed inset-0 flex items-center justify-center z-50 hidden p-4">

            <div class="edit-runner-modal-card">

                {{-- MODAL HEADER --}}

                <div class="edit-modal-header">

                    <div class="edit-modal-title-wrapper">

                        <div class="edit-modal-icon">

                            <i class="fa-solid fa-user-pen"></i>

                        </div>

                        <div class="edit-modal-title">

                            <h3>
                                Edit Runner Details
                            </h3>

                            <p>
                                Update participant profile and event record
                            </p>

                        </div>

                    </div>


                    <button type="button"
                            onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.add('hidden')"
                            class="edit-modal-close"
                            aria-label="Close">

                        <i class="fa-solid fa-xmark"></i>

                    </button>

                </div>


                {{-- MODAL BODY --}}

                <div class="edit-modal-body">

                    <form action="{{ route('admin.attendees.update', $attendee->id) }}"
                          method="POST"
                          class="space-y-4">

                        @csrf
                        @method('PUT')


                        {{-- NAME --}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-user"></i>
                                    Full Name
                                </label>

                                <input type="text"
                                       name="full_name"
                                       value="{{ $attendee->full_name }}"
                                       required
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-user-group"></i>
                                    Father Name
                                </label>

                                <input type="text"
                                       name="father_name"
                                       value="{{ $attendee->father_name }}"
                                       class="edit-input">

                            </div>

                        </div>


                        {{-- CONTACT --}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-envelope"></i>
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ $attendee->email }}"
                                       required
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-phone"></i>
                                    Phone
                                </label>

                                <input type="text"
                                       name="phone"
                                       value="{{ $attendee->phone }}"
                                       required
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-brands fa-viber"></i>
                                    Viber Number
                                </label>

                                <input type="text"
                                       name="viber"
                                       value="{{ $attendee->viber }}"
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-phone-volume"></i>
                                    Emergency Contact
                                </label>

                                <input type="text"
                                       name="emergency_contact"
                                       value="{{ $attendee->emergency_contact }}"
                                       required
                                       class="edit-input">

                            </div>

                        </div>


                        {{-- NATIONALITY / DOCUMENT --}}

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-earth-americas"></i>
                                    Nationality
                                </label>

                                <select name="nationality"
                                        class="edit-select">

                                    <option value="Myanmar"
                                        {{ $attendee->nationality === 'Myanmar' ? 'selected' : '' }}>
                                        Myanmar
                                    </option>

                                    <option value="Foreigner"
                                        {{ $attendee->nationality === 'Foreigner' ? 'selected' : '' }}>
                                        Foreigner
                                    </option>

                                </select>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-id-card"></i>
                                    NRC / Passport
                                </label>

                                <input type="text"
                                       name="nrc_passport"
                                       value="{{ $attendee->nrc_passport }}"
                                       required
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-globe"></i>
                                    Country
                                </label>

                                <input type="text"
                                       name="country"
                                       value="{{ $attendee->country }}"
                                       class="edit-input">

                            </div>

                        </div>


                        {{-- PERSONAL / BIB --}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-venus-mars"></i>
                                    Gender
                                </label>

                                <select name="gender"
                                        class="edit-select">

                                    <option value="male"
                                        {{ $attendee->gender === 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="female"
                                        {{ $attendee->gender === 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="prefer_not_to_say"
                                        {{ $attendee->gender === 'prefer_not_to_say' ? 'selected' : '' }}>
                                        Prefer not to say
                                    </option>

                                </select>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-cake-candles"></i>
                                    Date of Birth
                                </label>

                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ $attendee->date_of_birth }}"
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-hashtag"></i>
                                    BIB Number
                                </label>

                                <input type="text"
                                       name="bib_number"
                                       value="{{ $attendee->bib_number }}"
                                       class="edit-input"
                                       placeholder="e.g. 1001">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-id-badge"></i>
                                    BIB Name
                                </label>

                                <input type="text"
                                       name="bib_name"
                                       maxlength="10"
                                       value="{{ $attendee->bib_name }}"
                                       class="edit-input">

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-shirt"></i>
                                    T-Shirt Size
                                </label>

                                <select name="tshirt_size"
                                        class="edit-select">

                                    @foreach(['S', 'M', 'L', 'XL', '2XL'] as $size)

                                        <option value="{{ $size }}"
                                            {{ $attendee->tshirt_size === $size ? 'selected' : '' }}>

                                            {{ $size }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- HEALTH --}}

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-droplet"></i>
                                    Blood Type
                                </label>

                                <select name="blood_type"
                                        class="edit-select">

                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $type)

                                        <option value="{{ $type }}"
                                            {{ $attendee->blood_type === $type ? 'selected' : '' }}>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                    Has Medical Condition?
                                </label>

                                <select name="has_medical_condition"
                                        class="edit-select">

                                    <option value="no"
                                        {{ strtolower($attendee->has_medical_condition) !== 'yes' ? 'selected' : '' }}>
                                        No
                                    </option>

                                    <option value="yes"
                                        {{ strtolower($attendee->has_medical_condition) === 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                </select>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-notes-medical"></i>
                                    Medical Details
                                </label>

                                <input type="text"
                                       name="medical_details"
                                       value="{{ $attendee->medical_details }}"
                                       class="edit-input">

                            </div>

                        </div>


                        {{-- ITRA --}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-award"></i>
                                    ITRA Registered?
                                </label>

                                <select name="itra"
                                        class="edit-select">

                                    <option value="no"
                                        {{ strtolower($attendee->itra) !== 'yes' ? 'selected' : '' }}>
                                        No
                                    </option>

                                    <option value="yes"
                                        {{ strtolower($attendee->itra) === 'yes' ? 'selected' : '' }}>
                                        Yes
                                    </option>

                                </select>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-circle-info"></i>
                                    ITRA Details
                                </label>

                                <input type="text"
                                       name="itra_details"
                                       value="{{ $attendee->itra_details }}"
                                       class="edit-input">

                            </div>

                        </div>


                        {{-- ADDRESS / EXPERIENCE --}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-location-dot"></i>
                                    Address
                                </label>

                                <textarea name="address"
                                          rows="2"
                                          class="edit-textarea">{{ $attendee->address }}</textarea>

                            </div>


                            <div class="edit-field">

                                <label class="edit-label">
                                    <i class="fa-solid fa-person-running"></i>
                                    Running Experience
                                </label>

                                <textarea name="experience"
                                          rows="2"
                                          class="edit-textarea">{{ $attendee->experience }}</textarea>

                            </div>

                        </div>


                        {{-- FOOTER --}}

                        <div class="edit-modal-footer">

                            <button type="button"
                                    onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.add('hidden')"
                                    class="cancel-edit-button">

                                <i class="fa-solid fa-xmark"></i>

                                Cancel

                            </button>


                            <button type="submit"
                                    class="save-edit-button">

                                <i class="fa-solid fa-check"></i>

                                Save Changes

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

@endif


</div>

@if(auth('admin')->user()->isEventAdmin())

</div>

</body>
</html>

@else


@endsection


@endif
