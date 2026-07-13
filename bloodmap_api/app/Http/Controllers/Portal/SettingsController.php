<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        return view('portal.settings', compact('superAdmin'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preferences' => 'nullable|array',
            'preferences.notifications' => 'nullable|boolean',
            'preferences.emergency_alerts' => 'nullable|boolean',
            'preferences.location_services' => 'nullable|boolean',
            'preferences.dark_mode' => 'nullable|boolean',
        ]);

        $data = $request->only(['fullname', 'first_name', 'last_name', 'middle_name', 'phone', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        // Save preferences separately (JSON column)
        if ($request->has('preferences')) {
            $prefs = $request->input('preferences', []);
            $user->preferences = array_merge($user->preferences ?? [], $prefs);
            $user->save();
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function updateSuperAdmin(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403);
        }
        $superAdmin = User::where('role', 'super_admin')->firstOrFail();
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $superAdmin->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        $data = $request->only(['fullname', 'email']);
        $data['name'] = $request->fullname;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $superAdmin->update($data);

        return back()->with('success', 'Super admin account updated successfully!');
    }

    public function deleteSuperAdmin(): RedirectResponse
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403);
        }
        $superAdmin = User::where('role', 'super_admin')->firstOrFail();
        $superAdmin->delete();
        Auth::logout();
        return redirect()->route('portal.login')->with('success', 'Super admin account deleted successfully!');
    }
}
