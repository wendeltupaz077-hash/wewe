@extends('layouts.app')

@section('title', 'How It Works')

@section('content')
<div class="page-hero">
    <div class="container">
        @include('partials.blood-animation')
        <h1 class="reveal">How It Works</h1>
        <p class="reveal">From registration to emergency fulfillment — the complete system flow.</p>
    </div>
</div>

<section>
    <div class="container">
        <div class="section-title reveal">
            <h2>User → Donor Registration</h2>
        </div>
        <div class="timeline reveal">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>1. Register Account</h3>
                    <p>Sign up via email or phone number. Phone doubles as 2FA via OTP verification.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>2. Apply as Donor</h3>
                    <p>Choose blood type or "I don't know" → status: <span class="tag tag-unverified">Unverified</span></p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>3. Facility Verification</h3>
                    <p>Visit a registered facility for lab-based blood type confirmation → <span class="tag tag-verified">Verified</span></p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h3>4. Donation Lifecycle</h3>
                    <p>Registered → Matched → Confirmed → Donated → Cooldown → Available again. Re-verified at every donation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="alt-section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Emergency Request → Fulfillment</h2>
        </div>
        <div class="escalation-flow reveal">
            <div class="escalation-step active-step">
                <div class="step-number">1</div>
                <h4>Submit Request</h4>
                <p>Blood type + component + urgency</p>
            </div>
            <div class="escalation-arrow animated-arrow">→</div>
            <div class="escalation-step">
                <div class="step-number">2</div>
                <h4>Check Local Stock</h4>
                <p>Prioritize fresh units</p>
            </div>
            <div class="escalation-arrow animated-arrow">→</div>
            <div class="escalation-step">
                <div class="step-number">3</div>
                <h4>Nearby Facilities</h4>
                <p>Geolocation stock search</p>
            </div>
            <div class="escalation-arrow animated-arrow">→</div>
            <div class="escalation-step">
                <div class="step-number">4</div>
                <h4>Donor Database</h4>
                <p>Verified → Unconfirmed</p>
            </div>
            <div class="escalation-arrow animated-arrow">→</div>
            <div class="escalation-step">
                <div class="step-number">5</div>
                <h4>PRC Escalation</h4>
                <p>Formal requisition</p>
            </div>
        </div>
        <p class="text-center reveal" style="color:var(--muted);margin-top:2rem;">
            Response time is logged at each stage for reporting and accountability.
        </p>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-title reveal">
            <h2>Donation Drive → Inventory Intake</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon">🎪</div>
                <h3>Drive Conducted</h3>
                <p>Blood donation drive held at facility or community location.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon">🤝</div>
                <h3>Split Recorded</h3>
                <p>Staff encodes negotiated hospital/PRC split outcome (system records, does not automate negotiation).</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon">📥</div>
                <h3>Inventory Intake</h3>
                <p>Each unit enters with type, component, collection date, expiry date, and donor ID.</p>
            </div>
        </div>
    </div>
</section>

<section class="alt-section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Donor Status Lifecycle</h2>
        </div>
        <div class="lifecycle-flow reveal">
            <span class="lifecycle-badge">Registered</span>
            <span class="lifecycle-arrow">→</span>
            <span class="lifecycle-badge">Matched</span>
            <span class="lifecycle-arrow">→</span>
            <span class="lifecycle-badge">Confirmed</span>
            <span class="lifecycle-arrow">→</span>
            <span class="lifecycle-badge active">Donated</span>
            <span class="lifecycle-arrow">→</span>
            <span class="lifecycle-badge cooldown">Cooldown</span>
            <span class="lifecycle-arrow">→</span>
            <span class="lifecycle-badge">Available</span>
        </div>
        <div class="deferral-info reveal">
            <p><strong>Deferral:</strong> Temporary (with return-eligible date) or Permanent — active deferrals are automatically excluded from matching.</p>
        </div>
    </div>
</section>
@endsection
