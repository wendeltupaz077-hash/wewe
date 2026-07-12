@extends('layouts.portal')

@section('page-title', 'Stock Status')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Facility Stock Status</h2>
    </div>

    <div class="stock-legend" style="margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <span class="status-badge status-full_stock">● Full Stock</span>
        <span class="status-badge status-normal">● Normal</span>
        <span class="status-badge status-low_stock">● Low Stock</span>
        <span class="status-badge status-emergency">● Emergency</span>
    </div>

    <div class="facility-grid" style="grid-template-columns: repeat(auto-fill,minmax(300px,1fr));">
        @forelse($facilities as $facility)
        <div class="facility-card">
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
            <div class="accepts-donations">Accepts Donations</div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <h3>No Facilities Registered Yet</h3>
            <p>Facility stock data will appear here once facilities are onboarded.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
