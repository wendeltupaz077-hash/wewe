<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Default Password — SmartBlood PH</title>
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
        <h1>Change Default Password</h1>
        <p class="login-subtitle">This is your first login. Please set a secure password for your account.</p>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('portal.first-login.submit') }}" class="login-form">
            @csrf
            <div class="form-group floating-label">
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required autofocus placeholder=" " style="padding-right: 5rem;">
                    <label for="password">New Password</label>
                    <button type="button" id="toggle-password" class="toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 0.9rem; color: #6b7280; font-weight: 600; z-index: 2;">
                        Show
                    </button>
                </div>
            </div>
            <div class="form-group floating-label">
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder=" " style="padding-right: 5rem;">
                    <label for="password_confirmation">Confirm New Password</label>
                    <button type="button" id="toggle-password-confirm" class="toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 0.9rem; color: #6b7280; font-weight: 600; z-index: 2;">
                        Show
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">
                Set New Password
            </button>
        </form>
        
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('portal.logout') }}" class="back-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">← Sign Out</a>
        </div>
        
        <form id="logout-form" method="POST" action="{{ route('portal.logout') }}" style="display: none;">
            @csrf
        </form>
    </div>
    <script src="{{ asset('js/smartblood.js') }}"></script>
    <script>
        (function() {
            'use strict';
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const togglePasswordConfirmBtn = document.getElementById('toggle-password-confirm');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    togglePasswordBtn.textContent = type === 'password' ? 'Show' : 'Hide';
                });
            }
            
            if (togglePasswordConfirmBtn && passwordConfirmInput) {
                togglePasswordConfirmBtn.addEventListener('click', function() {
                    const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordConfirmInput.setAttribute('type', type);
                    togglePasswordConfirmBtn.textContent = type === 'password' ? 'Show' : 'Hide';
                });
            }
        })();
    </script>
</body>
</html>
