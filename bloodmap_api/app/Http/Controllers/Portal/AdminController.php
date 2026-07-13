<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\User;
use App\Notifications\AdminInvitationNotification;
use App\Services\AuthSecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private AuthSecurityService $security)
    {
    }

    public function index(Request $request): View
    {
        $admins = User::where('role', 'admin')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('fullname')
            ->paginate(20)
            ->withQueryString();

        return view('portal.admins.index', compact('admins'));
    }

    public function create(): View
    {
        return view('portal.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $password = $request->password ? Hash::make($request->password) : Hash::make(Str::random(16));

        $user = User::create([
            'name' => $request->fullname,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $password,
            'role' => 'admin',
            'status' => $request->password ? 'active' : 'pending',
            'is_first_login' => !$request->password,
            'is_registered' => $request->password ? true : false,
        ]);

        if (! $request->password) {
            $invitation = AdminInvitation::create([
                'email' => $request->email,
                'role' => 'admin',
                'inviter_id' => Auth::id(),
                'token' => Str::random(64),
                'expires_at' => now()->addHours(48),
                'status' => 'pending',
            ]);

            Notification::send($user, new AdminInvitationNotification($invitation));
            $this->security->recordAudit(Auth::user(), 'admin.invited', 'Admin account created and invitation sent.', [
                'email' => $request->email,
                'role' => 'admin',
                'invitation_id' => $invitation->id,
            ], AdminInvitation::class, $invitation->id);
        }

        return redirect()->route('portal.admins.index')->with('success', 'Admin account created successfully!');
    }

    public function edit(User $admin): View
    {
        if ($admin->role === 'super_admin') {
            abort(403);
        }
        return view('portal.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        if ($admin->role === 'super_admin') {
            abort(403);
        }
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'status' => 'required|in:active,inactive',
        ]);

        $admin->update([
            'name' => $request->fullname,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role' => 'admin',
            'status' => $request->status,
        ]);

        return redirect()->route('portal.admins.index')->with('success', 'Admin account updated successfully!');
    }

    public function resetPassword(Request $request, User $admin): RedirectResponse
    {
        if ($admin->role === 'super_admin') {
            abort(403);
        }
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin->update([
            'password' => Hash::make($request->password),
            'is_first_login' => true, // Require password change on next login
        ]);

        return redirect()->route('portal.admins.index')->with('success', 'Admin password reset successfully!');
    }

    public function destroy(User $admin): RedirectResponse
    {
        if ($admin->role === 'super_admin') {
            return redirect()->route('portal.admins.index')->with('error', 'You cannot delete the super admin account!');
        }
        // Don't allow deleting self
        if ($admin->id == Auth::id()) {
            return redirect()->route('portal.admins.index')->with('error', 'You cannot delete your own account!');
        }

        $admin->delete();

        return redirect()->route('portal.admins.index')->with('success', 'Admin account deleted successfully!');
    }
    
    public function loginHistory(Request $request): View
    {
        $loginHistory = LoginHistory::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('portal.admins.login-history', compact('loginHistory'));
    }
    
    public function auditLogs(Request $request): View
    {
        $auditLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('portal.admins.audit-logs', compact('auditLogs'));
    }
}
