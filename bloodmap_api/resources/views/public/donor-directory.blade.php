@extends('layouts.landing')

@section('title', 'Donor Directory')

@section('content')
@include('partials.landing.page-hero', [
    'eyebrow' => 'The Precision Grid',
    'title' => 'Donor Directory',
    'subtitle' => 'Search partner facilities and blood donation centers across the Philippines.',
])

<section class="hm-section-pad hm-section-pad-bottom" style="margin-top:-1.5rem;">
    <div class="hm-container-md">
        <div class="hm-search-wrap">
            <svg class="hm-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="search" id="hmDirectorySearch" class="hm-search-input" placeholder="Search by facility name or city...">
        </div>

        <div class="hm-filters">
            @foreach (['All', 'Stocked', 'Emergency Need', 'Closed'] as $filter)
                <button type="button"
                        class="hm-filter-btn {{ $filter === 'All' ? 'is-active' : '' }}"
                        data-directory-filter="{{ $filter }}">
                    {{ $filter }}
                </button>
            @endforeach
        </div>

        <p class="hm-results-count" id="hmDirectoryCount">{{ count($facilities) }} facilities found</p>

        <div class="hm-grid-3">
            @foreach ($facilities as $facility)
                <div class="hm-card hm-reveal"
                     data-directory-card
                     data-name="{{ $facility['name'] }}"
                     data-city="{{ $facility['city'] }}"
                     data-status-label="{{ $facility['status_label'] }}">
                    <div class="hm-facility-status">
                        <span class="hm-status-dot {{ $facility['status'] }}"></span>
                        <span class="hm-status-label">{{ $facility['status_label'] }}</span>
                    </div>
                    <h3 class="hm-facility-name">{{ $facility['name'] }}</h3>
                    <p class="hm-facility-city">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $facility['city'] }}
                    </p>
                    <p class="hm-blood-types">{{ $facility['types'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
