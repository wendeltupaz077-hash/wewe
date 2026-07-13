@extends('layouts.portal')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="portal-grid">
    @if(auth()->user()->role === 'super_admin' && $superAdmin)
        <div class="portal-card portal-card--centered" style="border-left: 4px solid var(--blood-red);">
            <div class="card-header">
                <div class="card-icon">👑</div>
                <div>
                    <h2>Super Admin Account</h2>
                    <div class="record-count">Manage the permanent super admin account. Handle with care!</div>
                </div>
            </div>

            <form method="POST" action="{{ route('portal.settings.super-admin.update') }}" enctype="multipart/form-data">
                @csrf
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>There were some problems with your input.</strong>
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-section">
                    <div class="form-grid">
                        <div class="form-group floating-label">
                            <input class="field-input" type="text" id="sa_fullname" name="fullname" value="{{ old('fullname', $superAdmin->fullname) }}" placeholder=" " required>
                            <label for="sa_fullname">Full Name</label>
                        </div>
                        <div class="form-group floating-label">
                            <input class="field-input" type="email" id="sa_email" name="email" value="{{ old('email', $superAdmin->email) }}" required placeholder=" ">
                            <label for="sa_email">Email Address</label>
                        </div>
                        <div class="form-group floating-label">
                            <input class="field-input" type="password" id="sa_password" name="password" placeholder=" ">
                            <label for="sa_password">New Password</label>
                            <small class="field-note">Leave blank to keep current password.</small>
                        </div>
                        <div class="form-group floating-label">
                            <input class="field-input" type="password" id="sa_password_confirmation" name="password_confirmation" placeholder=" ">
                            <label for="sa_password_confirmation">Confirm New Password</label>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="justify-content: space-between;">
                    <button type="submit" class="btn btn-primary">Update Super Admin</button>
                    <button type="button" onclick="confirmDeleteSuperAdmin()" class="btn btn-ghost" style="color: var(--danger); border-color: var(--danger);">Delete Super Admin</button>
                </div>
            </form>
            <form id="delete-super-admin-form" method="POST" action="{{ route('portal.settings.super-admin.delete') }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    @endif
    <div class="portal-card portal-card--centered">
        <div class="card-header">
            <div class="card-icon">⚙️</div>
            <div>
                <h2>Settings</h2>
                <div class="record-count">Control your profile, security, and notification preferences.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('portal.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any() && !old('fullname') || old('fullname') !== old('sa_fullname'))
                <div class="alert alert-error">
                    <strong>There were some problems with your input.</strong>
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-section">
                <h3>Profile</h3>
                <p>Update your account details and profile picture for the admin portal.</p>
                <div class="form-grid">
                    <div class="form-group floating-label full-width">
                        <label for="profile_picture">Profile Picture</label>
                        <input class="field-input" type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="text" id="fullname" name="fullname" value="{{ old('fullname', auth()->user()->fullname) }}" placeholder=" " aria-invalid="{{ $errors->has('fullname') ? 'true' : 'false' }}">
                        <label for="fullname">Full Name</label>
                        @error('fullname') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" placeholder=" ">
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="text" id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" placeholder=" ">
                        <label for="last_name">Last Name</label>
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', auth()->user()->middle_name) }}" placeholder=" ">
                        <label for="middle_name">Middle Name</label>
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required placeholder=" " aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                        <label for="email">Email Address</label>
                        @error('email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group floating-label">
                        <input class="field-input" type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder=" " aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}">
                        <label for="phone">Phone Number</label>
                        @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card-section">
                <h3>Security</h3>
            <p>Keep your account safe with a strong password.</p>
            <div class="form-grid">
                <div class="form-group floating-label">
                    <input class="field-input" type="password" id="password" name="password" placeholder=" " aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                    <label for="password">New Password</label>
                    <small class="field-note">Leave blank to keep current password.</small>
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group floating-label">
                    <input class="field-input" type="password" id="password_confirmation" name="password_confirmation" placeholder=" ">
                    <label for="password_confirmation">Confirm New Password</label>
                        @error('password_confirmation') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card-section">
            <h3>Preferences</h3>
            <p>Choose how you receive alerts and whether the portal should use dark mode.</p>
            <input type="hidden" name="preferences[notifications]" value="0">
            <input type="hidden" name="preferences[emergency_alerts]" value="0">
            <input type="hidden" name="preferences[location_services]" value="0">
            <input type="hidden" name="preferences[dark_mode]" value="0">

            <div class="toggle-row">
                <div>
                    <label for="pref_notifications">Push Notifications</label>
                    <p class="field-note">Receive alerts about new requests and updates.</p>
                </div>
                <label class="switch {{ old('preferences.notifications', auth()->user()->getPreference('notifications', false)) ? 'on' : '' }}">
                    <input type="checkbox" id="pref_notifications" name="preferences[notifications]" value="1" {{ old('preferences.notifications', auth()->user()->getPreference('notifications', false)) ? 'checked' : '' }}>
                    <span class="knob"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div>
                    <label for="pref_emergency_alerts">Emergency Alerts</label>
                    <p class="field-note">Send urgent facility alerts and push notifications.</p>
                </div>
                <label class="switch {{ old('preferences.emergency_alerts', auth()->user()->getPreference('emergency_alerts', false)) ? 'on' : '' }}">
                    <input type="checkbox" id="pref_emergency_alerts" name="preferences[emergency_alerts]" value="1" {{ old('preferences.emergency_alerts', auth()->user()->getPreference('emergency_alerts', false)) ? 'checked' : '' }}>
                    <span class="knob"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div>
                    <label for="pref_location_services">Location Services</label>
                    <p class="field-note">Enable location-based alerts and facility recommendations.</p>
                </div>
                <label class="switch {{ old('preferences.location_services', auth()->user()->getPreference('location_services', false)) ? 'on' : '' }}">
                    <input type="checkbox" id="pref_location_services" name="preferences[location_services]" value="1" {{ old('preferences.location_services', auth()->user()->getPreference('location_services', false)) ? 'checked' : '' }}>
                    <span class="knob"></span>
                </label>
            </div>

            <div class="toggle-row">
                <div>
                    <label for="pref_dark_mode">Dark Mode</label>
                    <p class="field-note">Switch the admin portal to a dark theme instantly.</p>
                </div>
                <label class="switch {{ old('preferences.dark_mode', auth()->user()->getPreference('dark_mode', false)) ? 'on' : '' }}">
                    <input type="checkbox" id="pref_dark_mode" name="preferences[dark_mode]" value="1" {{ old('preferences.dark_mode', auth()->user()->getPreference('dark_mode', false)) ? 'checked' : '' }}>
                    <span class="knob"></span>
                </label>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.privacy') }}" class="btn btn-ghost">Privacy Policy</a>
            <a href="{{ route('portal.about') }}" class="btn btn-ghost">About</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
        </form>
    </div>
</div>

<script>
    function confirmDeleteSuperAdmin() {
        if (confirm('Are you sure you want to delete the super admin account? This action cannot be undone!')) {
            document.getElementById('delete-super-admin-form').submit();
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        // Preference toggles: sync to localStorage and apply immediately for UX
        const prefs = ['notifications','emergency_alerts','location_services','dark_mode'];
        prefs.forEach(key => {
            const el = document.getElementById('pref_' + key);
            if (!el) return;
            // initialize localStorage from server value if not set
            try {
                const storageKey = 'portal:' + key;
                if (localStorage.getItem(storageKey) === null) {
                    localStorage.setItem(storageKey, el.checked ? '1' : '0');
                } else {
                    // if local value differs from server, apply local
                    const localVal = localStorage.getItem(storageKey) === '1';
                    el.checked = localVal;
                }
            } catch (e) {}

            const applyPref = (k, checked) => {
                if (k === 'dark_mode') {
                    document.documentElement.classList.toggle('dark-mode', checked);
                }
            };

            // apply initial
            applyPref(key, el.checked);

            el.addEventListener('change', function () {
                try { localStorage.setItem('portal:' + key, this.checked ? '1' : '0'); } catch (e) {}
                applyPref(key, this.checked);
            });
        });
    });
</script>
@endsection
