<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $is_registered ? 'Reset Password' : 'Set Password' }} — SmartBlood PH</title>
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
        <h1>{{ $is_registered ? 'Reset Your Password' : 'Set Your Password' }}</h1>
        <p class="login-subtitle">{{ $is_registered ? 'Enter a new password for your account.' : 'Create a secure password to access your account.' }}</p>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('portal.set-password.submit') }}" class="login-form">
            @csrf
            <div class="form-group floating-label">
                <input type="password" id="password" name="password" required autofocus placeholder=" ">
                <label for="password">Password</label>
            </div>
            <div class="form-group floating-label">
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder=" ">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">
                {{ $is_registered ? 'Reset Password' : 'Set Password & Sign In' }}
            </button>
        </form>
        
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('portal.login') }}" class="back-link">← Back to Sign In</a>
        </div>
    </div>
    <script src="{{ asset('js/smartblood.js') }}"></script>
</body>
</html>
