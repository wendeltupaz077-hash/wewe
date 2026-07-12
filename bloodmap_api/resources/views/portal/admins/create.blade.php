@extends('layouts.portal')

@section('page-title', 'Add New Admin')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Add New Admin</h2>
        <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost btn-sm">← Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <form action="{{ route('portal.admins.store') }}" method="POST" style="max-width:600px;">
        @csrf
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password (Leave blank to send a temporary password)</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <div style="display:flex;gap:1rem;margin-top:1.5rem;">
            <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Admin</button>
        </div>
    </form>
</div>
@endsection
