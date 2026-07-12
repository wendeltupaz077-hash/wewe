@extends('layouts.app')

@section('title', 'Stock Status')

@section('content')
<div class="page-hero">
    <div class="container">
        @include('partials.blood-animation')
        <h1 class="reveal">Facility Stock Status</h1>
        <p class="reveal">Public view of blood availability across partner facilities. Updated in real-time from facility inventory.</p>
    </div>
</div>

<section>
    <div class="container">
        <div class="stock-legend reveal">
            <span class="status-badge status-full_stock">● Full Stock</span>
            <span class="status-badge status-normal">● Normal</span>
            <span class="status-badge status-low_stock">● Low Stock</span>
            <span class="status-badge status-emergency">● Emergency</span>
        </div>

        <div class="facility-grid">
            @forelse($facilities as $facility)
            <div class="facility-card reveal">
                <div style="display:flex;justify-content:space-between;align-items:start;">
                    <span class="status-badge status-{{ $facility->computed_status }}">
                        {{ str_replace('_', ' ', ucfirst($facility->computed_status)) }}
                    </span>
                    <span class="facility-type">{{ strtoupper($facility->type) }}</span>
                </div>
                <h3>{{ $facility->name }}</h3>
                <p class="facility-meta">{{ $facility->address }}, {{ $facility->city }}</p>
                <div class="facility-stats">
                    <div class="facility-stat">
                        <strong>{{ $facility->total_units }}</strong>
                        <small>Available Units</small>
                    </div>
                    <div class="facility-stat">
                        <strong>{{ $facility->inventory->count() }}</strong>
                        <small>Inventory Records</small>
                    </div>
                </div>
                @if($facility->inventory->isNotEmpty())
                <div class="inventory-breakdown">
                    <h4>Blood Types Available</h4>
                    <div class="blood-type-tags">
                        @foreach($facility->inventory->groupBy('blood_type') as $type => $items)
                        <span class="blood-type-tag">{{ $type }} ({{ $items->sum('quantity') }})</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($facility->accepts_donations)
                <div class="accepts-donations">✓ Accepts Donations</div>
                @endif
            </div>
            @empty
            <div class="empty-state reveal">
                <div class="empty-icon">🏥</div>
                <h3>No Facilities Registered Yet</h3>
                <p>Facility stock data will appear here once facilities are onboarded.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="alt-section">
    <div class="container">
        <div class="notice-card reveal">
            <div class="notice-icon">🔒</div>
            <div>
                <h3>Privacy Notice</h3>
                <p>This public view shows aggregate stock levels only. Donor personal information and contact details are never displayed here. For emergency blood requests, use the mobile app or contact the facility directly.</p>
            </div>
        </div>
    </div>
</section>
@endsection
