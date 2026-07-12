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

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('portal.login.submit') }}" class="login-form" id="login-form">
            @csrf
            <div class="form-group floating-label">
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" ">
                <label for="email">Email Address</label>
            </div>
            <div class="form-group floating-label">
                <div style="position: relative;">
                    <input type="password" id="password" name="password" required style="padding-right: 5rem;" placeholder=" ">
                    <label for="password">Password</label>
                    <button type="button" id="toggle-password" class="toggle-password" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 0.9rem; color: #6b7280; font-weight: 600; z-index: 2;">
                        Show
                    </button>
                </div>
            </div>
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;" id="submit-btn">
                <span id="btn-text">Sign In</span>
                <span id="btn-spinner" style="display:none; margin-left:0.5rem; width:1rem; height:1rem; border:2px solid rgba(255,255,255,0.3); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite; vertical-align:middle;"></span>
            </button>
        </form>
        
        <div style="text-align:center;margin-top:1rem;">
            <a href="{{ route('portal.forgot-password') }}" class="back-link">Forgot Password?</a>
        </div>
        <a href="{{ route('home') }}" class="back-link" style="margin-top:1rem;">← Back to website</a>
    </div>
    <script src="{{ asset('js/smartblood.js') }}"></script>
</body>
</html>
