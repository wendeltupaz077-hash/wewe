@extends('layouts.landing')

@section('title', 'Blog')

@section('content')
@include('partials.landing.page-hero', [
    'eyebrow' => 'The Knowledge Base',
    'title' => 'Blog',
    'subtitle' => 'Articles on health, wellness, and the importance of regular blood donation.',
])

<section class="hm-blog-section">
    <div class="hm-container-md">
        <div class="hm-filters" style="justify-content:center;margin-bottom:3rem;">
            @foreach ($categories as $category)
                <button type="button"
                        class="hm-filter-btn {{ $category === 'All' ? 'is-active' : '' }}"
                        data-blog-filter="{{ $category }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        <article id="hmBlogFeatured" class="hm-featured-post hm-reveal">
            <p class="hm-eyebrow hm-mb-3">Featured · {{ $featured['category'] }}</p>
            <h2 class="hm-section-title" style="font-size:clamp(1.5rem,3vw,1.875rem);">{{ $featured['title'] }}</h2>
            <p class="hm-text-muted" style="max-width:42rem;margin:1rem 0;">{{ $featured['excerpt'] }}</p>
            <p class="hm-post-meta">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $featured['date'] }}
            </p>
        </article>

        <div id="hmBlogGrid"
             class="hm-grid-3 hm-mb-12"
             data-posts='@json($posts)'>
            @foreach ($rest as $post)
                <article class="hm-card hm-post-card hm-reveal">
                    <p class="hm-eyebrow" style="margin-bottom:0.75rem;font-size:0.75rem;">{{ $post['category'] }}</p>
                    <h3 style="font-weight:700;margin:0 0 0.5rem;">{{ $post['title'] }}</h3>
                    <p class="hm-text-muted" style="font-size:0.875rem;margin:0;">{{ $post['excerpt'] }}</p>
                    <div class="hm-post-footer">
                        <span class="hm-post-meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $post['date'] }}
                        </span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="hsl(0 72% 51%)" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hm-card hm-text-center hm-reveal" style="padding:2.5rem;">
            <h3 style="font-weight:700;font-size:1.25rem;margin:0 0 0.5rem;">Stay Informed</h3>
            <p class="hm-text-muted" style="margin:0 0 1.5rem;">Get new articles on health and donation delivered to your inbox.</p>
            <form id="hmSubscribeForm" class="hm-subscribe-form">
                <input type="email" required placeholder="you@email.com" class="hm-subscribe-input">
                <button type="submit" class="hm-btn hm-btn-primary" style="padding:0.75rem 1.5rem;">Subscribe</button>
            </form>
        </div>
    </div>
</section>
@endsection
