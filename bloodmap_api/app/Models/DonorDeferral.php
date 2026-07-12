<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorDeferral extends Model
{
    protected $fillable = [
        'donor_id', 'facility_id', 'type', 'reason',
        'eligible_again_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'eligible_again_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
