<?php

namespace App\Services;

use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use Illuminate\Support\Collection;

class DonorMatchingService
{
    public function __construct(
        private BloodCompatibilityService $compatibility
    ) {}

    public function matchForRequest(EmergencyRequest $request): array
    {
        $facility = $request->facility;
        $compatibleTypes = $this->compatibility->compatibleDonors($request->blood_type);

        $localStock = $this->findStock($facility, $compatibleTypes, $request->component_type, $request->quantity);

        $nearbyStock = collect();
        if ($localStock['fulfilled'] < $request->quantity) {
            $nearbyStock = $this->findNearbyStock($facility, $compatibleTypes, $request->component_type, $request->quantity - $localStock['fulfilled']);
        }

        $donors = $this->findEligibleDonors($facility, $compatibleTypes, $request->quantity);

        return [
            'local_stock' => $localStock,
            'nearby_stock' => $nearbyStock,
            'donors' => $donors,
            'escalation' => $this->determineEscalation($localStock, $nearbyStock, $donors, $request->quantity),
        ];
    }

    private function findStock(Facility $facility, array $types, string $component, int $needed): array
    {
        $units = BloodInventory::query()
            ->where('facility_id', $facility->id)
            ->whereIn('blood_type', $types)
            ->where('component_type', $component)
            ->where('status', 'available')
            ->where('expiry_date', '>=', now()->toDateString())
            ->orderBy('expiry_date')
            ->get();

        $fulfilled = min($needed, (int) $units->sum('quantity'));

        return ['units' => $units, 'fulfilled' => $fulfilled];
    }

    private function findNearbyStock(Facility $facility, array $types, string $component, int $needed): Collection
    {
        if (! $facility->latitude || ! $facility->longitude) {
            return collect();
        }

        $originLat = (float) $facility->latitude;
        $originLon = (float) $facility->longitude;

        return Facility::query()
            ->where('id', '!=', $facility->id)
            ->where('is_locked', false)
            ->get()
            ->map(function (Facility $f) use ($types, $component, $originLat, $originLon) {
                $stock = BloodInventory::query()
                    ->where('facility_id', $f->id)
                    ->whereIn('blood_type', $types)
                    ->where('component_type', $component)
                    ->where('status', 'available')
                    ->where('expiry_date', '>=', now()->toDateString())
                    ->sum('quantity');

                $distance = ($f->latitude && $f->longitude)
                    ? $this->haversine($originLat, $originLon, (float) $f->latitude, (float) $f->longitude)
                    : 9999;

                return ['facility' => $f, 'stock' => (int) $stock, 'distance_km' => $distance];
            })
            ->filter(fn ($r) => $r['stock'] > 0)
            ->sortBy('distance_km')
            ->take(5)
            ->values();
    }

    public function findEligibleDonors(Facility $facility, array $types, int $limit = 10): Collection
    {
        return Donor::query()
            ->with('user')
            ->where(function ($q) use ($types) {
                $q->whereIn('blood_type', $types)
                    ->orWhereNull('blood_type');
            })
            ->where('donor_status', 'available')
            ->get()
            ->filter(fn (Donor $d) => $d->isEligibleForMatching())
            ->sortByDesc(fn (Donor $d) => $d->verification_status === 'verified' ? 1 : 0)
            ->take($limit)
            ->map(fn (Donor $d) => [
                'id' => $d->id,
                'blood_type' => $d->blood_type ?? 'Unknown',
                'verification_status' => $d->verification_status,
                'document_verified' => $d->document_verified,
                'display_name' => 'Donor #'.$d->id,
            ])
            ->values();
    }

    private function determineEscalation(array $local, Collection $nearby, Collection $donors, int $needed): string
    {
        $localQty = $local['fulfilled'] ?? 0;
        $nearbyQty = (int) $nearby->sum('stock');

        if ($localQty >= $needed) {
            return 'local';
        }
        if ($localQty + $nearbyQty >= $needed) {
            return 'nearby_facility';
        }
        if ($donors->isNotEmpty()) {
            return 'donor_network';
        }

        return 'prc';
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earth = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return round($earth * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
    }
}
