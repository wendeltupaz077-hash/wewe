<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = [
        'name', 'type', 'address', 'city', 'province',
        'latitude', 'longitude', 'contact_phone', 'contact_email',
        'stock_status', 'accepts_donations', 'is_locked', 'head_user_id',
    ];

    protected function casts(): array
    {
        return [
            'accepts_donations' => 'boolean',
            'is_locked' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class);
    }

    public function emergencyRequests(): HasMany
    {
        return $this->hasMany(EmergencyRequest::class);
    }

    public function availableUnits(): int
    {
        return (int) $this->inventory()
            ->where('status', 'available')
            ->where('expiry_date', '>=', now()->toDateString())
            ->sum('quantity');
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function computeStockStatus(): string
    {
        $units = $this->availableUnits();
        $criticalTypes = ['O-', 'O+'];
        $hasCritical = $this->inventory()
            ->where('status', 'available')
            ->whereIn('blood_type', $criticalTypes)
            ->where('expiry_date', '>=', now()->toDateString())
            ->sum('quantity');

        if ($units === 0 || $hasCritical === 0) {
            return 'emergency';
        }
        if ($units < 10) {
            return 'low_stock';
        }
        if ($units >= 50) {
            return 'full_stock';
        }

        return 'normal';
    }
}
