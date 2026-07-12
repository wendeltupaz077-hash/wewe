@extends('layouts.portal')

@section('page-title', 'Edit User')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Edit User</h2>
    </div>
    <form action="{{ route('portal.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <label>
                Full Name
                <input type="text" name="fullname" value="{{ old('fullname', $user->fullname ?? $user->name) }}" required>
            </label>
            <label>
                Email
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
            </label>
            <label>
                Phone
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
            </label>
            <label>
                Role
                <select name="role" required>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Facility
                <select name="facility_id">
                    <option value="">None</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id', $user->facility_id) == $facility->id ? 'selected' : '' }}>{{ $facility->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Status
                <select name="status" required>
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </label>
            <label>
                Password
                <input type="password" name="password" autocomplete="new-password">
            </label>
            <label>
                Confirm Password
                <input type="password" name="password_confirmation" autocomplete="new-password">
            </label>
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.users.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
