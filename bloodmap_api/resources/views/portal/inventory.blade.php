@extends('layouts.portal')

@section('page-title', 'Inventory')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Blood Inventory</h2>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('portal.inventory') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search inventory" value="{{ request('search') }}" class="field-input" />
                <select name="facility_id" class="field-select">
                    <option value="">All facilities</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
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
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <span class="record-count">{{ $inventory->total() ?? $inventory->count() }} records · {{ $inventory->sum('quantity') }} total units</span>
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
                    <th>Collection</th>
                    <th>Expiry</th>
                    <th>Freshness</th>
                    <th>Expiry Tier</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory as $item)
                <tr>
                    <td>{{ $item->facility->name ?? '—' }}</td>
                    <td><span class="blood-type-tag">{{ $item->blood_type }}</span></td>
                    <td>{{ str_replace('_', ' ', ucfirst($item->component_type)) }}</td>
                    <td><strong>{{ $item->quantity }}</strong></td>
                    <td>{{ $item->collection_date->format('M d, Y') }}</td>
                    <td>{{ $item->expiry_date->format('M d, Y') }}</td>
                    <td>
                        @if($item->freshness_flag === 'fresh')
                            <span class="tag tag-fresh">Fresh</span>
                        @else
                            <span class="tag">Standard</span>
                        @endif
                    </td>
                    <td>
                        @php $tier = $item->expiryTier(); @endphp
                        <span class="expiry-tier expiry-{{ $tier }}">{{ str_replace('_', ' ', ucfirst($tier)) }}</span>
                    </td>
                    <td><span class="status-pill status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="9" class="empty-text">No inventory records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
