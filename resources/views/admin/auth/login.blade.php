<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Event Plus - Admin Login</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>

<style>
    :root {
        --ep-navy: #0f172a;
        --ep-navy-2: #17112f;
        --ep-purple: #4c1d95;
        --ep-purple-light: #7c3aed;
        --ep-pink: #ec4899;
        --ep-pink-dark: #db2777;
        --ep-lavender: #f5f3ff;
        --ep-border: #e2e8f0;
        --ep-muted: #64748b;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        min-height: 100%;
    }

    body {
        margin: 0;
        min-height: 100vh;
        font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI",
            Roboto, Helvetica, Arial, sans-serif;
        background:
            radial-gradient(
                circle at 15% 15%,
                rgba(236, 72, 153, 0.18),
                transparent 28%
            ),
            radial-gradient(
                circle at 85% 80%,
                rgba(124, 58, 237, 0.22),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #090d1a 0%,
                #111827 48%,
                #24113f 100%
            );
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 16px;
        position: relative;
        overflow-x: hidden;
    }

    /* =========================================================
       BACKGROUND DECORATIONS
    ========================================================== */

    body::before {
        content: "";
        position: fixed;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: rgba(236, 72, 153, 0.08);
        top: -180px;
        left: -160px;
        filter: blur(5px);
        pointer-events: none;
    }

    body::after {
        content: "";
        position: fixed;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: rgba(124, 58, 237, 0.10);
        bottom: -240px;
        right: -180px;
        filter: blur(5px);
        pointer-events: none;
    }

    /* =========================================================
       LOGIN WRAPPER
    ========================================================== */

    .login-wrapper {
        width: 100%;
        max-width: 980px;
        min-height: 600px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: rgba(255, 255, 255, 0.96);
        border-radius: 28px;
        overflow: hidden;
        box-shadow:
            0 35px 90px rgba(0, 0, 0, 0.40),
            0 10px 35px rgba(15, 23, 42, 0.18);
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       BRAND SIDE
    ========================================================== */

    .brand-panel {
        position: relative;
        overflow: hidden;
        padding: 50px;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background:
            radial-gradient(
                circle at 20% 15%,
                rgba(236, 72, 153, 0.30),
                transparent 30%
            ),
            radial-gradient(
                circle at 85% 80%,
                rgba(124, 58, 237, 0.35),
                transparent 35%
            ),
            linear-gradient(
                145deg,
                #0f172a 0%,
                #24113f 55%,
                #4c1d95 100%
            );
    }

    .brand-panel::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 50%;
        right: -120px;
        top: -80px;
    }

    .brand-panel::after {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 50%;
        left: -220px;
        bottom: -220px;
    }

    .brand-content {
        position: relative;
        z-index: 2;
    }

    .brand-logo {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 55px;
    }

    .brand-logo-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(
            135deg,
            #ec4899,
            #8b5cf6
        );
        box-shadow:
            0 10px 25px rgba(236, 72, 153, 0.30);
        font-size: 19px;
    }

    .brand-name {
        font-size: 1.15rem;
        font-weight: 900;
        letter-spacing: 1.8px;
        line-height: 1;
    }

    .brand-tagline {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.72rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-top: 5px;
    }

    .brand-heading {
        font-size: clamp(2.2rem, 4vw, 3.25rem);
        line-height: 1.05;
        font-weight: 900;
        letter-spacing: -1.8px;
        margin-bottom: 22px;
    }

    .brand-heading span {
        background: linear-gradient(
            90deg,
            #f9a8d4,
            #ec4899,
            #c4b5fd
        );
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .brand-description {
        max-width: 400px;
        color: rgba(255, 255, 255, 0.70);
        line-height: 1.75;
        font-size: 0.92rem;
        margin: 0;
    }

    .brand-features {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 13px;
        margin-top: 45px;
    }

    .brand-feature {
        display: flex;
        align-items: center;
        gap: 11px;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.82rem;
    }

    .brand-feature-icon {
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.10);
        color: #f9a8d4;
        font-size: 0.72rem;
    }

    .brand-footer {
        position: relative;
        z-index: 2;
        color: rgba(255, 255, 255, 0.40);
        font-size: 0.72rem;
        margin-top: 45px;
    }

    /* =========================================================
       LOGIN SIDE
    ========================================================== */

    .login-panel {
        padding: 55px 55px 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .login-content {
        width: 100%;
        max-width: 390px;
    }

    .login-header {
        margin-bottom: 30px;
    }

    .mobile-brand {
        display: none;
    }

    .login-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(
            135deg,
            #fce7f3,
            #ede9fe
        );
        color: var(--ep-pink-dark);
        font-size: 1.25rem;
        margin-bottom: 20px;
    }

    .login-title {
        color: var(--ep-navy);
        font-size: 1.65rem;
        font-weight: 850;
        letter-spacing: -0.7px;
        margin: 0 0 7px;
    }

    .login-subtitle {
        color: var(--ep-muted);
        font-size: 0.86rem;
        line-height: 1.6;
        margin: 0;
    }

    /* =========================================================
       ALERT
    ========================================================== */

    .login-alert {
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #be123c;
        border-radius: 12px;
        padding: 11px 13px;
        font-size: 0.78rem;
        margin-bottom: 22px;
    }

    /* =========================================================
       FORM
    ========================================================== */

    .form-label {
        color: #334155;
        font-size: 0.76rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.82rem;
        pointer-events: none;
        transition: color 0.2s ease;
        z-index: 2;
    }

    .form-control {
        height: 50px;
        border-radius: 13px;
        border: 1px solid var(--ep-border);
        background: #f8fafc;
        color: #0f172a;
        padding: 0 15px 0 43px;
        font-size: 0.84rem;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .form-control::placeholder {
        color: #a1aab8;
    }

    .form-control:hover {
        background: #ffffff;
        border-color: #cbd5e1;
    }

    .form-control:focus {
        background: #ffffff;
        border-color: var(--ep-pink);
        box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.12);
        outline: none;
    }

    .input-wrapper:focus-within .input-icon {
        color: var(--ep-pink);
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }

    .password-toggle {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #94a3b8;
        padding: 5px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: var(--ep-pink);
    }

    /* =========================================================
       REMEMBER
    ========================================================== */

    .remember-row {
        margin-top: 18px;
        margin-bottom: 24px;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-left: 0;
    }

    .form-check-input {
        margin: 0;
        width: 16px;
        height: 16px;
        border-radius: 5px;
        border-color: #cbd5e1;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--ep-pink);
        border-color: var(--ep-pink);
    }

    .form-check-label {
        color: #64748b;
        font-size: 0.78rem;
        cursor: pointer;
    }

    /* =========================================================
       LOGIN BUTTON
    ========================================================== */

    .login-button {
        width: 100%;
        height: 51px;
        border: 0;
        border-radius: 13px;
        background: linear-gradient(
            135deg,
            #ec4899 0%,
            #db2777 45%,
            #7c3aed 100%
        );
        color: #ffffff;
        font-size: 0.86rem;
        font-weight: 800;
        letter-spacing: 0.1px;
        box-shadow:
            0 12px 25px rgba(219, 39, 119, 0.20);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            filter 0.2s ease;
    }

    .login-button:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 16px 30px rgba(219, 39, 119, 0.28);
        filter: brightness(1.03);
    }

    .login-button:active {
        transform: translateY(0);
    }

    .login-button i {
        margin-left: 7px;
        font-size: 0.76rem;
    }

    /* =========================================================
       SECURITY NOTE
    ========================================================== */

    .security-note {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        color: #94a3b8;
        font-size: 0.70rem;
        text-align: center;
    }

    .security-note i {
        color: #a78bfa;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 850px) {
        .login-wrapper {
            max-width: 520px;
            min-height: auto;
            grid-template-columns: 1fr;
        }

        .brand-panel {
            display: none;
        }

        .mobile-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .mobile-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            background: linear-gradient(
                135deg,
                #ec4899,
                #7c3aed
            );
            box-shadow:
                0 8px 18px rgba(236, 72, 153, 0.20);
        }

        .mobile-brand-name {
            color: var(--ep-navy);
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 1.5px;
        }

        .login-panel {
            padding: 42px 35px 35px;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 15px;
        }

        .login-wrapper {
            border-radius: 22px;
        }

        .login-panel {
            padding: 32px 22px 28px;
        }

        .login-title {
            font-size: 1.45rem;
        }

        .login-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            font-size: 1.1rem;
        }

        .form-control {
            height: 48px;
        }

        .login-button {
            height: 49px;
        }
    }
