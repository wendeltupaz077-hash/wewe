@extends('layouts.portal')

@section('page-title', 'Edit Facility')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Edit Facility</h2>
    </div>
    <form action="{{ route('portal.facilities.update', $facility) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <label>
                Name
                <input type="text" name="name" value="{{ old('name', $facility->name) }}" required>
            </label>
            <label>
                Type
                <input type="text" name="type" value="{{ old('type', $facility->type) }}" required>
            </label>
            <label>
                Address
                <input type="text" name="address" value="{{ old('address', $facility->address) }}">
            </label>
            <label>
                City
                <input type="text" name="city" value="{{ old('city', $facility->city) }}">
            </label>
            <label>
                Province
                <input type="text" name="province" value="{{ old('province', $facility->province) }}">
            </label>
            <label>
                Contact Phone
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $facility->contact_phone) }}">
            </label>
            <label>
                Contact Email
                <input type="email" name="contact_email" value="{{ old('contact_email', $facility->contact_email) }}">
            </label>
            <label>
                Facility Head
                <select name="head_user_id">
                    <option value="">None</option>
                    @foreach($heads as $head)
                        <option value="{{ $head->id }}" {{ old('head_user_id', $facility->head_user_id) == $head->id ? 'selected' : '' }}>{{ $head->fullname ?? $head->name }} ({{ $head->role }})</option>
                    @endforeach
                </select>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="accepts_donations" value="1" {{ old('accepts_donations', $facility->accepts_donations) ? 'checked' : '' }}>
                Accepts Donations
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_locked" value="1" {{ old('is_locked', $facility->is_locked) ? 'checked' : '' }}>
                Locked
            </label>
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.facilities.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
