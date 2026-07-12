<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordSetupNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.login');
    }

    public function handleEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);

        if ($user) {
            // Existing user - send reset link
            $user->update(['remember_token' => $token]);
            Notification::send($user, new PasswordSetupNotification($token, false));
            session(['auth_email' => $request->email, 'auth_token' => $token, 'is_registered' => true]);
        } else {
            // New admin - send setup link
            $namePart = explode('@', $request->email)[0];
            $user = User::create([
                'name' => ucfirst($namePart),
                'email' => $request->email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'admin',
                'is_registered' => false,
                'remember_token' => $token,
            ]);
            Notification::send($user, new PasswordSetupNotification($token, true));
            session(['auth_email' => $request->email, 'auth_token' => $token, 'is_registered' => false]);
        }

        // Simulate email sent - in real app use Mail::send()
        return redirect()->route('portal.auth-notice')->with('success', 'Verification email sent!');
    }

    public function showNotice(): View
    {
        return view('portal.auth-notice');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        // Verify the signed URL
        if (! $request->hasValidSignature()) {
            return redirect()->route('portal.login')->with('error', 'Invalid or expired verification link.');
        }

        // Find the user by email and token
        $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();

        if (! $user) {
            return redirect()->route('portal.login')->with('error', 'Invalid verification link.');
        }

        // Set session variables
        session(['auth_email' => $user->email, 'auth_token' => $user->remember_token, 'is_registered' => $user->is_registered]);

        // Redirect to set password page
        return redirect()->route('portal.set-password');
    }

    public function showSetPassword(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('auth_email');
        $token = $request->session()->get('auth_token');
        
        if (!$email || !$token) {
            return redirect()->route('portal.login');
        }
        
        $user = User::where('email', $email)->where('remember_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('portal.login');
        }
        
        return view('portal.set-password', ['is_registered' => $user->is_registered]);
    }

    public function setPassword(Request $request): RedirectResponse
    {
        $email = $request->session()->get('auth_email');
        $token = $request->session()->get('auth_token');
        
        if (!$email || !$token) {
            return redirect()->route('portal.login');
        }
        
        $user = User::where('email', $email)->where('remember_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('portal.login');
        }
        
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user->update([
            'password' => Hash::make($request->password),
            'is_registered' => true,
            'remember_token' => null,
        ]);
        
        $request->session()->forget(['auth_email', 'auth_token', 'is_registered']);
        
        Auth::login($user);
        
        return redirect()->route('portal.dashboard')->with('success', 'Password set successfully!');
    }

    public function showForgotPassword(): View
    {
        return view('portal.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $token = Str::random(60);
            $user->update(['remember_token' => $token]);
            Notification::send($user, new PasswordSetupNotification($token, false));
            session(['auth_email' => $request->email, 'auth_token' => $token, 'is_registered' => true]);
        }
        
        return redirect()->route('portal.auth-notice')->with('success', 'Password reset email sent!');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is active
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->with('error', 'Your account is inactive.');
            }
            
            // Check if user has portal access
            if (! in_array($user->role, ['super_admin', 'admin', 'facility_head', 'facility_staff'], true)) {
                Auth::logout();
                return back()->with('error', 'You do not have portal access.');
            }

            $request->session()->regenerate();
            $user->update(['last_login_at' => now()]);

            // Check if first login
            if ($user->is_first_login) {
                return redirect()->route('portal.first-login');
            }

            return redirect()->route('portal.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function showFirstLogin(): View|RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->is_first_login) {
            return redirect()->route('portal.dashboard');
        }
        
        return view('portal.first-login');
    }

    public function firstLoginChangePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'is_first_login' => false,
        ]);
        
        return redirect()->route('portal.dashboard')->with('success', 'Password updated successfully!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')->with('success', 'Logged out successfully!');
    }
}
