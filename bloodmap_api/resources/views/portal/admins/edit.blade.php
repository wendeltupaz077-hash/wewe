@extends('layouts.portal')

@section('page-title', 'Edit Admin')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Edit Admin: {{ $admin->fullname ?? $admin->name }}</h2>
        <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost btn-sm">← Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <form action="{{ route('portal.admins.update', $admin) }}" method="POST" style="max-width:600px;">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="{{ old('fullname', $admin->fullname ?? $admin->name) }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="admin" {{ $admin->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super_admin" {{ $admin->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="active" {{ $admin->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $admin->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div style="display:flex;gap:1rem;margin-top:1.5rem;">
            <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Admin</button>
        </div>
    </form>
</div>
@endsection
