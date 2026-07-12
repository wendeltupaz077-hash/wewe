<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{

    public function index(Request $request): View
    {
        $admins = User::whereIn('role', ['super_admin', 'admin'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
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
            'role' => 'required|in:super_admin,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $password = $request->password ? Hash::make($request->password) : Hash::make(Str::random(16));

        User::create([
            'name' => $request->fullname,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'status' => 'active',
            'is_first_login' => !$request->password, // Require password change if no password set
            'is_registered' => true,
        ]);

        return redirect()->route('portal.admins.index')->with('success', 'Admin account created successfully!');
    }

    public function edit(User $admin): View
    {
        return view('portal.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'role' => 'required|in:super_admin,admin',
            'status' => 'required|in:active,inactive',
        ]);

        $admin->update([
            'name' => $request->fullname,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('portal.admins.index')->with('success', 'Admin account updated successfully!');
    }

    public function resetPassword(Request $request, User $admin): RedirectResponse
    {
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
        // Don't allow deleting self
        if ($admin->id == Auth::id()) {
            return redirect()->route('portal.admins.index')->with('error', 'You cannot delete your own account!');
        }

        $admin->delete();

        return redirect()->route('portal.admins.index')->with('success', 'Admin account deleted successfully!');
    }
}
