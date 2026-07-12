@extends('layouts.landing')

@section('title', 'FAQ')

@section('content')
@include('partials.landing.page-hero', [
    'eyebrow' => 'The Knowledge Base',
    'title' => 'Frequently Asked Questions',
    'subtitle' => 'Answers on donor eligibility, safety, and the donation process.',
])

<section class="hm-faq-section">
    <div class="hm-container-sm">
        <div class="hm-filters" style="justify-content:center;margin-bottom:2.5rem;">
            @foreach (array_keys($faqs) as $category)
                <button type="button"
                        class="hm-filter-btn {{ $category === 'Eligibility' ? 'is-active' : '' }}"
                        data-faq-tab="{{ $category }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        @foreach ($faqs as $category => $items)
            <div class="hm-accordion {{ $category !== 'Eligibility' ? 'hm-hidden' : '' }}"
                 data-faq-panel="{{ $category }}">
                @foreach ($items as $item)
                    <div class="hm-accordion-item">
                        <button type="button" class="hm-accordion-trigger">
                            {{ $item['q'] }}
                            <svg class="hm-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="hm-accordion-content">
                            <div class="hm-accordion-body">{{ $item['a'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="hm-card hm-faq-cta hm-reveal">
            <h3 style="font-weight:700;font-size:1.25rem;margin:0 0 0.5rem;">Still have questions?</h3>
            <p class="hm-text-muted" style="margin:0 0 1.5rem;">Reach out and our team will get back to you.</p>
            <a href="{{ route('contact') }}" class="hm-btn hm-btn-primary">Contact Us</a>
        </div>
    </div>
</section>
@endsection
