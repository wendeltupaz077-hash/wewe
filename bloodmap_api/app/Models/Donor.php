<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $fillable = [
        'user_id', 'blood_type', 'verification_status', 'document_verified',
        'document_path', 'donor_status', 'latitude', 'longitude',
        'last_donation_at', 'cooldown_until', 'verified_by_facility_id',
    ];

    protected function casts(): array
    {
        return [
            'document_verified' => 'boolean',
            'last_donation_at' => 'datetime',
            'cooldown_until' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deferrals(): HasMany
    {
        return $this->hasMany(DonorDeferral::class);
    }

    public function isEligibleForMatching(): bool
    {
        if ($this->donor_status === 'deferral') {
            return false;
        }
        if ($this->cooldown_until && $this->cooldown_until->isFuture()) {
            return false;
        }

        return ! $this->deferrals()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('type', 'permanent')
                    ->orWhere('eligible_again_at', '>', now()->toDateString());
            })
            ->exists();
    }
}
