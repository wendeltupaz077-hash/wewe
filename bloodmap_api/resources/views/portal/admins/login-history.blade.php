@extends('layouts.portal')

@section('page-title', 'Login History')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Login History</h2>
        <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost btn-sm">← Back</a>
    </div>

    @if($loginHistory->isEmpty())
        <p class="empty-text">No login history found.</p>
    @else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Successful</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loginHistory as $log)
                <tr>
                    <td>{{ $log->user ? $log->user->fullname : 'N/A' }}</td>
                    <td>{{ $log->event }}</td>
                    <td>
                        <span class="status-pill {{ $log->successful ? 'status-active' : 'status-inactive' }}">
                            {{ $log->successful ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">{{ $log->user_agent ?? 'N/A' }}</td>
                    <td>{{ $log->created_at->format('M j, Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<style>
.status-pill {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}
.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}
.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}
</style>
@endsection
