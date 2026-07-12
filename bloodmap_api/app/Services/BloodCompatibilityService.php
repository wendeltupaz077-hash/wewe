<?php

namespace App\Services;

class BloodCompatibilityService
{
    /** @var array<string, list<string>> */
    private array $compatibility = [
        'O-' => ['O-'],
        'O+' => ['O-', 'O+'],
        'A-' => ['O-', 'A-'],
        'A+' => ['O-', 'O+', 'A-', 'A+'],
        'B-' => ['O-', 'B-'],
        'B+' => ['O-', 'O+', 'B-', 'B+'],
        'AB-' => ['O-', 'A-', 'B-', 'AB-'],
        'AB+' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
    ];

    public function compatibleDonors(string $recipientType): array
    {
        return $this->compatibility[$recipientType] ?? [];
    }

    public function isCompatible(string $donorType, string $recipientType): bool
    {
        return in_array($donorType, $this->compatibleDonors($recipientType), true);
    }
}
