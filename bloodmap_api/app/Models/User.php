<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'first_name', 'last_name', 'middle_name', 'fullname',
        'email', 'phone', 'password', 'role', 'facility_id',
        'is_registered', 'phone_verified', 'last_login_at',
        'remember_token', 'profile_picture', 'status', 'is_first_login',
        'preferences',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_registered' => 'boolean',
            'phone_verified' => 'boolean',
            'last_login_at' => 'datetime',
            'is_first_login' => 'boolean',
            'preferences' => 'array',
        ];
    }

    public function getPreference(string $key, $default = null)
    {
        $prefs = $this->preferences ?? [];
        return $prefs[$key] ?? $default;
    }

    public function setPreference(string $key, $value): void
    {
        $prefs = $this->preferences ?? [];
        $prefs[$key] = $value;
        $this->preferences = $prefs;
        $this->save();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminUser(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function unreadNotifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->notifications()->where('is_read', false);
    }

    public function fullName(): string
    {
        return trim(collect([$this->first_name, $this->middle_name, $this->last_name])->filter()->join(' '))
            ?: ($this->name ?? 'User');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function isFacilityHead(): bool
    {
        return $this->role === 'facility_head';
    }

    public function isFacilityStaff(): bool
    {
        return in_array($this->role, ['facility_staff', 'facility_head'], true);
    }

    public function isDonor(): bool
    {
        return $this->donor()->exists();
    }
}
