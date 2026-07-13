<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — SmartBlood PH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hematic.css') }}">
</head>
<body class="hm-login-body">
    <div class="hm-login-page">
        <div class="hm-admin-login-artwork">
            <div class="hm-admin-login-content">
                <div class="hm-auth-header hm-auth-header--centered">
                    <div>
                        <p class="hm-eyebrow">Admin Access</p>
                        <h1>Admin Login</h1>
                    </div>
                </div>

                @php
                    $dialogMessage = '';
                    if (session('error')) {
                        $dialogMessage = session('error');
                    } elseif (session('success')) {
                        $dialogMessage = session('success');
                    } elseif ($errors->any()) {
                        $dialogMessage = implode(' ', $errors->all());
                    }
                @endphp

                <form method="POST" action="{{ route('portal.login.submit') }}" class="hm-auth-form hm-admin-login-form" id="admin-login-form">
                    @csrf

                    <div class="hm-form-group hm-floating-label">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="hm-form-input transparent-input" placeholder=" " autocomplete="email" required autofocus>
                        <label for="email" class="hm-form-label">Email Address</label>
                    </div>

                    <div class="hm-form-group hm-floating-label hm-password-field">
                        <input type="password" id="password" name="password" class="hm-form-input transparent-input" placeholder=" " autocomplete="current-password" required>
                        <label for="password" class="hm-form-label">Password</label>
                        <button type="button" id="password-toggle" class="hm-password-toggle" aria-label="Toggle password visibility">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path id="password-toggle-icon" d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 15.5C13.933 15.5 15.5 13.933 15.5 12C15.5 10.067 13.933 8.5 12 8.5C10.067 8.5 8.5 10.067 8.5 12C8.5 13.933 10.067 15.5 12 15.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <label class="hm-checkbox">
                        <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>

                    <button type="submit" class="hm-btn hm-btn-primary hm-btn-full hm-admin-submit-button" id="submit-btn">
                        <span id="btn-text">Sign In</span>
                        <span id="btn-spinner" style="display:none; margin-left:0.5rem; width:1rem; height:1rem; border:2px solid rgba(255,255,255,0.25); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite; vertical-align:middle;"></span>
                    </button>
                </form>

                <div class="hm-auth-footer hm-admin-login-footer">
                    <a href="{{ route('portal.forgot-password') }}">Forgot Password?</a>
                    <a href="{{ route('home') }}">← Back to website</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Submit spinner
            const form = document.getElementById('admin-login-form');
            if (form) {
                form.addEventListener('submit', function () {
                    const btn = document.getElementById('submit-btn');
                    const spinner = document.getElementById('btn-spinner');
                    const text = document.getElementById('btn-text');
                    const rememberCheckbox = document.getElementById('remember');
                    const emailInput = document.getElementById('email');

                    if (btn) btn.disabled = true;
                    if (spinner) spinner.style.display = 'inline-block';
                    if (text) text.textContent = 'Signing in...';

                    if (rememberCheckbox && emailInput) {
                        if (rememberCheckbox.checked) {
                            localStorage.setItem('portal_login_email', emailInput.value);
                        } else {
                            localStorage.removeItem('portal_login_email');
                        }
                    }
                });
            }

            const savedEmail = localStorage.getItem('portal_login_email');
            const emailInput = document.getElementById('email');
            const rememberCheckbox = document.getElementById('remember');

            if (savedEmail && emailInput && rememberCheckbox) {
                emailInput.value = savedEmail;
                rememberCheckbox.checked = true;
            }

            const passwordToggle = document.getElementById('password-toggle');
            if (passwordToggle) {
                passwordToggle.addEventListener('click', function () {
                    const passwordInput = document.getElementById('password');
                    if (!passwordInput) return;
                    const show = passwordInput.type === 'password';
                    passwordInput.type = show ? 'text' : 'password';
                    const iconPath = document.getElementById('password-toggle-icon');

                    if (iconPath) {
                        iconPath.setAttribute('d', show
                            ? 'M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12ZM12 15.5C13.933 15.5 15.5 13.933 15.5 12C15.5 10.067 13.933 8.5 12 8.5C10.067 8.5 8.5 10.067 8.5 12C8.5 13.933 10.067 15.5 12 15.5Z'
                            : 'M17.94 17.94C16.29 19.06 14.23 19.68 12 19.68C5 19.68 1 12 1 12C1 12 2.92 8.08 5.58 5.58M10.12 10.12C10.05 10.33 10 10.66 10 11C10 13.21 11.79 15 14 15C14.34 15 14.67 14.95 14.88 14.88M15.88 15.88C16.75 15.24 17.42 14.38 17.84 13.37M3 3L21 21');
                    }
                });
            }

            const dialog = document.getElementById('message-dialog');
            const dialogMessage = document.getElementById('dialog-message');
            const dialogClose = document.getElementById('dialog-close');
            const dialogText = @json($dialogMessage);

            if (dialogText && dialog && dialogMessage) {
                dialogMessage.textContent = dialogText;
                if (typeof dialog.showModal === 'function') {
                    dialog.showModal();
                } else {
                    alert(dialogText);
                    if (form) form.reset();
                    if (rememberCheckbox) rememberCheckbox.checked = false;
                    localStorage.removeItem('portal_login_email');
                    window.location.reload();
                }
            }

            if (dialogClose) {
                dialogClose.addEventListener('click', function () {
                    if (dialog) dialog.close();
                    if (form) form.reset();
                    if (rememberCheckbox) rememberCheckbox.checked = false;
                    localStorage.removeItem('portal_login_email');
                    window.location.reload();
                });
            }
        });
    </script>

    <dialog id="message-dialog" class="hm-message-dialog">
        <div class="hm-message-dialog__inner">
            <p id="dialog-message"></p>
            <button type="button" id="dialog-close" class="hm-dialog-button">OK</button>
        </div>
    </dialog>

    <style>
        .hm-admin-login-artwork {
            width: 480px;
            height: 538.22px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background-image: url('{{ asset('images/Logo.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hm-admin-login-artwork::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.06), rgba(0,0,0,0.72));
            pointer-events: none;
        }

        .hm-admin-login-content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.95rem;
            text-align: center;
            color: #fff;
            text-shadow: 0 1px 5px rgba(0,0,0,0.85);
        }

        .hm-auth-header.hm-auth-header--centered {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .hm-auth-header.hm-auth-header--centered img {
            width: 64px;
            height: 64px;
            border-radius: 1rem;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.18);
            box-shadow: 0 16px 40px rgba(0,0,0,0.34);
        }

        .hm-auth-header.hm-auth-header--centered h1 {
            margin: 0;
            font-size: clamp(2rem, 2.6vw, 2.6rem);
            line-height: 1.05;
        }

        .hm-auth-header.hm-auth-header--centered .hm-eyebrow {
            margin: 0;
            color: #f97372;
            font-size: 0.75rem;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .hm-admin-login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            margin-top: 0.75rem;
        }

        .hm-admin-login-form .hm-form-group {
            position: relative;
            width: 100%;
            margin-bottom: 0.9rem;
        }

        .hm-admin-login-form .hm-form-input {
            width: 100%;
            min-height: 3.35rem;
            padding: 1.2rem 1.1rem 0.9rem;
            background: rgba(0,0,0,0.12);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1rem;
            color: #fff;
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .hm-admin-login-form .hm-form-input[type="password"] {
            padding-right: 3.2rem;
        }

        .hm-admin-login-form .hm-form-input:focus {
            border-color: rgba(248,113,113,0.75);
            box-shadow: 0 0 0 10px rgba(248,113,113,0.12);
            background: rgba(0,0,0,0.16);
        }

        .hm-admin-login-form .hm-password-field {
            position: relative;
        }

        .hm-admin-login-form .hm-password-toggle {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            color: #fff;
            width: 2.4rem;
            height: 2.4rem;
            display: grid;
            place-items: center;
            border-radius: 999px;
            padding: 0;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .hm-admin-login-form .hm-password-toggle:hover {
            background: rgba(255,255,255,0.16);
        }

        dialog.hm-message-dialog {
            border: none;
            border-radius: 1.5rem;
            padding: 0;
            width: min(100%, 360px);
            background: rgba(255,255,255,0.95);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }

        .hm-message-dialog__inner {
            padding: 1.75rem 1.5rem 1.25rem;
            text-align: center;
            color: #111827;
        }

        .hm-message-dialog__inner p {
            margin: 0 0 1.5rem;
            font-size: 1rem;
            line-height: 1.65;
        }

        .hm-dialog-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 6rem;
            padding: 0.85rem 1.25rem;
            border: none;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .hm-dialog-button:hover {
            background: #dc2626;
        }

        .hm-admin-login-form .hm-password-toggle svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .hm-admin-login-form .hm-form-input::placeholder {
            color: transparent;
        }

        .hm-admin-login-form .hm-form-label {
            position: absolute;
            left: 1rem;
            top: 1.05rem;
            font-size: 0.87rem;
            color: rgba(255,255,255,0.72);
            transition: transform 0.2s ease, top 0.2s ease, font-size 0.2s ease, color 0.2s ease;
            background: rgba(0,0,0,0.22);
            padding: 0 0.35rem;
            pointer-events: none;
        }

        .hm-admin-login-form .hm-form-input:focus + .hm-form-label,
        .hm-admin-login-form .hm-form-input:not(:placeholder-shown) + .hm-form-label {
            top: -0.45rem;
            left: 0.95rem;
            font-size: 0.75rem;
            color: #f97372;
            transform: translateY(-2px);
        }

        .hm-admin-login-form .hm-checkbox {
            justify-content: flex-start;
            gap: 0.75rem;
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
        }

        .hm-admin-login-form .hm-checkbox input {
            width: 1rem;
            height: 1rem;
            accent-color: #f97372;
        }

        .hm-admin-submit-button {
            background: transparent;
            border: 1px solid rgba(248,113,113,0.35);
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.02em;
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
        }

        .hm-admin-submit-button:hover {
            background: rgba(248,113,113,0.16);
            border-color: rgba(248,113,113,0.85);
            transform: translateY(-1px);
        }

        .hm-admin-login-footer {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 1.25rem;
            color: rgba(255,255,255,0.76);
        }

        .hm-admin-login-footer a {
            color: #fb7185;
            font-size: 0.85rem;
        }

        .hm-admin-login-footer a:hover {
            color: #ff9ca8;
        }

        .alert {
            width: 100%;
            text-align: left;
        }

        .alert.alert-success,
        .alert.alert-error {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 1rem;
            padding: 1rem 1rem;
            color: #ffeaea;
        }

        .alert.alert-error ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .alert.alert-error li {
            margin-bottom: 0.45rem;
        }
    </style>
</body>
</html>
