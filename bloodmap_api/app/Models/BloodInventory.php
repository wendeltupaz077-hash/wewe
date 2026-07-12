<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventory extends Model
{
    protected $table = 'blood_inventory';

    protected $fillable = [
        'facility_id', 'blood_type', 'component_type', 'quantity',
        'collection_date', 'expiry_date', 'freshness_flag', 'status', 'donor_id',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public static function shelfLifeDays(string $component): int
    {
        return match ($component) {
            'platelets' => 5,
            'plasma' => 365,
            'packed_rbc', 'irradiated' => 42,
            default => 35,
        };
    }

    public function expiryTier(): string
    {
        $days = now()->diffInDays($this->expiry_date, false);
        if ($days < 0) {
            return 'expired';
        }
        if ($days <= 7) {
            return 'near_expiry';
        }

        return 'safe';
    }
}
