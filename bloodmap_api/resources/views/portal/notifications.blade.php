@extends('layouts.portal')

@section('page-title', 'Notifications')

@section('content')
<div class="portal-card reveal">
    <div class="portal-card-header" style="flex-wrap:wrap;gap:1rem;">
        <h2>Notifications</h2>
        <form method="POST" action="{{ route('portal.notifications.mark-all-read') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">Mark All as Read</button>
        </form>
    </div>

    @if($notifications->isEmpty())
    <p class="empty-text">No notifications yet.</p>
    @else
    <div class="notification-list" style="display:flex;flex-direction:column;gap:1rem;">
        @foreach($notifications as $notification)
        <div class="notification-item" style="padding:1.25rem;border-radius:0.75rem;background:{{ $notification->is_read ? '#f3f4f6' : 'rgba(225,6,0,0.05)' }};border:1px solid #e5e7eb;">
            <div style="display:flex;justify-content:space-between;align-items:start;gap:1rem;">
                <div style="flex:1;">
                    <h4 style="margin:0 0 0.25rem 0;color:{{ $notification->is_read ? '#6b7280' : '#1f2937' }};">{{ $notification->title }}</h4>
                    <p style="margin:0;color:#6b7280;font-size:0.9rem;">{{ $notification->message }}</p>
                    <small style="color:#6b7280;margin-top:0.5rem;display:block;">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->is_read)
                <form method="POST" action="{{ route('portal.notifications.mark-read', $notification) }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">Mark as Read</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
