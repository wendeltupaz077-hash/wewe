@extends('layouts.portal')

@section('page-title', 'Facilities')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Registered Facilities</h2>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('portal.facilities.index') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search facilities" value="{{ request('search') }}" class="field-input" />
                <select name="type" class="field-select">
                    <option value="">All types</option>
                    @foreach($facilityTypes as $k => $label)
                        <option value="{{ $k }}" {{ request('type') == $k ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="locked" class="field-select">
                    <option value="">Any lock</option>
                    <option value="0" {{ request('locked') === '0' ? 'selected' : '' }}>Unlocked</option>
                    <option value="1" {{ request('locked') === '1' ? 'selected' : '' }}>Locked</option>
                </select>
                <select name="head_id" class="field-select">
                    <option value="">Any head</option>
                    @foreach($heads as $head)
                        <option value="{{ $head->id }}" {{ request('head_id') == $head->id ? 'selected' : '' }}>{{ $head->fullname ?? $head->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <div style="margin-left:auto;display:flex;gap:.5rem;align-items:center;">
                <span class="record-count">{{ $facilities->total() ?? $facilities->count() }} facilities</span>
                <a href="{{ route('portal.facilities.create') }}" class="btn btn-primary">Create Facility</a>
            </div>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Head</th>
                    <th>Stock Status</th>
                    <th>Inventory</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facilities as $facility)
                <tr>
                    <td><strong>{{ $facility->name }}</strong></td>
                    <td><span class="facility-type">{{ strtoupper($facility->type) }}</span></td>
                    <td>{{ $facility->city }}, {{ $facility->province }}</td>
                    <td>{{ $facility->contact_phone }}</td>
                    <td>{{ optional($facility->head)->fullname ?? optional($facility->head)->name ?? 'None' }}</td>
                    <td><span class="status-badge status-{{ $facility->computeStockStatus() }}">{{ str_replace('_', ' ', ucfirst($facility->computeStockStatus())) }}</span></td>
                    <td>{{ $facility->inventory_count }} records</td>
                    <td>
                        @if($facility->is_locked)
                            <span class="status-pill status-locked">Locked</span>
                        @else
                            <span class="status-pill status-active">Active</span>
                        @endif
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('portal.facilities.edit', $facility) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('portal.facilities.destroy', $facility) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this facility?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-tertiary btn-sm">Delete</button>
                        </form>
                        <form action="{{ route('portal.facilities.toggle-lock', $facility) }}" method="POST" class="inline-form" style="margin-top:.5rem;">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm">{{ $facility->is_locked ? 'Unlock' : 'Lock' }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
