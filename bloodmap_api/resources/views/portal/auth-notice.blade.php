<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check Your Email — SmartBlood PH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/smartblood.css') }}">
    <style>
        .sending-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .sending-spinner {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid rgba(196, 30, 58, 0.2);
            border-top-color: #c41e3a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
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
        <h1>Check Your Email</h1>
        <div class="sending-indicator">
            <span style="color: #c41e3a; font-weight: 700;">Verification email sent!</span>
        </div>
        <p class="login-subtitle">We've sent a verification link to <strong>{{ session('auth_email') }}</strong>. Please click "Yes, it's me" in the email to continue setting your password.</p>
        
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('portal.login') }}" class="back-link">← Back to Sign In</a>
        </div>
    </div>
    <script src="{{ asset('js/smartblood.js') }}"></script>
</body>
</html>
