@extends('layouts.portal')

@section('page-title', 'Create User')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header card-header">
        <div class="card-icon">👤</div>
        <div>
            <h2>Create User</h2>
            <div class="record-count">Add a new staff or admin account</div>
        </div>
    </div>
    <form action="{{ route('portal.users.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-field input-with-icon">
                <label for="fullname">Full Name</label>
                <input class="field-input" id="fullname" type="text" name="fullname" value="{{ old('fullname') }}" required placeholder="Juan Dela Cruz">
                <span class="field-icon">👤</span>
            </div>

            <div class="form-field input-with-icon">
                <label for="email">Email</label>
                <input class="field-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="staff@facility.ph">
                <span class="field-icon">📧</span>
            </div>

            <div class="form-field input-with-icon">
                <label for="phone">Phone</label>
                <input class="field-input" id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
                <span class="field-icon">📱</span>
            </div>

            <div class="form-field">
                <label for="role">Role</label>
                <select class="field-select" id="role" name="role" required>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="facility_id">Facility</label>
                <select class="field-select" id="facility_id" name="facility_id">
                    <option value="">None</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="status">Status</label>
                <select class="field-select" id="status" name="status" required>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-field input-with-icon">
                <label for="password">Password</label>
                <input class="field-input" id="password" type="password" name="password" autocomplete="new-password" placeholder="Create a strong password">
                <span class="field-icon">🔒</span>
            </div>

            <div class="form-field input-with-icon">
                <label for="password_confirmation">Confirm Password</label>
                <input class="field-input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Confirm password">
                <span class="field-icon">🔒</span>
            </div>
        </div>
        <div class="form-actions">
            <a href="{{ route('portal.users.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
</div>
@endsection
