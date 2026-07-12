@extends('layouts.landing')

@section('title', 'Home')

@section('content')
<section class="hm-hero">
    <div class="hm-hero-bg hm-hero-bg-1 hm-breathe"></div>
    <div class="hm-hero-bg hm-hero-bg-2"></div>

    <div class="hm-hero-grid">
        <div class="hm-reveal">
            <p class="hm-eyebrow">The Living Network of Vitality</p>
            <h1 class="hm-hero-title">
                Every Second, <span class="hm-glow hm-text-arterial">Blood Flows.</span>
            </h1>
            <p class="hm-hero-desc">
                SmartBlood PH connects donors, facilities, and emergency responders in one real-time system — because life waits for no one.
            </p>
            <div class="hm-hero-actions">
                <a href="{{ route('donor-directory') }}" class="hm-btn hm-btn-primary">Get Started</a>
                <a href="{{ route('about') }}" class="hm-btn hm-btn-outline">Learn More</a>
            </div>
        </div>

        <div class="hm-reveal" style="transition-delay:0.15s;">
            @include('partials.landing.pulse-visual')
        </div>
    </div>
</section>

<section class="hm-stats">
    <div class="hm-stats-grid">
        <div class="hm-stat-card hm-reveal">
            <p class="hm-stat-value hm-flicker">{{ number_format($stats['facilities']) }}</p>
            <p class="hm-stat-label">Partner Facilities</p>
        </div>
        <div class="hm-stat-card hm-reveal" style="transition-delay:0.1s;">
            <p class="hm-stat-value hm-flicker">{{ number_format($stats['donors']) }}</p>
            <p class="hm-stat-label">Registered Donors</p>
        </div>
        <div class="hm-stat-card hm-reveal" style="transition-delay:0.2s;">
            <p class="hm-stat-value hm-flicker">{{ number_format($stats['active_requests']) }}</p>
            <p class="hm-stat-label">Active Requests</p>
        </div>
        <div class="hm-stat-card hm-reveal" style="transition-delay:0.3s;">
            <p class="hm-stat-value hm-flicker">{{ $stats['emergency_ready'] }}</p>
            <p class="hm-stat-label">Emergency Ready</p>
        </div>
    </div>
</section>

<section class="hm-features">
    <img src="https://media.base44.com/images/public/6a53332b899ec2f74694cd95/b3872a483_generated_image.png" alt="" class="hm-features-bg" aria-hidden="true">
    <div class="hm-container">
        <div class="hm-section-header hm-reveal">
            <p class="hm-eyebrow">Capabilities</p>
            <h2 class="hm-section-title">Built for the Speed of Life</h2>
        </div>

        <div class="hm-features-grid">
            <div class="hm-feature-card hm-reveal">
                <svg class="hm-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                <h3>Real-Time Stock Tracking</h3>
                <p>Live blood inventory across every partner facility, updated the moment donations arrive.</p>
            </div>
            <div class="hm-feature-card hm-reveal" style="transition-delay:0.1s;">
                <svg class="hm-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <h3>Nationwide Directory</h3>
                <p>Locate the nearest donation center or blood bank in seconds, anywhere in the Philippines.</p>
            </div>
            <div class="hm-feature-card hm-reveal" style="transition-delay:0.2s;">
                <svg class="hm-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <h3>Emergency Alerts</h3>
                <p>Instant escalation notifications when local stock runs critically low.</p>
            </div>
            <div class="hm-feature-card hm-reveal" style="transition-delay:0.3s;">
                <svg class="hm-feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <h3>Verified &amp; Safe</h3>
                <p>Every facility and donor record is verified against PRC and DOH safety standards.</p>
            </div>
        </div>
    </div>
</section>

<section class="hm-escalation">
    <div class="hm-container">
        <div class="hm-section-header hm-reveal">
            <p class="hm-eyebrow">Emergency Protocol</p>
            <h2 class="hm-section-title">The Escalation Flow</h2>
        </div>

        <div class="hm-escalation-flow">
            @foreach ([
                ['label' => 'Local Stock', 'dot' => 'hm-escalation-dot-1'],
                ['label' => 'District Chapter', 'dot' => 'hm-escalation-dot-2'],
                ['label' => 'Regional Center', 'dot' => 'hm-escalation-dot-3'],
                ['label' => 'PRC National', 'dot' => 'hm-escalation-dot-4'],
            ] as $i => $step)
                <div class="hm-escalation-step hm-reveal" style="transition-delay:{{ $i * 0.15 }}s;">
                    <div class="hm-escalation-dot {{ $step['dot'] }}"></div>
                    <p style="font-weight:700;font-size:0.875rem;">{{ $step['label'] }}</p>
                </div>
                @if ($i < 3)
                    <svg class="hm-escalation-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                @endif
            @endforeach
        </div>
    </div>
</section>

<section class="hm-download">
    <div class="hm-download-card hm-reveal">
        <div class="hm-download-card-bg hm-breathe"></div>
        <div class="hm-download-card-content">
            <svg class="hm-download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            <h2 class="hm-section-title" style="font-size:clamp(1.875rem,3vw,2.25rem);">SmartBlood PH, In Your Pocket</h2>
            <p class="hm-text-muted" style="max-width:28rem;margin:0.75rem auto 2rem;">
                The mobile app is now available — get the app and stay connected to real-time alerts.
            </p>
            <a href="{{ route('mobile-app') }}" class="hm-btn hm-btn-primary" style="padding:0.9rem 2rem;">Open Mobile App</a>
        </div>
    </div>
</section>
@endsection
