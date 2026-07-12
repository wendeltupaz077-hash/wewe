<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
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
        return view('portal.settings');
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
}