</style>


</head>

<body>

<div class="login-wrapper">


{{-- =========================================================
     BRAND PANEL
========================================================== --}}

<div class="brand-panel">

    <div class="brand-content">

        <div class="brand-logo">

            <div class="brand-logo-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <div>
                <div class="brand-name">EVENT PLUS</div>
                <div class="brand-tagline">Event Management</div>
            </div>

        </div>

        <h1 class="brand-heading">
            Your events.<br>
            <span>Your platform.</span>
        </h1>

        <p class="brand-description">
            Manage events, registrations, tickets and attendees
            from one powerful event management platform.
        </p>

        <div class="brand-features">

            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span>Manage your events with ease</span>
            </div>

            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <span>Control registrations and tickets</span>
            </div>

            <div class="brand-feature">
                <div class="brand-feature-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span>Keep track of your attendees</span>
            </div>

        </div>

    </div>

    <div class="brand-footer">
        © {{ date('Y') }} Event Plus. All rights reserved.
    </div>

</div>


{{-- =========================================================
     LOGIN PANEL
========================================================== --}}

<div class="login-panel">

    <div class="login-content">

        {{-- Mobile Brand --}}

        <div class="mobile-brand">

            <div class="mobile-brand-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <div class="mobile-brand-name">
                EVENT PLUS
            </div>

        </div>


        {{-- Header --}}

        <div class="login-header">

            <div class="login-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <h2 class="login-title">
                Admin Access
            </h2>

            <p class="login-subtitle">
                Sign in to manage your events, registrations and
                ticketing system.
            </p>

        </div>


        {{-- Validation Error --}}

        @if ($errors->any())

            <div class="login-alert">

                <i class="fa-solid fa-triangle-exclamation me-1"></i>

                {{ $errors->first() }}

            </div>

        @endif


        {{-- Login Form --}}

        <form
            action="{{ route('admin.login.post') }}"
            method="POST"
        >

            @csrf


            {{-- Email --}}

            <div class="mb-3">

                <label
                    for="email"
                    class="form-label"
                >
                    Email Address
                </label>

                <div class="input-wrapper">

                    <i class="fa-solid fa-envelope input-icon"></i>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="admin@example.com"
                    >

                </div>

            </div>


            {{-- Password --}}

            <div class="mb-3">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>

                <div class="input-wrapper password-wrapper">

                    <i class="fa-solid fa-lock input-icon"></i>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Show password"
                    >
                        <i class="fa-solid fa-eye"></i>
                    </button>

                </div>

            </div>


            {{-- Remember Me --}}

            <div class="remember-row">

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="remember"
                        class="form-check-input"
                        id="remember"
                        value="1"
                    >

                    <label
                        class="form-check-label"
                        for="remember"
                    >
                        Remember me
                    </label>

                </div>

            </div>


            {{-- Submit --}}

            <button
                type="submit"
                class="login-button"
            >
                Sign In

                <i class="fa-solid fa-arrow-right-to-bracket"></i>

            </button>

        </form>


        {{-- Security --}}

        <div class="security-note">

            <i class="fa-solid fa-shield-halved"></i>

            <span>
                Secure Event Plus administrator access
            </span>

        </div>

    </div>

</div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');

        if (passwordInput && passwordToggle) {

            passwordToggle.addEventListener('click', function () {

                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {

                    passwordInput.type = 'text';

                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');

                    this.setAttribute(
                        'aria-label',
                        'Hide password'
                    );

                } else {

                    passwordInput.type = 'password';

                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');

                    this.setAttribute(
                        'aria-label',
                        'Show password'
                    );

                }

            });

        }

    });
</script>

</body>
</html>
