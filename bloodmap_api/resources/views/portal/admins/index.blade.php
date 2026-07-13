@extends('layouts.portal')

@section('page-title', 'Manage Admins')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Admin Accounts</h2>
        <div style="display:flex;gap:1rem;align-items:center;">
            <form method="GET" action="{{ route('portal.admins.index') }}" style="display:flex;gap:.5rem;align-items:center;">
                <input type="search" name="search" placeholder="Search admins" value="{{ request('search') }}" class="field-input" />
                <select name="role" class="field-select">
                    <option value="">Any role</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="super_admin" {{ request('role')=='super_admin'?'selected':'' }}>Super Admin</option>
                </select>
                <select name="status" class="field-select">
                    <option value="">Any status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                </select>
                <button class="btn btn-ghost btn-sm">Filter</button>
            </form>
            <div style="display:flex;gap:.5rem;">
                <a href="{{ route('portal.admins.login-history') }}" class="btn btn-ghost btn-sm">Login History</a>
                <a href="{{ route('portal.admins.audit-logs') }}" class="btn btn-ghost btn-sm">Audit Logs</a>
                <a href="{{ route('portal.admins.create') }}" class="btn btn-primary btn-sm">+ Add Admin</a>
            </div>
        </div>
    </div>



    @if($admins->isEmpty())
        <p class="empty-text">No admin accounts found.</p>
    @else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->fullname ?? $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $admin->role }}">
                            {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                        </span>
                    </td>
                    <td>
                        <span class="status-pill status-{{ $admin->status }}">
                            {{ ucfirst($admin->status) }}
                        </span>
                    </td>
                    <td>
                        {{ $admin->last_login_at ? $admin->last_login_at->format('M j, Y h:i A') : 'Never' }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('portal.admins.edit', $admin) }}" class="btn btn-ghost btn-sm">Edit</a>
                            <button type="button" class="btn btn-ghost btn-sm" data-toggle="modal" data-target="#resetModal-{{ $admin->id }}">
                                Reset Password
                            </button>
                            @if($admin->id != Auth::id())
                            <form action="{{ route('portal.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endif
                        </div>

                        <!-- Reset Password Modal -->
                        <div class="modal" id="resetModal-{{ $admin->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reset Password for {{ $admin->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('portal.admins.reset-password', $admin) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="password-{{ $admin->id }}">New Password</label>
                                                <input type="password" id="password-{{ $admin->id }}" name="password" class="form-control" required minlength="8">
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation-{{ $admin->id }}">Confirm New Password</label>
                                                <input type="password" id="password_confirmation-{{ $admin->id }}" name="password_confirmation" class="form-control" required minlength="8">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Modal Styles -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
    background: rgba(0,0,0,0.5);
}
.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-dialog {
    position: relative;
    width: auto;
    max-width: 500px;
    margin: 1.75rem auto;
}
.modal-content {
    background: white;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
}
.close {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6b7280;
    background: none;
    border: none;
    cursor: pointer;
}
.btn-danger {
    background: #c41e3a;
    color: white;
}
.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}
.role-super_admin {
    background: rgba(225,6,0,0.1);
    color: #c41e3a;
}
.role-admin {
    background: rgba(0,0,0,0.05);
    color: #1f2937;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal handling
    document.querySelectorAll('[data-toggle="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-target');
            const modal = document.querySelector(modalId);
            if (modal) modal.classList.add('show');
        });
    });
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) modal.classList.remove('show');
        });
    });
});
</script>
@endsection
