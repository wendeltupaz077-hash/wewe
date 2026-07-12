@extends('layouts.portal')

@section('page-title', 'Dashboard')

@section('content')
<div style="background:linear-gradient(135deg, rgba(225,6,0,0.05), rgba(255,255,255,0));padding:1.5rem;border-radius:1rem;margin-bottom:1.5rem;border:1px solid #e5e7eb;">
    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
        <div>
            <div style="font-size:0.875rem;color:#6b7280;">Philippine Standard Time</div>
            <div id="philippine-time" style="font-size:1.75rem;font-weight:700;color:#c41e3a;"></div>
        </div>
    </div>
</div>

<div class="dashboard-stats">
    <div class="dash-stat-card reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['facilities'] }}">0</div>
            <div class="dash-stat-label">Facilities</div>
        </div>
    </div>
    <div class="dash-stat-card reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['inventory_units'] }}">0</div>
            <div class="dash-stat-label">Available Units</div>
        </div>
    </div>
    <div class="dash-stat-card reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['active_requests'] }}">0</div>
            <div class="dash-stat-label">Active Requests</div>
        </div>
    </div>
    @if(Auth::user()->isAdminUser())
    <div class="dash-stat-card reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['registered_users'] }}">0</div>
            <div class="dash-stat-label">Registered Users</div>
        </div>
    </div>
    @endif
    <div class="dash-stat-card reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['donors'] }}">0</div>
            <div class="dash-stat-label">Donors</div>
        </div>
    </div>
    <div class="dash-stat-card warn reveal">
        <div>
            <div class="dash-stat-num" data-count="{{ $stats['near_expiry'] }}">0</div>
            <div class="dash-stat-label">Near Expiry (7 days)</div>
        </div>
    </div>
</div>

<div class="portal-grid">
    <div class="portal-card reveal">
        <div class="portal-card-header">
            <h2>Recent Emergency Requests</h2>
            <a href="{{ route('portal.requests') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        @if($recentRequests->isEmpty())
            <p class="empty-text">No recent requests.</p>
        @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Facility</th>
                        <th>Blood Type</th>
                        <th>Component</th>
                        <th>Urgency</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests as $req)
                    <tr>
                        <td>{{ $req->facility->name ?? '—' }}</td>
                        <td><span class="blood-type-tag">{{ $req->blood_type }}</span></td>
                        <td>{{ str_replace('_', ' ', ucfirst($req->component_type)) }}</td>
                        <td><span class="urgency-badge urgency-{{ $req->urgency }}">{{ ucfirst($req->urgency) }}</span></td>
                        <td><span class="status-pill status-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="portal-card reveal">
        <h2>Quick Actions</h2>
        <div class="quick-actions">
            <a href="{{ route('portal.inventory') }}" class="quick-action">
                Encode Inventory
            </a>
            <a href="{{ route('portal.requests') }}" class="quick-action">
                View Requests
            </a>
            <a href="{{ route('portal.donors') }}" class="quick-action">
                Verify Donors
            </a>
            <a href="{{ route('portal.reports') }}" class="quick-action">
                View Reports
            </a>
            @if(Auth::user()->isAdminUser())
            <a href="{{ route('portal.users.index') }}" class="quick-action">
                View Registered Users
            </a>
            @endif
            <a href="{{ route('stock-status') }}" class="quick-action" target="_blank">
                Public Stock View
            </a>
        </div>
    </div>
</div>
@endsection
