@extends('layouts.portal')

@section('page-title', 'Emergency Requests')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Emergency Blood Requests</h2>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('portal.requests') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search requests" value="{{ request('search') }}" class="field-input" />
                <select name="blood_type" class="field-select">
                    <option value="">Any blood type</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ request('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="component_type" class="field-select">
                    <option value="">Any component</option>
                    <option value="whole_blood" {{ request('component_type')=='whole_blood'?'selected':'' }}>Whole Blood</option>
                    <option value="platelets" {{ request('component_type')=='platelets'?'selected':'' }}>Platelets</option>
                    <option value="plasma" {{ request('component_type')=='plasma'?'selected':'' }}>Plasma</option>
                </select>
                <select name="urgency" class="field-select">
                    <option value="">Any urgency</option>
                    <option value="low" {{ request('urgency')=='low'?'selected':'' }}>Low</option>
                    <option value="medium" {{ request('urgency')=='medium'?'selected':'' }}>Medium</option>
                    <option value="high" {{ request('urgency')=='high'?'selected':'' }}>High</option>
                </select>
                <select name="status" class="field-select">
                    <option value="">Any status</option>
                    <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                    <option value="fulfilled" {{ request('status')=='fulfilled'?'selected':'' }}>Fulfilled</option>
                    <option value="canceled" {{ request('status')=='canceled'?'selected':'' }}>Canceled</option>
                </select>
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <span class="record-count">{{ $requests->total() ?? $requests->count() }} requests</span>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Facility</th>
                    <th>Blood Type</th>
                    <th>Component</th>
                    <th>Qty</th>
                    <th>Urgency</th>
                    <th>Escalation</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td><strong>{{ $req->facility->name ?? '—' }}</strong></td>
                    <td><span class="blood-type-tag">{{ $req->blood_type }}</span></td>
                    <td>{{ str_replace('_', ' ', ucfirst($req->component_type)) }}</td>
                    <td>{{ $req->quantity }}</td>
                    <td><span class="urgency-badge urgency-{{ $req->urgency }}">{{ ucfirst($req->urgency) }}</span></td>
                    <td><span class="escalation-badge">{{ strtoupper($req->escalation_level) }}</span></td>
                    <td><span class="status-pill status-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                    <td class="notes-cell">{{ Str::limit($req->notes, 40) }}</td>
                    <td>{{ $req->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="empty-text">No emergency requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
