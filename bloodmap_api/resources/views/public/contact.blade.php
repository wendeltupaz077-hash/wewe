@extends('layouts.landing')

@section('title', 'Contact')

@section('content')
@include('partials.landing.page-hero', [
    'eyebrow' => 'Get In Touch',
    'title' => 'Contact Us',
    'subtitle' => 'Questions, feedback, or technical support — we\'re listening.',
])

<section class="hm-contact-section">
    <div class="hm-contact-grid">
        <div class="hm-contact-info">
            @foreach ([
                ['icon' => 'mail', 'label' => 'Email', 'value' => 'info@smartblood.ph', 'href' => 'mailto:info@smartblood.ph'],
                ['icon' => 'phone', 'label' => 'Phone', 'value' => '(02) 8981-1000', 'href' => 'tel:+6329811000'],
                ['icon' => 'map', 'label' => 'Location', 'value' => 'Manila, Philippines', 'href' => null],
            ] as $i => $contact)
                <div class="hm-card hm-contact-card hm-reveal" style="transition-delay:{{ $i * 0.1 }}s;">
                    @if ($contact['icon'] === 'mail')
                        <svg class="hm-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    @elseif ($contact['icon'] === 'phone')
                        <svg class="hm-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    @else
                        <svg class="hm-contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    @endif
                    <div>
                        <p class="hm-contact-label">{{ $contact['label'] }}</p>
                        @if ($contact['href'])
                            <a href="{{ $contact['href'] }}" style="font-weight:600;">{{ $contact['value'] }}</a>
                        @else
                            <p style="font-weight:600;margin:0;">{{ $contact['value'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="hm-card hm-reveal" style="padding:2rem;">
            <form id="hmContactForm" class="hm-contact-form">
                @foreach ([
                    ['name' => 'name', 'label' => 'Full Name', 'type' => 'text'],
                    ['name' => 'email', 'label' => 'Email Address', 'type' => 'email'],
                    ['name' => 'subject', 'label' => 'Subject', 'type' => 'text'],
                ] as $field)
                    <div class="hm-form-group">
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}" required placeholder=" " class="hm-form-input">
                        <label for="{{ $field['name'] }}" class="hm-form-label">{{ $field['label'] }}</label>
                    </div>
                @endforeach
                <div class="hm-form-group">
                    <textarea name="message" id="message" rows="4" required placeholder=" " class="hm-form-textarea"></textarea>
                    <label for="message" class="hm-form-label">Message</label>
                </div>
                <button type="submit" class="hm-btn hm-btn-primary hm-btn-full">Send Message</button>
            </form>

            <div id="hmContactSuccess" class="hm-form-success hm-hidden">
                <svg class="hm-form-success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <h3 style="font-weight:700;font-size:1.25rem;margin:0 0 0.5rem;">Message Sent</h3>
                <p class="hm-text-muted" style="margin:0;">Thank you for reaching out. We'll respond within 1-2 business days.</p>
            </div>
        </div>
    </div>
</section>
@endsection
