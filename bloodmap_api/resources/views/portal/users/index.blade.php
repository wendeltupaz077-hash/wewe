@extends('layouts.portal')

@section('page-title', 'Registered Users')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Registered Smart Blood Users</h2>
        <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('portal.users.index') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search users" value="{{ request('search') }}" class="field-input" />
                <select name="role" class="field-select">
                    <option value="">All roles</option>
                    <option value="donor" {{ request('role')=='donor' ? 'selected' : '' }}>Donor</option>
                    <option value="facility_staff" {{ request('role')=='facility_staff' ? 'selected' : '' }}>Facility Staff</option>
                    <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <select name="status" class="field-select">
                    <option value="">Any status</option>
                    <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <select name="facility_id" class="field-select">
                    <option value="">All facilities</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <div style="margin-left:auto;display:flex;gap:.5rem;align-items:center;">
                <span class="record-count">{{ $users->total() }} users</span>
                <a href="{{ route('portal.users.create') }}" class="btn btn-primary">Create User</a>
            </div>
        </div>
    </div>

    @if($users->isEmpty())
        <p class="empty-text">No registered users found.</p>
    @else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Facility</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->fullname ?? $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                    <td>{{ optional($user->facility)->name ?? 'N/A' }}</td>
                    <td>{{ $user->created_at ? $user->created_at->format('M j, Y') : '—' }}</td>
                    <td>
                        <a href="{{ route('portal.users.edit', $user) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('portal.users.destroy', $user) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrap">{{ $users->links() }}</div>
    @endif
    @endif
</div>
@endsection
