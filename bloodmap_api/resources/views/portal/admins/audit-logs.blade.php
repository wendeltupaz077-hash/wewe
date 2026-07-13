@extends('layouts.portal')

@section('page-title', 'Audit Logs')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header">
        <h2>Audit Logs</h2>
        <a href="{{ route('portal.admins.index') }}" class="btn btn-ghost btn-sm">← Back</a>
    </div>

    @if($auditLogs->isEmpty())
        <p class="empty-text">No audit logs found.</p>
    @else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Message</th>
                    <th>Target Type</th>
                    <th>Target ID</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditLogs as $log)
                <tr>
                    <td>{{ $log->user ? $log->user->fullname : 'N/A' }}</td>
                    <td>{{ $log->action }}</td>
                    <td style="max-width: 300px;">{{ $log->message ?? '-' }}</td>
                    <td>{{ $log->target_type ?? '-' }}</td>
                    <td>{{ $log->target_id ?? '-' }}</td>
                    <td>{{ $log->created_at->format('M j, Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
