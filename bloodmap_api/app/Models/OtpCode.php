<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['identifier', 'code', 'channel', 'expires_at', 'used'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    public function isValid(string $code): bool
    {
        return ! $this->used
            && $this->code === $code
            && $this->expires_at->isFuture();
    }
}
