@extends('layouts.portal')

@section('page-title', 'Donors')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Registered Donors</h2>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('portal.donors') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search donors" value="{{ request('search') }}" class="field-input" />
                <select name="blood_type" class="field-select">
                    <option value="">Any blood type</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $type)
                        <option value="{{ $type }}" {{ request('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="verification_status" class="field-select">
                    <option value="">Any verification</option>
                    <option value="verified" {{ request('verification_status')=='verified'? 'selected':'' }}>Verified</option>
                    <option value="unverified" {{ request('verification_status')=='unverified'? 'selected':'' }}>Unverified</option>
                </select>
                <select name="donor_status" class="field-select">
                    <option value="">Any status</option>
                    <option value="active" {{ request('donor_status')=='active'? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('donor_status')=='inactive'? 'selected':'' }}>Inactive</option>
                </select>
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <span class="record-count">{{ $donors->total() }} donors</span>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Blood Type</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Eligible</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donors as $donor)
                <tr>
                    <td><strong>{{ $donor->user->name ?? '—' }}</strong></td>
                    <td><span class="blood-type-tag">{{ $donor->blood_type ?? 'Unknown' }}</span></td>
                    <td>
                        @if($donor->verification_status === 'verified')
                            <span class="tag tag-verified">Verified</span>
                        @else
                            <span class="tag tag-unverified">Unconfirmed</span>
                        @endif
                    </td>
                    <td><span class="status-pill status-{{ $donor->donor_status }}">{{ ucfirst($donor->donor_status) }}</span></td>
                    <td>
                        @if($donor->isEligibleForMatching())
                            <span class="tag tag-verified">Yes</span>
                        @else
                            <span class="tag tag-unverified">No</span>
                        @endif
                    </td>
                    <td>{{ $donor->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($donors->hasPages())
    <div class="pagination-wrap">{{ $donors->links() }}</div>
    @endif
</div>
@endsection
