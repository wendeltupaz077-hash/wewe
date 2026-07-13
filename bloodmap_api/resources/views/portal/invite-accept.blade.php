<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accept Invitation — SmartBlood PH</title>
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
        <h1>Accept Invitation</h1>
        <p class="login-subtitle">Finish setting up your admin account for <strong>{{ $role }}</strong>.</p>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <div class="alert alert-info" style="margin-bottom:1rem;">
            You are accepting the invitation for <strong>{{ $email }}</strong>. Please choose a secure password to continue.
        </div>

        <form method="POST" action="{{ route('portal.invite.accept.submit') }}" class="login-form" id="invite-accept-form">
            @csrf
            <div class="form-group floating-label">
                <input type="password" id="password" name="password" required autofocus placeholder=" ">
                <label for="password">Password</label>
            </div>
            <div class="form-group floating-label">
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder=" ">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;" id="submit-btn">
                <span id="btn-text">Accept Invitation</span>
                <span id="btn-spinner" style="display:none; margin-left:0.5rem; width:1rem; height:1rem; border:2px solid rgba(255,255,255,0.3); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite; vertical-align:middle;"></span>
            </button>
        </form>

        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('portal.login') }}" class="back-link">← Back to Sign In</a>
        </div>
    </div>

    <script>
        const inviteForm = document.getElementById('invite-accept-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        if (inviteForm && submitBtn && btnText && btnSpinner) {
            inviteForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                btnText.textContent = 'Saving...';
                btnSpinner.style.display = 'inline-block';
            });
        }
    </script>
    <script src="{{ asset('js/smartblood.js') }}"></script>
</body>
</html>
