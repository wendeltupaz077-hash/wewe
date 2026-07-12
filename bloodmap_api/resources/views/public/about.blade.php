@extends('layouts.landing')

@section('title', 'Our Mission')

@section('content')
@include('partials.landing.page-hero', [
    'eyebrow' => 'The Mission Narrative',
    'title' => 'Our Mission',
    'subtitle' => 'Why SmartBlood PH exists, who built it, and how it keeps the nation\'s blood supply alive.',
])

<section class="hm-timeline">
    <div class="hm-timeline-line"></div>
    @foreach ($timeline as $item)
        <div class="hm-timeline-item hm-reveal">
            <div class="hm-timeline-dot"></div>
            <p class="hm-eyebrow">{{ $item['title'] }}</p>
            <p style="font-size:1.125rem;line-height:1.7;">{{ $item['text'] }}</p>
        </div>
    @endforeach
</section>

<section class="hm-mission-grid">
    <div class="hm-card hm-reveal">
        <h3 style="font-weight:700;font-size:1.25rem;margin:0 0 0.75rem;">Our Mission</h3>
        <p class="hm-text-muted" style="margin:0;line-height:1.7;">
            To eliminate preventable deaths from blood shortages by giving every facility and donor real-time visibility into the national supply.
        </p>
    </div>
    <div class="hm-card hm-reveal" style="transition-delay:0.1s;">
        <h3 style="font-weight:700;font-size:1.25rem;margin:0 0 0.75rem;">Our Vision</h3>
        <p class="hm-text-muted" style="margin:0;line-height:1.7;">
            A Philippines where no request for blood ever goes unanswered — a truly living, connected circulatory network.
        </p>
    </div>
</section>

<section style="padding:5rem 1.5rem 0;">
    <div class="hm-text-center hm-mb-12 hm-reveal">
        <p class="hm-eyebrow">The Team</p>
        <h2 class="hm-section-title">People Behind the Pulse</h2>
    </div>
    <div class="hm-team-grid">
        @foreach ($team as $i => $member)
            <div class="hm-card hm-team-card hm-reveal" style="transition-delay:{{ $i * 0.1 }}s;">
                <div class="hm-team-avatar"></div>
                <h4 style="font-weight:700;margin:0 0 0.25rem;">{{ $member['name'] }}</h4>
                <p class="hm-text-muted" style="font-size:0.875rem;margin:0;">{{ $member['role'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
