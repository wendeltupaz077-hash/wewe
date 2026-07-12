<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = Auth::user()->notifications()
            ->when($request->filled('read_status'), function ($q) use ($request) {
                if ($request->read_status === 'unread') {
                    return $q->where('is_read', false);
                }

                if ($request->read_status === 'read') {
                    return $q->where('is_read', true);
                }

                return $q;
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('portal.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, AppNotification $notification): RedirectResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->update(['is_read' => true]);
        return back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read');
    }

    public function apiUnreadCount(): \Illuminate\Http\JsonResponse
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function apiLatest(): \Illuminate\Http\JsonResponse
    {
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
        return response()->json($notifications);
    }
}
