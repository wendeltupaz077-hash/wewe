@extends('layouts.app')

@section('title', 'Features')

@section('content')
<div class="page-hero">
    <div class="container">
        @include('partials.blood-animation')
        <h1 class="reveal">System Features</h1>
        <p class="reveal">Organized by priority — must-have core features and recommended enhancements from real interview findings.</p>
    </div>
</div>

<section>
    <div class="container">
        <div class="section-title reveal">
            <span class="priority-badge must-have">Must-Have</span>
            <h2>Core Features</h2>
            <p>Essential to solving confirmed real problems in Ormoc blood banks.</p>
        </div>
        <div class="features-grid">
            @foreach([
                ['🧬', 'Component-Type Tracking', 'Whole blood, packed RBC, platelets, plasma, and irradiated units — each with distinct clinical purposes and shelf-life rules.'],
                ['✨', 'Freshness Flags', 'Units under 7–14 days old flagged as fresh — preferred for cardiac, massive transfusion, and neonatal cases.'],
                ['🚫', 'Donor Deferral Tracking', 'Temporary (with return date) and permanent deferrals — automatically excluded from matching. Currently manual and error-prone.'],
                ['📋', 'Hospital-to-PRC Requisition', 'Formalizes the currently informal call-and-memo practice for blood requests to Red Cross chapters.'],
                ['📍', 'Geolocation Matching', 'GPS-based donor and facility stock search by proximity and compatibility.'],
                ['📱', 'SMS + In-App Notifications', 'Essential for emergency response — alerts for critical shortages and donor requests.'],
            ] as $feature)
            <div class="feature-card reveal">
                <div class="feature-icon">{{ $feature[0] }}</div>
                <h3>{{ $feature[1] }}</h3>
                <p>{{ $feature[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="alt-section">
    <div class="container">
        <div class="section-title reveal">
            <span class="priority-badge recommended">Recommended</span>
            <h2>Strong Value Additions</h2>
        </div>
        <div class="features-grid">
            @foreach([
                ['📴', 'Offline-First Data Entry', 'Sync when online — addresses unreliable internet at some facilities.'],
                ['📖', 'Staff Onboarding Tutorial', 'Simple guided setup for facility staff with medium tech comfort levels.'],
                ['📇', 'Facility Coordination Directory', 'Replaces informal Viber/Messenger group chat coordination.'],
                ['🔄', 'Re-runnable User Guide', 'Tutorial with re-run option for staff training.'],
            ] as $feature)
            <div class="feature-card reveal">
                <div class="feature-icon">{{ $feature[0] }}</div>
                <h3>{{ $feature[1] }}</h3>
                <p>{{ $feature[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-title reveal">
            <span class="priority-badge optional">Future</span>
            <h2>Optional Enhancements</h2>
        </div>
        <div class="features-grid">
            @foreach([
                ['📢', 'Donation Program Announcements', 'Community engagement module for blood drives.'],
                ['🏆', 'Donor Appreciation Tracking', 'Boost retention with recognition features.'],
                ['📣', 'Public Appeal Notices', 'Auto-generated last-resort escalation beyond PRC.'],
                ['🔐', 'Authenticator App 2FA', 'Additional security for staff accounts.'],
            ] as $feature)
            <div class="feature-card reveal" style="opacity:0.85;">
                <div class="feature-icon">{{ $feature[0] }}</div>
                <h3>{{ $feature[1] }}</h3>
                <p>{{ $feature[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
