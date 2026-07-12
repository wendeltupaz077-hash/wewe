<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdminUser()) {
            abort(403);
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $users = User::with('facility')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('facility_id'), fn ($q) => $q->where('facility_id', $request->facility_id))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $facilities = Facility::orderBy('name')->get();

        return view('portal.users.index', compact('users', 'facilities'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();
        $roles = ['super_admin' => 'Super Admin', 'admin' => 'Admin', 'facility_head' => 'Facility Head', 'facility_staff' => 'Facility Staff', 'user' => 'User'];
        $facilities = Facility::orderBy('name')->get();

        return view('portal.users.create', compact('roles', 'facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'role' => 'required|in:super_admin,admin,facility_head,facility_staff,user',
            'facility_id' => 'nullable|exists:facilities,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $password = isset($data['password']) ? Hash::make($data['password']) : Hash::make(Str::random(16));

        $user = User::create([
            'name' => $data['fullname'],
            'fullname' => $data['fullname'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'facility_id' => $data['facility_id'] ?? null,
            'status' => $data['status'],
            'password' => $password,
            'is_registered' => true,
        ]);

        return redirect()->route('portal.users.index')->with('success', 'User account created successfully!');
    }

    public function edit(User $user): View
    {
        $this->authorizeAdmin();
        $roles = ['super_admin' => 'Super Admin', 'admin' => 'Admin', 'facility_head' => 'Facility Head', 'facility_staff' => 'Facility Staff', 'user' => 'User'];
        $facilities = Facility::orderBy('name')->get();

        return view('portal.users.edit', compact('user', 'roles', 'facilities'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'role' => 'required|in:super_admin,admin,facility_head,facility_staff,user',
            'facility_id' => 'nullable|exists:facilities,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $data['fullname'],
            'fullname' => $data['fullname'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'facility_id' => $data['facility_id'] ?? null,
            'status' => $data['status'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return redirect()->route('portal.users.index')->with('success', 'User account updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return redirect()->route('portal.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('portal.users.index')->with('success', 'User account deleted successfully!');
    }
}
