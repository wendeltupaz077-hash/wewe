<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\User;
use App\Notifications\PasswordSetupNotification;
use App\Services\AuthSecurityService;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(private OtpService $otpService, private AuthSecurityService $security)
    {
    }

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.login');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = Socialite::driver('google');

        if (method_exists($driver, 'scopes')) {
            $driver->scopes(['openid', 'profile', 'email']);
        }

        return $driver->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('portal.login')->with('error', 'Unable to complete Google sign-in. Please try again.');
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('portal.login')->with('error', 'Google did not provide an email address.');
        }

        $existingUser = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($existingUser && $existingUser->role === 'user') {
            return redirect()->route('portal.login')->with('error', 'This account does not have portal access.');
        }

        if (! $existingUser) {
            $invitation = AdminInvitation::query()
                ->where('email', $googleUser->getEmail())
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->first();

            if (! $invitation) {
                return redirect()->route('portal.login')->with('error', 'No admin invitation found for this Google account. Please contact your Super Admin.');
            }

            $existingUser = User::create([
                'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
                'fullname' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'role' => $invitation->role,
                'status' => 'active',
                'is_registered' => true,
                'email_verified_at' => now(),
                'is_first_login' => false,
            ]);

            $invitation->update([
                'accepted_at' => now(),
                'status' => 'accepted',
            ]);

            $this->security->recordAudit(
                $existingUser,
                'admin.invitation.accepted',
                'Admin invitation accepted via Google OAuth.',
                ['email' => $existingUser->email, 'role' => $existingUser->role],
                AdminInvitation::class,
                $invitation->id
            );
        }

        if ($this->security->isLocked($existingUser)) {
            return redirect()->route('portal.login')->with('error', 'Your account is temporarily locked. Please try again later.');
        }

        if ($existingUser->status !== 'active') {
            return redirect()->route('portal.login')->with('error', 'Your account is inactive.');
        }

        if (! in_array($existingUser->role, ['super_admin', 'admin', 'facility_head', 'facility_staff'], true)) {
            return redirect()->route('portal.login')->with('error', 'You do not have portal access.');
        }

        if (! $existingUser->google_id) {
            $existingUser->update(['google_id' => $googleUser->getId()]);
        }

        $this->security->recordLoginEvent($existingUser, 'login.google.initiated', true, $request, ['email' => $existingUser->email]);

        if ($existingUser->role !== 'super_admin' && $existingUser->two_factor_enabled) {
            $this->sendLoginOtp($existingUser, $request);
            $request->session()->put('pending_auth_user_id', $existingUser->id);
            $request->session()->put('pending_auth_type', 'google');

            return redirect()->route('portal.otp.verify')->with('success', 'A verification code was sent to your email.');
        }

        Auth::login($existingUser, true);
        $existingUser->update(['last_login_at' => now()]);
        $this->security->recordLoginEvent($existingUser, 'login.google.success', true, $request);

        return redirect()->route('portal.dashboard')->with('success', 'Signed in with Google successfully!');
    }

    public function showOtpVerify(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('pending_auth_user_id')) {
            return redirect()->route('portal.login');
        }

        return view('portal.verify-otp');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = $request->session()->get('pending_auth_user_id');
        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('portal.login')->with('error', 'Unable to validate verification code. Please sign in again.');
        }

        if (! $this->otpService->verify($user->email, $request->code)) {
            $this->security->incrementFailedLoginAttempts($user, $request);
            $this->security->recordLoginEvent($user, 'login.otp.failed', false, $request, ['code' => $request->code]);

            return back()->with('error', 'Invalid or expired verification code.');
        }

        Auth::loginUsingId($user->id);
        $user->update(['last_login_at' => now()]);
        $this->security->resetFailedLoginAttempts($user);
        $this->security->recordLoginEvent($user, 'login.otp.success', true, $request);

        $request->session()->forget(['pending_auth_user_id', 'pending_auth_type']);

        if ($user->is_first_login) {
            return redirect()->route('portal.first-login')->with('success', 'Verification successful. Please update your password.');
        }

        return redirect()->route('portal.dashboard')->with('success', 'Signed in successfully!');
    }

    public function handleEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);

        if ($user) {
            $user->update(['remember_token' => $token]);
            Notification::send($user, new PasswordSetupNotification($token, false));
            session(['auth_email' => $request->email, 'auth_token' => $token, 'is_registered' => true]);
        } else {
            $namePart = explode('@', $request->email)[0];
            $user = User::create([
                'name' => ucfirst($namePart),
                'email' => $request->email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'admin',
                'status' => 'pending',
                'is_registered' => false,
                'remember_token' => $token,
            ]);
            Notification::send($user, new PasswordSetupNotification($token, true));
            session(['auth_email' => $request->email, 'auth_token' => $token, 'is_registered' => false]);
        }

        return redirect()->route('portal.auth-notice')->with('success', 'Verification email sent!');
    }

    public function showNotice(): View
    {
        return view('portal.auth-notice');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('portal.login')->with('error', 'Invalid or expired verification link.');
        }

        $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();

        if (! $user) {
            return redirect()->route('portal.login')->with('error', 'Invalid verification link.');
        }

        session(['auth_email' => $user->email, 'auth_token' => $user->remember_token, 'is_registered' => $user->is_registered]);

        return redirect()->route('portal.set-password');
    }

    public function acceptInvite(Request $request): View|RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('portal.login')->with('error', 'Invalid or expired invitation link.');
        }

        $invitation = AdminInvitation::where('token', $request->token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (! $invitation) {
            return redirect()->route('portal.login')->with('error', 'This invitation link is no longer valid.');
        }

        $request->session()->put('invite_token', $invitation->token);
        $request->session()->put('invite_email', $invitation->email);
        $request->session()->put('invite_role', $invitation->role);

        return view('portal.invite-accept', [
            'email' => $invitation->email,
            'role' => $invitation->role,
        ]);
    }

    public function submitInvite(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $token = $request->session()->get('invite_token');
        $email = $request->session()->get('invite_email');
        $role = $request->session()->get('invite_role');

        if (! $token || ! $email || ! $role) {
            return redirect()->route('portal.login')->with('error', 'Unable to complete invitation acceptance. Please use your invitation link again.');
        }

        $invitation = AdminInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (! $invitation) {
            return redirect()->route('portal.login')->with('error', 'This invitation link is no longer valid.');
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->fill([
            'name' => $user->name ?: Str::before($email, '@'),
            'fullname' => $user->fullname ?: Str::before($email, '@'),
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => 'active',
            'is_registered' => true,
            'email_verified_at' => now(),
            'is_first_login' => false,
        ]);
        $user->save();

        $invitation->update([
            'accepted_at' => now(),
            'status' => 'accepted',
        ]);

        Auth::login($user);
        $request->session()->forget(['invite_token', 'invite_email', 'invite_role']);

        $this->security->recordAudit($user, 'admin.invitation.accepted', 'Invitation accepted and admin account created.', ['email' => $email, 'role' => $role], AdminInvitation::class, $invitation->id);

        return redirect()->route('portal.dashboard')->with('success', 'Your admin account has been activated.');
    }

    public function showSetPassword(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('auth_email');
        $token = $request->session()->get('auth_token');

        if (! $email || ! $token) {
            return redirect()->route('portal.login');
        }

        $user = User::where('email', $email)->where('remember_token', $token)->first();

        if (! $user) {
            return redirect()->route('portal.login');
        }

        return view('portal.set-password', ['is_registered' => $user->is_registered]);
    }

    public function setPassword(Request $request): RedirectResponse
    {
        $email = $request->session()->get('auth_email');
        $token = $request->session()->get('auth_token');

        if (! $email || ! $token) {
            return redirect()->route('portal.login');
        }

        $user = User::where('email', $email)->where('remember_token', $token)->first();

        if (! $user) {
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
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && $this->security->isLocked($user)) {
            return back()->with('error', 'Your account is temporarily locked. Please try again later.');
        }

        if (! Auth::attempt($validated, $request->boolean('remember'))) {
            if ($user) {
                $this->security->incrementFailedLoginAttempts($user, $request);
                $this->security->recordLoginEvent($user, 'login.password.failed', false, $request, ['email' => $validated['email']]);
            }

            return back()->with('error', 'Invalid credentials.');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->with('error', 'Your account is inactive.');
        }

        if (! in_array($user->role, ['super_admin', 'admin', 'facility_head', 'facility_staff'], true)) {
            Auth::logout();
            return back()->with('error', 'You do not have portal access.');
        }

        $user->update(['last_login_at' => now()]);
        $this->security->resetFailedLoginAttempts($user);
        $this->security->recordLoginEvent($user, 'login.password.success', true, $request);

        if ($user->role !== 'super_admin' && $user->two_factor_enabled) {
            Auth::logout();
            $this->sendLoginOtp($user, $request);
            $request->session()->put('pending_auth_user_id', $user->id);
            $request->session()->put('pending_auth_type', 'password');

            return redirect()->route('portal.otp.verify')->with('success', 'A verification code was sent to your email.');
        }

        $request->session()->regenerate();

        if ($user->is_first_login) {
            return redirect()->route('portal.first-login');
        }

        return redirect()->route('portal.dashboard')->with('success', 'Logged in successfully!');
    }

    protected function sendLoginOtp(User $user, Request $request): void
    {
        $code = $this->otpService->generate($user->email, 'email');
        $this->security->recordLoginEvent($user, 'login.otp.sent', true, $request, ['email' => $user->email]);

        if (app()->environment('local')) {
            session()->flash('debug_otp_code', $code);
        }
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
