@extends('layouts.app')

@section('title', 'Download')

@section('content')
<div class="page-hero">
    <div class="container">
        @include('partials.blood-animation')
        <h1 class="reveal">Download SmartBlood PH</h1>
        <p class="reveal">Get the mobile app for donor registration, blood requests, and emergency alerts.</p>
    </div>
</div>

<section>
    <div class="container">
        <div class="download-hero reveal">
            <div class="phone-mockup">
                <div class="phone-screen">
                    <div class="phone-header">SmartBlood PH</div>
                    <div class="phone-content">
                        <div class="phone-card pulse-card">
                            <span>🩸</span>
                            <strong>O+ Available</strong>
                            <small>Ormoc District Hospital</small>
                        </div>
                        <div class="phone-card">
                            <span>🚨</span>
                            <strong>Emergency Alert</strong>
                            <small>O- needed — 2 units</small>
                        </div>
                        <div class="phone-card">
                            <span>❤️</span>
                            <strong>Donor Match</strong>
                            <small>3 donors nearby</small>
                        </div>
                    </div>
                </div>
                <div class="phone-glow"></div>
            </div>
            <div class="download-info">
                <h2>SmartBlood PH Mobile App</h2>
                <p>Built with Flutter for Android and iOS. Connects to the Laravel API for real-time blood bank coordination.</p>
                <ul class="download-features">
                    <li>✓ Register as donor with OTP verification</li>
                    <li>✓ Submit and track blood requests</li>
                    <li>✓ Receive SMS & push notifications</li>
                    <li>✓ View nearby facility stock status</li>
                    <li>✓ Privacy-protected donor matching</li>
                </ul>
                <div class="apk-badge" style="background:rgba(196,30,58,0.08);border:1px solid rgba(196,30,58,0.15);color:var(--ink);margin-top:1.5rem;">
                    <span style="font-size:2rem;">📱</span>
                    <div style="text-align:left;">
                        <strong>Android APK</strong><br>
                        <small>Flutter · Material Design 3</small>
                    </div>
                    <span class="coming-soon">Coming Soon</span>
                </div>
                <p style="margin-top:1.5rem;color:var(--muted);font-size:0.9rem;">
                    Mobile app development is the next phase after web portal completion.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="alt-section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Who Can Use the App?</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon">👤</div>
                <h3>Registered Users</h3>
                <p>Submit blood requests, view status, and receive system-facilitated donor responses.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon">❤️</div>
                <h3>Donors</h3>
                <p>Register willingness to donate, get matched for emergencies, and track donation history.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon">🏥</div>
                <h3>Facility Staff</h3>
                <p>Use the web portal for inventory encoding, verification, and request processing.</p>
            </div>
        </div>
    </div>
</section>
@endsection
