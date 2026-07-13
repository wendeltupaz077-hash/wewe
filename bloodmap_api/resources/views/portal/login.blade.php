<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal — SmartBlood PH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/smartblood.css') }}">
</head>
<body class="login-body">
    <div class="login-bg">
        @include('partials.blood-animation')
    </div>
    <div class="login-card">
        <div class="logo" style="justify-content:center;margin-bottom:2rem;display:flex;align-items:center;justify-content:center;gap:0.5rem;cursor: default; pointer-events: none;">
            <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:32px;height:32px;object-fit:contain;border-radius:4px;">
            <span style="font-weight:600;">SmartBlood PH</span>
        </div>
        <h1>Admin Portal</h1>
        <p class="login-subtitle">Enter your credentials to sign in.</p>

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

        <form method="POST" action="{{ route('portal.login.submit') }}" class="login-form" id="login-form">
            @csrf
            <div class="form-group floating-label">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" ">
                <label for="email">Email Address</label>
            </div>
            <div class="form-group floating-label password-group">
                <input type="password" id="password" name="password" required placeholder=" ">
                <label for="password">Password</label>
                <button type="button" id="toggle-password" class="toggle-password">
                    Show
                </button>
            </div>
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;" id="submit-btn">
                <span id="btn-text">Sign In</span>
                <span id="btn-spinner" style="display:none; margin-left:0.5rem; width:1rem; height:1rem; border:2px solid rgba(255,255,255,0.3); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite; vertical-align:middle;"></span>
            </button>
        </form>

        <div style="margin-top:1rem; display:grid; gap:0.75rem;">
            <a href="{{ route('portal.login.google') }}" class="btn btn-outline" style="width:100%; display:flex; align-items:center; justify-content:center; gap:0.75rem;">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" width="20" height="20" style="display:inline-block;">
                Sign in with Google
            </a>
            <a href="{{ route('portal.forgot-password') }}" class="back-link">Forgot Password?</a>
        </div>
        <a href="{{ route('home') }}" class="back-link" style="margin-top:1rem; display:block;">← Back to website</a>
    </div>
    <dialog id="message-dialog" class="hm-message-dialog">
        <div class="hm-message-dialog__inner">
            <p id="dialog-message"></p>
            <button type="button" id="dialog-close" class="hm-dialog-button">OK</button>
        </div>
    </dialog>

    <script>
        const loginForm = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('toggle-password');
        const rememberCheckbox = document.querySelector('input[name="remember"]');

        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show' : 'Hide';
            });
        }

        if (loginForm) {
            loginForm.addEventListener('submit', () => {
                const submitBtn = document.getElementById('submit-btn');
                const spinner = document.getElementById('btn-spinner');
                const text = document.getElementById('btn-text');

                if (submitBtn) submitBtn.disabled = true;
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
        if (savedEmail && emailInput && rememberCheckbox) {
            emailInput.value = savedEmail;
            rememberCheckbox.checked = true;
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
                if (loginForm) loginForm.reset();
                if (rememberCheckbox) rememberCheckbox.checked = false;
                localStorage.removeItem('portal_login_email');
                window.location.reload();
            }
        }

        if (dialogClose) {
            dialogClose.addEventListener('click', function () {
                if (dialog) dialog.close();
                if (loginForm) loginForm.reset();
                if (rememberCheckbox) rememberCheckbox.checked = false;
                localStorage.removeItem('portal_login_email');
                window.location.reload();
            });
        }
    </script>

    <style>
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
    </style>
</body>
</html>
