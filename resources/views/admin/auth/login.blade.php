<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Event Plus - Admin Login</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/Eventplus.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================================
           EVENT PLUS — ADMIN LOGIN (redesigned, no Bootstrap needed)
        ========================================================== */
        :root {
            --lg-navy: #0f172a;
            --lg-purple: #7c3aed;
            --lg-pink: #ec4899;
            --lg-pink-dark: #db2777;
            --lg-border: #e2e8f0;
            --lg-muted: #64748b;
            --lg-faint: #94a3b8;
            --lg-danger: #e11d48;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 16px;
            color: var(--lg-navy);
            overflow-x: hidden;
            background:
                radial-gradient(circle at 15% 15%, rgba(236, 72, 153, .18), transparent 28%),
                radial-gradient(circle at 85% 80%, rgba(124, 58, 237, .22), transparent 30%),
                linear-gradient(135deg, #090d1a 0%, #111827 48%, #24113f 100%);
        }

        /* ---------- Card ---------- */
        .lg-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 980px;
            min-height: 620px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            border-radius: 28px;
            background: #fff;
            box-shadow: 0 35px 90px rgba(0, 0, 0, .4), 0 10px 35px rgba(15, 23, 42, .18);
        }

        /* ---------- Brand side ---------- */
        .lg-brand {
            position: relative;
            overflow: hidden;
            padding: 48px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background:
                radial-gradient(circle at 20% 15%, rgba(236, 72, 153, .3), transparent 30%),
                radial-gradient(circle at 85% 80%, rgba(124, 58, 237, .35), transparent 35%),
                linear-gradient(145deg, #0f172a 0%, #24113f 55%, #4c1d95 100%);
        }
        .lg-brand::before, .lg-brand::after {
            content: ""; position: absolute; border-radius: 50%; pointer-events: none;
        }
        .lg-brand::before { width: 280px; height: 280px; right: -120px; top: -80px; border: 1px solid rgba(255,255,255,.1); }
        .lg-brand::after  { width: 380px; height: 380px; left: -220px; bottom: -220px; border: 1px solid rgba(255,255,255,.07); }
        .lg-brand > * { position: relative; z-index: 2; }

        .lg-logo { display: inline-flex; align-items: center; gap: 12px; margin-bottom: 48px; }
        .lg-logo-icon {
            width: 46px; height: 46px; border-radius: 14px; font-size: 1.15rem; color: #fff;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--lg-pink), #8b5cf6);
            box-shadow: 0 10px 25px rgba(236, 72, 153, .3);
        }
        .lg-logo-name { font-size: 1.1rem; font-weight: 900; letter-spacing: .12em; line-height: 1; }
        .lg-logo-tag  { margin-top: 5px; color: rgba(255,255,255,.65); font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; }

        .lg-heading { margin: 0 0 20px; font-size: clamp(2.1rem, 4vw, 3.1rem); line-height: 1.06; font-weight: 900; letter-spacing: -.04em; }
        .lg-heading span {
            background: linear-gradient(90deg, #f9a8d4, #ec4899, #c4b5fd);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .lg-lead { max-width: 400px; margin: 0; color: rgba(255,255,255,.72); line-height: 1.7; font-size: .95rem; }

        .lg-features { display: flex; flex-direction: column; gap: 13px; margin-top: 40px; }
        .lg-feature { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,.8); font-size: .88rem; }
        .lg-feature i {
            width: 30px; height: 30px; flex: 0 0 30px; border-radius: 9px; font-size: .75rem; color: #f9a8d4;
            display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.1);
        }
        .lg-copy { margin-top: 40px; color: rgba(255,255,255,.45); font-size: .76rem; }

        /* ---------- Form side ---------- */
        .lg-panel { display: flex; align-items: center; justify-content: center; padding: 48px 52px; background: #fff; }
        .lg-panel-inner { width: 100%; max-width: 380px; }

        .lg-mobile-brand { display: none; align-items: center; gap: 10px; margin-bottom: 26px; }
        .lg-mobile-brand .lg-logo-icon { width: 40px; height: 40px; font-size: 1rem; border-radius: 12px; }
        .lg-mobile-brand span { font-weight: 900; font-size: .95rem; letter-spacing: .12em; }

        .lg-title { margin: 0 0 8px; font-size: 1.7rem; font-weight: 850; letter-spacing: -.03em; }
        .lg-sub   { margin: 0 0 26px; color: var(--lg-muted); font-size: .92rem; line-height: 1.55; }

        /* ---------- Alerts ---------- */
        .lg-alert {
            display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px; padding: 12px 14px;
            border-radius: 12px; font-size: .86rem; font-weight: 600; line-height: 1.45;
        }
        .lg-alert i { margin-top: 2px; }
        .lg-alert.error   { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }
        .lg-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        /* ---------- Fields ---------- */
        .lg-field { margin-bottom: 18px; }
        .lg-label { display: block; margin-bottom: 7px; color: #334155; font-size: .84rem; font-weight: 700; }
        .lg-wrap { position: relative; }
        .lg-wrap > i.lead {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%); z-index: 2;
            color: var(--lg-faint); font-size: .9rem; pointer-events: none; transition: color .2s;
        }
        .lg-input {
            width: 100%; height: 52px; padding: 0 16px 0 44px; border: 1.5px solid var(--lg-border); border-radius: 13px;
            background: #f8fafc; color: var(--lg-navy); font: inherit; font-size: .95rem; outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .lg-input::placeholder { color: #a1aab8; }
        .lg-input:hover { background: #fff; border-color: #cbd5e1; }
        .lg-input:focus { background: #fff; border-color: var(--lg-pink); box-shadow: 0 0 0 4px rgba(236, 72, 153, .13); }
        .lg-wrap:focus-within > i.lead { color: var(--lg-pink); }
        .lg-input.invalid { border-color: #fda4af; background: #fff; }
        .lg-input.invalid:focus { border-color: var(--lg-danger); box-shadow: 0 0 0 4px rgba(225, 29, 72, .12); }
        .lg-wrap.has-toggle .lg-input { padding-right: 52px; }

        .lg-toggle {
            position: absolute; right: 6px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px;
            border: 0; border-radius: 10px; background: transparent; color: var(--lg-faint); cursor: pointer; transition: all .15s;
        }
        .lg-toggle:hover { color: var(--lg-pink); background: #fdf2f8; }
        .lg-toggle:focus-visible { outline: 2px solid #f9a8d4; }

        .lg-msg { display: flex; align-items: center; gap: 6px; margin: 7px 0 0; font-size: .8rem; font-weight: 600; }
        .lg-msg.error { color: var(--lg-danger); }
        .lg-msg.warn  { color: #b45309; display: none; }
        .lg-msg.warn.show { display: flex; }

        /* ---------- Remember ---------- */
        .lg-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 4px 0 22px; }
        .lg-check { display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: var(--lg-muted); font-size: .88rem; user-select: none; }
        .lg-check input { position: absolute; opacity: 0; pointer-events: none; }
        .lg-box {
            width: 20px; height: 20px; border: 1.5px solid #cbd5e1; border-radius: 6px; background: #fff;
            display: flex; align-items: center; justify-content: center; color: #fff; font-size: .62rem; transition: all .15s;
        }
        .lg-box i { opacity: 0; transform: scale(.6); transition: all .15s; }
        .lg-check input:checked + .lg-box { background: var(--lg-pink); border-color: var(--lg-pink); }
        .lg-check input:checked + .lg-box i { opacity: 1; transform: scale(1); }
        .lg-check input:focus-visible + .lg-box { box-shadow: 0 0 0 4px rgba(236, 72, 153, .18); }

        /* ---------- Button ---------- */
        .lg-btn {
            width: 100%; height: 54px; border: 0; border-radius: 13px; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            background: linear-gradient(135deg, #ec4899 0%, #db2777 45%, #7c3aed 100%);
            color: #fff; font: inherit; font-size: .95rem; font-weight: 800;
            box-shadow: 0 12px 25px rgba(219, 39, 119, .22); transition: transform .2s, box-shadow .2s, filter .2s;
        }
        .lg-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 30px rgba(219, 39, 119, .3); filter: brightness(1.03); }
        .lg-btn:active { transform: translateY(0); }
        .lg-btn:focus-visible { outline: 3px solid #f9a8d4; outline-offset: 2px; }
        .lg-btn:disabled { cursor: wait; opacity: .85; transform: none; filter: none; }
        .lg-spinner {
            display: none; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff;
            border-radius: 50%; animation: lg-spin .7s linear infinite;
        }
        .lg-btn.loading .lg-spinner { display: inline-block; }
        .lg-btn.loading .lg-arrow { display: none; }
        @keyframes lg-spin { to { transform: rotate(360deg); } }

        .lg-help { margin: 18px 0 0; text-align: center; color: var(--lg-muted); font-size: .84rem; }
        .lg-secure {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 22px; padding-top: 18px; border-top: 1px solid #f1f5f9; color: var(--lg-faint); font-size: .76rem;
        }
        .lg-secure i { color: #a78bfa; }

        /* ---------- Responsive ---------- */
        @media (max-width: 850px) {
            .lg-card { max-width: 500px; min-height: auto; grid-template-columns: 1fr; }
            .lg-brand { display: none; }
            .lg-mobile-brand { display: inline-flex; }
            .lg-panel { padding: 38px 32px 32px; }
        }
        @media (max-width: 480px) {
            body { padding: 14px; align-items: flex-start; }
            .lg-card { border-radius: 22px; }
            .lg-panel { padding: 30px 20px 26px; }
            .lg-title { font-size: 1.5rem; }
            /* 16px inputs stop iOS zooming in on focus */
            .lg-input { font-size: 1rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>

<body>

@php
    // Errors that belong to a specific field are shown under it; anything else goes in the top alert.
    $emailError    = $errors->first('email');
    $passwordError = $errors->first('password');
    $otherErrors   = collect($errors->keys())->diff(['email', 'password']);
    $otherMessage  = $otherErrors->isNotEmpty() ? $errors->first($otherErrors->first()) : null;
@endphp

<main class="lg-card">

    {{-- ===== Brand side ===== --}}
    <section class="lg-brand" aria-hidden="true">
        <div>
            <div class="lg-logo">
                <div class="lg-logo-icon"><i class="fa-solid fa-ticket"></i></div>
                <div>
                    <div class="lg-logo-name">EVENT PLUS</div>
                    <div class="lg-logo-tag">Event Management</div>
                </div>
            </div>

            <h1 class="lg-heading">Your events.<br><span>Your platform.</span></h1>

            <p class="lg-lead">
                Manage events, registrations, tickets and attendees from one powerful event management platform.
            </p>

            <div class="lg-features">
                <div class="lg-feature"><i class="fa-solid fa-calendar-check"></i><span>Manage your events with ease</span></div>
                <div class="lg-feature"><i class="fa-solid fa-ticket"></i><span>Control registrations and tickets</span></div>
                <div class="lg-feature"><i class="fa-solid fa-users"></i><span>Keep track of your attendees</span></div>
            </div>
        </div>

        <div class="lg-copy">© {{ date('Y') }} Event Plus. All rights reserved.</div>
    </section>

    {{-- ===== Form side ===== --}}
    <section class="lg-panel">
        <div class="lg-panel-inner">

            <div class="lg-mobile-brand">
                <div class="lg-logo-icon"><i class="fa-solid fa-ticket"></i></div>
                <span>EVENT PLUS</span>
            </div>

            <h2 class="lg-title">Welcome back</h2>
            <p class="lg-sub">Sign in to manage your events, registrations and ticketing.</p>

            {{-- Logged-out / status messages --}}
            @if(session('success') || session('status'))
                <div class="lg-alert success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') ?? session('status') }}</span>
                </div>
            @endif

            {{-- Errors that aren't tied to email or password --}}
            @if($otherMessage)
                <div class="lg-alert error" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ $otherMessage }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" id="lg-form" novalidate>
                @csrf

                {{-- Email --}}
                <div class="lg-field">
                    <label for="email" class="lg-label">Email address</label>
                    <div class="lg-wrap">
                        <i class="fa-solid fa-envelope lead"></i>
                        <input type="email" name="email" id="email"
                               class="lg-input {{ $emailError ? 'invalid' : '' }}"
                               value="{{ old('email') }}"
                               required
                               autocomplete="username"
                               autocapitalize="none" spellcheck="false" inputmode="email"
                               placeholder="admin@example.com"
                               {{ old('email') ? '' : 'autofocus' }}
                               aria-invalid="{{ $emailError ? 'true' : 'false' }}"
                               @if($emailError) aria-describedby="email-error" @endif>
                    </div>
                    @if($emailError)
                        <p class="lg-msg error" id="email-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $emailError }}
                        </p>
                    @endif
                </div>

                {{-- Password --}}
                <div class="lg-field">
                    <label for="password" class="lg-label">Password</label>
                    <div class="lg-wrap has-toggle">
                        <i class="fa-solid fa-lock lead"></i>
                        <input type="password" name="password" id="password"
                               class="lg-input {{ $passwordError ? 'invalid' : '' }}"
                               required
                               autocomplete="current-password"
                               placeholder="Enter your password"
                               {{ old('email') ? 'autofocus' : '' }}
                               aria-invalid="{{ $passwordError ? 'true' : 'false' }}"
                               aria-describedby="caps-warning{{ $passwordError ? ' password-error' : '' }}">

                        <button type="button" class="lg-toggle" id="lg-toggle" aria-label="Show password" aria-pressed="false">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <p class="lg-msg warn" id="caps-warning" role="status">
                        <i class="fa-solid fa-arrow-up"></i> Caps Lock is on
                    </p>

                    @if($passwordError)
                        <p class="lg-msg error" id="password-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $passwordError }}
                        </p>
                    @endif
                </div>

                {{-- Remember me --}}
                <div class="lg-row">
                    <label class="lg-check" for="remember">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="lg-box"><i class="fa-solid fa-check"></i></span>
                        Keep me signed in
                    </label>
                </div>

                <button type="submit" class="lg-btn" id="lg-submit">
                    <span class="lg-spinner" aria-hidden="true"></span>
                    <span id="lg-submit-label">Sign in</span>
                    <i class="fa-solid fa-arrow-right-to-bracket lg-arrow"></i>
                </button>
            </form>

            <p class="lg-help">Trouble signing in? Contact your super administrator.</p>

            <div class="lg-secure">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Secure Event Plus administrator access</span>
            </div>
        </div>
    </section>
</main>

<script>
(function () {
    var form     = document.getElementById('lg-form');
    var email    = document.getElementById('email');
    var password = document.getElementById('password');
    var toggle   = document.getElementById('lg-toggle');
    var caps     = document.getElementById('caps-warning');
    var submit   = document.getElementById('lg-submit');
    var label    = document.getElementById('lg-submit-label');

    /* ----- Show / hide password ----- */
    toggle.addEventListener('click', function () {
        var show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
        toggle.querySelector('i').className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        password.focus();
    });

    /* ----- Caps Lock warning ----- */
    function checkCaps(e) {
        if (e.getModifierState) caps.classList.toggle('show', e.getModifierState('CapsLock'));
    }
    password.addEventListener('keydown', checkCaps);
    password.addEventListener('keyup', checkCaps);
    password.addEventListener('blur', function () { caps.classList.remove('show'); });

    /* ----- Clear the red state as soon as the user edits a field ----- */
    [email, password].forEach(function (input) {
        input.addEventListener('input', function () {
            input.classList.remove('invalid');
            input.setAttribute('aria-invalid', 'false');
        });
    });

    /* ----- Friendly client-side check, then lock the button to prevent double submits ----- */
    function fail(input, text) {
        input.classList.add('invalid');
        input.setAttribute('aria-invalid', 'true');
        var wrap = input.closest('.lg-field');
        var old = wrap.querySelector('.lg-msg.error.js');
        if (old) old.remove();
        var p = document.createElement('p');
        p.className = 'lg-msg error js';
        p.setAttribute('role', 'alert');
        p.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ';
        p.appendChild(document.createTextNode(text));
        wrap.appendChild(p);
        input.focus();
    }

    form.addEventListener('submit', function (e) {
        form.querySelectorAll('.lg-msg.error.js').forEach(function (n) { n.remove(); });

        if (!email.value.trim()) { e.preventDefault(); return fail(email, 'Please enter your email address.'); }
        if (!email.validity.valid) { e.preventDefault(); return fail(email, 'That doesn’t look like a valid email address.'); }
        if (!password.value) { e.preventDefault(); return fail(password, 'Please enter your password.'); }

        submit.disabled = true;
        submit.classList.add('loading');
        label.textContent = 'Signing in…';
    });

    /* Re-enable the button if the user comes back with the browser's Back button */
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            submit.disabled = false;
            submit.classList.remove('loading');
            label.textContent = 'Sign in';
        }
    });
})();
</script>

</body>
</html>